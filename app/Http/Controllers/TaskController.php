<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        // 優先度がセットされていない場合のデフォルト値
        if (!isset($data['priority'])) {
            $data['priority'] = Task::PRIORITY_MEDIUM;
        }
        
        $request->user()->tasks()->create($data);
        return redirect()->route('tasks.index')->with('success', 'タスクが作成されました！');
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update($request->validated());
        return redirect()->route('tasks.index')->with('success', 'タスクが更新されました！');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'タスクが削除されました！');
    }

    public function toggle(Task $task)
    {
        $this->authorize('update', $task);
        // 完了ステータスと未完了（todoまたはin_progress）を切り替える
        $newStatus = ($task->status === Task::STATUS_COMPLETED)
            ? Task::STATUS_TODO
            : Task::STATUS_COMPLETED;

        $task->update([
            'status' => $newStatus
        ]);
        return redirect()->back()->with('success', 'タスクの状態が変更されました！');
    }

    /**
     * タスクのステータスを更新する
     */
    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:todo,in_progress,completed',
        ]);

        $task->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'タスクのステータスを更新しました');
    }

    /**
     * タスクの優先度を更新する
     */
    public function updatePriority(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'priority' => 'required|in:high,medium,low',
        ]);

        $task->update([
            'priority' => $request->priority,
        ]);

        return redirect()->back()->with('success', 'タスクの優先度を更新しました');
    }
}
