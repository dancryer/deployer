<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\TemplateController as TemplateResourceController;
use Lang;

/**
 * Controller for managing deployment template.
 */
class TemplateController extends TemplateResourceController
{
    /**
     * Shows all templates.
     *
     * @return Response
     */
    public function index()
    {
        $templates = parent::index();

        return view('admin.templates.listing', [
            'title'     => Lang::get('templates.manage'),
            'templates' => $templates->toJson(), // Because PresentableInterface toJson() is not working in the view
        ]);
    }

    /**
     * Show the template configuration.
     *
     * @param  int      $template_id
     * @return Response
     */
    public function show($template_id)
    {
        $template = parent::show($template_id);

        return view('admin.templates.details', [
            'breadcrumb' => [
                ['url' => url('admin/templates'), 'label' => Lang::get('templates.label')],
            ],
            'title'         => $template->name,
            'sharedFiles'   => $template->sharedFiles,
            'projectFiles'  => $template->projectFiles,
            'project'       => $template,
            'route'         => 'template.commands',
        ]);
    }
}
