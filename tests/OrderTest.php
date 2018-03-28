<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetProductInOrderController()
    {
        $orderController = new \App\Http\Controllers\OrderController();
        $products = json_decode($orderController->getProduct());
        $this->assertFalse(empty($products));
    }
}
