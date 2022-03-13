<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OvertimesRequest extends FormRequest
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
            'employees_id'      => 'required|integer',
            'tanggal_lembur'    => 'required|date',
            'keterangan_lembur' => 'required',
            'jam_masuk'         => 'required|integer',
            'jam_istirahat'     => 'required|integer',
            'jam_pulang'        => 'required|integer',
            'jenis_lembur'      => 'required|string|in:Biasa,Libur'
        ];
    }
}
