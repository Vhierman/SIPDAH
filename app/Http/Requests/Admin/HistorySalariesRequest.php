<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HistorySalariesRequest extends FormRequest
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
            'employees_id'                  => 'required|integer',
            'gaji_pokok'                    => 'required|integer',
            'uang_makan'                    => 'required|integer',
            'uang_transport'                => 'required|integer',
            'tunjangan_tugas'               => 'required|integer',
            'tunjangan_pulsa'               => 'required|integer',
            'tunjangan_jabatan'             => 'required|integer',
            'jumlah_upah'                   => 'required|integer',
            'upah_lembur_perjam'            => 'required|integer',
            'potongan_bpjsks_perusahaan'    => 'required|integer',
            'potongan_jht_perusahaan'       => 'required|integer',
            'potongan_jp_perusahaan'        => 'required|integer',
            'potongan_jkm_perusahaan'       => 'required|integer',
            'potongan_jkk_perusahaan'       => 'required|integer',
            'jumlah_bpjstk_perusahaan'      => 'required|integer',
            'potongan_bpjsks_karyawan'      => 'required|integer',
            'potongan_jht_karyawan'         => 'required|integer',
            'potongan_jp_karyawan'          => 'required|integer',
            'jumlah_bpjstk_karyawan'        => 'required|integer',
            'take_home_pay'                 => 'required|integer'
        ];
    }
}
