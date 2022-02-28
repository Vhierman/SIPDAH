<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Positions extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'jabatan',
        'input_oleh',
        'edit_oleh'
    ];

    protected $hidden =[
        
    ];

    public function history_positions() {
        return $this->hasMany(HistoryPositions::class,'positions_id_history','id');
    }
}
