<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapPresetBusinessHours extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function preset() {
        return $this->belongsTo(MapPreset::class);
    }

    public function setOpenPeriodMinsAttribute($value) {
        $this->attributes['open_period_mins'] = Business::minutesCnt($value);
    }

    public function setClosePeriodMinsAttribute($value) {
        $this->attributes['close_period_mins'] = Business::minutesCnt($value);
    }

    public function getOpenAttribute() {
        if (null === $this->open_period_mins) {
            return null;
        }

        return date('h:ia', mktime(0, $this->open_period_mins));
    }

    public function getCloseAttribute() {
        if (null === $this->close_period_mins) {
            return null;
        }

        return date('h:ia', mktime(0, $this->close_period_mins));
    }
}
