<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\API\ResourceController;
use App\Http\Controllers\API\ServerController as ServerResourceController;
use Input;

/**
 * Server management controller.
 */
class ServerController extends ServerResourceController
{
    /**
     * Queues a connection test for the specified server.
     *
     * @param  int      $server_id
     * @return Response
     */
    public function test($server_id)
    {
        $this->repository->queueForTesting($server_id);

        return [
            'success' => true,
        ];
    }

    /**
     * Re-generates the order for the supplied servers.
     *
     * @return Response
     */
    public function reorder()
    {
        $order = 0;

        foreach (Input::get('servers') as $server_id) {
            $this->repository->updateById([
                'order' => $order,
            ], $server_id);

            $order++;
        }

        return [
            'success' => true,
        ];
    }
}
