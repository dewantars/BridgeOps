<?php

namespace Database\Seeders;

use App\Models\EngineeringEvent;
use App\Models\AiSummary;
use App\Models\ManualErrorLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@bridgeops.local'],
            ['name' => 'Admin BridgeOps', 'password' => Hash::make('password'), 'role' => 'admin']
        );
        $pm = User::firstOrCreate(
            ['email' => 'pm@bridgeops.local'],
            ['name' => 'Project Manager', 'password' => Hash::make('password'), 'role' => 'pm']
        );
        $client = User::firstOrCreate(
            ['email' => 'client@bridgeops.local'],
            ['name' => 'Client Demo', 'password' => Hash::make('password'), 'role' => 'client']
        );

        // 2. Create Projects
        $project1 = Project::firstOrCreate(
            ['name' => 'E-Commerce Platform'],
            [
                'client_name'      => 'PT. Maju Bersama',
                'description'      => 'Platform e-commerce lengkap dengan fitur payment gateway, manajemen inventory, dan dashboard analytics.',
                'repository_url'   => 'https://github.com/demo/ecommerce-platform',
                'github_repo_name' => 'ecommerce-platform',
                'status'           => 'on_track',
                'start_date'       => '2024-01-15',
                'end_date'         => '2024-06-30',
            ]
        );
        $project1->members()->syncWithoutDetaching([$admin->id => ['role' => 'admin'], $pm->id => ['role' => 'pm']]);

        $project2 = Project::firstOrCreate(
            ['name' => 'Mobile Banking App'],
            [
                'client_name'      => 'Bank Digital Indonesia',
                'description'      => 'Aplikasi mobile banking dengan fitur transfer, pembayaran tagihan, dan investasi.',
                'repository_url'   => 'https://github.com/demo/mobile-banking',
                'github_repo_name' => 'mobile-banking',
                'status'           => 'at_risk',
                'start_date'       => '2024-02-01',
                'end_date'         => '2024-08-31',
            ]
        );
        $project2->members()->syncWithoutDetaching([$pm->id => ['role' => 'pm'], $client->id => ['role' => 'client']]);

        // 3. Sample Engineering Events with AI Summaries
        $events = [
            [
                'project_id'  => $project1->id,
                'source'      => 'github',
                'event_type'  => 'push',
                'title'       => 'Push by dewanta to main [a1b2c3d]',
                'description' => 'fix: perbaiki validasi kode voucher pada proses checkout',
                'actor'       => 'dewanta',
                'branch_name' => 'main',
                'commit_hash' => 'a1b2c3d',
                'summary' => [
                    'technical_summary'       => 'Developer memperbaiki bug validasi pembayaran pada modul checkout.',
                    'business_summary'        => 'Perbaikan ini membantu mengurangi risiko kegagalan transaksi pelanggan.',
                    'client_friendly_summary' => 'Tim telah meningkatkan stabilitas proses pembayaran agar pengguna dapat menyelesaikan transaksi dengan lebih lancar.',
                    'risk_level'              => 'low',
                    'business_impact'         => 'Meningkatkan keandalan proses checkout dan mengurangi potensi kehilangan pendapatan.',
                    'recommended_action'      => 'Lakukan pengujian ulang pada alur pembayaran sebelum deploy ke production.',
                ],
            ],
            [
                'project_id'  => $project1->id,
                'source'      => 'github',
                'event_type'  => 'pull_request',
                'title'       => 'PR opened: feat: tambah fitur wishlist produk',
                'description' => 'Menambahkan fitur wishlist agar pengguna bisa menyimpan produk favorit.',
                'actor'       => 'budi.dev',
                'branch_name' => 'feat/wishlist',
                'summary' => [
                    'technical_summary'       => 'Pull request untuk menambahkan fitur wishlist dengan endpoint API baru.',
                    'business_summary'        => 'Fitur wishlist akan meningkatkan engagement pengguna dan potensi konversi penjualan.',
                    'client_friendly_summary' => 'Tim sedang mengembangkan fitur baru yang memungkinkan pelanggan menyimpan produk favorit mereka.',
                    'risk_level'              => 'low',
                    'business_impact'         => 'Peningkatan user engagement dan potensi peningkatan penjualan 5-10%.',
                    'recommended_action'      => 'Review PR dan lakukan testing pada staging environment.',
                ],
            ],
            [
                'project_id'  => $project2->id,
                'source'      => 'github',
                'event_type'  => 'issue',
                'title'       => 'Issue opened: Bug transfer gagal saat saldo tidak cukup',
                'description' => 'Aplikasi crash ketika pengguna mencoba transfer dengan saldo tidak cukup. Error tidak tertangani dengan baik.',
                'actor'       => 'user-report',
                'summary' => [
                    'technical_summary'       => 'Unhandled exception pada modul transfer ketika validasi saldo gagal.',
                    'business_summary'        => 'Bug ini menyebabkan pengalaman buruk saat pengguna mencoba transfer dengan saldo tidak cukup.',
                    'client_friendly_summary' => 'Terdapat masalah pada proses transfer yang perlu segera diperbaiki untuk menjaga kepercayaan pengguna.',
                    'risk_level'              => 'high',
                    'business_impact'         => 'Risiko tinggi kehilangan kepercayaan pengguna dan potensi churn.',
                    'recommended_action'      => 'Segera perbaiki error handling dan deploy hotfix ke production.',
                ],
            ],
        ];
 
        foreach ($events as $eventData) {
            $summary = $eventData['summary'];
            unset($eventData['summary']);
 
            $event = EngineeringEvent::create($eventData);
            AiSummary::create(array_merge($summary, ['engineering_event_id' => $event->id]));
        }
 
        // 4. Sample Manual Error Log
        $errorLog = ManualErrorLog::create([
            'project_id'    => $project2->id,
            'title'         => 'TypeError: Cannot read properties of undefined at PaymentService.validate()',
            'environment'   => 'production',
            'error_message' => 'TypeError: Cannot read properties of undefined (reading \'amount\')',
            'stack_trace'   => "at PaymentService.validate (/app/services/payment.js:45:12)\nat async processPayment (/app/controllers/payment.js:23:5)\nat async POST /api/payments",
            'severity'      => 'critical',
            'notes'         => 'Error terjadi saat peak hour. Berdampak ke ~15% transaksi dalam 30 menit terakhir.',
        ]);
 
        $errorEvent = EngineeringEvent::create([
            'project_id'  => $project2->id,
            'source'      => 'manual',
            'event_type'  => 'error_log',
            'title'       => '[Critical] TypeError: Cannot read properties of undefined at PaymentService.validate()',
            'description' => "Environment: production\n\nError: TypeError: Cannot read properties of undefined (reading 'amount')\n\nStack Trace:\nat PaymentService.validate (/app/services/payment.js:45:12)",
            'actor'       => 'Project Manager',
        ]);
 
        AiSummary::create([
            'engineering_event_id'    => $errorEvent->id,
            'technical_summary'       => 'Error kritis pada PaymentService.validate() — properti amount tidak terdefinisi saat proses validasi pembayaran.',
            'business_summary'        => 'Error ini menyebabkan gangguan serius pada proses pembayaran di production yang berdampak langsung ke pendapatan.',
            'client_friendly_summary' => 'Sistem mendeteksi gangguan pada proses pembayaran. Tim sedang menangani masalah ini sebagai prioritas utama.',
            'risk_level'              => 'critical',
            'business_impact'         => 'Estimasi ~15% transaksi terdampak dalam 30 menit terakhir. Potensi kehilangan pendapatan signifikan.',
            'recommended_action'      => 'Deploy hotfix segera, aktifkan rollback jika perlu, dan komunikasikan status kepada klien.',
        ]);

        $this->command->info('✅ BridgeOps AI seeders completed!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@bridgeops.local', 'password'],
                ['PM',    'pm@bridgeops.local',    'password'],
                ['Client','client@bridgeops.local','password'],
            ]
        );
    }
}
