<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ポートフォリオ</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800 leading-relaxed">

  <!-- ヘッダー -->
  <header class="bg-white shadow">
    <div class="max-w-5xl mx-auto px-4 py-6 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-indigo-600">Portfolio</h1>
      <nav class="space-x-4">
        <a href="#about" class="hover:text-indigo-500">About</a>
        <a href="#skills" class="hover:text-indigo-500">Skills</a>
        <a href="#works" class="hover:text-indigo-500">Works</a>
        <a href="#career" class="hover:text-indigo-500">Career</a>
        <a href="#contact" class="hover:text-indigo-500">Contact</a>
      </nav>
    </div>
  </header>

  <!-- 自己紹介 -->
  <section id="about" class="max-w-5xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-6 border-b-2 border-indigo-400 inline-block">About</h2>
    <p class="text-lg">
      Webエンジニア歴1年。Laravel＋Filamentが得意。Dockerを活用した開発環境構築やAWSでのインフラも経験あり。
      <br>「周りの人の心の支えになるエンジニア」を目指しています。
    </p>
  </section>

  <!-- スキルセット -->
  <section id="skills" class="bg-white py-12">
    <div class="max-w-5xl mx-auto px-4">
      <h2 class="text-3xl font-bold mb-6 border-b-2 border-indigo-400 inline-block">Skills</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
        <div class="p-4 bg-gray-100 rounded-xl shadow">
          <img src="/icons/laravel.svg" alt="Laravel" class="w-12 mx-auto mb-2">
          <p>Laravel</p>
        </div>
        <div class="p-4 bg-gray-100 rounded-xl shadow">
          <img src="/icons/filament.svg" alt="Filament" class="w-12 mx-auto mb-2">
          <p>Filament</p>
        </div>
        <div class="p-4 bg-gray-100 rounded-xl shadow">
          <img src="/icons/docker.svg" alt="Docker" class="w-12 mx-auto mb-2">
          <p>Docker</p>
        </div>
        <div class="p-4 bg-gray-100 rounded-xl shadow">
          <img src="/icons/aws.svg" alt="AWS" class="w-12 mx-auto mb-2">
          <p>AWS</p>
        </div>
        <!-- 他のスキルも追加可 -->
      </div>
    </div>
  </section>

  <!-- 制作実績 -->
  <section id="works" class="max-w-5xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-6 border-b-2 border-indigo-400 inline-block">Works</h2>
    <div class="grid md:grid-cols-2 gap-8">
      <div class="bg-white rounded-xl shadow overflow-hidden">
        <img src="/images/app1.png" alt="アプリ名" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-xl font-semibold">応募・抽選システム</h3>
          <p class="text-sm text-gray-600">Laravel＋Filamentを使ったキャンペーンサイト</p>
          <p class="mt-2 text-gray-700">担当: 要件定義〜実装・テスト</p>
          <div class="mt-3 space-x-3">
            <a href="https://github.com/yourrepo" class="text-indigo-600 hover:underline">GitHub</a>
            <a href="https://demoapp.com" class="text-indigo-600 hover:underline">デモ</a>
          </div>
        </div>
      </div>
      <!-- 追加の実績カード -->
    </div>
  </section>

  <!-- 経歴 -->
  <section id="career" class="bg-white py-12">
    <div class="max-w-5xl mx-auto px-4">
      <h2 class="text-3xl font-bold mb-6 border-b-2 border-indigo-400 inline-block">Career</h2>
      <ul class="space-y-4">
        <li>
          <span class="font-semibold">2024年〜現在</span> SES企業でWeb系案件に参画（Laravel＋Filament）
        </li>
        <!-- 追加可能 -->
      </ul>
    </div>
  </section>

  <!-- 連絡先 -->
  <section id="contact" class="max-w-5xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-6 border-b-2 border-indigo-400 inline-block">Contact</h2>
    <p class="mb-4">お仕事のご相談・ご連絡は以下からお願いします。</p>
    <div class="space-x-4">
      <a href="mailto:yourmail@example.com" class="text-indigo-600 hover:underline">Email</a>
      <a href="https://github.com/youraccount" class="text-indigo-600 hover:underline">GitHub</a>
      <a href="https://x.com/youraccount" class="text-indigo-600 hover:underline">X（旧Twitter）</a>
    </div>
  </section>

  <!-- フッター -->
  <footer class="bg-gray-100 text-center py-6 mt-8">
    <p class="text-sm text-gray-500">© 2025 Portfolio</p>
  </footer>

</body>
</html>
