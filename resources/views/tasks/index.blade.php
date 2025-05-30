@extends('layouts.app')

@section('content')
    <div class="mb-4 flex justify-between items-center">
        <h2 class="text-2xl font-semibold">タスク一覧</h2>
        <div class="flex space-x-2">
            <form action="{{ route('tasks.destroy.completed') }}" method="POST" onsubmit="return confirm('完了済みのタスクをすべて削除してもよろしいですか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    完了タスク削除
                </button>
            </form>
            <a href="{{ route('tasks.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                新規タスク作成
            </a>
        </div>
    </div>

    <div class="mb-6 p-4 bg-white rounded-lg shadow">
        <form action="{{ route('tasks.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1">
                <label for="category_filter" class="block text-sm font-medium text-gray-700 mb-1">カテゴリでフィルタ</label>
                <select name="category" id="category_filter" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="this.form.submit()">
                    <option value="">すべてのカテゴリ</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                    <option value="none" {{ request('category') === 'none' ? 'selected' : '' }}>カテゴリなし</option>
                </select>
            </div>
            <div class="flex-1">
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-1">ステータスでフィルタ</label>
                <select name="status" id="status_filter" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="this.form.submit()">
                    <option value="">すべてのステータス</option>
                    @foreach(App\Models\Task::getStatusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label for="priority_filter" class="block text-sm font-medium text-gray-700 mb-1">優先度でフィルタ</label>
                <select name="priority" id="priority_filter" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="this.form.submit()">
                    <option value="">すべての優先度</option>
                    @foreach(App\Models\Task::getPriorityOptions() as $value => $label)
                        <option value="{{ $value }}" {{ request('priority') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <label for="due_filter" class="block text-sm font-medium text-gray-700 mb-1">期限日でフィルタ</label>
                <select name="due_filter" id="due_filter" class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="this.form.submit()">
                    <option value="">すべての期限日</option>
                    <option value="today" {{ request('due_filter') == 'today' ? 'selected' : '' }}>今日</option>
                    <option value="tomorrow" {{ request('due_filter') == 'tomorrow' ? 'selected' : '' }}>明日</option>
                    <option value="this_week" {{ request('due_filter') == 'this_week' ? 'selected' : '' }}>今週</option>
                    <option value="overdue" {{ request('due_filter') == 'overdue' ? 'selected' : '' }}>期限切れ</option>
                    <option value="no_due_date" {{ request('due_filter') == 'no_due_date' ? 'selected' : '' }}>期限日なし</option>
                </select>
            </div>
            <div class="w-full flex justify-end">
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">フィルタをクリア</a>
            </div>
        </form>
    </div>

    @if($tasks->isEmpty())
        <p class="text-center py-8 text-gray-500">タスクはありません。新しいタスクを作成しましょう！</p>
    @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">完了</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">タイトル</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ステータス</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">優先度</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">期限日</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">カテゴリ</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">アクション</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="mr-3">
                                        @if($task->status === App\Models\Task::STATUS_COMPLETED)
                                            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <a href="{{ route('tasks.show', $task) }}" class="text-lg font-medium {{$task->status === App\Models\Task::STATUS_COMPLETED ? 'line-through text-gray-400' : 'text-gray-700' }}">
                                        {{ $task->title }}
                                    </a>
                                    @if($task->description)
                                        <p class="text-sm text-gray-500 truncate max-w-md">{{ Str::limit($task->description, 50) }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('tasks.update.status', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                            class="rounded border-gray-300
                                                {{ $task->status == 'todo' ? 'bg-gray-100' : '' }}
                                                {{ $task->status == 'in_progress' ? 'bg-blue-100' : '' }}
                                                {{ $task->status == 'completed' ? 'bg-green-100' : '' }}"
                                            onchange="this.form.submit()">
                                        @foreach(App\Models\Task::getStatusOptions() as $value => $label)
                                            <option value="{{ $value }}" {{ $task->status == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-sm
                                    {{ $task->priority == 'high' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $task->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $task->priority == 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ App\Models\Task::getPriorityOptions()[$task->priority ?? 'medium'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->due_date)
                                    <span class="{{ $task->due_date->isPast() && $task->status !== 'completed' ? 'text-red-600 font-bold' : '' }}">
                                        {{ $task->due_date->format('Y年m月d日') }}
                                    </span>
                                @else
                                    <span class="text-gray-400">未設定</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($task->category)
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: #{{ $task->category->color }}"></div>
                                        <span>{{ $task->category->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">未設定</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-blue-500 hover:text-blue-600">
                                        編集
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">削除</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
