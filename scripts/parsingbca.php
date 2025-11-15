<?php
if (file_exists(__DIR__ . '/credentials.tmp')) {
    require __DIR__ . '/credentials.tmp';
} else {
    $username = getenv('KLIK_BCA_USER');
    $password = getenv('KLIK_BCA_PASS');
    if (!is_string($username) || !is_string($password) || $username === '' || $password === '')
        die("Kredensial tidak tersedia\n");
}

$ua = 'Mozilla/5.0 (Linux; Android 10; Mobile) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0 Mobile Safari/537.36';
$cookie = __DIR__ . '/cookie.tmp';
@touch($cookie);
$ca = __DIR__ . '/cacert.pem';
$verify = file_exists($ca);
$hasilDir = __DIR__ . '/hasil';
if (!is_dir($hasilDir))
    @mkdir($hasilDir, 0777, true);

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
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
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
        'Sec-Fetch-Site: ' . $fetchSite
    ];
    if ($referer)
        $def[CURLOPT_REFERER] = $referer;
    if (isset($opt[CURLOPT_POST]) && $opt[CURLOPT_POST])
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    $def[CURLOPT_HTTPHEADER] = $headers;
    foreach ($opt as $k => $v)
        $def[$k] = $v;
    curl_setopt_array($ch, $def);
    $out = curl_exec($ch);
    $info = curl_getinfo($ch);
    $err = curl_error($ch);
    $ern = curl_errno($ch);
    curl_close($ch);
    return ['out' => $out, 'info' => $info, 'err' => $err, 'ern' => $ern];
}

$o = go('https://m.klikbca.com/login.jsp');
if ($o['ern'] || $o['err'])
    die("HTTP error\n");

if (!preg_match('/const\s+publicKeyString\s*=\s*"([A-Za-z0-9+\/=]+)"/Us', $o['out'], $pkm))
    die("Public key tidak ditemukan\n");
if (!preg_match('/var\s+dtSign\s*=\s*new\s+Date\((\d+),\s*parseInt\("(\d+)"\)\-1,\s*(\d+),\s*(\d+),\s*(\d+),\s*(\d+)\)/Us', $o['out'], $dm))
    die("dtSign tidak ditemukan\n");

$pubKeyPem = "-----BEGIN PUBLIC KEY-----\n" . $pkm[1] . "\n-----END PUBLIC KEY-----\n";

$base = mktime((int) $dm[4], (int) $dm[5], (int) $dm[6], (int) $dm[2], (int) $dm[3], (int) $dm[1]);
$now = time();
$diff = abs($now - $now);  // gunakan nol untuk kesederhanaan
$dt = $base + $diff;
$formatedDate = date('YmdHis', $dt);
$plain = $password . $formatedDate;
$enc = '';
if (!openssl_public_encrypt($plain, $encRaw, $pubKeyPem, OPENSSL_PKCS1_PADDING))
    die("Enkripsi gagal\n");
$enc = base64_encode($encRaw);

$posts = [
    'value(actions)' => 'login',
    'value(user_id)' => $username,
    'value(pswd)' => $enc,
    'value(mobile)' => 'true',
    'mobile' => 'true',
    'value(Submit)' => 'LOGIN'
];

$o = go('https://m.klikbca.com/authentication.do', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($posts),
], 'https://m.klikbca.com/login.jsp', 'same-origin');
if (!preg_match('/authentication\.do\?value\(actions\)=menu/i', (string) $o['out']) && !preg_match('/MENU UTAMA/i', (string) $o['out']))
    die("Login gagal\n");

$o = go('https://m.klikbca.com/balanceinquiry.do', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => ''
], 'https://m.klikbca.com/authentication.do?value(actions)=menu', 'same-origin');

