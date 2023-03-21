<?php

namespace App\Models;

use App\Enums\InvestStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['created_time'];


    protected $casts = [
        'status' => InvestStatus::class,
    ];

    public function schema()
    {
        return $this->hasOne(Schema::class, 'id', 'schema_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function getCreatedAtAttribute($value)
    {
        return date('M d, Y H:i', strtotime($value));
    }

    public function getNextProfitTimeAttribute($value)
    {
        return date('M d, Y H:i', strtotime($value));
    }

    public function getCreatedTimeAttribute(): string
    {
        return Carbon::parse($this->attributes['created_at'])->format('M d Y h:i');
    }

}
