@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="text-2xl font-semibold">タスク編集</h2>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('tasks.update', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">タイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300" required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-medium mb-2">ステータス</label>
                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    @foreach(App\Models\Task::getStatusOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('status', $task->status) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-gray-700 font-medium mb-2">優先度</label>
                <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    @foreach(App\Models\Task::getPriorityOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('priority', $task->priority ?? 'medium') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 font-medium mb-2">カテゴリ</label>
                <select name="category_id" id="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    <option value="">カテゴリなし</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                            <span style="color: #{{ $category->color }}">■</span> {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-800">キャンセル</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">更新する</button>
            </div>
        </form>
    </div>
@endsection
