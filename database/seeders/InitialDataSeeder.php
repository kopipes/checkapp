<?php

namespace Database\Seeders;

use App\Models\HealthCheck;
use App\Models\User;
use App\Services\HealthStatusService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(HealthStatusService::class);

        // Admin user (created_by)
        $admin = User::where('role', 'admin')->first();

        $records = [
            // No;Tanggal Mulai;Nama;Gender;GDP;GDS;Catatan GDS;Asam Urat;Kolesterol;Sistolik;Diastolik
            ['2026-06-09', 'Pak Koko', 'male',   91,   null, null,      4.8,  null, null, null],
            ['2026-06-09', 'Oding',    'male',   133,   null, null,      4.6,  159,  115,   82],
            ['2026-06-09', 'Mahadie',  'male',    89,   null, null,      7.8,  229,  134,   85],
            ['2026-06-09', 'Denny',    'male',   123,   136, '23 Jun',   8.2,  169, null, null],
            ['2026-06-09', 'Ghian',    'male',   125,   null, null,      8.9,  null, null, null],
            ['2026-06-09', 'Mini',     'female',  96,   null, null,      4.7,  136, null, null],
            ['2026-06-09', 'Hilman',   'male',   108,   null, null,      8.1,  178,  138,   96],
            ['2026-06-11', 'Agung',    'male',   104,   null, null,      6.2,  103,  147,  101],
            ['2026-06-11', 'Asep',     'male',   129,   173,  null,      5.9,  184,  164,  110],
            ['2026-06-11', 'Bob',      'male',  null,    91,  null,      8.3,  183, null, null],
            ['2026-06-11', 'Jenny',    'female', null,  115,  null,     null,  null, null, null],
            ['2026-06-12', 'Yudi',     'male',    96,   null, null,      5.0,  206,  135,  101],
            ['2026-06-12', 'Arul',     'male',   107,   null, null,      7.6,  141,   96,   63],
            ['2026-06-12', 'Maul',     'male',   116,   155, '23 Jun',   6.5,  172,  122,   78],
            ['2026-06-15', 'Sefi',     'female', 103,   null, null,      3.6,  173, null, null],
            ['2026-06-15', 'Jule',     'female',  93,   null, null,      3.4,  186,  103,   78],
            ['2026-06-15', 'Novi',     'female', 100,   null, null,      4.3,  164,  120,   75],
            ['2026-06-15', 'Negga',    'male',   123,   null, null,      6.3,  153,  146,   99],
            ['2026-06-17', 'Diyan',    'male',   100,   null, null,      4.5,  150, null, null],
            ['2026-06-17', 'Pras',     'male',   113,   101, '23 Jun',   8.1,  169, null, null],
            ['2026-06-17', 'Pay',      'male',   100,   null, null,      4.8,  190, null, null],
            ['2026-06-18', 'Winton',   'male',   104,   null, null,      4.0,  184,  116,   78],
            ['2026-06-18', 'Regen',    'male',   106,   null, null,      6.5,  176, null, null],
            ['2026-06-22', 'Mirza',    'male',   110,   null, null,      4.6,  137,  168,   91],
            ['2026-06-23', 'Danuri',   'male',   108,   null, null,      7.3,  139,  127,   99],
            ['2026-06-23', 'Jiung',    'male',   102,   null, null,      6.3,  156,  125,   81],
            ['2026-06-24', 'Marco',    'male',   102,   null, null,      3.7,  179,  116,   73],
            ['2026-06-24', 'Ardi',     'male',    97,   null, null,      4.5,  157,  129,   73],
            ['2026-06-25', 'Arlan',    'male',    94,   null, null,      3.5,  160,  100,   71],
            ['2026-06-25', 'Ady W.',   'male',   102,   null, null,      4.1,  155,  129,   84],
        ];

        // Notes per name (from Catatan GDS column)
        $notesMap = [
            'Denny' => 'GDS diulang tanggal 23 Jun',
            'Maul'  => 'GDS diulang tanggal 23 Jun',
            'Pras'  => 'GDS diulang tanggal 23 Jun',
        ];

        foreach ($records as [
            $checkDate, $name, $gender,
            $gdp, $gds, $gdsNote,
            $uricAcid, $cholesterol,
            $systolic, $diastolic
        ]) {
            // Create user if not exists
            $slug  = strtolower(str_replace([' ', '.'], ['_', ''], $name));
            $email = $slug . '@checkapp.local';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'      => $name,
                    'username'  => $slug,
                    'password'  => Hash::make('user123'),
                    'role'      => 'user',
                    'gender'    => $gender,
                    'is_active' => true,
                ]
            );

            // Build data array
            $data = [
                'fasting_blood_sugar' => $gdp,
                'random_blood_sugar'  => $gds,
                'uric_acid'           => $uricAcid,
                'cholesterol'         => $cholesterol,
                'systolic'            => $systolic,
                'diastolic'           => $diastolic,
            ];

            // Calculate statuses
            $statuses = $service->calculateStatuses($data, $user->gender ?? 'male');

            // Build notes
            $notes = isset($notesMap[$name]) ? $notesMap[$name] : null;

            HealthCheck::create(array_merge($data, $statuses, [
                'user_id'    => $user->id,
                'check_date' => $checkDate,
                'notes'      => $notes,
                'created_by' => $admin->id,
            ]));
        }

        $this->command->info('Initial health check data seeded: ' . count($records) . ' records.');
    }
}
