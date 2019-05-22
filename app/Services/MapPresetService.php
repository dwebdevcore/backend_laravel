<?php
/**
 * Created by PhpStorm.
 * User: byabuzyak
 * Date: 11/13/18
 * Time: 4:14 PM
 */

namespace App\Services;

use App\Models\MapPreset;
use App\Repositories\MapPresetsRepository;

class MapPresetService
{
    /**
     * @var MapPreset
     */
    private $mapPresetRepository;

    /**
     * MapPresetService constructor.
     * @param MapPresetsRepository $mapPresetRepository
     */
    public function __construct(MapPresetsRepository $mapPresetRepository)
    {
        $this->mapPresetRepository = $mapPresetRepository;
    }

    /**
     * @return MapPreset|\Illuminate\Database\Eloquent\Builder
     */
    public function getActive()
    {
        return $this->mapPresetRepository->getActive()->paginate();
    }
}