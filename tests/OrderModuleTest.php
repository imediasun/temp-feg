<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderModuleTest extends TestCase
{

    public function testOrderGridPage(){
        $this->visit('/order')
            ->see(' FEG LLC ');
    }

    public function testGetAutoCompleteFunction(){
        $this->get('/order?term=office&vendor_id='.self::TEST_VENDOR)
            ->seeJson([
                'value' => "HP1 OfficeJet Pro 8710 All-in-One Wireless Printer with Mobile Printing (M9L66A)",
            ]);
    }

    public function testGetProductInOrderController()
    {
        $orderController = new \App\Http\Controllers\OrderController();
        $products = json_decode($orderController->getProduct());
        $this->assertFalse(empty($products));
    }
}
