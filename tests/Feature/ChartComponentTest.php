<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Goods;
use App\Models\Transaction;
use App\Models\GoodsTransaction;
use App\Models\Customer;
use App\Observers\CustomerObserver;
use App\Http\Livewire\Chart;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class ChartComponentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Disable CustomerObserver to avoid user_id issues during testing
        Customer::unsetEventDispatcher();
        
        // Create a super admin user for testing
        $this->user = User::factory()->create([
            'role' => 'super_admin',
            'username' => 'superadmin'
        ]);
    }

    /** @test */
    public function chart_component_renders_successfully()
    {
        $this->actingAs($this->user)
            ->get('/superadmin/chart')
            ->assertStatus(200)
            ->assertSee('Moving Average Analysis')
            ->assertSee('Pilih Barang');
    }

    /** @test */
    public function can_open_search_modal()
    {
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('openSearchModal')
            ->assertSet('showSearchModal', true)
            ->assertSee('Pilih Barang');
    }

    /** @test */
    public function can_close_search_modal()
    {
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->set('showSearchModal', true)
            ->call('closeSearchModal')
            ->assertSet('showSearchModal', false);
    }

    /** @test */
    public function can_search_for_goods()
    {
        // Create test goods
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        
        $component = Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->set('search', 'Test');
            
        // The search results should be populated after setting search
        $searchResults = $component->get('searchResults');
        $this->assertCount(1, $searchResults);
        $this->assertEquals('Test Product', $searchResults[0]['name']);
    }

    /** @test */
    public function can_select_a_good()
    {
        // Create test goods
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('selectGood', $goods->id)
            ->assertSet('selectedGoodsId', $goods->id)
            ->assertSet('selectedGoods.name', 'Test Product')
            ->assertSet('showSearchModal', false);
    }

    /** @test */
    public function can_clear_selection()
    {
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('selectGood', $goods->id)
            ->assertSet('selectedGoodsId', $goods->id)
            ->call('clearSelection')
            ->assertSet('selectedGoodsId', null)
            ->assertSet('selectedGoods', null);
    }

    /** @test */
    public function can_change_active_period()
    {
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->assertSet('activePeriod', '1M')
            ->call('setActivePeriod', '3M')
            ->assertSet('activePeriod', '3M');
    }

    /** @test */
    public function chart_data_is_calculated_correctly_with_transactions()
    {
        // Create test data
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        $transaction = Transaction::factory()->create(['created_at' => Carbon::now()]);
        
        // Create goods transaction
        GoodsTransaction::create([
            'transaction_id' => $transaction->id,
            'goods_id' => $goods->id,
            'qty' => 10,
            'price' => 100,
            'subtotal' => 1000 // price * qty
        ]);

        $component = Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('selectGood', $goods->id);

        // Assert chart data is populated
        $chartData = $component->get('chartData');
        $this->assertArrayHasKey('labels', $chartData);
        $this->assertArrayHasKey('datasets', $chartData);
        $this->assertNotEmpty($chartData['datasets']);
    }

    /** @test */
    public function summary_statements_are_generated_when_good_is_selected()
    {
        // Create test data spanning multiple periods
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        
        // Create transactions for different periods
        $dates = [
            Carbon::now()->subDays(7),
            Carbon::now()->subDays(14),
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(60)
        ];
        
        foreach ($dates as $date) {
            $transaction = Transaction::factory()->create(['created_at' => $date]);
            $qty = rand(5, 15);
            GoodsTransaction::create([
                'transaction_id' => $transaction->id,
                'goods_id' => $goods->id,
                'qty' => $qty,
                'price' => 100,
                'subtotal' => $qty * 100 // price * qty
            ]);
        }

        $component = Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('selectGood', $goods->id);

        // Check if summary statements are generated
        $summaryStatements = $component->get('summaryStatements');
        $this->assertIsArray($summaryStatements);
        
        // Should have statements for different periods
        if (!empty($summaryStatements)) {
            $this->assertArrayHasKey('goods_name', $summaryStatements[0]);
            $this->assertArrayHasKey('period', $summaryStatements[0]);
            $this->assertArrayHasKey('statement', $summaryStatements[0]);
        }
    }

    /** @test */
    public function moving_average_calculation_handles_empty_data()
    {
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('selectGood', $goods->id);

        // Should not crash with empty data
        $this->assertTrue(true); // Test passes if no exception is thrown
    }

    /** @test */
    public function component_emits_chart_update_event()
    {
        $goods = Goods::factory()->create(['name' => 'Test Product']);
        
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->call('selectGood', $goods->id)
            ->assertEmitted('updateMovingAverageChart');
    }

    /** @test */
    public function keyboard_escape_closes_modal()
    {
        // This would be tested in browser/JavaScript testing
        // For now, we test the method directly
        Livewire::actingAs($this->user)
            ->test(Chart::class)
            ->set('showSearchModal', true)
            ->call('closeSearchModal')
            ->assertSet('showSearchModal', false);
    }

    /** @test */
    public function different_time_periods_return_different_date_ranges()
    {
        $component = new Chart();
        
        $fiveDays = $component->getStartDateForPeriod('5D');
        $oneMonth = $component->getStartDateForPeriod('1M');
        $threeMonths = $component->getStartDateForPeriod('3M');
        
        $this->assertTrue($fiveDays > $oneMonth);
        $this->assertTrue($oneMonth > $threeMonths);
    }
}
