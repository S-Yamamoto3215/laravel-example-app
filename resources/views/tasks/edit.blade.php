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
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_completed" value="1" class="rounded border-gray-300 text-blue-500 focus:border-blue-300 focus:ring focus:ring-blue-200" {{ old('is_completed', $task->is_completed) ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">完了済み</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-800">キャンセル</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">更新する</button>
            </div>
        </form>
    </div>
@endsection
