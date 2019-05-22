<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Category;
use App\Models\MapPreset;
use App\Repositories\BusinessesRepository;
use App\Repositories\MapPresetsRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class BusinessService
{
    /**
     * @var BusinessesRepository
     */
    private $businessRepository;

    /**
     * @var MapPresetsRepository
     */
    private $mapPresetRepository;

    /**
     * BusinessService constructor.
     * @param BusinessesRepository $businessRepository
     * @param MapPresetsRepository $mapPresetRepository
     */
    public function __construct(BusinessesRepository $businessRepository, MapPresetsRepository $mapPresetRepository)
    {
        $this->businessRepository  = $businessRepository;
        $this->mapPresetRepository = $mapPresetRepository;
    }

    /**
     * @param float|null $lat
     * @param float|null $lng
     * @param string $query
     * @param int|null $categoryId
     * @param int|null $mapPresetId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|LengthAwarePaginator
     * @throws \Exception
     */
    public function get(?float $lat, ?float $lng, ?string $query = '*', $categoryId = null, $mapPresetId = null)
    {
        $mapPreset = null;
        $query     = $query ?? '*';

        if (null !== $mapPresetId) {
            $mapPreset = $this->mapPresetRepository->getActive(MapPreset::uuid($mapPresetId)->id);
            if (!$mapPreset) {
                throw new \Exception("Map preset is inactive currently");
            }
        }

        if (null !== $categoryId) {
            $categoryId = Category::uuid($categoryId)->id;
        }

        return
            $this->businessRepository->get($lat, $lng, $query, $categoryId, $mapPreset);
    }

    /**
     * @param int $businessId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @throws \Exception
     */
    public function similar($businessId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $business = $this->businessRepository->find(Business::uuid($businessId)->id);

        if (!$business) {
            throw new \Exception("Business not found");
        }

        return
            $this->businessRepository->similar($business);
    }

    /**
     * @param null|string $query
     * @return array
     * @throws \Exception
     */
    public function suggest(?string $query = ''): array
    {
        if ('' == $query) {
            throw new \Exception("The required text option is missing");
        }
        $suggestions = $this->businessRepository->suggest($query);
        $result      = [
            'query'       => $query,
            'suggestions' => []
        ];
        foreach ($suggestions['suggest']['business-suggest'] as $businessSuggest) {
            foreach ($businessSuggest['options'] as $option) {
                $result['suggestions'][] = $option['_source']['suggest'];
            }
        }

        return
            $result;
    }
}