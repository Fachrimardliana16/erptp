<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetRequests extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assets_requests';

    protected $fillable = [
        'document_number',
        'date',
        'asset_name',
        'category_id',
        'quantity',
        'purpose',
        'desc',
        'kepala_sub_bagian',
        'kepala_bagian_umum',
        'kepala_bagian_keuangan',
        'direktur_umum',
        'direktur_utama',
        'docs',
        'users_id',
        'status_request'
    ];

    public function category()
    {
        return $this->belongsTo(MasterAssetsCategory::class, 'category_id');
    }
}
