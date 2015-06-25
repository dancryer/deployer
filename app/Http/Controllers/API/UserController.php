<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Resources\ResourceController as Controller;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\Contracts\UserRepositoryInterface;

/**
 * The controller for managing users.
 */
class UserController extends Controller
{
    /**
     * Class constructor.
     *
     * @param  UserRepositoryInterface $repository
     * @return void
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  StoreUserRequest $request
     * @return Response
     */
    public function store(StoreUserRequest $request)
    {
        return $this->repository->create($request->only(
            'name',
            'email',
            'password'
        ));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  int              $user_id
     * @param  StoreUserRequest $request
     * @return Response
     */
    public function update($user_id, StoreUserRequest $request)
    {
        return $this->repository->updateById($request->only(
            'name',
            'email',
            'password'
        ), $user_id);
    }
}
