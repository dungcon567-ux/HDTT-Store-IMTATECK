/**
 * =====================================================================
 *  HDTT Store — `npm run dev`
 * =====================================================================
 *  Dự án là PHP thuần nên KHÔNG có bước build (không Vite/Webpack).
 *  Script này:
 *    1. Kiểm tra PHP + MySQL + file cấu hình trước khi chạy
 *    2. Bật web server PHP
 *    3. Tô màu log request cho dễ đọc (2xx xanh, 3xx vàng, 4xx/5xx đỏ)
 *    4. Cảnh báo ngay khi có lỗi PHP thay vì để trôi trong log
 *
 *  Không cần `npm install` — script chỉ dùng thư viện chuẩn của Node.
 * =====================================================================
 */

'use strict';

const { spawn, spawnSync } = require('child_process');
const net = require('net');
const path = require('path');
const fs = require('fs');

const ROOT = path.resolve(__dirname, '..');

const HOST = 'localhost';
const START_PORT = Number(process.env.PORT) || 8000;

// ===== Màu =====
const useColor = process.stdout.isTTY;
const paint = (code) => (s) => (useColor ? `\x1b[${code}m${s}\x1b[0m` : s);

const dim = paint('90');
const red = paint('31');
const green = paint('32');
const yellow = paint('33');
const cyan = paint('36');
const bold = paint('1');

const ok = (m) => console.log('  ' + green('✔') + ' ' + m);
const warn = (m) => console.log('  ' + yellow('!') + ' ' + m);
const bad = (m) => console.log('  ' + red('✘') + ' ' + m);

// ===== Kiểm tra trước khi chạy =====

function findPhp() {
  const probe = spawnSync('php', ['-r', 'echo PHP_VERSION;'], { encoding: 'utf8' });
  if (probe.status === 0 && probe.stdout) {
    return probe.stdout.trim();
  }
  return null;
}

function ensureEnvFile() {
  const env = path.join(ROOT, 'Backend', 'commons', 'env.php');
  const sample = path.join(ROOT, 'Backend', 'commons', 'env.example.php');

  if (fs.existsSync(env)) return true;

  if (!fs.existsSync(sample)) {
    bad('Không tìm thấy Backend/commons/env.example.php');
    return false;
  }

  fs.copyFileSync(sample, env);
  warn('Chưa có env.php — đã tạo tự động từ env.example.php');
  return true;
}

function checkDatabase() {
  // Gọi PHP để kiểm tra, vì thông tin DB nằm trong env.php.
  const code = `
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    require ${JSON.stringify(path.join(ROOT, 'Backend', 'commons', 'env.php'))};
    try {
        $pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4',
                       DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $n = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
        echo 'OK|'.DB_NAME.'|'.$n;
    } catch (Throwable $e) {
        echo 'ERR|'.$e->getMessage();
    }`;

  const r = spawnSync('php', ['-r', code], { encoding: 'utf8' });
  const out = (r.stdout || '').trim();

  if (out.startsWith('OK|')) {
    const [, name, count] = out.split('|');
    return { ok: true, name, count: Number(count) };
  }

  return { ok: false, message: out.replace(/^ERR\|/, '') || 'không rõ nguyên nhân' };
}

function portFree(port) {
  return new Promise((resolve) => {
    const srv = net.createServer();
    srv.once('error', () => resolve(false));
    srv.once('listening', () => srv.close(() => resolve(true)));
    srv.listen(port, HOST);
  });
}

async function pickPort() {
  for (let p = START_PORT; p < START_PORT + 20; p++) {
    if (await portFree(p)) {
      if (p !== START_PORT) warn(`Cổng ${START_PORT} đang bận — dùng cổng ${p}`);
      return p;
    }
  }
  throw new Error(`Không tìm được cổng trống trong khoảng ${START_PORT}-${START_PORT + 20}`);
}

// ===== Định dạng log của php -S =====

// Ví dụ: [Tue Aug 18 21:28:40 2026] [::1]:55051 [200]: GET /index.php?act=cart
const REQ = /^\[.+?\]\s+\S+\s+\[(\d{3})\]:\s+(\w+)\s+(.*)$/;
const PHP_ERR = /(Fatal error|Parse error|Uncaught|Warning|Deprecated|Notice):/i;

function formatLine(line) {
  const text = line.trim();
  if (!text) return null;

  // Bỏ tiếng ồn: Accepted / Closing
  if (/\b(Accepted|Closing)\s*$/.test(text)) return null;
  if (/Development Server \(http/.test(text)) return null;

  const m = text.match(REQ);
  if (m) {
    const [, status, method, url] = m;
    const s = Number(status);
    const color = s >= 500 ? red : s >= 400 ? red : s >= 300 ? yellow : green;
    return '  ' + color(status) + '  ' + dim(method.padEnd(4)) + ' ' + url;
  }

  if (PHP_ERR.test(text)) {
    return '  ' + red('LỖI PHP') + '  ' + text;
  }

  return dim('  ' + text);
}

// ===== Chạy =====

async function main() {
  console.log('');
  console.log('  ' + bold('HDTT Store') + dim('  —  development server'));
  console.log(dim('  ─────────────────────────────────────────────────'));

  const phpVersion = findPhp();
  if (!phpVersion) {
    bad('Không tìm thấy PHP trong PATH.');
    console.log(dim('    Cài PHP hoặc bật Laragon/XAMPP rồi thử lại.'));
    process.exit(1);
  }
  ok('PHP ' + phpVersion);

  if (!ensureEnvFile()) process.exit(1);

  const db = checkDatabase();
  if (db.ok) {
    ok(`Database \`${db.name}\` ` + dim(`(${db.count} sản phẩm)`));
  } else {
    bad('Không kết nối được database: ' + db.message);
    console.log(dim('    Bật MySQL trong Laragon, rồi chạy: ') + cyan('php artisan migrate'));
    console.log('');
    process.exit(1);
  }

  const port = await pickPort();
  const url = `http://${HOST}:${port}`;

  console.log(dim('  ─────────────────────────────────────────────────'));
  console.log('  Trang chủ:  ' + cyan(url));
  console.log('  Đăng nhập:  ' + cyan(`${url}/index.php?act=login`));
  console.log(dim('  ─────────────────────────────────────────────────'));
  console.log(dim('  Đang theo dõi request. Ctrl+C để dừng.'));
  console.log('');

  const php = spawn('php', ['-S', `${HOST}:${port}`, '-t', ROOT], {
    cwd: ROOT,
    stdio: ['ignore', 'pipe', 'pipe'],
  });

  let buffer = '';
  const onData = (chunk) => {
    buffer += chunk.toString();
    const lines = buffer.split(/\r?\n/);
    buffer = lines.pop();
    for (const line of lines) {
      const formatted = formatLine(line);
      if (formatted !== null) console.log(formatted);
    }
  };

  php.stdout.on('data', onData);
  php.stderr.on('data', onData);

  php.on('exit', (code) => {
    console.log('');
    console.log(dim('  Server đã dừng.'));
    process.exit(code === null ? 0 : code);
  });

  const stop = () => {
    php.kill();
  };
  process.on('SIGINT', stop);
  process.on('SIGTERM', stop);
}

main().catch((e) => {
  bad(e.message);
  process.exit(1);
});
