<?php

namespace App\Repositories;

use App\Models\BusinessAttribute;

class BusinessAttributesRepository
{
    /**
     * @var BusinessAttribute
     */
    private $model;

    public function __construct(BusinessAttribute $model)
    {
        $this->model = $model;
    }

    public function getAttributesValues($businessId, ...$names)
    {
        return $this->model->select('key', 'value')
            ->where('business_id', $businessId)
            ->whereIn('key', $names)
            ->get();
    }
}
