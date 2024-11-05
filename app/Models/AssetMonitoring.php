<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AssetMonitoring extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'assets_monitoring';

    protected $fillable = [
        'monitoring_date',
        'assets_id',
        'name',
        'assets_number',
        'old_condition_id',
        'new_condition_id',
        'user_id',
        'desc',
        'img',
        'users_id',
    ];

    // Menggunakan guarded untuk melindungi atribut dari mass assignment
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Jika ada data sensitif, Anda bisa mengenkripsinya
    protected $casts = [
        // Contoh jika Anda menggunakan enkripsi
        'img' => 'encrypted', // Jika img adalah data sensitif
    ];

    public function assetMonitoring()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }

    public function employeeAssetMonitoring()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }

    public function monitoringLocation()
    {
        return $this->belongsTo(MasterAssetsLocation::class, 'location_id', 'id');
    }

    public function monitoringSubLocation()
    {
        return $this->belongsTo(MasterAssetsSubLocation::class, 'sub_location_id', 'id');
    }

    public function MonitoringoldCondition()
    {
        return $this->belongsTo(MasterAssetsCondition::class, 'old_condition_id', 'id');
    }

    public function MonitoringNewCondition()
    {
        return $this->belongsTo(MasterAssetsCondition::class, 'new_condition_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    // Menangani upload file dengan aman
    public function setImgAttribute($value)
    {
        if (request()->hasFile('img')) {
            $this->attributes['img'] = $value->store('Asset_Monitoring', 'public'); // Simpan di folder public
        }
    }

    // Menambahkan metode untuk menghapus file yang diupload saat model dihapus
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if ($model->img) {
                Storage::disk('public')->delete($model->img);
            }
        });
    }
}
