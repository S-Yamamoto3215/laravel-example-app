<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->tasks()->latest();

        // カテゴリによるフィルタリング
        if ($request->has('category')) {
            if ($request->category === 'none') {
                $query->whereNull('category_id');
            } elseif ($request->category) {
                $query->where('category_id', $request->category);
            }
        }

        // ステータスによるフィルタリング
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // 優先度によるフィルタリング
        if ($request->has('priority') && $request->priority) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->get();
        $categories = \App\Models\Category::all();

        return view('tasks.index', compact('tasks', 'categories'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('tasks.create', compact('categories'));
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
        $categories = \App\Models\Category::all();
        return view('tasks.show', compact('task', 'categories'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $categories = \App\Models\Category::all();
        return view('tasks.edit', compact('task', 'categories'));
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

    public function destroyCompleted()
    {
        // 完了したタスクのみを取得して削除
        $completedTasks = Auth::user()->tasks()->where('status', Task::STATUS_COMPLETED)->get();

        // 各タスクに対して権限チェックと削除を実行
        foreach($completedTasks as $task) {
            $this->authorize('delete', $task);
            $task->delete();
        }

        return redirect()->route('tasks.index')->with('success', '完了済みタスクがすべて削除されました！');
    }
}
