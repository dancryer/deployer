<?php namespace App\Http\Controllers\Admin;

use Lang;
use App\Http\Controllers\API\ProjectController as ProjectAPIController;
use App\Repositories\Contracts\ProjectRepositoryInterface;

/**
 * The controller for managging projects
 */
class ProjectController extends ProjectAPIController
{
    /**
     * Shows all projects
     *
     * @param ProjectRepositoryInterface $projectRepository
     * @return Response
     */
    public function index(ProjectRepositoryInterface $projectRepository)
    {
        $projects = $projectRepository->getAll();

        return view('projects.listing', [
            'title'    => Lang::get('projects.manage'),
            'projects' => $projects->toJson() // Because PresentableInterface toJson() is not working in the view
        ]);
    }
}
