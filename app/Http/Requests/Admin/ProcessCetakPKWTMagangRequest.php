<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProcessCetakPKWTMagangRequest extends FormRequest
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
            'nik_magang'  => 'required',
            'nama_magang'  => 'required',
            'jabatan_magang'  => 'required',
            'penempatan_magang'  => 'required',
            'tempat_lahir_magang'  => 'required',
            'tanggal_lahir_magang'  => 'required',
            'pendidikan_terakhir_magang'  => 'required',
            'jenis_kelamin_magang'  => 'required',
            'agama_magang'  => 'required',
            'alamat_magang'  => 'required',
            'rt_magang'  => 'required',
            'rw_magang'  => 'required',
            'kelurahan_magang'  => 'required',
            'kecamatan_magang'  => 'required',
            'kota_magang'  => 'required',
            'provinsi_magang'  => 'required',
            'cetak_surat_magang'  => 'required',
            'akhir_magang'  => 'required'
        ];
    }
}
