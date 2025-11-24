<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;
use Psr\Log\LoggerInterface;

class WahaService
{
    protected string $baseUrl;
    protected ?string $apiToken;
    protected LoggerInterface $logger;
    protected bool $sslVerify;
    protected string $sendPath;
    protected ?string $fallbackSendPath;
    protected string $defaultCountryCode;

    public function __construct(?LoggerInterface $logger = null)
    {
        $this->baseUrl = rtrim(trim((string) (env('WAHA_BASE_URL') ?? ''), " \t\n\r\0\v\"'`"), '/');
        // Sanitize token: trim surrounding quotes and whitespace
        $rawToken = env('WAHA_API_TOKEN');
        $this->apiToken = is_string($rawToken) ? trim($rawToken, " \t\n\r\0\v\"'") : $rawToken;
        $this->logger = $logger ?? service('logger');
        // Default: non-SSL environments (WAMP) often lack CA bundle; allow opt-out
        $sslEnv = filter_var(env('WAHA_SSL_VERIFY'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $this->sslVerify = $sslEnv !== null ? $sslEnv : false;
        // Allow configurable send path to support different WAHA servers
        $this->sendPath = trim((string) (env('WAHA_SEND_PATH') ?? '/messages/send'));
        $fb = env('WAHA_SEND_PATH_FALLBACK');
        $this->fallbackSendPath = is_string($fb) ? trim($fb) : '/api/sendText';
        // Negara default untuk normalisasi nomor (mis. Indonesia = 62)
        $cc = (string) (env('WAHA_DEFAULT_COUNTRY_CODE') ?? '62');
        $this->defaultCountryCode = preg_replace('/[^0-9]/', '', $cc) ?: '62';
    }

    public function isConfigured(): bool
    {
        return $this->baseUrl !== '' && $this->apiToken !== null && $this->apiToken !== '';
    }

    /**
     * Validasi koneksi sederhana ke WAHA API.
     * Mengembalikan array [success=>bool, message=>string].
     */
    public function validateConnection(): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'WAHA belum dikonfigurasi'];
        }

