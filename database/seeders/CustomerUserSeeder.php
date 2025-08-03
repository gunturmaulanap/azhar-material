<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all customers that have username and password but no user_id
        $customers = Customer::whereNotNull('username')
            ->whereNotNull('password')
            ->whereNull('user_id')
            ->get();

        foreach ($customers as $customer) {
            // Create user with role 'customer'
            $user = User::create([
                'name' => $customer->name,
                'username' => $customer->username,
                'password' => $customer->password, // Password already hashed in customer table
                'role' => 'customer',
                'phone' => $customer->phone,
                'address' => $customer->address,
            ]);

            // Update customer with user_id
            $customer->update(['user_id' => $user->id]);

            $this->command->info("Created user for customer: {$customer->name} with username: {$customer->username}");
        }

        $this->command->info("Total users created: " . $customers->count());
    }
}
