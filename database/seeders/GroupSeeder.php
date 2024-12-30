<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ["name" => "Programação"],
            ["name" => "Vida"],
            ["name" => "Saúde"],
            ["name" => "Amor"],
            ["name" => "Meta"],
            ["name" => "Diária"],
            ["name" => "Semanal"],
            ["name" => "Anual"],
            ["name" => "Semestral"],
        ];

        DB::table('groups')->insert($groups);
    }
}
