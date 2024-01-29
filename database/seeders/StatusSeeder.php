<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = Status::all();

        if (count($list) === 0) {
            Status::insert([
                ['name' => 'Todo'],
                ['name' => 'In Prgress'],
                ['name' => 'Done'],
            ]);
        }
    }
}
