<?php

namespace App\Models;

use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Filament\Resources\AssetResource\Pages\EditAsset;
use App\Filament\Resources\AssetResource\Pages\ViewAsset;
use App\Filament\Resources\AssetResource\Pages\ListAssets;
use App\Filament\Resources\AssetResource\Pages\CreateAsset;

class Asset extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assets';

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
        'date_document_extension',
        'location_id',
        'sub_location_id',
        'status_id',
        'transaction_status_id',
        'desc',
        'users_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'book_value_expiry' => 'date',
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
    public function AssetDocumentExtension()
    {
        return $this->hasMany(AssetDocumentExtension::class, 'assets_id', 'id');
    }
}
