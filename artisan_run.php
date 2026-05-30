<?php
/**
 * Laravel Artisan Runner - HAPUS FILE INI SETELAH SELESAI!
 * Akses via: https://yourdomain.com/artisan_run.php
 */

// Security: Simple token check - ganti dengan string random kamu
$secret = 'nusaterapi2026';
if (!isset($_GET['token']) || $_GET['token'] !== $secret) {
    die('<h2 style="color:red">❌ Akses Ditolak. Tambahkan ?token=nusaterapi2026 di URL</h2>');
}

define('LARAVEL_START', microtime(true));

$rootPath = __DIR__;

// Load Laravel
require $rootPath . '/vendor/autoload.php';
$app = require_once $rootPath . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$command = $_GET['cmd'] ?? 'list';

echo '<html><head><meta charset="UTF-8"><style>
    body { font-family: monospace; background: #1e1e1e; color: #d4d4d4; padding: 20px; }
    h2 { color: #4ec9b0; }
    .output { background: #252526; padding: 15px; border-radius: 8px; white-space: pre-wrap; border: 1px solid #3c3c3c; }
    .btn { display:inline-block; margin: 6px 4px; padding: 10px 18px; background: #0e639c; color: white; 
           text-decoration:none; border-radius:6px; font-size:13px; }
    .btn:hover { background: #1177bb; }
    .btn.danger { background: #c72e2e; }
    .btn.success { background: #16825d; }
    .warning { background: #5a4000; border: 1px solid #9f6a00; color: #ffd700; padding:12px; border-radius:8px; margin-bottom:15px; }
</style></head><body>';

echo '<h2>🛠️ Nusa Terapi — Artisan Runner</h2>';
echo '<div class="warning">⚠️ <strong>PENTING:</strong> Hapus file ini setelah migrasi selesai!</div>';

echo '<p>Perintah tersedia:</p>';
$token = htmlspecialchars($secret);
$commands = [
    'Migrate (Fresh + Seed)'  => 'migrate:fresh --seed --force',
    'Migrate Only'            => 'migrate --force',
    'Seed Only'               => 'db:seed --force',
    'Clear All Cache'         => 'optimize:clear',
    'Config Cache'            => 'config:cache',
    'Storage Link'            => 'storage:link',
    'List Routes'             => 'route:list',
];

foreach ($commands as $label => $cmd) {
    $class = str_contains($cmd, 'fresh') ? 'btn danger' : 'btn';
    $class = str_contains($cmd, 'storage') || str_contains($cmd, 'cache') ? 'btn success' : $class;
    echo "<a class='$class' href='?token={$token}&cmd=" . urlencode($cmd) . "'>{$label}</a>";
}

echo '<hr style="border-color:#3c3c3c; margin:20px 0">';
echo '<h3 style="color:#ce9178">▶ Menjalankan: <code>' . htmlspecialchars($command) . '</code></h3>';
echo '<div class="output">';

ob_start();
$exitCode = $kernel->call($command);
$output = ob_get_clean();

if (empty(trim($output))) {
    echo "✅ Selesai tanpa output tambahan. Exit code: {$exitCode}";
} else {
    echo htmlspecialchars($output);
}

echo '</div>';

if ($exitCode === 0) {
    echo '<p style="color:#4ec9b0; font-size:16px">✅ Berhasil! Exit code: 0</p>';
} else {
    echo '<p style="color:#f44747; font-size:16px">❌ Ada error. Exit code: ' . $exitCode . '</p>';
}

echo '</body></html>';
