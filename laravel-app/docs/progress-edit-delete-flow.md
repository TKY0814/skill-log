# 進捗編集・削除機能の処理フロー解説

## 目次
1. [全体の流れ](#全体の流れ)
2. [ルーティングの仕組み](#ルーティングの仕組み)
3. [編集機能の処理フロー](#編集機能の処理フロー)
4. [削除機能の処理フロー](#削除機能の処理フロー)
5. [セキュリティ対策](#セキュリティ対策)
6. [フロントエンドの動作](#フロントエンドの動作)

---

## 全体の流れ

```
ユーザー操作
    ↓
フロントエンド（Blade + JavaScript）
    ↓
HTTPリクエスト
    ↓
ルーティング（web.php）
    ↓
コントローラー（SkillController）
    ↓
モデル（SkillProgress）
    ↓
データベース（SQLite）
    ↓
レスポンス（リダイレクト + フラッシュメッセージ）
    ↓
ビュー表示
```

---

## ルーティングの仕組み

### ファイル: `routes/web.php`

```php
// 進捗の更新ルート
Route::put('skills/{skill}/progresses/{progress}', 
    [SkillController::class, 'updateProgress'])
    ->name('skills.progresses.update');

// 進捗の削除ルート
Route::delete('skills/{skill}/progresses/{progress}', 
    [SkillController::class, 'destroyProgress'])
    ->name('skills.progresses.destroy');
```

### ルートパラメータの解決
- `{skill}`: Laravelのルートモデルバインディングにより、自動的に`Skill`モデルのインスタンスに解決
- `{progress}`: 同様に`SkillProgress`モデルのインスタンスに解決
- URL例: `/skills/1/progresses/5`
  - `skill = 1` (Skill ID)
  - `progress = 5` (SkillProgress ID)

### HTTPメソッド
- **PUT**: 更新処理（HTMLフォームでは`@method('PUT')`で実現）
- **DELETE**: 削除処理（HTMLフォームでは`@method('DELETE')`で実現）

---

## 編集機能の処理フロー

### 1. フロントエンド: 編集ボタンのクリック

**ファイル: `resources/views/skills/show.blade.php` (84-91行目)**

```php
<button 
    type="button"
    class="edit-progress-btn"
    data-progress-id="{{ $p->id }}"
    data-progress-date="{{ $p->progress_date }}"
    data-progress-title="{{ htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8') }}"
    data-progress-content="{{ htmlspecialchars($p->content, ENT_QUOTES, 'UTF-8') }}">
    編集
</button>
```

**ポイント:**
- `data-*`属性で進捗データを保存
- `htmlspecialchars()`でXSS対策（HTMLエスケープ）
- `type="button"`で通常のフォーム送信を防ぐ

### 2. JavaScript: モーダルを開く

**ファイル: `resources/views/skills/show.blade.php` (168-179行目)**

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-progress-btn');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // data属性から値を取得
            const progressId = this.getAttribute('data-progress-id');
            const progressDate = this.getAttribute('data-progress-date');
            const progressTitle = this.getAttribute('data-progress-title');
            const progressContent = this.getAttribute('data-progress-content');
            
            // モーダルを開く関数を呼び出し
            openEditModal(progressId, progressDate, progressTitle, progressContent);
        });
    });
});
```

**処理内容:**
1. ページ読み込み完了後に実行（`DOMContentLoaded`）
2. すべての編集ボタンを取得
3. 各ボタンにクリックイベントリスナーを追加
4. クリック時にdata属性から値を取得してモーダルを開く

### 3. JavaScript: モーダルにデータを設定

**ファイル: `resources/views/skills/show.blade.php` (152-162行目)**

```javascript
function openEditModal(progressId, progressDate, title, content) {
    const form = document.getElementById('edit-form');
    const modal = document.getElementById('edit-modal');
    const skillId = {{ $skill->id }};
    
    // フォームのaction属性を動的に設定
    form.action = `{{ url('skills') }}/${skillId}/progresses/${progressId}`;
    
    // フォームフィールドに値を設定
    document.getElementById('edit-progress-date').value = progressDate;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-content').value = content;
    
    // モーダルを表示
    modal.classList.remove('hidden');
}
```

**処理内容:**
1. 編集フォームとモーダル要素を取得
2. フォームの`action`属性を動的に設定（ルーティング用URL）
3. 各入力フィールドに現在の値を設定
4. モーダルを表示（`hidden`クラスを削除）

### 4. ユーザー: フォームを編集して送信

**ファイル: `resources/views/skills/show.blade.php` (124-147行目)**

```php
<form id="edit-form" method="POST" class="space-y-4">
    @csrf
    @method('PUT')
    <input type="date" name="progress_date" id="edit-progress-date" required>
    <input type="text" name="title" id="edit-title" required>
    <textarea name="content" id="edit-content"></textarea>
    <button type="submit">更新</button>
