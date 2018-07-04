<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleFormRequest extends FormRequest
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
            'name' => 'required|max:10|unique:role,name',
            'permissions' => 'required'
        ];
    }

    public function messages(){
        return [
            'name.required' => '角色名不能为空',
            'name.max' => '角色名最大长度为10',
            'name.unique' => '角色名重复',
            'permissions.required' => '角色权限不能为空'
        ];
    }
}
