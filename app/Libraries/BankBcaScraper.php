<?php

namespace App\Libraries;

class BankBcaScraper
{
    private string $baseUrl = 'https://ibank.klikbca.com';
    private string $userId;
    private string $pin;
    private string $cookieFile;
    private bool $sslVerify = true;
    private ?string $caCertPath = null;

    public function __construct(?string $userId = null, ?string $pin = null)
    {
        $this->userId = $userId ?? (string) (env('BANK_USER_ID') ?? '');
        $this->pin = $pin ?? (string) (env('BANK_PIN') ?? '');
        $cookieDir = WRITEPATH . 'cookies';
        if (!is_dir($cookieDir)) {
            @mkdir($cookieDir, 0755, true);
        }
        $this->cookieFile = $cookieDir . DIRECTORY_SEPARATOR . 'klikbca.cookie';

        // SSL verification controls (Windows often lacks CA bundle by default)
        $sslEnv = env('BCA_SSL_VERIFY');
        if ($sslEnv !== null) {
            $val = strtolower((string)$sslEnv);
            $this->sslVerify = !in_array($val, ['false', '0', 'no', 'off'], true);
        }
        $caPath = env('BCA_CACERT_PATH');
        if (is_string($caPath) && $caPath !== '' && file_exists($caPath)) {
            $this->caCertPath = $caPath;
        }
    }

    public function fetchBalance(): array
    {
        if ($this->userId === '' || $this->pin === '') {
            return ['success' => false, 'message' => 'BANK_USER_ID atau BANK_PIN kosong di .env'];
        }

        $session = curl_init();
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerify,
            CURLOPT_SSL_VERIFYHOST => $this->sslVerify ? 2 : 0,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_COOKIEJAR => $this->cookieFile,
            CURLOPT_COOKIEFILE => $this->cookieFile,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0 Safari/537.36',
        ];
        if ($this->sslVerify && $this->caCertPath) {
            $opts[CURLOPT_CAINFO] = $this->caCertPath;
        }
        curl_setopt_array($session, $opts);

