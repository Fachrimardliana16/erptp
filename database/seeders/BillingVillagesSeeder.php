<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillingVillagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $subdistricts = DB::table('master_billing_subdistricts')->get();
        $userId = DB::table('users')->value('id');

        $villages = [
            // Bobotsari
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Banjarsari'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Bobotsari'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Dagan'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Gandasuli'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Gunungkarang'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Kalapacung'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Karangduren'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Karangmalang'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Karangtalun'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Limbasari'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Majapura'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Pakuncen'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Palumbungan'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Palumbungan Wetan'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Talagening'],
            ['subdistricts_name' => 'Bobotsari', 'villages_name' => 'Tlagayasa'],

            // Bojongsari
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Banjaran'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Beji'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Bojongsari'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Brobot'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Bumisari'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Galuh'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Gembong'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Kajongan'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Karangbanjar'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Metenggeng'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Pagedangan'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Patemon'],
            ['subdistricts_name' => 'Bojongsari', 'villages_name' => 'Pekalongan'],

            // Bukateja
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Bajong'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Bukateja'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Cipawon'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Karangcengis'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Karanggedang'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Karangnangka'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Kebutuh'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Kedungjati'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Kembangan'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Kutawis'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Majasari'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Penaruban'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Tidu'],
            ['subdistricts_name' => 'Bukateja', 'villages_name' => 'Wirasaba'],

            // Kaligondang
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Arenan'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Brecek'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Cilapar'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Kaligondang'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Kalikajar'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Kembaran Wetan'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Lamongan'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Pagerandong'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Penaruban'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Penolih'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Selakambang'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Selanegara'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Sempor Lor'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Sidanegara'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Sidareja'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Sinduraja'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Slinga'],
            ['subdistricts_name' => 'Kaligondang', 'villages_name' => 'Tejasari'],

            // Kalimanah
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Babakan'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Blater'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Grecol'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Jompo'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Kalimanah Kulon'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Kalimanah Wetan'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Karangpetir'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Karangsari'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Kedungwuluh'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Klapasawit'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Manduraga'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Rabak'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Selabaya'],
            ['subdistricts_name' => 'Kalimanah', 'villages_name' => 'Sidakangen'],

            // Karanganyar
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Banjarkerta'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Brakas'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Buara'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Bungkanel'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Jambudesa'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Kabunderan'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Kalijaran'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Kaliori'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Karanganyar'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Karanggedang'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Lumpang'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Maribaya'],
            ['subdistricts_name' => 'Karanganyar', 'villages_name' => 'Ponjen'],

            // Karangjambu
            ['subdistricts_name' => 'Karangjambu', 'villages_name' => 'Danasari'],
            ['subdistricts_name' => 'Karangjambu', 'villages_name' => 'Jingkang'],
            ['subdistricts_name' => 'Karangjambu', 'villages_name' => 'Karangjambu'],
            ['subdistricts_name' => 'Karangjambu', 'villages_name' => 'Purbasari'],
            ['subdistricts_name' => 'Karangjambu', 'villages_name' => 'Sanguwatang'],
            ['subdistricts_name' => 'Karangjambu', 'villages_name' => 'Sirandu'],

            // Karangmoncol
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Baleraksa'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Grantung'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Karangsari'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Kramat'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Pekiringan'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Pepedan'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Rajawana'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Sirau'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Tajug'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Tamansari'],
            ['subdistricts_name' => 'Karangmoncol', 'villages_name' => 'Tunjungmuli'],

            // Karangreja
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Gondang'],
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Karangreja'],
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Kutabawa'],
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Serang'],
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Siwarak'],
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Tlahab Kidul'],
            ['subdistricts_name' => 'Karangreja', 'villages_name' => 'Tlahab Lor'],

            // Kejobong
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Bandingan'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Gumiwang'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Kedarpan'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Kejobong'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Krenceng'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Lamuk'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Langgar'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Nangkasawit'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Nangkod'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Pandansari'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Pangempon'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Sokanegara'],
            ['subdistricts_name' => 'Kejobong', 'villages_name' => 'Timbang'],

            // Kemangkon
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Bakulan'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Bokol'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Gambarsari'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Jetis'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Kalialang'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Karangkemiri'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Karangtengah'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Kedungbenda'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Kedunglegok'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Kemangkon'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Majasem'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Majatengah'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Muntang'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Panican'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Pegandekan'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Pelumutan'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Senon'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Sumilir'],
            ['subdistricts_name' => 'Kemangkon', 'villages_name' => 'Toyareka'],

            // Kertanegara
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Adiarsa'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Condong'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Darma'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Karang Tengah'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Karangasem'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Karangpucung'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Kasih'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Kertanegara'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Krangean'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Langkap'],
            ['subdistricts_name' => 'Kertanegara', 'villages_name' => 'Margasana'],

            // Kutasari
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Candinata'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Candiwulan'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Cendana'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Karangaren'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Karangcegak'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Karangjengkol'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Karangklesem'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Karanglewas'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Karangreja'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Kutasari'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Limbangan'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Meri'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Munjul'],
            ['subdistricts_name' => 'Kutasari', 'villages_name' => 'Sumingkir'],

            // Mrebet
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Binangun'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Bojong'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Campakoah'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Cipaku'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Karang Nangka'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Karangturi'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Kradenan'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Lambur'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Mangunegara'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Mrebet'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Onje'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Pager Andong'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Pengalusan'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Sangkanayu'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Selaganggeng'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Serayu Karanganyar'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Serayu Larangan'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Sindang'],
            ['subdistricts_name' => 'Mrebet', 'villages_name' => 'Tangkisan'],

            // Padamara
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Bojanegara'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Dawuhan'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Gemuruh'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Kalitinggar'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Kalitinggar Kidul'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Karanggambas'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Karangjambe'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Karangpule'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Mipiran'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Padamara'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Prigi'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Purbayasa'],
            ['subdistricts_name' => 'Padamara', 'villages_name' => 'Sokawera'],

            // Pengadegan
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Bedagas'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Karangjoho'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Larangan'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Pangadegan'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Panunggalan'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Pasunggingan'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Tegalpingen'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Tetel'],
            ['subdistricts_name' => 'Pengadegan', 'villages_name' => 'Tumanggal'],

            // Purbalingga
            ['subdistricts_name' => 'Purbalingga', 'villages_name' => 'Jatisaba'],
            ['subdistricts_name' => 'Purbalingga', 'villages_name' => 'Toyareja'],

            // Rembang
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Bantarbarang'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Bodas Karangjati'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Gunungwuled'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Karangbawang'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Losari'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Makam'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Panusupan'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Sumampir'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Tanalum'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Wanogara Wetan'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Wlahar'],
            ['subdistricts_name' => 'Rembang', 'villages_name' => 'Wonogara Kulon'],
        ];

        foreach ($villages as $village) {
            $subdistrict = DB::table('master_billing_subdistricts')->where('name', $village['subdistricts_name'])->first();
            if ($subdistrict) {
                DB::table('master_billing_villages')->insert([
                    'id' => Str::uuid(),
                    'subdistricts_id' => $subdistrict->id,
                    'name' => $village['villages_name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'users_id' => $userId,
                ]);
            }
        }
    }
}
