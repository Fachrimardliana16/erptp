<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AssetMutation extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'assets_mutation';

    // Menggunakan guarded untuk melindungi atribut dari mass assignment
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Jika ada data sensitif, Anda bisa mengenkripsinya
    // protected $casts = [
    //     'scan_doc' => 'encrypted', // Contoh jika Anda menggunakan enkripsi
    // ];

    protected $fillable = [
        'mutation_date',
        'mutations_number',
        'assets_id',
        'name',
        'assets_number',
        'condition_id',
        'employees_id',
        'location_id',
        'sub_location_id',
        'transaction_status_id',
        'scan_doc',
        'desc',
        'users_id',
    ];

    public function AssetsMutation()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }

    public function AssetsMutationemployee()
    {
        return $this->belongsTo(Employees::class, 'employees_id', 'id');
    }

    public function AssetsMutationlocation()
    {
        return $this->belongsTo(MasterAssetsLocation::class, 'location_id', 'id');
    }

    public function AssetsMutationsubLocation()
    {
        return $this->belongsTo(MasterAssetsSubLocation::class, 'sub_location_id', 'id');
    }

    public function AssetsMutationtransactionStatus()
    {
        return $this->belongsTo(MasterAssetsTransactionStatus::class, 'transaction_status_id', 'id');
    }

    public function MutationCondition()
    {
        return $this->belongsTo(MasterAssetsCondition::class, 'condition_id', 'id');
    }

    public function setScanDocAttribute($value)
    {
        if (request()->hasFile('scan_doc')) {
            $this->attributes['scan_doc'] = $value->store('Asset_Mutation', 'public'); // Simpan di folder public
        }
    }

    // Menambahkan metode untuk menghapus file yang diupload saat model dihapus
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if ($model->scan_doc) {
                Storage::disk('public')->delete($model->scan_doc);
            }
        });
    }
}
