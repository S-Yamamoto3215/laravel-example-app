@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="text-2xl font-semibold">カテゴリ編集</h2>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">カテゴリ名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="color" class="block text-gray-700 font-medium mb-2">カテゴリカラー</label>
                <div class="flex items-center">
                    <input type="color" name="color_picker" id="color_picker" value="#{{ old('color', $category->color) }}" class="h-10 w-10 border border-gray-300 rounded-md mr-2">
                    <input type="text" name="color" id="color" value="{{ old('color', $category->color) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300" placeholder="カラーコード（例: 3490dc）" required>
                </div>
                <p class="text-gray-500 text-sm mt-1">6桁の16進カラーコードを入力してください（例: FF5733）</p>
                @error('color')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">プレビュー</label>
                <div class="flex items-center">
                    <div id="color_preview" class="w-6 h-6 rounded-full mr-2" style="background-color: #{{ $category->color }}"></div>
                    <span id="preview_name">{{ $category->name }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-800">キャンセル</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">更新する</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorPicker = document.getElementById('color_picker');
            const colorInput = document.getElementById('color');
            const colorPreview = document.getElementById('color_preview');
            const nameInput = document.getElementById('name');
            const previewName = document.getElementById('preview_name');

            // カラーピッカーの値が変わったらテキストフィールドを更新
            colorPicker.addEventListener('input', function() {
                const hexColor = colorPicker.value.substring(1); // '#' を除去
                colorInput.value = hexColor;
                colorPreview.style.backgroundColor = colorPicker.value;
            });

            // テキストフィールドの値が変わったらカラーピッカーを更新
            colorInput.addEventListener('input', function() {
                const hexColor = colorInput.value.replace(/[^0-9a-fA-F]/g, '').substring(0, 6);
                colorInput.value = hexColor;
                colorPicker.value = '#' + hexColor.padEnd(6, '0');
                colorPreview.style.backgroundColor = '#' + hexColor;
            });

            // 名前が変更されたらプレビューを更新
            nameInput.addEventListener('input', function() {
                previewName.textContent = nameInput.value || 'カテゴリ名';
            });
        });
    </script>
@endsection
