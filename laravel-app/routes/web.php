<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB; // Added DB facade

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-viewer', function () {
    // テーブル一覧を取得
    $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table"');
    
    // 各テーブルのデータを取得
    $tableData = [];
    foreach ($tables as $table) {
        $tableName = $table->name;
        $data = DB::table($tableName)->get();
        $columns = [];
        
        if ($data->count() > 0) {
            $columns = array_keys((array) $data->first());
        }
        
        $tableData[$tableName] = [
            'columns' => $columns,
            'data' => $data
        ];
    }
    
    return view('db-viewer', compact('tableData'));
});
