<?php

namespace App\Http\Controllers\API;

use App\Group;
use App\Http\Controllers\Resources\ResourceController as Controller;
use App\Http\Requests\StoreGroupRequest;
use App\Repositories\Contracts\GroupRepositoryInterface;

/**
 * The controller for managing groups.
 */
class GroupController extends Controller
{
    /**
     * Class constructor.
     *
     * @param  GroupRepositoryInterface $repository
     * @return void
     */
    public function __construct(GroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created group in storage.
     *
     * @param  StoreGroupRequest $request
     * @return Response
     */
    public function store(StoreGroupRequest $request)
    {
        return $this->repository->create($request->only(
            'name'
        ));
    }

    /**
     * Update the specified group in storage.
     *
     * @param  int               $group_id
     * @param  StoreGroupRequest $request
     * @return Response
     */
    public function update($group_id, StoreGroupRequest $request)
    {
        return $this->repository->updateById($request->only(
            'name'
        ), $group_id);
    }
}
