<?php

namespace REBELinBLUE\Deployer\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use REBELinBLUE\Deployer\Jobs\Job;
use REBELinBLUE\Deployer\Server;
use Symfony\Component\Process\Process;

/**
 * Tests if a server can successfully be SSHed into.
 */
class TestServerConnection extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $server;

    /**
     * Create a new command instance.
     *
     * @param  Server               $server
     * @return TestServerConnection
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        DB::reconnect();
        $this->server->status = Server::TESTING;
        $this->server->save();

        $key = tempnam(storage_path() . '/app/', 'sshkey');
        file_put_contents($key, $this->server->project->private_key);

        try {
            $command = $this->sshCommand($this->server, $key);
            $process = new Process($command);
            $process->setTimeout(null);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->server->status = Server::FAILED;

                // TODO: See if there are other strings which are needed
                if (preg_match('/(tty present|askpass)/', $process->getErrorOutput())) {
                    $this->server->status = Server::FAILED_FPM;
                }

            } else {
                $this->server->status = Server::SUCCESSFUL;
            }
        } catch (\Exception $error) {
            $this->server->status = Server::FAILED;
        }

        $this->server->save();

        unlink($key);
    }

    /**
     * Generates the SSH command for running the script on a server.
     *
     * @param  Server $server
     * @param  string $script The script to run
     * @return string
     */
    private function sshCommand(Server $server, $private_key)
    {
        $script = <<< EOF
            set -e
            ls
            if [ ! -z "$(ps -ef | grep -v grep | grep php-fpm)" ]; then
                sudo /usr/sbin/service php5-fpm restart
            fi
EOF;

        return 'ssh -o CheckHostIP=no \
                 -o IdentitiesOnly=yes \
                 -o StrictHostKeyChecking=no \
                 -o PasswordAuthentication=no \
                 -o IdentityFile=' . $private_key . ' \
                 -p ' . $server->port . ' \
                 ' . $server->user . '@' . $server->ip_address . ' \'bash -s\' << EOF
                 ' . $script . '
EOF';
    }
}
