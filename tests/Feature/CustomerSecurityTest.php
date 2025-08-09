<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        
        // Create test customers
        $this->customer1 = Customer::create([
            'name' => 'Customer 1',
            'phone' => '081234567890',
            'address' => 'Address 1',
            'username' => 'customer1',
            'password' => Hash::make('password'),
            'balance' => 100000,
            'debt' => 0,
        ]);
        
        $this->customer2 = Customer::create([
            'name' => 'Customer 2', 
            'phone' => '081234567891',
            'address' => 'Address 2',
            'username' => 'customer2',
            'password' => Hash::make('password'),
            'balance' => 50000,
            'debt' => 0,
        ]);
        
        // Create test transaction for customer 1
        $this->transaction1 = Transaction::create([
            'user_id' => $this->user->id,
            'customer_id' => $this->customer1->id,
            'total' => 25000,
            'balance' => 25000,
            'bill' => 0,
            'grand_total' => 0,
            'return' => 0,
            'status' => 'selesai'
        ]);
        
        // Create test delivery for customer 1's transaction
        $this->delivery1 = Delivery::create([
            'transaction_id' => $this->transaction1->id,
            'status' => 'pengiriman'
        ]);
    }
    
    public function test_customer_cannot_access_other_customer_dashboard(): void
    {
        // Login as customer 2
        $this->actingAs($this->customer2, 'customer');
        
        // Try to access customer 1's dashboard
        $response = $this->get('/customer/' . $this->customer1->id);
        
        // Should return 403 Forbidden
        $response->assertStatus(403);
    }
    
    public function test_customer_can_access_own_dashboard(): void
    {
        // Login as customer 1
        $this->actingAs($this->customer1, 'customer');
        
        // Access own dashboard
        $response = $this->get('/customer/' . $this->customer1->id);
        
        // Should return 200 OK
        $response->assertStatus(200);
    }
    
    public function test_customer_cannot_access_other_customer_transaction(): void
    {
        // Login as customer 2
        $this->actingAs($this->customer2, 'customer');
        
        // Try to access customer 1's transaction
        $response = $this->get('/detail-transaksi/' . $this->transaction1->id);
        
        // Should return 403 Forbidden
        $response->assertStatus(403);
    }
    
    public function test_customer_can_access_own_transaction(): void
    {
        // Login as customer 1
        $this->actingAs($this->customer1, 'customer');
        
        // Access own transaction
        $response = $this->get('/detail-transaksi/' . $this->transaction1->id);
        
        // Should return 200 OK
        $response->assertStatus(200);
    }
    
    public function test_customer_cannot_access_other_customer_delivery(): void
    {
        // Login as customer 2
        $this->actingAs($this->customer2, 'customer');
        
        // Try to access customer 1's delivery
        $response = $this->get('/pengiriman-barang/' . $this->delivery1->id);
        
        // Should return 403 Forbidden
        $response->assertStatus(403);
    }
    
    public function test_customer_can_access_own_delivery(): void
    {
        // Login as customer 1
        $this->actingAs($this->customer1, 'customer');
        
        // Access own delivery
        $response = $this->get('/pengiriman-barang/' . $this->delivery1->id);
        
        // Should return 200 OK
        $response->assertStatus(200);
    }
    
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        // Try to access customer dashboard without authentication
        $response = $this->get('/customer/' . $this->customer1->id);
        
        // Should redirect to login
        $response->assertRedirect('/login');
    }
}
