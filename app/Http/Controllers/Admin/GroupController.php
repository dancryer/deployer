<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\GroupController as GroupAPIController;
use Lang;

/**
 * Group management controller.
 */
class GroupController extends GroupAPIController
{
    /**
     * Display a listing of the groups.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.groups.listing', [
            'title'  => Lang::get('groups.manage'),
            'groups' => parent::index(),
        ]);
    }
}
