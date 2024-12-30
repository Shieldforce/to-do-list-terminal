<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;

class CheckTaskCommand extends Command
{
    protected $signature = 'tdlt:check-task-command';

    protected $description = 'Finished tasks';

    public function handle()
    {
        $tasks = Task::where("finished", false)->get();

        if ($tasks->isEmpty()) {
            $this->info('Nenhuma tarefa pendente para finalizar.');
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
            ->setTitle("Selecione as tarefas para marcar como concluídas")
            ->addCheckboxItems($options)
            ->setWidth(300)
            ->setPadding(1)
            ->setMargin(1)
            ->setTitleSeparator('-')
            ->setExitButtonText("Finalizar")
            //->setForegroundColour('green')
            //->setBackgroundColour('black')
            ->build();

        $menu->open();

        $itemsChecked = [];

        foreach ($menu->getItems() as $item) {
            if (
                $item->getText() != "Finalizar" &&
                $item->getChecked()
            )
                $itemsChecked[] = $item->getText();
        }

        Task::whereIn("description", $itemsChecked)->update(['finished' => true]);
        $count = count($itemsChecked);

        $this->info("($count) Tarefas foram finalizas com sucesso!)");

        return 0;
    }
}
