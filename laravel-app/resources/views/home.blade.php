
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
				<div class="logo">SL</div>
				<div>
					<div style="font-weight:600">Skill Log</div>
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
					<a href="#contact" class="btn" style="margin-left:8px">連絡する</a>
				</div>
			</div>
			<div class="profile card">
				<div style="width:220px;height:140px;background:linear-gradient(135deg,var(--accent),#ec4899);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700">Profile</div>
			</div>
		</section>

		<section id="about" class="card" style="margin-top:20px">
			<h3>About</h3>
			<p>Webエンジニア歴1年。主に Laravel と Filament を使った開発が得意です。チーム開発やインフラ周りの理解を深め、周りの人の支えになれるエンジニアを目指しています。</p>
		</section>

		<section id="skills" style="margin-top:20px">
			<h3>Skills</h3>
			<div class="grid">
				<div class="card skill-item">
					<img src="/icons/laravel.svg" alt="Laravel">
					<div>
						<div style="font-weight:600">Laravel</div>
						<div style="font-size:13px;color:var(--muted)">API / バックエンド</div>
					</div>
				</div>
				<div class="card skill-item">
					<img src="/icons/filament.svg" alt="Filament">
					<div>
						<div style="font-weight:600">Filament</div>
						<div style="font-size:13px;color:var(--muted)">管理画面構築</div>
					</div>
				</div>
				<div class="card skill-item">
					<img src="/icons/docker.svg" alt="Docker">
					<div>
						<div style="font-weight:600">Docker</div>
						<div style="font-size:13px;color:var(--muted)">環境構築</div>
					</div>
				</div>
				<div class="card skill-item">
					<img src="/icons/aws.svg" alt="AWS">
					<div>
						<div style="font-weight:600">AWS</div>
						<div style="font-size:13px;color:var(--muted)">簡易的な運用</div>
					</div>
				</div>
			</div>
		</section>

		<section id="works" style="margin-top:20px">
			<h3>Works</h3>
			<div class="card work-card">
				<img src="/images/app1.png" alt="Skill Log">
				<div>
					<div style="font-weight:700">Skill Log</div>
					<div style="font-size:13px;color:var(--muted)">スキル管理アプリ。要件定義〜設計〜実装を担当。Filament 管理画面を実装。</div>
					<div style="margin-top:8px">
						<a href="https://github.com/yourrepo">GitHub</a>
						<a href="#" style="margin-left:12px">Demo</a>
					</div>
				</div>
			</div>
		</section>

		<section id="contact" style="margin-top:20px">
			<h3>Contact</h3>
			<div class="card">
				<p>メール: <a href="mailto:yourmail@example.com">yourmail@example.com</a></p>
				<p style="margin-top:8px">SNS: <a href="https://github.com/youraccount">GitHub</a> / <a href="https://x.com/youraccount">X</a></p>
			</div>
		</section>

		<footer class="footer">© 2025 Skill Log</footer>
	</div>
</body>
</html>
