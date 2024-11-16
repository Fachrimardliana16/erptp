<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\MasterHelpDeskCategory;
use App\Models\MasterHelpDeskSla;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            EmployeeStatusEmploymentSeeder::class,
            EmployeeEducationSeeder::class,
            EmployeePositionSeeder::class,
            EmployeeAgreementSeeder::class,
            EmployeePermisionSeeder::class,
            EmployeeGradeSeeder::class,
            EmployeeBasicSalarySeeder::class,
            EmployeeBenefitSeeder::class,
            EmployeeSalaryCutsSeeder::class,
            EmployeeServiceGradeSeeder::class,
            DepartmentsSeeder::class,
            SubDepartmentsSeeder::class,
            AssetLocationSeeder::class,
            AssetSubLocationSeeder::class,
            AssetConditionSeeder::class,
            AssetComplaintStatusSeeder::class,
            AssetTransactionStatusSeeder::class,
            AssetCategorySeeder::class,
            AssetsStatusSeeder::class,
            FuelTypeSeeder::class,
            VoucherStatusTypeSeeder::class,
            MoneyVoucherTypeSeeder::class,
            EmployeeFamilySeeder::class,
            BillingCustomerStatusSeeder::class,
            BillingComplaintStatusSeeder::class,
            BillingSubdistrictsSeeder::class,
            BillingVillagesSeeder::class,
            BillingRegistrationTypeSeeder::class,
            BillingServiceTypeSeeder::class,
            IdTypeSeeder::class,
            LoggerTypeSeeder::class,
            FloorTypeSeeder::class,
            RoofTypeSeeder::class,
            VehicleTypeSeeder::class,
            WallTypeSeeder::class,
        ]);

        Artisan::call('shield:generate --all');
    }
}
