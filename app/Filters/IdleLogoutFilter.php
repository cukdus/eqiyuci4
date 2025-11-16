<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class IdleLogoutFilter implements FilterInterface
{
    protected int $idleSeconds = 7200;

    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('authentication');
        $user = $auth->user();
        if (!$user) {
            // Tidak ada user terautentikasi, lewati saja
            return;
        }

        $session = session();
        $last = $session->get('last_activity');
        $now = time();

        if ($last !== null && ($now - (int) $last) > $this->idleSeconds) {
            // Idle melebihi batas, logout dan arahkan ke halaman login
            $auth->logout();
            $session->destroy();

            return redirect()
                ->to(base_url('login'))
                ->with('message', 'Sesi berakhir: tidak ada aktivitas selama 2 jam.');
        }

        // lanjutkan request
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Perbarui timestamp aktivitas terakhir jika user masih login
        $auth = service('authentication');
        if ($auth->user()) {
            session()->set('last_activity', time());
        }
    }
}
