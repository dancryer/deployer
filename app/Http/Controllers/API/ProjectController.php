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
     * The project repository
     *
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * Class constructor
     *
     * @param GroupRepositoryInterface $projectRepository
     * @return void
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Shows all projects
     *
     * @return Response
     */
    public function index()
    {
        return $this->projectRepository->getAll();
    }

    /**
     * Shows a project
     *
     * @param int $project_id
     * @return Response
     */
    public function show($project_id)
    {
        return $this->projectRepository->getById($project_id);
    }

    /**
     * Store a newly created project in storage.
     *
     * @param StoreProjectRequest $request
     * @return Response
     */
    public function store(StoreProjectRequest $request)
    {
        return $this->projectRepository->create($request->only(
            'name',
            'repository',
            'branch',
            'group_id',
            'builds_to_keep',
            'url',
            'build_url'
        ));
    }

    /**
     * Update the specified project in storage.
     *
     * @param int $project_id
     * @param StoreProjectRequest $request
     * @return Response
     */
    public function update($project_id, StoreProjectRequest $request)
    {
        return $this->projectRepository->updateById($request->only(
            'name',
            'repository',
            'branch',
            'group_id',
            'builds_to_keep',
            'url',
            'build_url'
        ), $project_id);
    }

    /**
     * Remove the specified project from storage.
     *
     * @param int $project_id
     * @return Response
     */
    public function destroy($project_id)
    {
        $this->projectRepository->delete($project_id);

        return [
            'success' => true
        ];
    }
}
