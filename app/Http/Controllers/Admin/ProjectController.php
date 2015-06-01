<?php namespace App\Http\Controllers\Admin;

use Lang;
use App\Http\Controllers\API\ProjectController as ProjectResourceController;
use App\Repositories\Contracts\ProjectRepositoryInterface;

/**
 * The controller for managing projects
 */
class ProjectController extends ProjectResourceController
{
    /**
     * Shows all projects
     *
     * @return Response
     */
    public function index()
    {
        $projects = $this->projectRepository->getAll();

        return view('projects.listing', [
            'title'    => Lang::get('projects.manage'),
            'projects' => $projects->toJson() // Because PresentableInterface toJson() is not working in the view
        ]);
    }
}
