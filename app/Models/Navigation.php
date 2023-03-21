<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function page()
    {
        return $this->belongsTo(Page::class)->withDefault();
    }
}
