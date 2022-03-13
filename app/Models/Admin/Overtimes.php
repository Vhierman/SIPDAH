<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Overtimes extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employees_id',
        'jam_masuk',
        'jam_istirahat',
        'jam_pulang',
        'keterangan_lembur',
        'tanggal_lembur',
        'jenis_lembur',
        'jam_lembur',
        'jam_pertama',
        'jumlah_jam_pertama',
        'jam_kedua',
        'jumlah_jam_kedua',
        'jam_ketiga',
        'jumlah_jam_ketiga',
        'jam_keempat',
        'jumlah_jam_keempat',
        'uang_makan_lembur',
        'input_oleh',
        'edit_oleh',
        'hapus_oleh',
        'acc_hrd',
        'waktu_acc_hrd'
    ];

    protected $hidden =[
        
    ];

    public function employees(){
        return $this->belongsTo(Employees::class,'employees_id','nik_karyawan');
    }
}
