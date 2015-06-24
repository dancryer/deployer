<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\ProjectController as ProjectAPIController;
use App\Http\Requests\StoreProjectRequest;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TemplateRepositoryInterface;
use Lang;

/**
 * The controller for managging projects.
 */
class ProjectController extends ProjectAPIController
{
    /**
     * Shows all projects.
     *
     * @param  TemplateRepositoryInterface $templateRepository
     * @return Response
     */
    public function index(TemplateRepositoryInterface $templateRepository)
    {
        $projects = parent::index();

        return view('admin.projects.listing', [
            'title'     => Lang::get('projects.manage'),
            'templates' => $templateRepository->getAll(),
            'projects'  => $projects->toJson(), // Because PresentableInterface toJson() is not working in the view
        ]);
    }
}
