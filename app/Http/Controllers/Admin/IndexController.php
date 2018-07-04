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

    public function headUpload(Request $request){
        //判断文件是否存在
        if (!$request->hasFile('file')) {
            return ['code' => 1,'message' => '文件不存在'];
        }
        //判断文件是否上传成功
        if (!$request->file('file')->isValid()){
            return ['code' => 1,'message' => '文件上传失败'];
        }
        $path = $request->file->path();
        $extension = $request->file->extension();
        
    }
}
