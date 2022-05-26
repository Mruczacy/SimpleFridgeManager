<?php

namespace App\Observers;

use App\Models\Fridge;

class FridgeObserver
{
    /**
     * Handle the Fridge "created" event.
     *
     * @param  \App\Models\Fridge  $fridge
     * @return void
     */
    public function created(Fridge $fridge)
    {
        //
    }

    /**
     * Handle the Fridge "updated" event.
     *
     * @param  \App\Models\Fridge  $fridge
     * @return void
     */
    public function updated(Fridge $fridge)
    {
        //
    }

    /**
     * Handle the Fridge "deleted" event.
     *
     * @param  \App\Models\Fridge  $fridge
     * @return void
     */
    public function deleted(Fridge $fridge)
    {
        foreach ($fridge->users as $user) {
            $user->fridges()->detach($fridge->id);
        }
        $fridge->delete();
    }

    /**
     * Handle the Fridge "restored" event.
     *
     * @param  \App\Models\Fridge  $fridge
     * @return void
     */
    public function restored(Fridge $fridge)
    {
        //
    }

    /**
     * Handle the Fridge "force deleted" event.
     *
     * @param  \App\Models\Fridge  $fridge
     * @return void
     */
    public function forceDeleted(Fridge $fridge)
    {
        foreach ($fridge->users as $user) {
            $user->fridges()->detach($fridge->id);
        }
    }
}
