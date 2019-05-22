<?php
/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 11/21/18
 * Time: 10:43 AM
 */

namespace App\Services;

use App\Repositories\FeedRepository;
use App\Rules\Uuid;

class FeedService
{
    /**
     * @var FeedRepository
     */
    private $feedRepository;

    /**
     * FeedService constructor.
     * @param FeedRepository $feedRepository
     */
    public function __construct(FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;
    }

    /**
     * @param $lat
     * @param $lng
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function get($lat, $lng)
    {
        return $this->feedRepository->get($lat, $lng);
    }

    /**
     * @param $businessId
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \Exception
     */
    public function forBusiness($businessId)
    {
        if (!(new Uuid())->passes('business_id', $businessId)) {
            throw new \Exception("The required business_id param is invalid");
        }
        return $this->feedRepository->forBusiness($businessId);
    }

    /**
     * @return mixed
     */
    public function forUser() {
        return $this->feedRepository->forUser();
    }
}