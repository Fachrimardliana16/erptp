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

    public static function createRequest($data)
    {
        $validatedData = validator($data, [
            'document_number' => 'required|max:255',
            'date' => 'required|date',
            'asset_name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric',
            'purpose' => 'required|max:255',
            'desc' => 'nullable|string',
            'docs' => 'required|mimes:jpeg,png|max:10240',
        ])->validate();

        return self::create($validatedData);
    }
}
