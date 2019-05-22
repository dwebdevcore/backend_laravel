<?php

namespace App\Observers;

use App\Models\Business;
use App\Models\BusinessAttribute;

class BusinessAttributeObserver
{
    /**
     * Handle the business attribute "created" event.
     *
     * @param  \App\Models\BusinessAttribute  $businessAttribute
     * @return void
     */
    public function created(BusinessAttribute $businessAttribute)
    {
        Business::find($businessAttribute->business_id)->updateScores();
    }

    /**
     * Handle the business attribute "updated" event.
     *
     * @param  \App\Models\BusinessAttribute  $businessAttribute
     * @return void
     */
    public function updated(BusinessAttribute $businessAttribute)
    {
        Business::find($businessAttribute->business_id)->updateScores();
    }

    /**
     * Handle the business attribute "deleted" event.
     *
     * @param  \App\Models\BusinessAttribute  $businessAttribute
     * @return void
     */
    public function deleted(BusinessAttribute $businessAttribute)
    {
        Business::find($businessAttribute->business_id)->updateScores();
    }

    /**
     * Handle the business attribute "restored" event.
     *
     * @param  \App\Models\BusinessAttribute  $businessAttribute
     * @return void
     */
    public function restored(BusinessAttribute $businessAttribute)
    {
        Business::find($businessAttribute->business_id)->updateScores();
    }

    /**
     * Handle the business attribute "force deleted" event.
     *
     * @param  \App\Models\BusinessAttribute  $businessAttribute
     * @return void
     */
    public function forceDeleted(BusinessAttribute $businessAttribute)
    {
        Business::find($businessAttribute->business_id)->updateScores();
    }
}
