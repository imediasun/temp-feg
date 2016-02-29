<?php
/**
 * Created by PhpStorm.
 * User: NSC
 * Date: 2/29/2016
 * Time: 12:02 PM
 */

namespace App\Http\Controllers\sximo;
use App\Http\Controllers\Controller;

class TestController extends Controller{
    function index()
    {
        Route::to('HomeController@index');
    }
    function testfunction()
    {
        $dbaccess=\DB::table('tb_module')->where('module_name',"=","users")->get();
        $data['testpassing']=('This is testing for passing values between controller and view');
        echo "<pre>";
        print_r($dbaccess);
        die();
        return view('test.testview',$data);

    }

}