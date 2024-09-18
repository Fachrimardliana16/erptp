<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingRegistration extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'billing_registrations';

    protected $fillable = [
        'village_id',
        'sub_district_id',
        'registration_type_id',
        'date',
        'name',
        'id_type',
        'id_number',
        'detail_address',
        'altitude',
        'latitude',
        'longitude',
        'desc',
        'need_to_survey',
        'users_id',
        'number',
        'number_phone',
        'email',
        'work_name',
        'family_home',
        'surface_area',
        'floor_type_id',
        'roof_type_id',
        'vehicle_type_id',
        'building_area',
        'wall_type_id',
        'nominal_payment',
        'branch_office_id',
        'spl_image',
        'agreement_image',
        'fast_installation'
    ];

    public function village()
    {
        return $this->belongsTo(MasterBillingVillages::class, 'village_id');
    }

    public function subDistrict()
    {
        return $this->belongsTo(MasterBillingSubDistricts::class, 'sub_district_id');
    }

    public function registrationType()
    {
        return $this->belongsTo(MasterBillingRegistrationType::class, 'registration_type_id');
    }

    public function idType()
    {
        return $this->belongsTo(MasterBillingIdType::class, 'id_type');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function floorType()
    {
        return $this->belongsTo(MasterBillingFloorType::class, 'floor_type_id');
    }

    public function roofType()
    {
        return $this->belongsTo(MasterBillingRoofType::class, 'roof_type_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(MasterBillingVehicleType::class, 'vehicle_type_id');
    }

    public function wallType()
    {
        return $this->belongsTo(MasterBillingWallType::class, 'wall_type_id');
    }

    public function branchOffice()
    {
        return $this->belongsTo(MasterBranchOffice::class, 'branch_office_id');
    }
}
