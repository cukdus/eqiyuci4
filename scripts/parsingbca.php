<?php
date_default_timezone_set(getenv('TZ') ?: 'Asia/Jakarta');
// Parser KlikBCA (mobile) yang menghasilkan JSON mutasi rekening untuk tanggal hari ini.
// Menggunakan kredensial dari ENV: BANK_USER_ID / BANK_PIN (fallback: KLIK_BCA_USER / KLIK_BCA_PASS)

// Kredensial
$get = static function (string $key, ?string $fallback = null): string {
    $v = getenv($key);
    if (is_string($v) && $v !== '') {
        return $v;
    }
    $root = dirname(__DIR__);
    $envFile = $root . DIRECTORY_SEPARATOR . '.env';
    if (is_file($envFile)) {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || strpos($line, '=') === false) {
                continue;
            }
            [$k, $val] = explode('=', $line, 2);
            $k = trim($k);
            $val = trim($val, " \t\"'");
            if ($k !== '') {
                putenv($k . '=' . $val);
            }
        }
        $v = getenv($key);
        if (is_string($v) && $v !== '') {
            return $v;
        }
    }
    return (string) ($fallback ?? '');
};
$username = $get('BANK_USER_ID', $get('KLIK_BCA_USER'));
$password = $get('BANK_PIN', $get('KLIK_BCA_PASS'));
if (!is_string($username) || !is_string($password) || $username === '' || $password === '') {
    fwrite(STDERR, "Kredensial tidak tersedia (BANK_USER_ID/BANK_PIN atau KLIK_BCA_USER/KLIK_BCA_PASS)\n");
    exit(1);
}

$ua = 'Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0 Mobile Safari/537.36';

// Lokasi penyimpanan cookie dan hasil di dalam aplikasi CI4
$root = dirname(__DIR__);
$writable = $root . DIRECTORY_SEPARATOR . 'writable';
$cookieDir = $writable . DIRECTORY_SEPARATOR . 'cookies';
if (!is_dir($cookieDir)) {
    @mkdir($cookieDir, 0755, true);
}
$cookie = $cookieDir . DIRECTORY_SEPARATOR . 'klikbca_m.cookie';
@touch($cookie);

// CA bundle optional via ENV
$caEnv = $get('BCA_CACERT_PATH');
$ca = (is_string($caEnv) && $caEnv !== '' && file_exists($caEnv)) ? $caEnv : null;
$verify = $ca !== null;

// Direktori hasil JSON
$hasilDir = $writable . DIRECTORY_SEPARATOR . 'klikbca' . DIRECTORY_SEPARATOR . 'hasil';
if (!is_dir($hasilDir)) {
    @mkdir($hasilDir, 0755, true);
}

function go($url, $opt = [], $referer = null, $fetchSite = 'none')
{
    global $ua, $cookie, $verify, $ca;
    $ch = curl_init($url);
    $def = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => $ua,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_ENCODING => '',
        CURLOPT_AUTOREFERER => true,
        CURLOPT_TCP_KEEPALIVE => 1,
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    ];
    if ($verify) {
        $def[CURLOPT_SSL_VERIFYPEER] = true;
        $def[CURLOPT_SSL_VERIFYHOST] = 2;
        $def[CURLOPT_CAINFO] = $ca;
    } else {
        $def[CURLOPT_SSL_VERIFYPEER] = false;
        $def[CURLOPT_SSL_VERIFYHOST] = 0;
    }
    $headers = [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
        'Cache-Control: no-cache',
        'Pragma: no-cache',
        'Upgrade-Insecure-Requests: 1',
        'Sec-CH-UA: "Chromium";v="129", "Google Chrome";v="129", "Not.A/Brand";v="24"',
        'Sec-CH-UA-Mobile: ?1',
        'Sec-CH-UA-Platform: "Android"',
        'Sec-Fetch-Dest: document',
        'Sec-Fetch-Mode: navigate',
        'Sec-Fetch-Site: ' . $fetchSite,
    ];
    if ($referer) {
        $def[CURLOPT_REFERER] = $referer;
    }
    if (isset($opt[CURLOPT_POST]) && $opt[CURLOPT_POST]) {
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    }
    $def[CURLOPT_HTTPHEADER] = $headers;
    foreach ($opt as $k => $v) {
        $def[$k] = $v;
    }
    curl_setopt_array($ch, $def);
    $out = curl_exec($ch);
    $info = curl_getinfo($ch);
    $err = curl_error($ch);
    $ern = curl_errno($ch);
    curl_close($ch);
    return ['out' => $out, 'info' => $info, 'err' => $err, 'ern' => $ern];
}

