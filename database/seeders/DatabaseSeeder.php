<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Report;
use App\Models\ReportAction;
use App\Models\Evaluation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $vendorAdmin = User::create([
            'name' => 'Fasilitator Swasta (adhexa.id)',
            'email' => 'vendor@efkdm.go.id',
            'phone' => '081122334455',
            'role' => 'vendor_admin',
            'status' => 'active',
            'institution_name' => 'adhexa.id',
            'district' => 'Kabupaten Bogor',
            'subdistrict' => 'Wilayah Pemda',
            'village' => 'Pusat Fasilitasi',
            'password' => Hash::make('password123'),
        ]);

        $admin = User::create([
            'name' => 'Kepala Badan Kesbangpol Pemda',
            'email' => 'admin@efkdm.go.id',
            'phone' => '081234567890',
            'role' => 'admin',
            'status' => 'active',
            'institution_name' => 'Badan Kesbangpol Pemerintah Daerah',
            'district' => 'Kabupaten Bogor',
            'subdistrict' => 'Kecamatan Kota',
            'village' => 'Kelurahan Pusat',
            'password' => Hash::make('password123'),
        ]);

        $fkdmKabupaten = User::create([
            'name' => 'H. Haryanto (Admin FKDM Kabupaten Bogor)',
            'email' => 'kabupaten@efkdm.go.id',
            'phone' => '081388776655',
            'role' => 'fkdm_kabupaten',
            'status' => 'active',
            'institution_name' => 'Pengurus FKDM Kabupaten Bogor',
            'district' => 'Kabupaten Bogor',
            'subdistrict' => 'Kecamatan Cibinong',
            'village' => 'Kelurahan Tengah',
            'password' => Hash::make('password123'),
        ]);

        $fkdmMember = User::create([
            'name' => 'H. Budi Santoso (Ketua FKDM Kecamatan)',
            'email' => 'fkdm@efkdm.go.id',
            'phone' => '081987654321',
            'role' => 'fkdm_member',
            'status' => 'active',
            'institution_name' => 'FKDM Kecamatan Sukamajubaru',
            'district' => 'Kabupaten Bogor',
            'subdistrict' => 'Kecamatan Sukamajubaru',
            'village' => 'Kelurahan Mekar',
            'password' => Hash::make('password123'),
        ]);

        $desaMember = User::create([
            'name' => 'Bambang Wijaya (Anggota FKDM Kelurahan Mekar)',
            'email' => 'desa@efkdm.go.id',
            'phone' => '081377889900',
            'role' => 'fkdm_member',
            'status' => 'active',
            'institution_name' => 'FKDM Kelurahan Mekar',
            'district' => 'Kabupaten Bogor',
            'subdistrict' => 'Kecamatan Sukamajubaru',
            'village' => 'Kelurahan Mekar',
            'password' => Hash::make('password123'),
        ]);

        $pendingMember = User::create([
            'name' => 'Rudi Permana (Pendaftar Baru FKDM)',
            'email' => 'pending@efkdm.go.id',
            'phone' => '085299887766',
            'role' => 'fkdm_member',
            'status' => 'pending',
            'institution_name' => 'FKDM Kecamatan Sukamajubaru',
            'district' => 'Kabupaten Bogor',
            'subdistrict' => 'Kecamatan Sukamajubaru',
            'village' => 'Kelurahan Mekar',
            'password' => Hash::make('password123'),
        ]);

        // 2. Create Categories
        $categories = [
            [
                'name' => 'Konflik Sosial & Sengketa Lahan',
                'slug' => 'konflik-sosial',
                'description' => 'Potensi perselisihan warga, sengketa lahan, gesekan ormas, atau ketegangan antar kelompok.',
                'icon' => 'users',
                'color_code' => '#f59e0b',
            ],
            [
                'name' => 'Keamanan & Ketertiban Umum',
                'slug' => 'keamanan-ketertiban',
                'description' => 'Aksi kriminalitas, pencurian beruntun, balap liar, tempat berkumpul geng motor.',
                'icon' => 'shield-alert',
                'color_code' => '#ef4444',
            ],
            [
                'name' => 'Bencana Alam & Bahaya Lingkungan',
                'slug' => 'bencana-alam',
                'description' => 'Luapan sungai, potensi tanah longsor, pohon lapuk berisiko tumbang, kebakaran pemukiman.',
                'icon' => 'flame',
                'color_code' => '#dc2626',
            ],
            [
                'name' => 'Pengawasan Isu Radikalisme',
                'slug' => 'radikalisme',
                'description' => 'Penyebaran pamflet provokatif, aktivitas kelompok eksklusif mencurigakan, paham intoleran.',
                'icon' => 'alert-triangle',
                'color_code' => '#7c3aed',
            ],
            [
                'name' => 'Dinamika Politik & Pilkada',
                'slug' => 'politik-pilkada',
                'description' => 'Perusakan APK kampanye, gesekan pendukung paslon, isu hoax pemilu.',
                'icon' => 'vote',
                'color_code' => '#2563eb',
            ],
            [
                'name' => 'Kesehatan & Kerawanan Masyarakat',
                'slug' => 'kesehatan-lingkungan',
                'description' => 'Wabah penyakit menular lokal, pencemaran limbah pabrik ilegal, penumpukan sampah liar.',
                'icon' => 'activity',
                'color_code' => '#10b981',
            ],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[$cat['slug']] = Category::create($cat);
        }

        // 3. Create Sample Reports (Laporan Informasi Deteksi Dini)
        $reports = [
            [
                'report_number' => 'LAP-001',
                'user_id' => $fkdmMember->id,
                'category_id' => $catModels['konflik-sosial']->id,
                'title' => 'Potensi Gesekan Ormas Terkait Penguasaan Lahan Parkir Pasar Tradisional',
                'chronology' => 'Berdasarkan pemantauan lapangan Anggota FKDM pada pukul 14:00 WIB, terdapat peningkatan konsentrasi massa dari dua kelompok Ormas di sekitar gerbang selatan Pasar Tradisional. Dipicu oleh perselisihan pengelolaan retribusi parkir baru. Potensi terjadi bentrokan fisik jika tidak segera ditengahi.',
                'risk_level' => 'red',
                'status' => 'in_progress',
                'incident_date' => now()->subHours(5),
                'district' => 'Kabupaten Bogor',
                'subdistrict' => 'Kecamatan Sukamajubaru',
                'village' => 'Kelurahan Mekar',
                'address' => 'Jl. Raya Pasar Induk No. 45, RT 03 / RW 05',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'reporter_name' => 'Budi Santoso (FKDM)',
                'reporter_phone' => '081987654321',
                'is_anonymous' => false,
            ],
            [
                'report_number' => 'LAP-002',
                'user_id' => $fkdmMember->id,
                'category_id' => $catModels['bencana-alam']->id,
                'title' => 'Tanggul Sungai Ciliwung Retak dan Berpotensi Jebol Saat Hujan Deras',
                'chronology' => 'Warga menemukan keretakan sepanjang 12 meter pada struktur beton penahan luapan air sungai. Debit air mulai meningkat akibat hujan intensitas tinggi di daerah hulu.',
                'risk_level' => 'yellow',
                'status' => 'verified',
                'incident_date' => now()->subDays(1),
                'district' => 'Kabupaten Bogor',
                'subdistrict' => 'Kecamatan Sukamajubaru',
                'village' => 'Kelurahan Mekar',
                'address' => 'Bantaran Sungai Ciliwung, RT 08 / RW 02',
                'latitude' => -6.2150,
                'longitude' => 106.8520,
                'reporter_name' => 'Ahmad Warga',
                'reporter_phone' => '085712345678',
                'is_anonymous' => false,
            ],
            [
                'report_number' => 'LAP-003',
                'user_id' => null,
                'category_id' => $catModels['keamanan-ketertiban']->id,
                'title' => 'Aktivitas Kerumunan Remaja & Balap Liar Tengah Malam di Jalan Protokol',
                'chronology' => 'Setiap akhir pekan pukul 01:00 - 03:30 WIB terjadi penutupan jalan sepihak oleh puluhan pemuda untuk ajang taruhan balap liar yang meresahkan warga sekitar dan pengguna jalan.',
                'risk_level' => 'yellow',
                'status' => 'pending',
                'incident_date' => now()->subDays(2),
                'district' => 'Kabupaten Bogor',
                'subdistrict' => 'Kecamatan Kota',
                'village' => 'Kelurahan Pusat',
                'address' => 'Jl. Boulevard Utama KM 4',
                'latitude' => -6.1950,
                'longitude' => 106.8230,
                'reporter_name' => 'Masyarakat Anonim',
                'reporter_phone' => null,
                'is_anonymous' => true,
            ],
            [
                'report_number' => 'LAP-004',
                'user_id' => $admin->id,
                'category_id' => $catModels['kesehatan-lingkungan']->id,
                'title' => 'Penimbunan Sampah Liar dan Bau Menyengat Dekat Pemukiman',
                'chronology' => 'Laporan penanganan sampah ilegal yang dibuang oleh pihak tidak bertanggungjawab. Telah diselesaikan koordinasi dengan Dinas Lingkungan Hidup.',
                'risk_level' => 'green',
                'status' => 'resolved',
                'incident_date' => now()->subDays(4),
                'district' => 'Kabupaten Bogor',
                'subdistrict' => 'Kecamatan Kota',
                'village' => 'Kelurahan Pusat',
                'address' => 'Lahan Kosong Samping Lapangan RW 04',
                'latitude' => -6.2010,
                'longitude' => 106.8340,
                'reporter_name' => 'Tim Deteksi Dini Kesbangpol',
                'reporter_phone' => '081234567890',
                'is_anonymous' => false,
            ],
            [
                'report_number' => 'LAP-005',
                'user_id' => $fkdmMember->id,
                'category_id' => $catModels['keamanan-ketertiban']->id,
                'title' => 'Pemantauan Posko Kewaspadaan Industri & Kawasan Pabrik Bekasi',
                'chronology' => 'Situasi kondusif di kawasan industri Cikarang pasca mediasi tuntutan serikat pekerja kawasan.',
                'risk_level' => 'green',
                'status' => 'resolved',
                'incident_date' => now()->subHours(8),
                'district' => 'Kabupaten Bekasi',
                'subdistrict' => 'Kecamatan Cikarang Selatan',
                'village' => 'Desa Sukaresmi',
                'address' => 'Kawasan Industri EJIP Plot 3A',
                'latitude' => -6.3262,
                'longitude' => 107.1350,
                'reporter_name' => 'Anggota FKDM Bekasi',
                'reporter_phone' => '081311223344',
                'is_anonymous' => false,
            ],
            [
                'report_number' => 'LAP-006',
                'user_id' => $fkdmMember->id,
                'category_id' => $catModels['bencana-alam']->id,
                'title' => 'Pohon Tumbang Menutup Akses Jalan Utama Dago Bandung',
                'chronology' => 'Hujan angin menyebabkan pohon besar tumbang menimpa kabel listrik dan menghambat arus lalu lintas.',
                'risk_level' => 'yellow',
                'status' => 'in_progress',
                'incident_date' => now()->subHours(3),
                'district' => 'Kota Bandung',
                'subdistrict' => 'Kecamatan Coblong',
                'village' => 'Kelurahan Dago',
                'address' => 'Jl. Ir. H. Juanda No. 120',
                'latitude' => -6.8850,
                'longitude' => 107.6135,
                'reporter_name' => 'Warga Dago',
                'reporter_phone' => '082144556677',
                'is_anonymous' => false,
            ],
            [
                'report_number' => 'LAP-007',
                'user_id' => $fkdmMember->id,
                'category_id' => $catModels['konflik-sosial']->id,
                'title' => 'Monitoring Ketertiban Kawasan Bandara Kualanamu Deli Serdang',
                'chronology' => 'Patroli kewaspadaan dini FKDM wilayah pesisir dan akses jalan tol penerbangan.',
                'risk_level' => 'green',
                'status' => 'resolved',
                'incident_date' => now()->subDays(1),
                'district' => 'Kabupaten Deli Serdang',
                'subdistrict' => 'Kecamatan Beringin',
                'village' => 'Desa Kualanamu',
                'address' => 'Kawasan Bandara Internasional Kualanamu',
                'latitude' => 3.5186,
                'longitude' => 98.7180,
                'reporter_name' => 'Tim FKDM Sumut',
                'reporter_phone' => '081266778899',
                'is_anonymous' => false,
            ],
            [
                'report_number' => 'LAP-008',
                'user_id' => $fkdmMember->id,
                'category_id' => $catModels['keamanan-ketertiban']->id,
                'title' => 'Pemantauan Arus Wisata Kebun Raya Kota Bogor',
                'chronology' => 'Peningkatan kepadatan pengunjung liburan weekend. Situasi terpantau aman dan terkendali.',
                'risk_level' => 'green',
                'status' => 'resolved',
                'incident_date' => now()->subHours(6),
                'district' => 'Kota Bogor',
                'subdistrict' => 'Kecamatan Bogor Tengah',
                'village' => 'Kelurahan Paledang',
                'address' => 'Jl. Ir. H. Juanda No. 13 (Kebun Raya)',
                'latitude' => -6.5971,
                'longitude' => 106.8060,
                'reporter_name' => 'FKDM Bogor Tengah',
                'reporter_phone' => '085211447788',
                'is_anonymous' => false,
            ],
        ];

        foreach ($reports as $rData) {
            $rep = Report::create($rData);

            ReportAction::create([
                'report_id' => $rep->id,
                'user_id' => $admin->id,
                'action_type' => 'status_change',
                'note' => 'Laporan masuk dalam sistem deteksi dini e-FKDM dan dikategorikan status: ' . $rep->status_label,
            ]);
        }

        // 4. Create Sample e-Monev Reports (Dokumen Hasil Fasilitasi Konsultan Swasta untuk Pemda)
        Evaluation::create([
            'evaluation_code' => 'MONEV-2026-Q3-01',
            'user_id' => $vendorAdmin->id,
            'title' => 'Laporan Hasil Monitoring & Evaluasi Triwulan III Kinerja FKDM & Peta Kerawanan Pemda',
            'period_name' => 'Triwulan III - 2026',
            'target_region' => 'Pemerintah Daerah Kabupaten/Kota',
            'total_target_reports' => 60,
            'total_realized_reports' => 48,
            'compliance_rate' => 80.00,
            'risk_index_score' => 64.50,
            'executive_summary' => 'Berdasarkan hasil pengolahan data monitoring dan evaluasi oleh Fasilitator Swasta (adhexa.id), tingkat kepatuhan pelaporan FKDM di wilayah Pemda mencapai 80%. Isu paling dominan yang membutuhkan perhatian pimpinan daerah adalah potensi konflik sosial dan bencana hidro-meteorologi.',
            'consultant_recommendations' => "1. Meningkatkan alokasi anggaran operasional deteksi dini bagi anggota FKDM di tingkat Kelurahan/Desa.\n2. Melakukan patroli bersinergi antara Kesbangpol, TNI, POLRI, dan FKDM di titik rawan konflik pasar.\n3. Mempercepat penguatan infrastruktur penahan luapan air sungai di Kecamatan Sukamajubaru.",
            'status' => 'published',
        ]);
    }
}
