<?php

namespace App\Repositories;

use App\Models\Ownerships;

class OwnershipsRepository
{
    /**
     * @var Ownerships
     */
    private $model;

    public function __construct(Ownerships $model)
    {
        $this->model = $model;
    }

    public function create($userId, $businessId, $requestId = null)
    {
        return $this->model->create([
            'user_id' => $userId,
            'business_id' => $businessId,
            'request_id' => $requestId,
        ]);
    }
}
