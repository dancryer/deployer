<?php namespace App\Http\Controllers\Admin;

use Lang;
use App\Http\Controllers\API\GroupController as GroupResourceController;
use App\Repositories\Contracts\GroupRepositoryInterface;

/**
 * Group management controller
 */
class GroupController extends GroupResourceController
{
   /**
     * Display a listing of the groups.
     *
     * @return Response
     */
    public function index()
    {
        return view('groups.listing', [
            'title'  => Lang::get('groups.manage'),
            'groups' => $this->groupRepository->getAll()
        ]);
    }
}
