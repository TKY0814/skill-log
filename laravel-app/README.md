<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

データベースの内容を確認する方法をいくつか紹介します。現在のプロジェクトはSQLiteを使用しているので、それに合わせた方法を説明します。

## 1. Laravel Tinkerを使用する方法（推奨）

```bash
cd laravel-app
php artisan tinker
```

Tinker内で以下のコマンドを実行：

```php
// 全テーブルの一覧を確認
DB::select('SELECT name FROM sqlite_master WHERE type="table"');

// usersテーブルの内容を確認
App\Models\User::all();

// 特定のテーブルの全レコードを確認
DB::table('users')->get();

// テーブル構造を確認
DB::select('PRAGMA table_info(users)');
```

## 2. SQLiteコマンドラインを使用する方法

```bash
cd laravel-app
sqlite3 database/database.sqlite
```

SQLite内で以下のコマンドを実行：

```sql
-- テーブル一覧を表示
.tables

-- usersテーブルの構造を確認
.schema users

-- usersテーブルの内容を確認
SELECT * FROM users;

-- テーブル一覧を詳細表示
SELECT name FROM sqlite_master WHERE type='table';
```

## 3. Laravel Artisanコマンドを使用する方法

```bash
cd laravel-app

# データベースの状態を確認
php artisan migrate:status

# テーブル一覧を表示
php artisan db:show

# 特定のテーブルの内容を表示（カスタムコマンドが必要）
```

## 4. データベースファイルを直接確認する方法

```bash
cd laravel-app

# SQLiteファイルの存在確認
ls -la database/database.sqlite

# ファイルサイズ確認
du -h database/database.sqlite
```

## 5. 簡単な確認用スクリプトを作成

`laravel-app/check_db.php` ファイルを作成：

```php
<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== データベース確認 ===\n";

// テーブル一覧
$tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
echo "テーブル一覧:\n";
foreach ($tables as $table) {
    echo "- " . $table->name . "\n";
}

echo "\n=== usersテーブル ===\n";
$users = DB::table('users')->get();
if ($users->count() > 0) {
    foreach ($users as $user) {
        echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    }
} else {
    echo "usersテーブルにデータがありません\n";
}
```

実行：
```bash
cd laravel-app
php check_db.php
```

## 最も簡単な方法

まずは **Laravel Tinker** を使用することをお勧めします：

```bash
cd laravel-app
php artisan tinker
```

Tinker内で：
```php
// テーブル一覧
DB::select('SELECT name FROM sqlite_master WHERE type="table"');

// usersテーブルの内容
App\Models\User::all();
```

これで現在のデータベースの状態を確認できます。どの方法を試してみますか？
