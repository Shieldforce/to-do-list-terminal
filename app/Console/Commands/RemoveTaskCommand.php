<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class RemoveTaskCommand extends Command
{
    protected $signature = 'tdlt:remove-task-command';

    protected $description = 'Remove tasks';

    public function handle()
    {
        $tasks = Task::where("finished", false)->get();

        if ($tasks->isEmpty()) {
            $this->info('Nenhuma tarefa pendente para remover.');
            return 0;
        }

        $options = $tasks->map(function ($task) {
            return [
                $task->description,
                function () use ($task) {
                    return $task->description;
                }
            ];
        })->toArray();

        //Available colors: black, red, green, yellow, blue, magenta, cyan, white.
        $menu = (new CliMenuBuilder())
            ->setTitle("Selecione as tarefas para remover!")
            ->addCheckboxItems($options)
            ->setWidth(300)
            ->setPadding(1)
            ->setMargin(1)
            ->setTitleSeparator('-')
            ->setExitButtonText("Finalizar Exclusão")
            //->setForegroundColour('green')
            //->setBackgroundColour('black')
            ->build();

        $menu->open();

        $itemsChecked = [];

        foreach ($menu->getItems() as $item) {
            if (
                $item->getText() != "Finalizar Exclusão" &&
                $item->getChecked()
            )
                $itemsChecked[] = $item->getText();
        }

        Task::whereIn("description", $itemsChecked)->delete();
        $count = count($itemsChecked);

        $this->info("($count) Tarefas foram removidas com sucesso!)");

        return 0;
    }
}
