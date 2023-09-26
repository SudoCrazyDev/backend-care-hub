<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Appointment;
use App\Models\MetaValues;
use App\Models\Patient;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'firstname' => 'GERALDINE',
            'lastname' => null,
            'username' => 'GERALDINE001',
            'email' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        User::create([
            'firstname' => 'NATIVIDAD',
            'lastname' => 'TORRE',
            'username' => 'TORRE002',
            'email' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        User::create([
            'firstname' => 'PHILIP LOUIS',
            'lastname' => 'CALUB',
            'username' => 'administrator',
            'email' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        MetaValues::create([
            'meta_key' => 'lab_request_template',
            'meta_values' => '[{"id":"laboratory_request","title":"LABORATORY REQUESTS","value":[{"id":"cbc","title":"CBC","value":false},{"id":"urinalysis","title":"URINALYSIS","value":false},{"id":"stool_exam","title":"STOOL EXAM","value":false}]},{"id":"blood_chemistry","title":"BLOOD CHEMISTRY","value":[{"id":"fbs","title":"FBS","value":false},{"id":"bun","title":"BUN","value":false},{"id":"creatinenine","title":"Creatinine","value":false},{"id":"uric_acid","title":"Uric Acid","value":false},{"id":"total_cholesterol","title":"Total Cholesterol","value":false},{"id":"hdl","title":"HDL","value":false},{"id":"globulin","title":"Globulin","value":false},{"id":"serum_na","title":"Serum Na","value":false},{"id":"serum_k","title":"Serum K","value":false},{"id":"serum_cl","title":"Serum Cl","value":false},{"id":"serum_ca","title":"Serum Ca","value":false},{"id":"serum_mg","title":"Serum Mg","value":false},{"id":"bt","title":"BT","value":false},{"id":"ldl","title":"LDL","value":false},{"id":"lol","title":"LOL","value":false},{"id":"hbsag","title":"HBSaG","value":false},{"id":"sgpt","title":"SGPT","value":false},{"id":"sgot","title":"SGOT","value":false},{"id":"ldh","title":"LDH","value":false},{"id":"alk_phos","title":"Alk Phos","value":false},{"id":"tryglycerides","title":"Tryglycerides","value":false},{"id":"albumin","title":"Albumin","value":false},{"id":"ptpa","title":"PTPA","value":false}]},{"id":"xray_examination","title":"X-RAY EXAMINATION","value":[{"id":"css","title":"Cervical Spine Serires","value":false},{"id":"lss","title":"Lumbosacral Spine Series","value":false},{"id":"tss","title":"Thoracolumbar Spine Series","value":false},{"id":"chest_xray","title":"Chest X-Ray","value":false},{"id":"barium_enema","title":"Barium Enema","value":false},{"id":"ugi_series","title":"UGI Series","value":false},{"id":"complete_abdomen","title":"Complete Abdomen","value":false},{"id":"kvb_ivp","title":"KVB-IVP","value":false}]}]'
        ]);

        MetaValues::create([
            'meta_key' => 'Doctor License No.',
            'meta_values' => '0077811'
        ]);

        MetaValues::create([
            'meta_key' => 'PTR No.',
            'meta_values' => '0525578A'
        ]);

        MetaValues::create([
            'meta_key' => 'S2 No.',
            'meta_values' => '010505RM21-044-M'
        ]);
    }
}
