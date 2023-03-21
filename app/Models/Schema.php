<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'id', 'return_period');
    }
}
