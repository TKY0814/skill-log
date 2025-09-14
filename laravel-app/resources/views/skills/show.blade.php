<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スキル詳細</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">スキル詳細</h1>
            <a href="{{ route('skills.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">一覧へ戻る</a>
        </div>

        @if (session('status'))
            <div id="success-banner" class="mb-4 p-3 bg-green-50 text-green-700 border border-green-200 rounded">
                今日もお疲れ様です！進捗を追加しました
            </div>
        @endif

        <div id="success-toast" class="fixed top-6 right-6 z-50 hidden">
            <div class="shadow-lg rounded-md bg-white border border-green-200 px-4 py-3 flex items-center gap-3">
                <span class="text-lg">🎉</span>
                <div class="text-sm">
                    <div class="font-medium text-gray-900">今日もお疲れ様です！</div>
                    <div class="text-gray-700">進捗を追加しました</div>
                </div>
            </div>
        </div>

        <section class="bg-white border rounded p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">{{ $skill->title }}</h2>
            <p class="text-gray-700 whitespace-pre-wrap">{{ $skill->description }}</p>
        </section>

        <section class="bg-white border rounded p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">日々の進捗</h2>
            </div>

            <form action="{{ route('skills.progresses.store', $skill) }}" method="POST" class="mb-6 grid md:grid-cols-3 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-sm text-gray-700 mb-1">日付</label>
                    <input type="date" name="progress_date" value="{{ now()->toDateString() }}" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">行なったことのタイトル</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2" placeholder="例：教材2章完了" required>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm text-gray-700 mb-1">内容</label>
                    <textarea name="content" rows="3" class="w-full border rounded px-3 py-2" placeholder="詳細や気づきなど"></textarea>
                </div>
                <div>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">追加</button>
                </div>
            </form>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 text-red-700 border border-red-200 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($skill->progresses->isEmpty())
                <div class="text-gray-600">進捗はまだありません。</div>
            @else
                <div class="divide-y">
                    @foreach ($skill->progresses as $p)
                        <div class="py-3">
                            <div class="text-sm text-gray-500">{{ \Illuminate\Support\Carbon::parse($p->progress_date)->format('Y-m-d') }}</div>
                            <div class="font-medium text-gray-900">{{ $p->title }}</div>
                            <div class="text-gray-700 whitespace-pre-wrap">{{ $p->content }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
    <script>
        (function () {
            const hasStatus = {{ session()->has('status') ? 'true' : 'false' }};
            if (!hasStatus || typeof confetti === 'undefined') return;

            // Confetti burst
            const duration = 1200;
            const end = Date.now() + duration;
            const colors = ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444'];

            (function frame() {
                confetti({
                    particleCount: 2,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors,
                });
                confetti({
                    particleCount: 2,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors,
                });
                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                } else {
                    confetti({ particleCount: 80, spread: 70, origin: { y: 0.6 }, colors });
                }
            })();

            // Toast show/hide
            const toast = document.getElementById('success-toast');
            toast.classList.remove('hidden');
            toast.classList.add('animate-[fadeIn_0.2s_ease-out]');
            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.classList.add('hidden'), 400);
            }, 2400);
        })();
    </script>
</body>
</html>