$o = go('https://m.klikbca.com/accountstmt.do?value(actions)=acct_stmt', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => ''
], 'https://m.klikbca.com/authentication.do?value(actions)=menu', 'same-origin');

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
    'value(endYr)' => $sy
];
if (preg_match_all('/<input.+>/Us', $o['out'], $m)) {
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
    CURLOPT_POSTFIELDS => http_build_query($posts)
], 'https://m.klikbca.com/accountstmt.do?value(actions)=acct_stmt', 'same-origin');

$getRowVal = function ($html, $label) {
    $re = '/<td[^>]*>\s*' . preg_quote($label, '/') . '\s*<\/td>\s*<td[^>]*>:\s*<\/td>\s*<td[^>]*>(.*?)<\/td>/is';
    if (preg_match($re, $html, $m))
        return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8'));
    return NULL;
};

$header = [
    'account_number' => $getRowVal($o['out'], 'NO. REK.'),
    'name' => $getRowVal($o['out'], 'NAMA'),
    'period' => $getRowVal($o['out'], 'PERIODE'),
    'currency' => $getRowVal($o['out'], 'MATA UANG')
];

$tx = [];
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($o['out']);
$xpath = new DOMXPath($dom);
$rows = $xpath->query('//tr[td]');
foreach ($rows as $tr) {
    $tds = $tr->getElementsByTagName('td');
    if ($tds->length < 2)
        continue;
    $date = trim(html_entity_decode($tds->item(0)->textContent, ENT_QUOTES, 'UTF-8'));
    $dateBlack = [
        '', 'NO. REK.', 'NAMA', 'PERIODE', 'MATA UANG', 'TGL.',
        'SALDO AWAL', 'MUTASI KREDIT', 'MUTASI DEBET', 'SALDO AKHIR',
        'INFORMASI REKENING - MUTASI REKENING', chr(194) . chr(160)
    ];
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
    $amount = NULL;
    $type = NULL;
    if ($tds->length >= 3) {
        $typeCandidate = strtoupper(trim($tds->item(2)->textContent));
        if ($typeCandidate === 'CR' || $typeCandidate === 'DR')
            $type = $typeCandidate;
    }
    if (!empty($segments)) {
        $last = $segments[count($segments) - 1];
        if (preg_match('/([0-9.,]+)\s*(CR|DR)?$/i', $last, $m)) {
            $amount = $m[1];
            if (!$type && isset($m[2]) && $m[2] !== '')
                $type = strtoupper($m[2]);
            array_pop($segments);
        }
    }
    $info = trim(implode(' ', $segments));
    if ($amount === NULL || !($type === 'CR' || $type === 'DR'))
        continue;
    $amountClean = preg_replace('/[^0-9.,]/', '', $amount);
    $amountClean = str_replace(',', '', $amountClean);
    $amountNum = round((float) $amountClean, 2);
    $tx[] = [
        'date' => $date,
        'type' => $type,
        'amount' => $amountNum,
        'amount_formatted' => number_format($amountNum, 2, '.', ''),
        'info' => $info
    ];
}

$sa = $getRowVal($o['out'], 'SALDO AWAL');
$sc = $getRowVal($o['out'], 'MUTASI KREDIT');
$sd = $getRowVal($o['out'], 'MUTASI DEBET');
$sl = $getRowVal($o['out'], 'SALDO AKHIR');
$norm = function($s){
    $c = preg_replace('/[^0-9.,]/', '', (string)$s);
    $c = str_replace(',', '', $c);
    $n = round((float)$c, 2);
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
    'closing_balance_formatted' => $slFmt
];

$mutasiJson = [
    'header' => $header,
    'transactions' => $tx,
    'summary' => $summary
];
file_put_contents($hasilDir . '/mutasirekening.json', json_encode($mutasiJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION));

$logout = go('https://m.klikbca.com/authentication.do?value(actions)=logout', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => ''
], 'https://m.klikbca.com/accountstmt.do?value(actions)=acctstmtview', 'same-origin');
echo "Selesai. JSON tersimpan di folder hasil.\n";
