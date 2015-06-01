<?php namespace App\Http\Controllers\API;

use App\Project;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Http\Requests\StoreProjectRequest;

/**
 * The controller for managing projects
 */
class ProjectController extends Controller
{
    /**
     * Shows all projects
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @return Response
     */
    public function index(ProjectRepositoryInterface $projectRepository)
    {
        return $projectRepository->getAll();
    }

    /**
     * Shows a project
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @return Response
     */
    public function show(Project $project)
    {
        return $project;
    }

    /**
     * Store a newly created project in storage.
     *
     * @param StoreProjectRequest $request
     * @return Response
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->only(
            'name',
            'repository',
            'branch',
            'group_id',
            'builds_to_keep',
            'url',
            'build_url'
        ));

        return $project;
    }

    /**
     * Update the specified project in storage.
     *
     * @param Project $project
     * @param StoreProjectRequest $request
     * @return Response
     */
    public function update(Project $project, StoreProjectRequest $request)
    {
        $project->update($request->only(
            'name',
            'repository',
            'branch',
            'group_id',
            'builds_to_keep',
            'url',
            'build_url'
        ));

        return $project;
    }

    /**
     * Remove the specified project from storage.
     *
     * @param Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return [
            'success' => true
        ];
    }
}
