<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;

/**
 * Generic Controller class.
 */
abstract class ResourceController extends Controller
{
    /**
     * The model repository.
     *
     * @var EloquentRepository
     */
    protected $repository;

    /**
     * Shows all models in the repository.
     *
     * @return Response
     */
    public function index()
    {
        return $this->repository->getAll();
    }

    /**
     * Shows a specific instance of the model from the repository.
     *
     * @param  int      $project_id
     * @return Response
     */
    public function show($model_id)
    {
        return $this->repository->getById($model_id);
    }

    /**
     * Remove the specified model from storage.
     *
     * @param  int      $model_id
     * @return Response
     */
    public function destroy($model_id)
    {
        $this->repository->deleteById($model_id);

        return [
            'success' => true,
        ];
    }
}
