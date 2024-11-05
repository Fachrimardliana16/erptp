<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TravelLetterFollowers extends Model
{
    use HasFactory;

    protected $table = 'travel_letter_followers';

    public $incrementing = false; // Pastikan ini diatur ke false
    protected $keyType = 'string'; // Pastikan ini diatur ke 'string'

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid(); // Menghasilkan UUID
        });
    }
}
