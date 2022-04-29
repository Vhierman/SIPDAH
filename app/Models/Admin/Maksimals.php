<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maksimals extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'maksimal_upah',
        'edit_oleh'
    ];

    protected $hidden =[
        
    ];
}
