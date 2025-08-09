<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class TestAuthMiddleware extends Command
{
    protected $signature = 'test:auth';
    protected $description = 'Test authentication status';

    public function handle()
    {
        $this->info('Testing Authentication Status...');
        
        // Test web guard
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $this->info("Web Guard: Authenticated as {$user->name} (Role: {$user->role})");
        } else {
            $this->info('Web Guard: Not authenticated');
        }
        
        // Test customer guard
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $this->info("Customer Guard: Authenticated as {$customer->name}");
        } else {
            $this->info('Customer Guard: Not authenticated');
        }
        
        $this->info('Test completed.');
    }
}
