<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreTemplateRequest;
use App\Repositories\Contracts\TemplateRepositoryInterface;

/**
 * The controller for managing templates.
 */
class TemplateController extends ResourceController
{
    /**
     * Class constructor.
     *
     * @param  TemplateRepositoryInterface $repository
     * @return void
     */
    public function __construct(TemplateRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store a newly created template in storage.
     *
     * @param  StoreTemplateRequest $request
     * @return Response
     */
    public function store(StoreTemplateRequest $request)
    {
        return $this->repository->create($request->only(
            'name'
        ));
    }

    /**
     * Update the specified template in storage.
     *
     * @param  int                  $template_id
     * @param  StoreTemplateRequest $request
     * @return Response
     */
    public function update($template_id, StoreTemplateRequest $request)
    {
        return $this->repository->updateById($request->only(
            'name'
        ), $template_id);
    }
}
