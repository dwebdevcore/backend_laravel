<?php

namespace App\Repositories;

use App\Elastic\Entities\Business;
use App\Elastic\Rules\BusinessRule;
use App\Elastic\Rules\BusinessSuggestRule;
use App\Elastic\Rules\SimilarBusinessesRule;
use App\Models\MapPreset;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class BusinessesRepository
{
    /**
     * @var Business
     */
    private $elasticModel;

    /**
     * @var \App\Models\Business
     */
    private $eloquentModel;

    /**
     * @var int
     */
    public $perPage = 10;

    /**
     * @var string
     */
    public $pageName = 'page';

    /**
     * BusinessRepository constructor.
     * @param Business $elasticBusiness
     * @param \App\Models\Business $eloquentBusiness
     */
    public function __construct(Business $elasticBusiness, \App\Models\Business $eloquentBusiness)
    {
        $this->elasticModel = $elasticBusiness;
        $this->eloquentModel = $eloquentBusiness;
    }

    /**
     * @param $rawResults
     * @return LengthAwarePaginator
     */
    private function buildResult($rawResults)
    {
        $results   = $rawResults->isNotEmpty() ? $this->eloquentModel->find($rawResults) : [];
        $paginator = (new LengthAwarePaginator($results, $rawResults->total(), $this->perPage, Paginator::resolveCurrentPage($this->pageName), [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => $this->pageName,
        ]));

        return
            $paginator;
    }

    /**
     * @param float $lat
     * @param float $lng
     * @param string $query
     * @param int $categoryId
     * @param MapPreset $mapPreset
     * @return LengthAwarePaginator
     */
    public function get($lat, $lng, $query = '*', ?int $categoryId, ?MapPreset $mapPreset): LengthAwarePaginator
    {
        $businessRule = BusinessRule::build($lat, $lng, $query, $categoryId, $mapPreset);
        $rawResults   = $this->elasticModel->query($businessRule)->paginate($this->perPage);
        return
            $this->buildResult($rawResults);
    }

    /**
     * @param \App\Models\Business $business
     * @return LengthAwarePaginator
     */
    public function similar(\App\Models\Business $business): LengthAwarePaginator
    {
        $rawResults = $this->elasticModel->query(SimilarBusinessesRule::build($business))->paginate($this->perPage);
        return
            $this->buildResult($rawResults);
    }

    /**
     * @param int $businessId
     * @return \App\Models\Business
     */
    public function find(int $businessId): ?\App\Models\Business
    {
        return
            \App\Models\Business::with('categories')->find($businessId);
    }

    /**
     * @param string $query
     * @return array
     */
    public function suggest(string $query): array
    {
        return
            $this
                ->elasticModel
                ->rule(BusinessSuggestRule::build($query))
                ->all();
    }
}