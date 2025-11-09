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
                {{ session('status') }}
            </div>
        @endif

        <div id="success-toast" class="fixed top-6 right-6 z-50 hidden">
            <div class="shadow-lg rounded-md bg-white border border-green-200 px-4 py-3 flex items-center gap-3">
                <span class="text-lg">🎉</span>
                <div class="text-sm">
                    <div class="font-medium text-gray-900">{{ session('status') }}</div>
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
                        <div class="py-3 group">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="text-sm text-gray-500">{{ \Illuminate\Support\Carbon::parse($p->progress_date)->format('Y-m-d') }}</div>
                                    <div class="font-bold text-gray-900 mt-1">{{ $p->title }}</div>
                                    <div class="text-gray-700 whitespace-pre-wrap mt-1">{{ $p->content }}</div>
                                </div>
                                <div class="ml-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        type="button"
                                        class="edit-progress-btn px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                                        data-progress-id="{{ $p->id }}"
                                        data-progress-date="{{ $p->progress_date }}"
                                        data-progress-title="{{ htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8') }}"
                                        data-progress-content="{{ htmlspecialchars($p->content, ENT_QUOTES, 'UTF-8') }}">
                                        編集
                                    </button>
                                    <form 
                                        action="{{ route('skills.progresses.destroy', [$skill, $p]) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('この進捗を削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">
                                            削除
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- 編集モーダル -->
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">進捗を編集</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="edit-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-700 mb-1">日付</label>
                    <input type="date" name="progress_date" id="edit-progress-date" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">行なったことのタイトル</label>
                    <input type="text" name="title" id="edit-title" class="w-full border rounded px-3 py-2" placeholder="例：教材2章完了" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-1">内容</label>
                    <textarea name="content" id="edit-content" rows="3" class="w-full border rounded px-3 py-2" placeholder="詳細や気づきなど"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                        キャンセル
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(progressId, progressDate, title, content) {
            const form = document.getElementById('edit-form');
            const modal = document.getElementById('edit-modal');
            const skillId = {{ $skill->id }};
            
            form.action = `{{ url('skills') }}/${skillId}/progresses/${progressId}`;
            document.getElementById('edit-progress-date').value = progressDate;
            document.getElementById('edit-title').value = title;
            document.getElementById('edit-content').value = content;
            modal.classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        // 編集ボタンのクリックイベント
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-progress-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const progressId = this.getAttribute('data-progress-id');
                    const progressDate = this.getAttribute('data-progress-date');
                    const progressTitle = this.getAttribute('data-progress-title');
                    const progressContent = this.getAttribute('data-progress-content');
                    openEditModal(progressId, progressDate, progressTitle, progressContent);
                });
            });

            // モーダルの外側をクリックしたら閉じる
            const modal = document.getElementById('edit-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditModal();
                    }
                });
            }
        });
    </script>
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