        try {
            $client = service('curlrequest');
            /** @var CURLRequest $client */
            $resp = $client->get($this->baseUrl . '/', [
                'headers' => $this->defaultHeaders(),
                'timeout' => 5,
                // Disable SSL verification when configured (local dev without CA bundle)
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => $this->sslVerify,
                    CURLOPT_SSL_VERIFYHOST => $this->sslVerify ? 2 : 0,
                ],
            ]);
            $ok = $resp->getStatusCode() >= 200 && $resp->getStatusCode() < 400;
            $this->logger->info('WAHA validateConnection status', [
                'status' => $resp->getStatusCode(),
                'success' => $ok,
            ]);
            return ['success' => $ok, 'message' => $ok ? 'Terhubung' : ('Status ' . $resp->getStatusCode())];
        } catch (\Throwable $e) {
            $this->logger->error('WAHA validateConnection error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Kirim pesan teks.
     * @param string $to Nomor tujuan (msisdn)
     * @param string $text Pesan
     */
    public function sendMessage(string $to, string $text): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'WAHA belum dikonfigurasi'];
        }
        // Normalisasi penerima lebih dulu (menangani 08…, +62…, JID @c.us/@g.us)
        $normalizedTo = $this->normalizeRecipient($to);
        $payload = $this->buildPayload($normalizedTo, $text);

        try {
            $client = service('curlrequest');
            /** @var CURLRequest $client */
            $isLegacy = (stripos($this->sendPath, 'sendText') !== false);
            $headers = $this->defaultHeaders();
            $options = [
                'headers' => $headers,
                'timeout' => 10,
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => $this->sslVerify,
                    CURLOPT_SSL_VERIFYHOST => $this->sslVerify ? 2 : 0,
                ],
            ];
            if ($isLegacy) {
                unset($options['headers']['Content-Type']);
                $options['form_params'] = $payload;
            } else {
                $options['json'] = $payload;
            }
            $resp = $client->post($this->baseUrl . $this->sendPath, $options);
            $ok = $resp->getStatusCode() >= 200 && $resp->getStatusCode() < 300;
            $body = (string) $resp->getBody();
            if ($ok) {
                $this->logger->info('WAHA sendMessage success', ['to' => $to]);
            } else {
                $this->logger->warning('WAHA sendMessage failed', ['to' => $to, 'status' => $resp->getStatusCode(), 'body' => $body]);
                $needFallback = (stripos($body, 'Failed to parse JSON') !== false) || ($resp->getStatusCode() >= 400 && $resp->getStatusCode() < 500);
                if ($needFallback && is_string($this->fallbackSendPath) && $this->fallbackSendPath !== '') {
                    $jid = str_ends_with($normalizedTo, '@c.us') || str_ends_with($normalizedTo, '@g.us') ? $normalizedTo : ($normalizedTo . '@c.us');
                    $session = env('WAHA_SESSION');
                    $legacy = ['chatId' => $jid, 'text' => $text, 'message' => $text];
                    if ($session) {
                        $legacy['session'] = $session;
                    } else {
                        $legacy['session'] = 'default';
                    }
                    $url = $this->baseUrl . $this->fallbackSendPath;
                    $fbHeaders = $this->defaultHeaders();
                    $fbHeaders['Content-Type'] = 'application/x-www-form-urlencoded';
                    $fbBody = http_build_query($legacy);
                    $fb = $this->postUsingCurl($url, $fbHeaders, $fbBody);
                    return $fb;
                }
            }
            return ['success' => $ok, 'message' => $ok ? 'sent' : ('HTTP ' . $resp->getStatusCode()), 'body' => $body];
        } catch (\Throwable $e) {
            $this->logger->error('WAHA sendMessage error: ' . $e->getMessage(), ['to' => $to]);
            // Fallback: gunakan cURL langsung untuk memaksa non-SSL verify bila diizinkan
            if (!$this->sslVerify && stripos($e->getMessage(), 'SSL certificate') !== false) {
                $url = $this->baseUrl . $this->sendPath;
                $isLegacy = (stripos($this->sendPath, 'sendText') !== false);
                $hdrs = $this->defaultHeaders();
                if ($isLegacy) {
                    $hdrs['Content-Type'] = 'application/x-www-form-urlencoded';
                    $fallback = $this->postUsingCurl($url, $hdrs, http_build_query($payload));
                } else {
                    $fallback = $this->postUsingCurl($url, $hdrs, json_encode($payload));
                }
                return $fallback;
            }
            return ['success' => false, 'message' => 'Exception: ' . $e->getMessage()];
        }
    }

    /**
     * Render template dengan data dinamis sederhana
     * Placeholder format {{key}}
     */
    public function renderTemplate(string $template, array $data): string
    {
        $rendered = $template;
        foreach ($data as $key => $val) {
            $rendered = str_replace('{{' . $key . '}}', (string) $val, $rendered);
        }
        return $rendered;
    }

    protected function defaultHeaders(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $authType = strtolower((string) (env('WAHA_AUTH_TYPE') ?? ''));
        // Beberapa WAHA menggunakan Authorization Bearer, lainnya X-API-KEY.
        // Jika WAHA_AUTH_TYPE diset, kirim hanya header yang relevan.
        if ($authType === 'bearer') {
            $headers['Authorization'] = 'Bearer ' . (string) $this->apiToken;
        } elseif ($authType === 'api_key' || $authType === 'x-api-key') {
            $headers['X-API-KEY'] = (string) $this->apiToken;
        } else {
            // Default: kirim keduanya untuk kompatibilitas; server akan memilih yang sesuai
            $headers['Authorization'] = 'Bearer ' . (string) $this->apiToken;
            $headers['X-API-KEY'] = (string) $this->apiToken;
        }
        return $headers;
    }

    /**
     * Bangun payload sesuai jenis endpoint WAHA.
     * Jika menggunakan endpoint legacy '/api/sendText', gunakan chatId/text.
     */
    protected function buildPayload(string $to, string $text): array
    {
        $session = env('WAHA_SESSION');
        // Legacy endpoint: expects chatId + '@c.us'
        if (stripos($this->sendPath, 'sendText') !== false) {
            if (str_ends_with($to, '@c.us') || str_ends_with($to, '@g.us')) {
                $out = [
                    'chatId' => $to,
                    'text' => $text,
                    'message' => $text,
                ];
                if ($session) {
                    $out['session'] = $session;
                } else {
                    $out['session'] = 'default';
                }
                return $out;
            }
            $chatId = ltrim($to, '+');
            $chatId = (str_ends_with($chatId, '@c.us') || str_ends_with($chatId, '@g.us')) ? $chatId : ($chatId . '@c.us');
            $out = [
                'chatId' => $chatId,
                'text' => $text,
                'message' => $text,
            ];
            if ($session) {
                $out['session'] = $session;
            } else {
                $out['session'] = 'default';
            }
            return $out;
        }
        // Default modern endpoint: support either msisdn or full JID
        if (str_ends_with($to, '@c.us') || str_ends_with($to, '@g.us')) {
            // Kirim beberapa field kompatibel: 'message', 'text', dan 'body'
            $payload = [
                'jid' => $to,
                'message' => $text,
                'text' => $text,
                'body' => $text,
            ];
            if ($session) {
                $payload['session'] = $session;
            }
            return $payload;
        }
        $payload = [
            'phone' => $to,
            'message' => $text,
            'text' => $text,
            'body' => $text,
        ];
        if ($session) {
            $payload['session'] = $session;
        }
        return $payload;
    }

    /**
     * Normalisasi penerima ke format yang diharapkan WAHA.
     * - Biarkan JID (@c.us/@g.us) apa adanya
     * - Hilangkan spasi, tanda baca; buang '+'
     * - Jika mulai dengan '0', ganti prefix dengan kode negara default (mis. '62')
     * - Jika mulai dengan '8' (khas Indonesia), tambahkan kode negara default
     */
    protected function normalizeRecipient(string $to): string
    {
        $t = trim($to);
        // JID group/individual: pass-through
        if (str_ends_with($t, '@c.us') || str_ends_with($t, '@g.us')) {
            return $t;
        }
        // Hapus spasi, tanda baca selain '+' lalu buang '+'
        $t = preg_replace('/[^0-9+]/', '', $t) ?? '';
        $t = ltrim($t, '+');
        if ($t === '') {
            return $t;
        }
        // 08xxxxx -> 62xxxxxx
        if (str_starts_with($t, '0')) {
            return $this->defaultCountryCode . substr($t, 1);
        }
        // 8xxxxxx -> 62xxxxxx (input tanpa 0)
        if (str_starts_with($t, '8')) {
            return $this->defaultCountryCode . $t;
        }
        // Sudah 62xxxxx atau kode negara lain -> kembalikan apa adanya
        return $t;
    }

    /**
     * Fallback: kirim POST menggunakan ext-curl secara langsung.
     */
    protected function postUsingCurl(string $url, array $headers, string $jsonBody): array
    {
        $ch = curl_init($url);
        $hdrs = [];
        foreach ($headers as $k => $v) {
            $hdrs[] = $k . ': ' . $v;
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $hdrs);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        // Nonaktifkan verifikasi SSL bila dikonfigurasi
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerify);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->sslVerify ? 2 : 0);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            return ['success' => false, 'message' => 'cURL: ' . $curl_error, 'body' => $response];
        }
        $ok = $httpcode >= 200 && $httpcode < 300;
        return ['success' => $ok, 'message' => $ok ? 'sent' : ('HTTP ' . $httpcode), 'body' => (string) $response];
    }
}