// 1) Ambil halaman login untuk public key & dtSign
$o = go('https://m.klikbca.com/login.jsp');
if ($o['ern'] || $o['err']) {
    fwrite(STDERR, "HTTP error saat memuat login: {$o['err']}\n");
    exit(1);
}
if (!preg_match('/const\s+publicKeyString\s*=\s*"([A-Za-z0-9+\/=]+)"/Us', (string) $o['out'], $pkm)) {
    fwrite(STDERR, "Public key tidak ditemukan\n");
    exit(1);
}
if (!preg_match('/var\s+dtSign\s*=\s*new\s+Date\((\d+),\s*parseInt\("(\d+)"\)\-1,\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+)\)/Us', (string) $o['out'], $dm)) {
    fwrite(STDERR, "dtSign tidak ditemukan\n");
    exit(1);
}

$pubKeyPem = "-----BEGIN PUBLIC KEY-----\n" . $pkm[1] . "\n-----END PUBLIC KEY-----\n";
$base = mktime((int) $dm[4], (int) $dm[5], (int) $dm[6], (int) $dm[2], (int) $dm[3], (int) $dm[1]);
$now = time();
$diff = 0;  // gunakan nol untuk kesederhanaan
$dt = $base + $diff;
$formatedDate = date('YmdHis', $dt);
$plain = $password . $formatedDate;
if (!openssl_public_encrypt($plain, $encRaw, $pubKeyPem, OPENSSL_PKCS1_PADDING)) {
    fwrite(STDERR, "Enkripsi gagal\n");
    exit(1);
}
$enc = base64_encode($encRaw);

$posts = [
    'value(actions)' => 'login',
    'value(user_id)' => $username,
    'value(pswd)' => $enc,
    'value(mobile)' => 'true',
    'mobile' => 'true',
    'value(Submit)' => 'LOGIN',
];

// 2) Login
$o = go('https://m.klikbca.com/authentication.do', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($posts),
], 'https://m.klikbca.com/login.jsp', 'same-origin');
if (!preg_match('/authentication\.do\?value\(actions\)=menu/i', (string) $o['out']) && !preg_match('/MENU UTAMA/i', (string) $o['out'])) {
    fwrite(STDERR, "Login gagal\n");
    exit(1);
}

// 3) Navigasi ke balance & account statement
go('https://m.klikbca.com/balanceinquiry.do', [CURLOPT_POST => true, CURLOPT_POSTFIELDS => ''], 'https://m.klikbca.com/authentication.do?value(actions)=menu', 'same-origin');
$o = go('https://m.klikbca.com/accountstmt.do?value(actions)=acct_stmt', [CURLOPT_POST => true, CURLOPT_POSTFIELDS => ''], 'https://m.klikbca.com/authentication.do?value(actions)=menu', 'same-origin');

// 4) Submit form untuk tanggal hari ini
$st = time();
$sd = date('d', $st);
$sm = date('m', $st);
$sy = date('Y', $st);
$posts = [
    'r1' => '1',
    'value(D1)' => '0',
    'value(startDt)' => $sd,
    'value(startMt)' => $sm,
    'value(startYr)' => $sy,
    'value(endDt)' => $sd,
    'value(endMt)' => $sm,
    'value(endYr)' => $sy,
];
if (preg_match_all('/<input.+>/Us', (string) $o['out'], $m)) {
    foreach ($m[0] as $v) {
        if (!preg_match('/hidden/i', $v))
            continue;
        if (!preg_match('/name="(.*)"/Us', $v, $mm))
            continue;
        $key = html_entity_decode($mm[1], ENT_QUOTES, 'UTF-8');
        if (preg_match('/value="(.*)"/Us', $v, $mm))
            $val = html_entity_decode($mm[1], ENT_QUOTES, 'UTF-8');
        else
            $val = '';
        $posts[$key] = $val;
    }
}

$o = go('https://m.klikbca.com/accountstmt.do?value(actions)=acctstmtview', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($posts),
], 'https://m.klikbca.com/accountstmt.do?value(actions)=acct_stmt', 'same-origin');

$getRowVal = function ($html, $label) {
    $re = '/<td[^>]*>\s*' . preg_quote($label, '/') . '\s*<\/td>\s*<td[^>]*>:\s*<\/td>\s*<td[^>]*>(.*?)<\/td>/is';
    if (preg_match($re, (string) $html, $m))
        return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8'));
    return null;
};
$getVal = function ($html, array $labels) use ($getRowVal) {
    foreach ($labels as $lab) {
        $v = $getRowVal($html, $lab);
        if ($v !== null && $v !== '') return $v;
    }
    return null;
};

$header = [
    'account_number' => $getVal($o['out'], ['NO. REK.', 'NO. REKENING', 'No. Rekening', 'Account Number']),
    'name' => $getVal($o['out'], ['NAMA', 'Nama', 'Name']),
    'period' => $getVal($o['out'], ['PERIODE', 'Periode', 'Period']),
    'currency' => $getVal($o['out'], ['MATA UANG', 'Mata Uang', 'Currency']),
];

