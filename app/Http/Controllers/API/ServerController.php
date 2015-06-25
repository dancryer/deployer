<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreServerRequest;
use App\Repositories\Contracts\ServerRepositoryInterface;

class ServerController extends ResourceController
{
    protected $projectRepository;

    /**
     * Class constructor.
     *
     * @param  ServerRepositoryInterface $repository
     * @return void
     */
    public function __construct(
        ServerRepositoryInterface $repository,
        ProjectRepositoryInterface $projectRepository
    ) {
        $this->repository = $repository;
        $this->projectRepository = $projectRepository;
    }

    public function index($project_id)
    {
        $project = $this->projectRepository->getById($project_id);
        return $project->servers;
    }

    /**
     * Store a newly created server in storage.
     *
     * @param  StoreServerRequest $request
     * @return Response
     */
    public function store(StoreServerRequest $request)
    {
        return $this->repository->create($request->only(
            'name',
            'user',
            'ip_address',
            'port',
            'path',
            'project_id',
            'deploy_code',
            'add_commands'
        ));
    }

    /**
     * Update the specified server in storage.
     *
     * @param  StoreServerRequest $request
     * @return Response
     */
    public function update($server_id, StoreServerRequest $request)
    {
        return $this->repository->updateById($request->only(
            'name',
            'user',
            'ip_address',
            'port',
            'path',
            'project_id',
            'deploy_code'
        ), $server_id);
    }
}