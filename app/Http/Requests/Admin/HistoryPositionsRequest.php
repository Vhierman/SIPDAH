<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HistoryPositionsRequest extends FormRequest
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
            'employees_id'          => 'required',
            'companies_id_history'  => 'required',
            'areas_id_history'      => 'required',
            'divisions_id_history'  => 'required',
            'positions_id_history'  => 'required',
            'tanggal_mutasi'        => 'required|date',
            'file_surat_mutasi'     => 'required|image|max:512'
            
        ];
    }
}
