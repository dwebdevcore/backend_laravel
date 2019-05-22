<?php

namespace App\Observers;

use App\Models\BusinessReview;

class BusinessReviewObserver
{
    /**
     * Handle the business review "created" event.
     *
     * @param  \App\Models\BusinessReview  $businessReview
     * @return void
     */
    public function created(BusinessReview $businessReview)
    {
        $businessReview->business->updateScores();
    }

    /**
     * Handle the business review "updated" event.
     *
     * @param  \App\Models\BusinessReview  $businessReview
     * @return void
     */
    public function updated(BusinessReview $businessReview)
    {
        if ($businessReview->wasChanged('code')) {
            $businessReview->business->updateScores();
        }
    }

    /**
     * Handle the business review "deleted" event.
     *
     * @param  \App\Models\BusinessReview  $businessReview
     * @return void
     */
    public function deleted(BusinessReview $businessReview)
    {
        $businessReview->business->updateScores();
    }

    /**
     * Handle the business review "restored" event.
     *
     * @param  \App\Models\BusinessReview  $businessReview
     * @return void
     */
    public function restored(BusinessReview $businessReview)
    {
        $businessReview->business->updateScores();
    }

    /**
     * Handle the business review "force deleted" event.
     *
     * @param  \App\Models\BusinessReview  $businessReview
     * @return void
     */
    public function forceDeleted(BusinessReview $businessReview)
    {
        $businessReview->business->updateScores();
    }
}