        // 1) Load login page to initialize session
        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/',
            CURLOPT_HTTPGET => true,
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);
        $loginPage = curl_exec($session);
        if ($loginPage === false) {
            $err = curl_error($session);
            // Fallback: retry without SSL verification if CA bundle missing
            if (stripos($err, 'SSL certificate') !== false) {
                curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 0);
                $loginPage = curl_exec($session);
            }
            if ($loginPage === false) {
                $err = curl_error($session);
                curl_close($session);
                return ['success' => false, 'message' => 'Gagal memuat halaman login: ' . $err];
            }
        }

        // 2) Submit login form
        $postFields = http_build_query([
            'value(user_id)' => $this->userId,
            'value(pswd)' => $this->pin,
        ]);
        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/authentication.do',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Origin: ' . $this->baseUrl,
                'Referer: ' . $this->baseUrl . '/',
            ],
        ]);
        $authResp = curl_exec($session);
        if ($authResp === false) {
            $err = curl_error($session);
            curl_close($session);
            return ['success' => false, 'message' => 'Gagal autentikasi: ' . $err];
        }

        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/menu.do',
            CURLOPT_HTTPGET => true,
            CURLOPT_POST => false,
        ]);
        $menuPage = curl_exec($session);
        if ($menuPage === false) {
            $err = curl_error($session);
            curl_close($session);
            return ['success' => false, 'message' => 'Gagal membuka menu: ' . $err];
        }

        // Simple check: if response contains login error indicators
        if (stripos($authResp, 'login') !== false && stripos($authResp, 'user') !== false && stripos($authResp, 'password') !== false) {
            curl_close($session);
            return ['success' => false, 'message' => 'Autentikasi ditolak atau butuh langkah tambahan (OTP/CAPTCHA).'];
        }

        // 3) Navigate to balance inquiry
        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/balanceinquiry.do',
            CURLOPT_HTTPGET => true,
            CURLOPT_POST => false,
            CURLOPT_HTTPHEADER => [
                'Referer: ' . $this->baseUrl . '/menu.do',
            ],
        ]);
        $balanceHtml = curl_exec($session);
        $err = ($balanceHtml === false) ? curl_error($session) : null;
        curl_close($session);
        if ($balanceHtml === false) {
            return ['success' => false, 'message' => 'Gagal mengambil saldo: ' . $err];
        }

        // Parse HTML table for account & balance
        $accounts = $this->parseBalanceTable($balanceHtml);
        if (empty($accounts)) {
            return [
                'success' => false,
                'message' => 'Tidak menemukan data saldo. Struktur halaman mungkin berubah atau butuh langkah verifikasi tambahan.',
                'raw' => $balanceHtml,
            ];
        }

        return [
            'success' => true,
            'accounts' => $accounts,
        ];
    }

    private function parseBalanceTable(string $html): array
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $loaded = $doc->loadHTML($html);
        libxml_clear_errors();
        if (!$loaded) {
            return [];
        }
        $xpath = new \DOMXPath($doc);
        $rows = $xpath->query('//table//tr');
        $data = [];
        foreach ($rows as $tr) {
            $cells = $tr->getElementsByTagName('td');
            if ($cells->length >= 2) {
                $acc = trim($cells->item(0)->textContent ?? '');
                $bal = trim($cells->item(1)->textContent ?? '');
                if ($acc !== '' && $bal !== '') {
                    $data[] = [
                        'account' => $acc,
                        'balance' => $bal,
                    ];
                }
            }
        }
        return $data;
    }

    public function fetchAccountStatementForToday(): array
    {
        $today = new \DateTime('now');
        return $this->fetchAccountStatementForDate($today);
    }

    public function fetchAccountStatementForDate(\DateTimeInterface $date): array
    {
        if ($this->userId === '' || $this->pin === '') {
            return ['success' => false, 'message' => 'BANK_USER_ID atau BANK_PIN kosong di .env'];
        }

        $session = curl_init();
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerify,
            CURLOPT_SSL_VERIFYHOST => $this->sslVerify ? 2 : 0,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 25,
            CURLOPT_COOKIEJAR => $this->cookieFile,
            CURLOPT_COOKIEFILE => $this->cookieFile,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0 Safari/537.36',
        ];
        if ($this->sslVerify && $this->caCertPath) {
            $opts[CURLOPT_CAINFO] = $this->caCertPath;
        }
        curl_setopt_array($session, $opts);

        // Init session
        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/',
            CURLOPT_HTTPGET => true,
        ]);
        $loginPage = curl_exec($session);
        if ($loginPage === false) {
            $err = curl_error($session);
            // Fallback: retry without SSL verification if CA bundle missing
            if (stripos($err, 'SSL certificate') !== false) {
                curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 0);
                $loginPage = curl_exec($session);
            }
            if ($loginPage === false) {
                $err = curl_error($session);
                curl_close($session);
                return ['success' => false, 'message' => 'Gagal memuat halaman login: ' . $err];
            }
        }
        // Authenticate
        $postFields = http_build_query([
            'value(user_id)' => $this->userId,
            'value(pswd)' => $this->pin,
        ]);
        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/authentication.do',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Origin: ' . $this->baseUrl,
                'Referer: ' . $this->baseUrl . '/',
            ],
        ]);
        $authResp = curl_exec($session);
        if ($authResp === false) {
            $err = curl_error($session);
            curl_close($session);
            return ['success' => false, 'message' => 'Gagal autentikasi: ' . $err];
        }

        // Navigate to Account Statement page (form)
        curl_setopt_array($session, [
            CURLOPT_URL => $this->baseUrl . '/accountstatement.do',
            CURLOPT_HTTPGET => true,
            CURLOPT_POST => false,
        ]);
        $stmtPage = curl_exec($session);
        if ($stmtPage === false) {
            $err = curl_error($session);
            curl_close($session);
            return ['success' => false, 'message' => 'Gagal membuka Account Statement: ' . $err];
        }

        $dateStr = date('d/m/Y', $date->getTimestamp());
        $viewHtml = $this->submitStatementForm($session, $stmtPage, $dateStr);
        if ($viewHtml === false) {
            $err = curl_error($session);
            curl_close($session);
            return ['success' => false, 'message' => 'Gagal submit Account Statement: ' . $err];
        }

        // Parse transactions from resulting page
        $parsed = $this->parseStatementTable($viewHtml);
        curl_close($session);
        if (empty($parsed['transactions'])) {
            return [
                'success' => false,
                'message' => 'Tidak menemukan transaksi pada tanggal tersebut atau butuh verifikasi tambahan.',
                'raw' => $viewHtml,
            ];
        }
        return [
            'success' => true,
            'date' => $date->format('Y-m-d'),
            'account_no' => $parsed['account_no'] ?? null,
            'transactions' => $parsed['transactions'],
            'raw' => $viewHtml,
        ];
    }

    private function submitStatementForm($session, string $html, string $dateStr)
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $loaded = $doc->loadHTML($html);
        libxml_clear_errors();
        if (!$loaded) {
            return false;
        }
        $xpath = new \DOMXPath($doc);
        $forms = $xpath->query('//form');
        $chosen = null;
        foreach ($forms as $form) {
            $inputs = (new \DOMXPath($doc))->query('.//input|.//select|.//button', $form);
            $names = [];
            foreach ($inputs as $in) {
                $n = strtolower(trim($in->getAttribute('name')));
                if ($n !== '') $names[] = $n;
            }
            $score = 0;
            foreach ($names as $n) {
                if (strpos($n, 'start') !== false || strpos($n, 'awal') !== false) $score++;
                if (strpos($n, 'end') !== false || strpos($n, 'akhir') !== false) $score++;
                if (strpos($n, 'date') !== false || strpos($n, 'periode') !== false) $score++;
            }
            if ($score >= 2) { $chosen = $form; break; }
        }
        if (!$chosen && $forms->length > 0) {
            $chosen = $forms->item(0);
        }
        if (!$chosen) return false;
        $method = strtolower($chosen->getAttribute('method') ?: 'post');
        $action = $chosen->getAttribute('action') ?: '/accountstatement.do';
        if (strpos($action, 'http') !== 0) {
            $action = rtrim($this->baseUrl, '/') . '/' . ltrim($action, '/');
        }
        $fields = [];
        $inputs = (new \DOMXPath($doc))->query('.//input|.//select|.//button', $chosen);
        foreach ($inputs as $in) {
            $name = $in->getAttribute('name');
            if ($name === '') continue;
            $val = $in->getAttribute('value');
            $lname = strtolower($name);
            if ((strpos($lname, 'start') !== false || strpos($lname, 'awal') !== false) && (strpos($lname, 'date') !== false || strpos($lname, 'periode') !== false)) {
                $val = $dateStr;
            } elseif ((strpos($lname, 'end') !== false || strpos($lname, 'akhir') !== false) && (strpos($lname, 'date') !== false || strpos($lname, 'periode') !== false)) {
                $val = $dateStr;
            } elseif ($lname === 'value(submit)' || strpos($lname, 'submit') !== false) {
                $val = $val ?: 'View Account Statement';
            }
            $fields[$name] = $val;
        }
        $post = http_build_query($fields);
        $headers = ['Content-Type: application/x-www-form-urlencoded', 'Referer: ' . $this->baseUrl . '/accountstatement.do'];
        if ($method === 'get') {
            $url = $action . (strpos($action, '?') === false ? '?' : '&') . $post;
            curl_setopt_array($session, [CURLOPT_URL => $url, CURLOPT_HTTPGET => true, CURLOPT_POST => false, CURLOPT_HTTPHEADER => $headers]);
        } else {
            curl_setopt_array($session, [CURLOPT_URL => $action, CURLOPT_POST => true, CURLOPT_POSTFIELDS => $post, CURLOPT_HTTPHEADER => $headers]);
        }
        return curl_exec($session);
    }

    private function parseStatementTable(string $html): array
    {
        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $loaded = $doc->loadHTML($html);
        libxml_clear_errors();
        if (!$loaded) {
            return ['transactions' => []];
        }
        $xpath = new \DOMXPath($doc);
        // Try to find account number text somewhere
        $accountNo = null;
        $accNodes = $xpath->query("//*[contains(text(),'Account') or contains(text(),'Rekening') or contains(text(),'Number') or contains(text(),'No.')]");
        foreach ($accNodes as $node) {
            $txt = trim($node->textContent);
            if (preg_match('/(\d{6,})/', $txt, $m)) {
                $accountNo = $m[1];
                break;
            }
        }
        $tables = $xpath->query('//table');
        $transactions = [];
        foreach ($tables as $table) {
            $rows = (new \DOMXPath($doc))->query('.//tr', $table);
            foreach ($rows as $tr) {
                $tds = $tr->getElementsByTagName('td');
                if ($tds->length < 2) continue;
                $desc = '';
                $amtTxt = '';
                $typeTxt = '';
                // Bias: kolom ke-2 biasanya berisi Description
                if ($tds->length >= 2) {
                    $descCandidate = trim($tds->item(1)->textContent ?? '');
                    if ($descCandidate !== '') { $desc = $descCandidate; }
                }
                $bestAmtIdx = -1; $typeIdx = -1;
                for ($i=0; $i<$tds->length; $i++) {
                    $txt = trim($tds->item($i)->textContent ?? '');
                    if ($desc === '' && $txt !== '' && !preg_match('/^[\d,.\-]+$/', str_replace(['Rp','IDR'], '', $txt))) {
                        $desc = $txt;
                    }
                    if (preg_match('/[\d]{1,3}(?:[\.,][\d]{3})*(?:[\.,]\d{2})?/', $txt)) {
                        $bestAmtIdx = $i; $amtTxt = $txt;
                    }
                    if ($typeIdx === -1 && preg_match('/\b(CR|DB|DEBET|KREDIT|DR)\b/i', $txt)) {
                        $typeIdx = $i; $typeTxt = $txt;
                    }
                }
                if ($desc === '' && $amtTxt === '') continue;

                $amount = $this->normalizeAmount($amtTxt);
                $crdb = $this->normalizeType($typeTxt);
                $transactions[] = [
                    'description' => $desc,
                    'amount' => $amount,
                    'crdb' => $crdb,
                ];
            }
            if (!empty($transactions)) break;
        }
        return ['account_no' => $accountNo, 'transactions' => $transactions];
    }

    private function normalizeAmount(string $text): float
    {
        $t = preg_replace('/[^0-9,().-]/', '', $text);
        $neg = false;
        if (strpos($t, '(') !== false && strpos($t, ')') !== false) { $neg = true; }
        if (strpos($t, '-') !== false && substr_count($t, '-') === 1) { $neg = true; }
        if (substr_count($t, ',') === 1 && substr_count($t, '.') > 1) {
            $t = str_replace('.', '', $t);
            $t = str_replace(',', '.', $t);
        } else {
            $t = str_replace(',', '', $t);
        }
        $t = str_replace(['(',')'], '', $t);
        $val = (float) ($t !== '' ? $t : '0');
        if ($neg) $val = -abs($val);
        return $val;
    }

    private function normalizeType(string $text): string
    {
        $t = strtoupper(trim($text));
        if (strpos($t, 'CR') !== false || strpos($t, 'KREDIT') !== false) return 'CR';
        if (strpos($t, 'DB') !== false || strpos($t, 'DEBET') !== false || strpos($t, 'DR') !== false) return 'DB';
        return '';
    }

    // Public wrapper untuk kebutuhan debug/CLI (parsing HTML lokal)
    public function parseStatementHtml(string $html): array
    {
        return $this->parseStatementTable($html);
    }
}