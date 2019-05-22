<?php
/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 11/21/18
 * Time: 12:40 PM
 */

namespace App\Elastic\Entities;

class Feed extends BaseElasticModel
{
    /**
     * @var string
     */
    protected $index = 'business_review,business_post';

    /**
     * @var array
     */
    protected $columns = [
        'id',
        'business_name',
        'business_id',
        'images',
        'type',
        'location',
        'code',
        'comment',
        'meta',
        'text',
        'expire_date'
    ];
}