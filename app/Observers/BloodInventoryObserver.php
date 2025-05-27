<?php

namespace App\Observers;

use App\Models\BloodInventory;
use App\Models\BloodStock;

class BloodInventoryObserver
{
    /**
     * Handle the BloodInventory "created" event.
     */
    public function created(BloodInventory $inventory): void
    {
        BloodStock::updateOrCreate(
            [
                'hospital_id' => $inventory->hospital_id,
                'blood_type' => $inventory->blood_type,
            ],
            [
                'quantity' => BloodInventory::where('hospital_id', $inventory->hospital_id)
                    ->where('blood_type', $inventory->blood_type)
                    ->sum('quantity'),
            ]
        );
    }

    /**
     * Handle the BloodInventory "updated" event.
     */
    public function updated(BloodInventory $inventory): void
    {
        BloodStock::updateOrCreate(
            [
                'hospital_id' => $inventory->hospital_id,
                'blood_type' => $inventory->blood_type,
            ],
            [
                'quantity' => BloodInventory::where('hospital_id', $inventory->hospital_id)
                    ->where('blood_type', $inventory->blood_type)
                    ->sum('quantity'),
            ]
        );
    }

    /**
     * Handle the BloodInventory "deleted" event.
     */
    public function deleted(BloodInventory $inventory): void
    {
        $totalQuantity = BloodInventory::where('hospital_id', $inventory->hospital_id)
            ->where('blood_type', $inventory->blood_type)
            ->sum('quantity');

        if ($totalQuantity > 0) {
            BloodStock::updateOrCreate(
                [
                    'hospital_id' => $inventory->hospital_id,
                    'blood_type' => $inventory->blood_type,
                ],
                [
                    'quantity' => $totalQuantity,
                ]
            );
        } else {
            BloodStock::where('hospital_id', $inventory->hospital_id)
                ->where('blood_type', $inventory->blood_type)
                ->delete();
        }
    
    }

    /**
     * Handle the BloodInventory "restored" event.
     */
    public function restored(BloodInventory $bloodInventory): void
    {
        //
    }

    /**
     * Handle the BloodInventory "force deleted" event.
     */
    public function forceDeleted(BloodInventory $bloodInventory): void
    {
        //
    }
}
