<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AssetPurchase extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'asset_purchases';

    // Specify which attributes are mass assignable
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

    public static function createPurchase(array $data)
    {
        // Define validation rules
        $rules = [
            'assetrequest_id' => 'required|exists:assets_requests,id',
            'document_number' => 'required|string|max:255',
            'assets_number' => 'required|string|max:255',
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'purchase_date' => 'required|date',
            'condition_id' => 'required|exists:master_assets_conditions,id',
            'payment_receipt' => 'nullable|string|max:255',
            'img' => 'nullable|mimes:jpeg,png|max:10240',
            'price' => 'required|numeric|min:0', // Ensure price is a positive number
            'funding_source' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'users_id' => 'required|exists:users,id',
        ];

        // Validate the incoming data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Create the asset purchase
        return self::create($validator->validated());
    }
}
