## スキル詳細・日々の進捗 機能設計（MVP）

### 目的
スキル詳細ページでスキル概要を表示し、日々の進捗（学習日、タイトル、内容）を登録・一覧できる。

### 画面
URL: `/skills/{skill}`
- 表示: `title`, `description`
- 進捗一覧: `progress_date`, `title`, `content`
- 新規進捗行の追加ボタン（行を下に追加して複数同時保存も可能なUI）

MVPでは1行ずつ保存（POST）→ 即時一覧反映。将来拡張で複数行同時保存に対応。

### データモデル
`skill_progresses`
- id (bigint, PK)
- skill_id (bigint, FK -> skills.id)
- progress_date (date)
- title (string)
- content (text)
- timestamps

### バリデーション
- progress_date: required|date
- title: required|string|max:255
- content: nullable|string

### ルーティング
- GET `/skills/{skill}` -> SkillController@show
- POST `/skills/{skill}/progresses` -> SkillController@storeProgress

### 成功時
- フラッシュメッセージ表示し詳細にリダイレクト



