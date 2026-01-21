<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testServiceForm()
    {
        return view('admin.service.create');
    }
    
    public function testActualiteForm()
    {
        return view('admin.actualite.create');
    }
}
