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

    /**
     * 管理员头像上传
     * @param Request $request
     * @return array
     */
    public function headUpload(Request $request){
        //判断文件是否存在
        if (!$request->hasFile('file')) {
            return ['code' => 1,'message' => '文件不存在'];
        }
        //判断文件是否上传成功
        if (!$request->file('file')->isValid()){
            return ['code' => 1,'message' => '文件上传失败'];
        }
        $extension = $request->file->extension();
        $filename = date('YmdHis').uniqid().'.'.$extension;
        $path = $request->file->storeAs('admin/head', $filename,'local');
        return ['code' => 0,'message' => '上传成功','path' => '/upload/'.$path];
    }

    /**
     * 管理员资料提交
     * @param Request $request
     * @return array
     */
    public function adminInfoPost(Request $request){
        $args = $request->post();
        $args['imageUrl'] = $args['imageUrl'] ?? '';
        if(empty($args['imageUrl'])){
            return ['code' => 1,'message' => '头像url不能为空'];
        }
        $identity = session('identity');
        $args['id'] = $identity['adminer_id'];
        return Adminer::adminInfoPost($args);
    }
}
