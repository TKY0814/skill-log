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

echo "\n=== マイグレーション状態 ===\n";
$migrations = DB::table('migrations')->get();
if ($migrations->count() > 0) {
    foreach ($migrations as $migration) {
        echo "- {$migration->migration}\n";
    }
} else {
    echo "マイグレーション履歴がありません\n";
}

echo "\n=== データベースファイル情報 ===\n";
$dbPath = 'database/database.sqlite';
if (file_exists($dbPath)) {
    $size = filesize($dbPath);
    echo "ファイルサイズ: " . number_format($size) . " bytes\n";
    echo "最終更新: " . date('Y-m-d H:i:s', filemtime($dbPath)) . "\n";
} else {
    echo "データベースファイルが見つかりません\n";
}