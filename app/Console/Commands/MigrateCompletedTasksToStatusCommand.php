<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateCompletedTasksToStatusCommand extends Command
{
    protected $signature = 'tasks:migrate-status';
    protected $description = 'Migrates tasks with is_completed flag to use status field instead';

    public function handle()
    {
        $this->info('Starting migration of task completion status to status field...');

        // もし is_completed カラムがまだ存在するなら
        if (DB::getSchemaBuilder()->hasColumn('tasks', 'is_completed')) {
            $completedCount = 0;
            $incompleteCount = 0;

            // Find tasks where is_completed is true and status is not 'completed'
            $completedTasks = Task::where('is_completed', true)
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->get();

            foreach ($completedTasks as $task) {
                $task->update(['status' => Task::STATUS_COMPLETED]);
                $completedCount++;
            }

            // Find tasks where is_completed is false and status is not set or is 'completed'
            $incompleteTasks = Task::where('is_completed', false)
                ->where(function ($query) {
                    $query->whereNull('status')
                        ->orWhere('status', Task::STATUS_COMPLETED);
                })
                ->get();

            foreach ($incompleteTasks as $task) {
                $task->update(['status' => Task::STATUS_TODO]);
                $incompleteCount++;
            }

            $this->info("Updated $completedCount completed tasks and $incompleteCount incomplete tasks.");
        } else {
            $this->info('The is_completed column no longer exists. No migration needed.');
        }

        $this->info('Migration completed successfully!');
    }
}
