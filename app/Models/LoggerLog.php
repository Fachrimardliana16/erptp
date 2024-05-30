<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class LoggerLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'logger_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'logger_info_id',
        'sensor_value',
        'logger_time',
        'server_time',
        'users_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'logger_time' => 'datetime',
        'server_time' => 'datetime',
    ];

    /**
     * Get the logger info that owns the logger log.
     */
    public function loggerInfo()
    {
        return $this->belongsTo(LoggerInfo::class, 'logger_info_id');
    }
}
