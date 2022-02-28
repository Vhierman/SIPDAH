<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryLaptops extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'employees_id',
        'merk_laptop',
        'type_laptop',
        'processor',
        'ram',
        'hardisk',
        'vga',
        'sistem_operasi',
        'tanggal_penyerahan_laptop',
        'foto_laptop',
        'input_oleh',
        'edit_oleh'
    ];

    protected $hidden =[
        
    ];

    public function employees(){
        return $this->belongsTo(Employees::class,'employees_id','nik_karyawan');
    }
}
