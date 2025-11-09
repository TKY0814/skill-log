<!doctype html>
<html lang="ja">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<title>Portfolio — Skill Log</title>
	<link rel="stylesheet" href="{{ asset('css/portfolio.css') }}">
</head>

<body>
	<div class="container">
		<header class="header">
			<div class="brand">

				<div>
					<div style="font-weight:600">鈴木 拓弥</div>
					<div style="font-size:12px;color:var(--muted)">Portfolio</div>
				</div>
			</div>
			<nav class="nav">
				<a href="#about">About</a>
				<a href="#skills">Skills</a>
				<a href="#works">Works</a>
				<a href="#contact">Contact</a>
			</nav>
		</header>

		<section class="hero">
			
			<div class="intro">
				<h1>Webエンジニア — Laravel / Filament を得意としています</h1>
				<p>要件定義から実装、テスト、デプロイまで一貫して対応。Docker を使った開発環境構築や簡易的な AWS 運用の経験があります。</p>
				<div class="cta">
					<a href="#works" class="btn btn-primary">制作実績を見る</a>
					<a href="#contact" class="btn btn-primary" style="margin-left:8px">連絡する</a>
				</div>
			</div>
			<div class="profile card">
				<img src="{{ asset('images/IMG_0366.JPG') }}" alt="Profile" class="profile-image">
			</div>
		</section>

		<section id="about" class="card" style="margin-top:20px">
			<h3>About</h3>
			<p>東京都福生市在住。Webエンジニアとして2年間、LaravelとPHPを用いたシステム開発に従事してきました。主にAPI設計・実装、PostgreSQLによるデータベース設計・運用、認証処理、チーム開発（Git／GitHub運用）などに強みを持っています。
				<br>
				設計工程では基本設計から詳細設計、実装まで担当し、業務系Webシステムの構築経験が豊富です。RESTful API設計やEloquent ORMによる効率的な実装、Docker＋WSL環境での開発にも対応しています。
			</p>
		</section>

		<section id="skills" style="margin-top:20px">
			<h3>Skills</h3>
			<div class="grid">
				<div class="card skill-item">
					<div>
						<div style="font-weight:600">Laravel</div>
						<div style="font-size:13px;color:var(--muted)">API / バックエンド</div>
					</div>
				</div>
				<div class="card skill-item">
					<div>
						<div style="font-weight:600">Filament</div>
						<div style="font-size:13px;color:var(--muted)">管理画面構築</div>
					</div>
				</div>
				<div class="card skill-item">
					<div>
						<div style="font-weight:600">Docker</div>
						<div style="font-size:13px;color:var(--muted)">環境構築</div>
					</div>
				</div>
				<div class="card skill-item">
					<div>
						<div style="font-weight:600">PostgreSQL</div>
						<div style="font-size:13px;color:var(--muted)">データベース</div>
					</div>
				</div>
			</div>
		</section>

		<section id="works" style="margin-top:20px">
			<h3>Works</h3>
			<div class="card work-card">
				<div>
					<div style="font-weight:700">Skill Log</div>
					<div style="font-size:13px;color:var(--muted)">スキル管理アプリ。要件定義〜設計〜実装を担当。Filament 管理画面を実装。</div>
					<div style="margin-top:12px;display:flex;gap:8px;flex-wrap:wrap">
						<a href="https://github.com/TKY0814/skill-log" class="btn btn-primary">GitHubへ</a>
						<a href="{{ route('skills.index') }}" class="btn btn-primary">アプリへ</a>
					</div>
				</div>
			</div>
		</section>

		<section id="contact" style="margin-top:20px">
			<h3>Contact</h3>
			<div class="card">
				<p>メール: <a href="suzutaku.dev@gmail.com">suzutaku.dev@gmail.com</a></p>
				<p style="margin-top:8px">SNS: <a href="https://github.com/TKY0814?tab=repositories">GitHub</a>
			</div>
		</section>

		<footer class="footer">© 2025 Skill Log</footer>
	</div>
</body>

</html>