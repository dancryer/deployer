<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\API\UserController as UserResourceController;
use Lang;

/**
 * User management controller.
 */
class UserController extends UserResourceController
{
    /**
     * Display a listing of the users.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.users.listing', [
            'title' => Lang::get('users.manage'),
            'users' => parent::index(),
        ]);
    }
}
