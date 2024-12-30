<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class ListGroupCommand extends Command
{
    protected $signature = 'tdlt:list-group-command';

    protected $description = 'List groups';

    public function handle()
    {
        $groups = Group::get();

        $this->table([
            'ID',
            'Nome',
        ], $groups->map(function ($group) {
            return [
                $group->id,
                $group->name,
            ];
        })->toArray());

        return 0;
    }
}
