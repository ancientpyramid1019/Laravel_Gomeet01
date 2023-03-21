<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User;

class Message extends \Coderflex\LaravelTicket\Models\Message
{
    use HasFactory;

    public function user(): BelongsTo
    {
        $model = $this->model == 'admin' ? Admin::class : User::class;
        return $this->belongsTo($model)->withDefault();
    }
}
