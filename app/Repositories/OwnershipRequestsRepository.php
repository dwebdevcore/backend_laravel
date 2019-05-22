<?php

namespace App\Repositories;

use App\Models\OwnershipRequest;
use Carbon\Carbon;

class OwnershipRequestsRepository
{
    /**
     * @var OwnershipRequest
     */
    private $model;

    public function __construct(OwnershipRequest $model)
    {
        $this->model = $model;
    }

    public function activeRequestExists(int $userId)
    {
        return $this->model->selectRaw('1')
            ->where('user_id', $userId)
            ->isNotConfirmed()
            ->isNotExpired()
            ->exists();
    }

    /**
     * @param int $userId
     * @param int $businessId
     * @return OwnershipRequest|null
     */
    public function getActiveRequest(int $userId, int $businessId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('business_id', $businessId)
            ->isNotConfirmed()
            ->isNotExpired()
            ->first();
    }

    public function confirmRequest(OwnershipRequest $ownershipRequest)
    {
        $ownershipRequest['confirmed_at'] = Carbon::now();

        $ownershipRequest->save();

        return $this;
    }

    public function create(int $userId, int $businessId, string $method, string $address, string $token, array $userInfo = [])
    {
        return $this->model->create([
            'user_id' => $userId,
            'business_id' => $businessId,
            'method' => $method,
            'address' => $address,
            'token' => $token,
            'user_info' => $userInfo,
        ]);
    }
}
