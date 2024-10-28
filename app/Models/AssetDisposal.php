<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class AssetDisposal extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'assets_disposals';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Jika ada data sensitif, Anda bisa mengenkripsinya
    protected $casts = [
        'disposal_notes' => 'encrypted', // Enkripsi kolom notes
        'docs' => 'string', // Mengatur docs sebagai string, jika diperlukan
    ];

    protected $fillable = [
        'disposal_date',
        'disposals_number',
        'assets_id',
        'book_value',
        'disposal_reason',
        'disposal_value',
        'disposal_process',
        'employee_id',
        'disposal_notes',
        'docs',
        'users_id',
    ];

    public function assetDisposals()
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }

    /**
     * Get the employee who handled the disposal.
     */
    public function employeeDisposals()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'id');
    }

    public function setDocsAttribute($value)
    {
        if (request()->hasFile('docs')) {
            // Simpan file dan simpan path-nya
            $this->attributes['docs'] = $value->store('Asset_Disposals', 'public'); // Simpan di folder public
        }
    }

    // Menambahkan metode untuk menghapus file yang diupload saat model dihapus
    public static function boot()
    {
        parent::boot();

        static::deleted(function ($model) {
            if ($model->docs) {
                Storage::disk('public')->delete($model->docs);
            }
        });
    }

    // Menambahkan metode untuk mendekripsi notes saat diambil
    public function getDisposalNotesAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Menambahkan metode untuk mengenkripsi notes sebelum disimpan
    public function setDisposalNotesAttribute($value)
    {
        $this->attributes['disposal_notes'] = Crypt::encryptString($value);
    }
}
