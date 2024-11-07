<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class TravelLetterFollowers extends Pivot
{
    use HasUuids;

    protected $table = 'travel_letter_followers';

    public $incrementing = false; // Pastikan ini diatur ke false
    protected $keyType = 'string'; // Pastikan ini diatur ke 'string'
    protected static function boot()
    {
        parent::boot();
    
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
    
}