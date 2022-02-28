<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HistoryContractsRequest extends FormRequest
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
            'tanggal_awal_kontrak'  => 'required|date',
            'tanggal_akhir_kontrak' => 'required|date',
            'status_kontrak_kerja'  => 'required|in:PKWTT,PKWT,Outsourcing,Harian'
            
        ];
    }
}
