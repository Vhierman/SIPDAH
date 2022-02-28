<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InventoryLaptopsRequest extends FormRequest
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
            //
            'employees_id'              => 'required',
            'merk_laptop'               => 'required',
            'type_laptop'               => 'required',
            'processor'                 => 'required',
            'ram'                       => 'required',
            'hardisk'                   => 'required',
            'vga'                       => 'required',
            'sistem_operasi'            => 'required',
            'tanggal_penyerahan_laptop' => 'required|date',
            'foto_laptop'               => 'required|image|max:512'
        ];
    }
}
