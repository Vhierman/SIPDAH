<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'companies_id',
        'working_hours_id',
        'divisions_id',
        'positions_id',
        'areas_id',
        'nik_karyawan',
        'nama_karyawan',
        'email_karyawan',
        'nomor_absen',
        'nomor_npwp',
        'nomor_handphone',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'jenis_kelamin',
        'pendidikan_terakhir',
        'golongan_darah',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'kode_pos',
        'foto_karyawan',
        'foto_ktp',
        'foto_npwp',
        'foto_kk',
        'nomor_jkn',
        'nomor_jht',
        'nomor_jp',
        'nomor_kartu_keluarga',
        'status_nikah',
        'nama_ibu',
        'nama_ayah',
        'tanggal_mulai_kerja',
        'tanggal_akhir_kerja',
        'status_kerja',
        'nama_bank',
        'nomor_rekening',
        'input_oleh',
        'edit_oleh'
    ];

    protected $hidden =[
        
    ];

    public function companies() {
        return $this->belongsTo(Companies::class,'companies_id','id');
    }
    public function working_hours() {
        return $this->belongsTo(WorkingHour::class,'working_hours_id','id');
    }
    public function divisions() {
        return $this->belongsTo(Divisions::class,'divisions_id','id');
    }
    public function positions() {
        return $this->belongsTo(Positions::class,'positions_id','id');
    }
    public function areas() {
        return $this->belongsTo(Areas::class,'areas_id','id');
    }

    //One To Many
    public function history_salaries() {
        return $this->hasMany(HistorySalaries::class,'employees_id','id');
    }
    public function history_contracts() {
        return $this->hasMany(HistoryContracts::class,'employees_id','id');
    }
    public function history_positions() {
        return $this->hasMany(HistoryPositions::class,'employees_id','id');
    }
    public function history_training_internals() {
        return $this->hasMany(HistoryTrainingInternals::class,'employees_id','id');
    }
    public function history_training_eksternals() {
        return $this->hasMany(HistoryTrainingEksternals::class,'employees_id','id');
    }
    public function history_families() {
        return $this->hasMany(HistoryFamilies::class,'employees_id','id');
    }
    public function inventory_laptops() {
        return $this->hasMany(InventoryLaptops::class,'employees_id','id');
    }
    public function inventory_motorcycles() {
        return $this->hasMany(InventoryMotorcycles::class,'employees_id','id');
    }
    public function inventory_cars() {
        return $this->hasMany(InventoryCars::class,'employees_id','id');
    }
}
