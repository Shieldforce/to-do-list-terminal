<?php

namespace Database\Seeders;

use App\Models\Group;
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

        foreach ($groups as $group) {
            Group::updateOrCreate(["name" => $group["name"]], []);
        }
    }
}
