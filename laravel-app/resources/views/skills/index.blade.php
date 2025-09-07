<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スキル一覧</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">スキル一覧</h1>
            <div class="flex gap-3">
                <a href="/" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">トップへ</a>
                <a href="{{ route('skills.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">新規スキル登録</a>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if ($skills->count() === 0)
            <div class="bg-white border rounded p-6 text-center text-gray-600">スキルがありません。右上の「新規スキル登録」から追加してください。</div>
        @else
            <div class="bg-white border rounded overflow-hidden">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">タイトル</th>
                            <th class="px-4 py-2 text-left text-sm text-gray-600">概要</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $skill)
                            <tr class="border-t">
                                <td class="px-4 py-2 align-top font-medium text-gray-900">{{ $skill->title }}</td>
                                <td class="px-4 py-2 align-top text-gray-700">{{ Str::limit($skill->description, 120) }}</td>
                                <td class="px-4 py-2 text-right">
                                    <a href="#" class="text-blue-600 hover:underline">詳細を見る</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $skills->links() }}
            </div>
        @endif
    </div>
</body>
</html>


