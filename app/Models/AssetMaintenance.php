<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class AssetMaintenance extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assets_maintenance';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Jika ada data sensitif, Anda bisa mengenkripsinya
    protected $casts = [
        'desc' => 'encrypted', // Enkripsi kolom deskripsi
        'invoice_file' => 'string', // Pastikan ini adalah string yang aman
    ];

    protected $fillable = [
        'maintenance_date',
        'location_service',
        'assets_id',
        'service_type',
        'service_cost',
        'desc',
        'invoice_file',
        'users_id',
    ];

    public function AssetMaintenance()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }

    // Menambahkan metode untuk mendekripsi deskripsi saat diambil
    public function getDescAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Menambahkan metode untuk mengenkripsi deskripsi sebelum disimpan
    public function setDescAttribute($value)
    {
        $this->attributes['desc'] = Crypt::encryptString($value);
    }
}