$tx = [];
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML((string) $o['out']);
$xpath = new DOMXPath($dom);
$rows = $xpath->query('//tr[td]');
foreach ($rows as $tr) {
    $tds = $tr->getElementsByTagName('td');
    if ($tds->length < 2)
        continue;
    $date = trim(html_entity_decode($tds->item(0)->textContent, ENT_QUOTES, 'UTF-8'));
    $dateBlack = ['', 'NO. REK.', 'NAMA', 'PERIODE', 'MATA UANG', 'TGL.', 'SALDO AWAL', 'MUTASI KREDIT', 'MUTASI DEBET', 'SALDO AKHIR', 'INFORMASI REKENING - MUTASI REKENING', chr(194) . chr(160)];
    if (in_array($date, $dateBlack, true))
        continue;
    $descTd = $tds->item(1);
    $segments = [];
    $buffer = '';
    foreach ($descTd->childNodes as $node) {
        if ($node->nodeType === XML_TEXT_NODE) {
            $buffer .= $node->textContent;
        } elseif ($node->nodeType === XML_ELEMENT_NODE && strtolower($node->nodeName) === 'br') {
            $segments[] = trim($buffer);
            $buffer = '';
        }
    }
    if (trim($buffer) !== '')
        $segments[] = trim($buffer);
    if (empty($segments)) {
        $text = trim($descTd->textContent);
        if ($text !== '')
            $segments = preg_split('/\s*\n+\s*/', $text);
    }
    $segments = array_values(array_filter(array_map(function ($x) {
        $t = trim(html_entity_decode($x, ENT_QUOTES, 'UTF-8'));
        return $t;
    }, $segments), function ($x) {
        return $x !== '' && stripos($x, 'MENU UTAMA') === false && stripos($x, 'LOGOUT') === false;
    }));
    $type = null;
    if ($tds->length >= 3) {
        $typeCandidate = strtoupper(trim($tds->item(2)->textContent));
        if (preg_match('/\b(CR|DR|DB)\b/', $typeCandidate, $tm)) {
            $type = ($tm[1] === 'DB') ? 'DR' : $tm[1];
        }
    }
    $amtCellText = '';
    if ($tds->length >= 4) {
        $amtCellText = trim($tds->item(3)->textContent);
    } else {
        $amtCellText = trim($tds->item(2)->textContent);
    }
    $money = function ($s) {
        $c = preg_replace('/[^0-9.,-]/', '', (string) $s);
        if (strpos($c, ',') !== false && strpos($c, '.') !== false) {
            $c = str_replace('.', '', $c);
            $c = str_replace(',', '.', $c);
        } elseif (strpos($c, ',') !== false) {
            $c = str_replace(',', '.', $c);
        } elseif (substr_count($c, '.') > 1) {
            $c = str_replace('.', '', $c);
        }
        $n = (float) $c;
        $fmt = number_format($n, 2, '.', '');
        return [$n, $fmt];
    };
    list($amountNum, $amountFmt) = $money($amtCellText);
    if (!($type === 'CR' || $type === 'DR'))
        continue;
    if ($amountNum <= 0)
        continue;
    $info = trim(implode(' ', $segments));
    $tx[] = [
        'date' => $date,
        'type' => $type,
        'amount' => $amountNum,
        'amount_formatted' => $amountFmt,
        'info' => $info,
    ];
}

$sa = $getRowVal($o['out'], 'SALDO AWAL');
$sc = $getRowVal($o['out'], 'MUTASI KREDIT');
$sd = $getRowVal($o['out'], 'MUTASI DEBET');
$sl = $getRowVal($o['out'], 'SALDO AKHIR');
$norm = function ($s) {
    $c = preg_replace('/[^0-9.,-]/', '', (string) $s);
    if (strpos($c, ',') !== false && strpos($c, '.') !== false) {
        $c = str_replace('.', '', $c);
        $c = str_replace(',', '.', $c);
    } elseif (strpos($c, ',') !== false) {
        $c = str_replace(',', '.', $c);
    } elseif (substr_count($c, '.') > 1) {
        $c = str_replace('.', '', $c);
    }
    $n = (float) $c;
    return [$n, number_format($n, 2, '.', '')];
};
list($saNum, $saFmt) = $norm($sa);
list($scNum, $scFmt) = $norm($sc);
list($sdNum, $sdFmt) = $norm($sd);
list($slNum, $slFmt) = $norm($sl);
$summary = [
    'opening_balance' => $saNum,
    'opening_balance_formatted' => $saFmt,
    'credit_total' => $scNum,
    'credit_total_formatted' => $scFmt,
    'debit_total' => $sdNum,
    'debit_total_formatted' => $sdFmt,
    'closing_balance' => $slNum,
    'closing_balance_formatted' => $slFmt,
];

$mutasiJson = [
    'header' => $header,
    'transactions' => $tx,
    'summary' => $summary,
];
file_put_contents($hasilDir . DIRECTORY_SEPARATOR . 'mutasirekening.json', json_encode($mutasiJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION));

// Logout
go('https://m.klikbca.com/authentication.do?value(actions)=logout', [CURLOPT_POST => true, CURLOPT_POSTFIELDS => ''], 'https://m.klikbca.com/accountstmt.do?value(actions)=acctstmtview', 'same-origin');
echo "Selesai. JSON tersimpan di writable/klikbca/hasil/mutasirekening.json\n";
exit(0);
