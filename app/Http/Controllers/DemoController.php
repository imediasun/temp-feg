<?php
/**
 * Created by PhpStorm.
 * User: NSC
 * Date: 6/16/2016
 * Time: 4:10 PM
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;



use Illuminate\Support\Facades\Input;
use DB;

class DemoController  extends Controller
{


    public function getIndex()

    {

        $active = DB::table('active')->get();

        return view('demo.index',['active' => $active]);
    }
}