<?php

use App\Deployment;

class DeploymentTest extends TestCase
{
    public function __construct()
    {
        $this->deployment = new Deployment;
    }

    public function testShortCommitHash()
    {
        $this->deployment->commit = '820128af7784b801306ecc505e684b67e0a444e3';

        $this->assertEquals('820128a', $this->deployment->shortCommit());
    }
}
