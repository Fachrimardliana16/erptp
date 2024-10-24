<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class AssetPurchase extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asset_purchases';

    protected $fillable = [
        'assetrequest_id',
        'document_number',
        'assets_number',
        'asset_name',
        'category_id',
        'purchase_date',
        'condition_id',
        'payment_receipt',
        'img',
        'price',
        'funding_source',
        'brand',
        'users_id',
    ];



    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function assetRequest()
    {
        return $this->belongsTo(AssetRequests::class, 'assetrequest_id');
    }

    public function category()
    {
        return $this->belongsTo(MasterAssetsCategory::class, 'category_id');
    }

    public function condition()
    {
        return $this->belongsTo(MasterAssetsCondition::class, 'condition_id');
    }
}
