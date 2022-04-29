<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaksimalBpjsketenagakerjaans extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'maksimalupah_bpjsketenagakerjaan',
        'edit_oleh'
    ];

    protected $hidden =[
        
    ];
}
