<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LoggerInfo extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'logger_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'logger_type_id',
        'build_date',
        'activation_date',
        'lat',
        'lon',
        'treatment_date',
        'desc',
        'users_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'build_date' => 'datetime',
        'activation_date' => 'datetime',
        'treatment_date' => 'datetime',
    ];

    /**
     * Get the type logger that owns the logger info.
     */
    public function loggerType()
    {
        return $this->belongsTo(MasterLoggerType::class, 'logger_type_id');
    }

    public function loggerInfo()
    {
        return $this->hasMany(LoggerLog::class, 'logger_info_id');
    }
}
