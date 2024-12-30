<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Task;
use Illuminate\Console\Command;

class AddTaskCommand extends Command
{
    protected $signature = 'tdlt:add-task-command';

    protected $description = 'Create a new task';

    public function handle()
    {
        $idGroup = $this->idGroup();

        $taskDescription = $this->ask('Qual a descrição da tarefa?');

        $taskCreate = Task::updateOrCreate([
            'description' => $taskDescription
        ], [
            "group_id" => $idGroup
        ]);

        $tasks = Task::where('id', $taskCreate->id)->get();

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

    public function idGroup()
    {
        $groups = Group::all();

        if ($groups->isEmpty()) {
            $this->error('Nenhum grupo encontrado. Por favor, crie um grupo antes de continuar.');
            return 1;
        }

        $groupOptions = $groups->pluck('name', 'name')->toArray();

        $groupNames = $this->choice(
            'Escolha o grupo para a tarefa',
            array_keys($groupOptions),
            null,
            null,
            true
        );

        return Group::whereIn("name", array_values($groupNames))
            ->first()->id;
    }
}
