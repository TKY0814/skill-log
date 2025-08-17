<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>データベースビューアー</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .table-container table {
            border-collapse: collapse;
            width: 100%;
        }
        .table-container th,
        .table-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-container th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
        }
        .table-container tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table-container tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">データベースビューアー</h1>
            <a href="/" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">ホームに戻る</a>
        </div>
        
        @foreach($tableData as $tableName => $table)
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="bg-blue-500 text-white px-6 py-3 rounded-t-lg">
                    <h2 class="text-xl font-semibold">{{ $tableName }}</h2>
                    <p class="text-sm opacity-90">{{ count($table['data']) }} 件のレコード</p>
                </div>
                
                <div class="table-container p-6">
                    @if(count($table['data']) > 0)
                        <table>
                            <thead>
                                <tr>
                                    @foreach($table['columns'] as $column)
                                        <th class="bg-gray-100 font-semibold">{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($table['data'] as $row)
                                    <tr>
                                        @foreach($table['columns'] as $column)
                                            <td class="text-sm">
                                                @if(is_string($row->$column) && strlen($row->$column) > 50)
                                                    {{ substr($row->$column, 0, 50) }}...
                                                @else
                                                    {{ $row->$column }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>このテーブルにはデータがありません</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">データベース情報</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p><strong>テーブル数:</strong> {{ count($tableData) }}</p>
                    <p><strong>総レコード数:</strong> 
                        {{ collect($tableData)->sum(function($table) { return count($table['data']); }) }}
                    </p>
                </div>
                <div>
                    <p><strong>データベースファイル:</strong> database.sqlite</p>
                    <p><strong>最終更新:</strong> {{ date('Y-m-d H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
