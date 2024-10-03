<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetDocumentExtension extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asset_document_extensions';

    protected $fillable = [
        'extension_date',
        'document_type',
        'location',
        'assets_id',
        'cost',
        'next_expiry_date',
        'notes',
        'users_id',
    ];

    public function AssetDocumentExtension()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }
}
