<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorController extends Controller
{
    //
    public function VendorDashboard(){
        return view('vendor.index');
    }// End Vendor Dashboard


    public function VendorLogin(){
        return view('vendor.vendor_login');
    }//End Admin Login

}
