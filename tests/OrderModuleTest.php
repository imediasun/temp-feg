<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderModuleTest extends TestCase
{
    private $order = null;
    private $orderController = null;
    private $orderModel = null;

    function setUp(){
        parent::setUp();
        $controller = new \App\Http\Controllers\OrderController();
        $this->orderController = $controller;

        $model = new \App\Models\Order();
        $this->orderModel = $model;
        $this->order = $model->orderBy('id', 'desc')->first();
    }

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

    public function testIsPOAvailable(){
        $po = $this->order->po_number;
        $this->assertFalse($this->orderModel->isPOAvailable($po));
        $this->assertTrue($this->orderModel->isPOAvailable("2008-000000-000"));
    }

    public function testGetOrderReceipt(){
        $order_id = $this->order->id;
        $this->actingAs($this->superAdmin)
            ->get('/order/orderreceipt/'.$order_id)
            ->see('Order Receipt');

        $this->actingAs($this->superAdmin)
            ->get('/order/orderreceipt/')
            ->see('Whoops, looks like something went wrong.');
    }

    public function testGetProductInOrderController()
    {
        $orderController = new \App\Http\Controllers\OrderController();
        $products = json_decode($orderController->getProduct());
        $this->assertFalse(empty($products));
    }
}
