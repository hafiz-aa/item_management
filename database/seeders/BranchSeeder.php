<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'branch_id' => 1,
                'comp_id' => 1,
                'branch_code' => 'HQ',
                'branch_name' => 'Head Office',
                'branch_is_headquarter' => true,
                'branch_address' => 'Jakarta',
            ],
            [
                'branch_id' => 2,
                'comp_id' => 1,
                'branch_code' => 'BDG',
                'branch_name' => 'Bandung Branch',
                'branch_is_headquarter' => false,
                'branch_address' => 'Bandung',
            ],
            [
                'branch_id' => 3,
                'comp_id' => 1,
                'branch_code' => 'SBY',
                'branch_name' => 'Surabaya Branch',
                'branch_is_headquarter' => false,
                'branch_address' => 'Surabaya',
            ],
        ];

        foreach ($branches as $data) {
            Branch::updateOrCreate(
                ['branch_id' => $data['branch_id']],
                $data
            );
        }
    }
}
