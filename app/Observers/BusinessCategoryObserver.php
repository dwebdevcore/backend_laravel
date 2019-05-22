<?php

namespace App\Observers;

use App\Models\Business;
use App\Models\BusinessCategory;

class BusinessCategoryObserver
{
    /**
     * Handle the business category "created" event.
     *
     * @param  \App\Models\BusinessCategory  $businessCategory
     * @return void
     */
    public function created(BusinessCategory $businessCategory)
    {
        Business::find($businessCategory->business_id)->updateScores();
    }

    /**
     * Handle the business category "updated" event.
     *
     * @param  \App\Models\BusinessCategory  $businessCategory
     * @return void
     */
    public function updated(BusinessCategory $businessCategory)
    {
        Business::find($businessCategory->business_id)->updateScores();
    }

    /**
     * Handle the business category "deleted" event.
     *
     * @param  \App\Models\BusinessCategory  $businessCategory
     * @return void
     */
    public function deleted(BusinessCategory $businessCategory)
    {
        Business::find($businessCategory->business_id)->updateScores();
    }

    /**
     * Handle the business category "restored" event.
     *
     * @param  \App\Models\BusinessCategory  $businessCategory
     * @return void
     */
    public function restored(BusinessCategory $businessCategory)
    {
        Business::find($businessCategory->business_id)->updateScores();
    }

    /**
     * Handle the business category "force deleted" event.
     *
     * @param  \App\Models\BusinessCategory  $businessCategory
     * @return void
     */
    public function forceDeleted(BusinessCategory $businessCategory)
    {
        Business::find($businessCategory->business_id)->updateScores();
    }
}
