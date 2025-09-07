<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スキル登録</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">スキル登録</h1>
            <div class="flex gap-3">
                <a href="{{ route('skills.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">一覧へ戻る</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('skills.store') }}" class="bg-white border rounded p-6 space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-sm text-gray-700 mb-1">タイトル（必須）</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full border rounded px-3 py-2" placeholder="例：Laravel, React">
            </div>
            <div>
                <label for="description" class="block text-sm text-gray-700 mb-1">概要（任意）</label>
                <textarea id="description" name="description" rows="5" class="w-full border rounded px-3 py-2" placeholder="学習の目的やメモなど">{{ old('description') }}</textarea>
            </div>
            <div class="pt-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">保存する</button>
            </div>
        </form>
    </div>
</body>
</html>


