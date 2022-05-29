<?php

namespace App\Observers;

use App\Models\Fridge;

class FridgeObserver
{

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
