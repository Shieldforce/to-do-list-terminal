<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class ListTaskCommand extends Command
{
    protected $signature = 'tdlt:list-task-command {all}';

    protected $description = 'List all tasks';

    public function handle()
    {
        $tasks = Task::where("finished", false)->get();

        if($this->argument('all')=="all=yes") {
            $tasks = Task::get();
        }

        $this->table([
            'ID',
            'Grupo',
            'Descrição',
            'Concluída',
        ], $tasks->map(function ($task) {
            return [
                $task->id,
                $task->group->name,
                substr($task->description, 0, 200) . "...",
                $task->finished
                    ? '<fg=green>Sim</>'
                    : '<fg=red>Não</>',
            ];
        })->toArray());

        return 0;
    }
}
