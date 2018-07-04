<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionFormRequest extends FormRequest
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
            'name' => 'required|unique:permission,name',
            'route' => 'required|unique:permission,route'
        ];
    }

    public function messages(){
        return [
            'name.required' => '权限名不能为空',
            'name.unique' => '权限名已存在',
            'route.required' => '路由名称不能为空',
            'route.unique' => '路由已存在',
        ];
    }
}