</form>
```

**ポイント:**
- `@csrf`: CSRFトークンを自動生成（セキュリティ）
- `@method('PUT')`: HTMLフォームはPUTメソッドを直接送信できないため、`_method`フィールドで指定
- `required`: 必須項目のバリデーション（クライアント側）

### 5. コントローラー: 更新処理

**ファイル: `app/Http/Controllers/SkillController.php` (92-108行目)**

```php
public function updateProgress(Request $request, Skill $skill, SkillProgress $progress)
{
    // セキュリティチェック: 進捗がこのスキルに属しているか確認
    if ($progress->skill_id !== $skill->id) {
        abort(404); // 不正なアクセスの場合は404エラー
    }

    // バリデーション
    $validated = $request->validate([
        'progress_date' => ['required', 'date'],
        'title' => ['required', 'string', 'max:255'],
        'content' => ['nullable', 'string'],
    ]);

    // データベースを更新
    $progress->update($validated);

    // リダイレクト（フラッシュメッセージ付き）
    return redirect()->route('skills.show', $skill)
        ->with('status', '進捗を更新しました');
}
```

**処理の流れ:**
1. **権限チェック**: 進捗が指定されたスキルに属しているか確認
2. **バリデーション**: 入力データの検証
   - `progress_date`: 必須、日付形式
   - `title`: 必須、文字列、最大255文字
   - `content`: オプション、文字列
3. **更新処理**: Eloquentの`update()`メソッドでデータベースを更新
4. **リダイレクト**: スキル詳細ページに戻り、成功メッセージを表示

---

## 削除機能の処理フロー

### 1. フロントエンド: 削除ボタンのクリック

**ファイル: `resources/views/skills/show.blade.php` (93-103行目)**

```php
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
```

**ポイント:**
- `onsubmit="return confirm(...)"`: 削除前に確認ダイアログを表示
- ユーザーが「キャンセル」を選択すると`false`を返し、送信を中止
- `@method('DELETE')`: DELETEメソッドを指定

### 2. コントローラー: 削除処理

**ファイル: `app/Http/Controllers/SkillController.php` (110-120行目)**

```php
public function destroyProgress(Request $request, Skill $skill, SkillProgress $progress)
{
    // セキュリティチェック: 進捗がこのスキルに属しているか確認
    if ($progress->skill_id !== $skill->id) {
        abort(404); // 不正なアクセスの場合は404エラー
    }

    // データベースから削除
    $progress->delete();

    // リダイレクト（フラッシュメッセージ付き）
    return redirect()->route('skills.show', $skill)
        ->with('status', '進捗を削除しました');
}
```

**処理の流れ:**
1. **権限チェック**: 編集と同様に、進捗がスキルに属しているか確認
2. **削除処理**: Eloquentの`delete()`メソッドでデータベースから削除
3. **リダイレクト**: スキル詳細ページに戻り、成功メッセージを表示

---

## セキュリティ対策

### 1. CSRF保護
```php
@csrf
```
- LaravelのCSRFトークンでクロスサイトリクエストフォージェリを防止
- すべてのPOST/PUT/DELETEリクエストに必須

### 2. XSS対策
```php
data-progress-title="{{ htmlspecialchars($p->title, ENT_QUOTES, 'UTF-8') }}"
```
- `htmlspecialchars()`でHTML特殊文字をエスケープ
- ユーザー入力がJavaScriptに悪影響を与えることを防止

### 3. 権限チェック
```php
if ($progress->skill_id !== $skill->id) {
    abort(404);
}
```
- 進捗が指定されたスキルに属しているか確認
- 他のスキルの進捗を編集/削除できないようにする

### 4. バリデーション
```php
$validated = $request->validate([
    'progress_date' => ['required', 'date'],
    'title' => ['required', 'string', 'max:255'],
    'content' => ['nullable', 'string'],
]);
```
- サーバー側で入力データを検証
- 不正なデータの登録を防止

### 5. ルートモデルバインディング
- URLパラメータから自動的にモデルインスタンスを取得
- 存在しないIDの場合は自動的に404エラー

---

## フロントエンドの動作

### UI/UXの特徴

#### 1. ホバー時にボタン表示
```php
<div class="opacity-0 group-hover:opacity-100 transition-opacity">
```
- 通常時は透明（`opacity-0`）
- マウスホバーで表示（`group-hover:opacity-100`）
- スムーズなトランジション効果

#### 2. モーダルウィンドウ
```php
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
```
- 画面全体を覆うオーバーレイ（`fixed inset-0`）
- 半透明の背景（`bg-opacity-50`）
- 最前面表示（`z-50`）
- 初期状態は非表示（`hidden`）

#### 3. モーダルを閉じる方法
1. 閉じるボタンをクリック
2. モーダルの外側（オーバーレイ）をクリック
3. キャンセルボタンをクリック

```javascript
// モーダルの外側をクリックしたら閉じる
modal.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
```

### データの流れ（編集）

```
1. データベース
   ↓
