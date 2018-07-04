<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:adminer,name',
            'account' => 'required|unique:adminer,account',
            'roles' => 'required'
        ];
    }

    public function messages(){
        return [
            'name.required' => '用户名不能为空',
            'name.unique' => '用户名已存在',
            'account.required' => '账户名不能为空',
            'roles.required' => '角色不能为空',
            'account.unique' => '登陆账户已存在'
        ];
    }
}
