<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderModuleTest extends TestCase
{

    public function testOrderGridPage(){
        $this->actingAs($this->superAdmin)
            ->visit('/order')
            ->see(self::FEG_TITLE);
    }

    public function testGetAutoComplete(){
        $product = 'Printer, Laser Monochrome HP Laserjet Pro P1102W';
        $this->actingAs($this->superAdmin)
            ->get('/order/autocomplete?term=office&vendor_id='.self::TEST_VENDOR)
            ->seeJson([
                'value' => $product,
            ]);

        $this->actingAs($this->superAdmin)
            ->get('/order/autocomplete?term=office')
            ->seeJson([
                'value' => $product,
            ]);

        $this->actingAs($this->superAdmin)
            ->get('/order/autocomplete?term=```&vendor_id='.self::TEST_VENDOR)
            ->seeJson([
                'value' => 'No Match',
            ]);
    }

    public function testGetComboSelect(){

        $json = $this->actingAs($this->superAdmin)
            ->get('/order/comboselect?filter=order_type:id:order_type&parent=can_request:1', ['X-Requested-With' => 'XMLHttpRequest'])
        ->response->getContent();
        $json = json_decode($json);
        if($json){
            $this->assertTrue(is_array($json[0]));
        }else{
            $this->assertFalse(true);
        }

        $json = $this->actingAs($this->superAdmin)
            ->get('/order/comboselect?filter=order_type:id:order_type', ['X-Requested-With' => 'XMLHttpRequest'])
            ->response->getContent();
        $json = json_decode($json);
        if($json){
            $this->assertTrue(is_array($json[0]));
        }else{
            $this->assertFalse(true);
        }

        $this->actingAs($this->superAdmin)
            ->get('/order/comboselect?filter=order_type:id:order_type&parent=can_request:1')
            ->seeJson([
                'OMG' => ' Ops .. Cant access the page !',
            ]);
    }

    public function testGetProductInOrderController()
    {
        $orderController = new \App\Http\Controllers\OrderController();
        $products = json_decode($orderController->getProduct());
        $this->assertFalse(empty($products));
    }
}