2. Bladeテンプレートでレンダリング
   ↓
3. data属性にデータを格納
   ↓
4. JavaScriptでdata属性から取得
   ↓
5. モーダルのフォームフィールドに設定
   ↓
6. ユーザーが編集
   ↓
7. フォーム送信（PUTリクエスト）
   ↓
8. コントローラーでバリデーション
   ↓
9. データベースを更新
   ↓
10. リダイレクト（フラッシュメッセージ付き）
   ↓
11. ビューを再表示（更新されたデータ）
```

### データの流れ（削除）

```
1. 削除ボタンをクリック
   ↓
2. 確認ダイアログ表示
   ↓
3. ユーザーが確認
   ↓
4. フォーム送信（DELETEリクエスト）
   ↓
5. コントローラーで権限チェック
   ↓
6. データベースから削除
   ↓
7. リダイレクト（フラッシュメッセージ付き）
   ↓
8. ビューを再表示（削除されたデータは表示されない）
```

---

## 技術的なポイント

### 1. RESTful設計
- リソース指向のURL設計
- HTTPメソッドの適切な使用（PUT, DELETE）
- 一貫性のあるルート命名

### 2. Eloquent ORM
- ルートモデルバインディングで自動的にモデルインスタンスを取得
- `update()`や`delete()`メソッドでシンプルにデータ操作

### 3. フラッシュメッセージ
```php
->with('status', '進捗を更新しました')
```
- セッションに一時的にメッセージを保存
- リダイレクト後の1回限りの表示

### 4. Bladeテンプレート
- `@csrf`, `@method()`などのディレクティブ
- `{{ }}`でエスケープ付き出力
- `{!! !!}`でエスケープなし出力（使用時は注意）

### 5. JavaScript
- イベントデリゲーション（複数要素に一度にイベントリスナーを追加）
- DOM操作（要素の取得、値の設定、クラスの追加/削除）
- 非同期処理（`DOMContentLoaded`）

---

## まとめ

この実装では以下の技術が使われています：

1. **バックエンド**: Laravel（ルーティング、コントローラー、モデル、バリデーション）
2. **フロントエンド**: Bladeテンプレート、JavaScript、Tailwind CSS
3. **セキュリティ**: CSRF保護、XSS対策、権限チェック、バリデーション
4. **UX**: モーダルウィンドウ、確認ダイアログ、フラッシュメッセージ

これにより、安全で使いやすい進捗管理機能が実現されています。

