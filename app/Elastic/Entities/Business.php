<?php

namespace App\Elastic\Entities;

class Business extends BaseElasticModel
{
    /**
     * @var string
     */
    protected $index = 'business';
    /**
     * @var string
     */
    protected $indexType = 'businesses';
}