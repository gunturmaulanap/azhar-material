<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        // If customer has username and password but no user_id, create a user
        if ($customer->username && $customer->password && !$customer->user_id) {
            $user = User::create([
                'name' => $customer->name,
                'username' => $customer->username,
                'password' => $customer->password, // Already hashed
                'role' => 'customer',
                'phone' => $customer->phone,
                'address' => $customer->address,
            ]);

            // Update customer with user_id without triggering observer again
            $customer->updateQuietly(['user_id' => $user->id]);
        }
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        // If customer has a linked user, sync the changes
        if ($customer->user_id && $customer->user) {
            $userUpdates = [];
            
            // Check what fields changed and sync them
            if ($customer->wasChanged('name')) {
                $userUpdates['name'] = $customer->name;
            }
            
            if ($customer->wasChanged('username')) {
                $userUpdates['username'] = $customer->username;
            }
            
            if ($customer->wasChanged('phone')) {
                $userUpdates['phone'] = $customer->phone;
            }
            
            if ($customer->wasChanged('address')) {
                $userUpdates['address'] = $customer->address;
            }
            
            if ($customer->wasChanged('password')) {
                $userUpdates['password'] = $customer->password;
            }

            // Update user if there are changes
            if (!empty($userUpdates)) {
                $customer->user->updateQuietly($userUpdates);
            }
        }
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        // Optionally delete the associated user when customer is deleted
        if ($customer->user_id && $customer->user) {
            $customer->user->delete();
        }
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
