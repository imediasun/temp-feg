<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderModuleTest extends TestCase
{
    const TEST_VENDOR = '114';

    public function testOrderGridPage(){
        $this->actingAs($this->superAdmin)
            ->visit('/order')->see(self::FEG_TITLE);
    }

    public function testGetAutoCompleteFunction(){
        $this->actingAs($this->superAdmin)
            ->get('/order/autocomplete?term=office&vendor_id='.self::TEST_VENDOR)
            ->seeJson([
                'value' => "HP OfficeJet Pro 8710 All-in-One Wireless Printer with Mobile Printing (M9L66A)",
            ]);
    }

    public function testGetProductInOrderController()
    {
        $orderController = new \App\Http\Controllers\OrderController();
        $products = json_decode($orderController->getProduct());
        $this->assertFalse(empty($products));
    }
}
