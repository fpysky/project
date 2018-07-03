<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adminer;

class IndexController extends Controller
{
    public function index(){
        return view('admin.index.index');
    }

    public function main(){
        return view('admin.index.main');
    }

    public function getAdminInfo(){
        $identity = session('identity');
        return Adminer::getAdminInfo($identity);
    }

    public function admininfo(){
        return view('admin.index.admininfo');
    }
}
