<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class Asset extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assets';

    // Menggunakan guarded untuk melindungi atribut dari mass assignment
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Jika ada data sensitif, Anda bisa mengenkripsinya
    protected $casts = [
        'purchase_date' => 'date',
        'book_value_expiry' => 'date',
        'desc' => 'encrypted', // Enkripsi kolom deskripsi
        'price' => 'decimal:2', // Mengatur format harga
        // Jika img adalah data sensitif, pertimbangkan untuk mengenkripsinya
    ];

    protected $fillable = [
        'assets_number',
        'name',
        'category_id',
        'purchase_date',
        'condition_id',
        'img',
        'price',
        'funding_source',
        'brand',
        'book_value',
        'book_value_expiry',
        'location_id',
        'sub_location_id',
        'status_id',
        'transaction_status_id',
        'desc',
        'users_id',
    ];

    public function categoryAsset()
    {
        return $this->belongsTo(MasterAssetsCategory::class, 'category_id');
    }

    public function conditionAsset()
    {
        return $this->belongsTo(MasterAssetsCondition::class, 'condition_id');
    }

    public function AssetMaintenance()
    {
        return $this->hasMany(AssetMaintenance::class, 'assets_id', 'id');
    }

    public function assetMonitoring()
    {
        return $this->hasMany(Asset::class, 'assets_id', 'id');
    }

    public function AssetsMutation()
    {
        return $this->hasMany(AssetMutation::class, 'assets_id');
    }

    public function assetDisposals()
    {
        return $this->hasMany(AssetDisposal::class, 'assets_id', 'id');
    }

    public function assetsStatus()
    {
        return $this->belongsTo(MasterAssetsStatus::class, 'status_id', 'id');
    }

    public function AssetTransactionStatus()
    {
        return $this->belongsTo(MasterAssetsTransactionStatus::class, 'transaction_status_id', 'id');
    }

    public function AssetMutationLocation()
    {
        return $this->belongsTo(MasterAssetsLocation::class, 'location_id', 'id');
    }

    public function AssetMutationSubLocation()
    {
        return $this->belongsTo(MasterAssetsSubLocation::class, 'sub_location_id', 'id');
    }

    // Menangani upload file dengan aman
    public function setImgAttribute($value)
    {
        if (request()->hasFile('img')) {
            // Simpan file dan simpan path-nya
            $this->attributes['img'] = $value->store('Assets', 'public'); // Simpan di folder public
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
