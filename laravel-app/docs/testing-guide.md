# テスト実行ガイド

## 目次
1. [基本的なテスト実行方法](#基本的なテスト実行方法)
2. [テストの種類](#テストの種類)
3. [テスト実行オプション](#テスト実行オプション)
4. [進捗機能のテスト例](#進捗機能のテスト例)
5. [トラブルシューティング](#トラブルシューティング)

---

## 基本的なテスト実行方法

### 1. Composer経由で実行（推奨）

```bash
# laravel-appディレクトリに移動
cd laravel-app

# すべてのテストを実行
composer test

# または
php artisan test
```

### 2. PHPUnitを直接実行

```bash
# laravel-appディレクトリに移動
cd laravel-app

# vendor/bin/phpunitを使用
vendor/bin/phpunit

# または、グローバルにインストール済みの場合
phpunit
```

### 3. 特定のテストファイルを実行

```bash
# 特定のテストファイルを実行
php artisan test tests/Feature/ExampleTest.php

# または
vendor/bin/phpunit tests/Feature/ExampleTest.php
```

### 4. 特定のテストメソッドを実行

```bash
# フィルターオプションを使用
php artisan test --filter test_the_application_returns_a_successful_response

# または
vendor/bin/phpunit --filter test_the_application_returns_a_successful_response
```

---

## テストの種類

### Unit テスト（単体テスト）
- 個々のクラスやメソッドの動作をテスト
- データベースにアクセスしない
- 場所: `tests/Unit/`

```bash
# Unitテストのみ実行
php artisan test --testsuite=Unit

# または
vendor/bin/phpunit tests/Unit
```

### Feature テスト（機能テスト）
- アプリケーションの機能全体をテスト
- HTTPリクエスト、データベースアクセスを含む
- 場所: `tests/Feature/`

```bash
# Featureテストのみ実行
php artisan test --testsuite=Feature

# または
vendor/bin/phpunit tests/Feature
```

---

## テスト実行オプション

### よく使うオプション

```bash
# 詳細な出力を表示
php artisan test --verbose

# カバレッジレポートを生成
php artisan test --coverage

# 特定のテストスイートを実行
php artisan test --testsuite=Feature

# パラレル実行（複数のテストを並行実行）
php artisan test --parallel

# ストップオンフェイル（最初のエラーで停止）
php artisan test --stop-on-failure

# 色なし出力
php artisan test --no-colors
```

### PHPUnitのオプション

```bash
# テスト実行時間を表示
vendor/bin/phpunit --testdox

# カバレッジHTMLレポートを生成
vendor/bin/phpunit --coverage-html coverage/

# 特定のグループのみ実行
vendor/bin/phpunit --group=slow
```

---

## 進捗機能のテスト例

### 進捗編集機能のテスト

`tests/Feature/SkillProgressTest.php` を作成:

```php
<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\SkillProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillProgressTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 進捗を編集できる()
    {
        // テストデータの準備
        $skill = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill->id,
            'progress_date' => '2024-01-01',
            'title' => '元のタイトル',
            'content' => '元の内容',
        ]);

        // 編集リクエスト
        $response = $this->put(route('skills.progresses.update', [$skill, $progress]), [
            'progress_date' => '2024-01-02',
            'title' => '更新されたタイトル',
            'content' => '更新された内容',
        ]);

        // レスポンスの検証
        $response->assertRedirect(route('skills.show', $skill));
        $response->assertSessionHas('status', '進捗を更新しました');

        // データベースの検証
        $this->assertDatabaseHas('skill_progresses', [
            'id' => $progress->id,
            'progress_date' => '2024-01-02',
            'title' => '更新されたタイトル',
            'content' => '更新された内容',
        ]);
    }

    /** @test */
    public function 他のスキルの進捗を編集できない()
    {
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill1->id,
        ]);

        // skill2の進捗として編集を試みる
        $response = $this->put(route('skills.progresses.update', [$skill2, $progress]), [
            'progress_date' => '2024-01-02',
            'title' => '更新されたタイトル',
            'content' => '更新された内容',
        ]);

        // 404エラーが返されることを確認
        $response->assertStatus(404);
    }

    /** @test */
    public function 進捗の編集時にバリデーションエラーが発生する()
    {
        $skill = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill->id,
        ]);

        // 必須項目を空で送信
        $response = $this->put(route('skills.progresses.update', [$skill, $progress]), [
            'progress_date' => '',
            'title' => '',
            'content' => '内容',
        ]);

        // バリデーションエラーが返されることを確認
        $response->assertSessionHasErrors(['progress_date', 'title']);
    }

    /** @test */
    public function 進捗を削除できる()
    {
        $skill = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill->id,
        ]);

        // 削除リクエスト
        $response = $this->delete(route('skills.progresses.destroy', [$skill, $progress]));

        // レスポンスの検証
        $response->assertRedirect(route('skills.show', $skill));
        $response->assertSessionHas('status', '進捗を削除しました');

        // データベースから削除されたことを確認
        $this->assertDatabaseMissing('skill_progresses', [
            'id' => $progress->id,
        ]);
    }

    /** @test */
    public function 他のスキルの進捗を削除できない()
    {
        $skill1 = Skill::factory()->create();
        $skill2 = Skill::factory()->create();
        $progress = SkillProgress::factory()->create([
            'skill_id' => $skill1->id,
        ]);

        // skill2の進捗として削除を試みる
        $response = $this->delete(route('skills.progresses.destroy', [$skill2, $progress]));

        // 404エラーが返されることを確認
        $response->assertStatus(404);

        // データベースにまだ存在することを確認
        $this->assertDatabaseHas('skill_progresses', [
            'id' => $progress->id,
        ]);
    }

    /** @test */
    public function 進捗を追加できる()
    {
        $skill = Skill::factory()->create();

        // 進捗追加リクエスト
        $response = $this->post(route('skills.progresses.store', $skill), [
            'progress_date' => '2024-01-01',
            'title' => '新しい進捗',
            'content' => '進捗の内容',
        ]);

        // レスポンスの検証
        $response->assertRedirect(route('skills.show', $skill));
        $response->assertSessionHas('status', '進捗を追加しました');

        // データベースに追加されたことを確認
        $this->assertDatabaseHas('skill_progresses', [
            'skill_id' => $skill->id,
            'progress_date' => '2024-01-01',
            'title' => '新しい進捗',
            'content' => '進捗の内容',
        ]);
    }
}
```

### ファクトリーの作成

`database/factories/SkillProgressFactory.php` を作成:

```php
<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\SkillProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillProgressFactory extends Factory
{
    protected $model = SkillProgress::class;

    public function definition(): array
    {
        return [
            'skill_id' => Skill::factory(),
            'progress_date' => $this->faker->date(),
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
        ];
    }
}
```

### モデルにファクトリーを追加

`app/Models/SkillProgress.php` を更新:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillProgress extends Model
{
    use HasFactory; // この行を追加

    protected $table = 'skill_progresses';

    protected $fillable = [
        'skill_id',
        'progress_date',
        'title',
        'content',
    ];

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
```

同様に、`app/Models/Skill.php` にも `HasFactory` トレイトを追加:

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;
    // ...
}
```

---

## テスト実行のワークフロー

### 開発中のテスト実行

```bash
# 1. テストを実行
php artisan test

# 2. 特定のテストファイルのみ実行（開発中）
php artisan test tests/Feature/SkillProgressTest.php

# 3. 特定のテストメソッドのみ実行（デバッグ中）
php artisan test --filter 進捗を編集できる

# 4. 失敗したテストを再実行
php artisan test --failed
```

### CI/CDでのテスト実行

```bash
# すべてのテストを実行
composer test

# カバレッジレポートを生成
php artisan test --coverage

# パラレル実行（高速化）
php artisan test --parallel
```

---

## トラブルシューティング

### よくある問題と解決方法

#### 1. テストデータベースの設定

`phpunit.xml` でテスト用データベースが設定されています:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

メモリ内SQLiteを使用するため、追加の設定は不要です。

#### 2. ファクトリーが見つからない

```bash
# モデルにHasFactoryトレイトを追加
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkillProgress extends Model
{
    use HasFactory;
}
```

#### 3. テストが遅い

```bash
# パラレル実行を使用
php artisan test --parallel

# 特定のテストスイートのみ実行
php artisan test --testsuite=Feature
```

#### 4. データベースの状態がリセットされない

`RefreshDatabase` トレイトを使用:

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkillProgressTest extends TestCase
{
    use RefreshDatabase;
    // ...
}
```

#### 5. セッションエラー

CSRFトークンの問題がある場合:

```php
// テストではCSRFミドルウェアが無効になる場合がある
// 必要に応じて、テストでセッションを使用
$response = $this->withSession(['key' => 'value'])->get('/');
```

---

## テストのベストプラクティス

### 1. テストの命名

```php
// 良い例: テストの意図が明確
public function test_進捗を編集できる()
public function test_他のスキルの進捗を編集できない()
public function test_進捗の編集時にバリデーションエラーが発生する()

// 悪い例: 何をテストしているか分からない
public function test_update()
public function test_progress()
```

### 2. アレンジ・アクト・アサート（AAA）パターン

```php
public function test_進捗を編集できる()
{
    // Arrange（準備）: テストデータの作成
    $skill = Skill::factory()->create();
    $progress = SkillProgress::factory()->create([
        'skill_id' => $skill->id,
    ]);

    // Act（実行）: テスト対象のメソッドを呼び出し
    $response = $this->put(route('skills.progresses.update', [$skill, $progress]), [
        'progress_date' => '2024-01-02',
        'title' => '更新されたタイトル',
        'content' => '更新された内容',
    ]);

    // Assert（検証）: 結果を確認
    $response->assertRedirect(route('skills.show', $skill));
    $this->assertDatabaseHas('skill_progresses', [
        'id' => $progress->id,
        'title' => '更新されたタイトル',
    ]);
}
```

### 3. テストの独立性

- 各テストは独立して実行できるようにする
- `RefreshDatabase` トレイトを使用してデータベースをリセット
- テスト間でデータを共有しない

### 4. テストカバレッジ

```bash
# カバレッジレポートを生成
php artisan test --coverage

# HTMLレポートを生成
vendor/bin/phpunit --coverage-html coverage/
```

---

## まとめ

### 基本的なコマンド

```bash
# すべてのテストを実行
composer test

# 特定のテストファイルを実行
php artisan test tests/Feature/SkillProgressTest.php

# 特定のテストメソッドを実行
php artisan test --filter test_進捗を編集できる

# 詳細な出力
php artisan test --verbose

# パラレル実行
php artisan test --parallel
```

### テストの種類

- **Unit テスト**: 個々のクラスやメソッドをテスト
- **Feature テスト**: アプリケーションの機能全体をテスト

### 重要なポイント

1. `RefreshDatabase` トレイトでデータベースをリセット
2. ファクトリーを使用してテストデータを作成
3. アレンジ・アクト・アサートパターンに従う
4. テストは独立して実行できるようにする
5. わかりやすいテスト名を使用する

テストを書くことで、コードの品質を保ち、リファクタリングを安全に行うことができます。

