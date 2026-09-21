<?php

namespace Database\Seeders;

use App\Models\OperationalFunction;
use Illuminate\Database\Seeder;

class OperationalFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Sopir', 'Petugas Lapangan'] as $name) {
            OperationalFunction::query()->firstOrCreate(
                ['normalized_name' => OperationalFunction::normalizeName($name)],
                ['name' => $name, 'is_active' => true],
            );
        }
    }
}
