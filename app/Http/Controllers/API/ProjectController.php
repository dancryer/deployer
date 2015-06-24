<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Resources\ResourceController as Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;

/**
 * The controller for managing projects.
 */
class ProjectController extends Controller
{
    /**
     * Class constructor.
     *
     * @param  GroupRepositoryInterface $repository
     * @return void
     */
    public function __construct(ProjectRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  StoreProjectRequest $request
     * @return Response
     */
    public function store(StoreProjectRequest $request)
    {
        return $this->repository->create($request->only(
            'name',
            'repository',
            'branch',
            'group_id',
            'builds_to_keep',
            'url',
            'build_url',
            'template_id'
        ));
    }

    /**
     * Update the specified project in storage.
     *
     * @param  int                 $project_id
     * @param  StoreProjectRequest $request
     * @return Response
     */
    public function update($project_id, StoreProjectRequest $request)
    {
        return $this->repository->updateById($request->only(
            'name',
            'repository',
            'branch',
            'group_id',
            'builds_to_keep',
            'url',
            'build_url'
        ), $project_id);
    }
}
