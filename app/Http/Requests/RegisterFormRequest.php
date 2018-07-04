<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:10',
        ];
    }

    public function messages(){
        return [
            'name.required' => '用户名不能为空',
            'name.string' => '用户名必须是字符型数据',
            'name.unique' => '用户名重复',
            'email.required' => '邮箱不能为空',
            'email.email' => '邮箱格式不正确',
            'email.unique' => '邮箱已存在',
            'password.required' => '密码不能为空',
            'password.string' => '密码必须是字符型数据',
            'password.min' => '密码最少6位',
            'password.max' => '密码最多10位'
        ];
    }
}
