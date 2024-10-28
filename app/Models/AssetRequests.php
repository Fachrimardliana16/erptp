<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AssetRequests extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assets_requests';

    // Specify which attributes are mass assignable
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

    public static function createRequest(array $data)
    {
        // Define validation rules
        $rules = [
            'document_number' => 'required|string|max:255',
            'date' => 'required|date',
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|numeric|min:1',
            'purpose' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'kepala_sub_bagian' => 'required|boolean', // Assuming this is a toggle
            'kepala_bagian_umum' => 'required|boolean', // Assuming this is a toggle
            'kepala_bagian_keuangan' => 'required|boolean', // Assuming this is a toggle
            'direktur_umum' => 'required|boolean', // Assuming this is a toggle
            'direktur_utama' => 'required|boolean', // Assuming this is a toggle
            'docs' => 'required|mimes:jpeg,png|max:10240',
            'users_id' => 'required|exists:users,id', // Assuming this references the users table
            'status_request' => 'required|boolean', // Assuming this is a status field
        ];

        // Validate the incoming data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Create the asset request
        return self::create($validator->validated());
    }
}
