<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE TRIGGER after_asset_purchase_insert
            AFTER INSERT ON asset_purchases
            FOR EACH ROW
            BEGIN
                DECLARE expiry_date DATE;

                IF NEW.category_id = "elektronik-uuid" THEN
                    SET expiry_date = DATE_ADD(NEW.purchase_date, INTERVAL 5 YEAR);
                ELSEIF NEW.category_id = "furnitur-uuid" THEN
                    SET expiry_date = DATE_ADD(NEW.purchase_date, INTERVAL 3 YEAR);
                ELSEIF NEW.category_id = "kendaraan-uuid" THEN
                    SET expiry_date = DATE_ADD(NEW.purchase_date, INTERVAL 8 YEAR);
                ELSE
                    SET expiry_date = DATE_ADD(NEW.purchase_date, INTERVAL 5 YEAR); -- Default value jika tidak sesuai
                END IF;
                
                INSERT INTO assets (
                    id,
                    assets_number,
                    name,
                    category_id,
                    purchase_date,
                    condition_id,
                    img,
                    price,
                    funding_source,
                    brand,
                    book_value,
                    book_value_expiry,
                    status_id,
                    users_id,
                    created_at,
                    updated_at
                ) VALUES (
                    (SELECT UUID()),
                    NEW.assets_number,
                    NEW.asset_name,
                    NEW.category_id,
                    NEW.purchase_date,
                    NEW.condition_id,
                    NEW.img,
                    NEW.price,
                    NEW.funding_source,
                    NEW.brand,
                    0,
                    expiry_date, -- Gunakan nilai expiry_date yang telah dideklarasikan sebelumnya
                    (SELECT id FROM master_assets_status WHERE name = "active" LIMIT 1), -- Mengambil status_id dengan nama "active"
                    NEW.users_id,
                    NOW(),
                    NOW()
                );
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('
            DROP TRIGGER IF EXISTS after_asset_purchase_insert;
        ');
    }
};
