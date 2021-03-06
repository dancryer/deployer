<?php

namespace REBELinBLUE\Deployer\Http\Controllers;

use Illuminate\Support\Facades\Input;
use REBELinBLUE\Deployer\Repositories\Contracts\DeploymentRepositoryInterface;
use REBELinBLUE\Deployer\Repositories\Contracts\ProjectRepositoryInterface;

/**
 * The deployment webhook controller.
 */
class WebhookController extends Controller
{
    /**
     * The project repository.
     *
     * @var ProjectRepositoryInterface
     */
    private $projectRepository;

    /**
     * The deployment repository.
     *
     * @var deploymentRepository
     */
    private $deploymentRepository;

    /**
     * Class constructor.
     *
     * @param  ProjectRepositoryInterface    $projectRepository
     * @param  DeploymentRepositoryInterface $deploymentRepository
     * @return void
     */
    public function __construct(
        ProjectRepositoryInterface $projectRepository,
        DeploymentRepositoryInterface $deploymentRepository
    ) {
        $this->projectRepository    = $projectRepository;
        $this->deploymentRepository = $deploymentRepository;
    }

    /**
     * Handles incoming requests from Gitlab or PHPCI to trigger deploy.
     *
     * @param  string   $hash The webhook hash
     * @return Response
     */
    public function webhook($hash)
    {
        $project = $this->projectRepository->getByHash($hash);

        $success = false;
        if ($project->servers->where('deploy_code', true)->count() > 0) {
            $optional = [];

            // Check if the commands input is set, if so explode on comma and filter out any invalid commands
            if (Input::has('commands')) {
                $valid = $project->commands->lists('id');

                $optional = collect(explode(',', Input::get('commands')))
                                    ->unique()
                                    ->intersect($valid);
            }

            // TODO: Validate URL and only accept it if source is set?
            $this->deploymentRepository->create([
                'reason'     => Input::get('reason'),
                'project_id' => $project->id,
                'branch'     => $project->branch,
                'optional'   => $optional,
                'source'     => Input::get('source'),
                'build_url'  => Input::get('url'),
            ]);

            $success = true;
        }

        return [
            'success' => $success,
        ];
    }

    /**
     * Generates a new webhook URL.
     *
     * @param  int      $project_id
     * @return Response
     */
    public function refresh($project_id)
    {
        $project = $this->projectRepository->getById($project_id);
        $project->generateHash();
        $project->save();

        return [
            'url' => route('webhook', $project->hash),
        ];
    }
}
