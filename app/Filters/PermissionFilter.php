<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Exceptions\PermissionException;

class PermissionFilter implements FilterInterface
{
    /**
     * @param array|null $arguments
     *
     * @return RedirectResponse|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('authentication');
        $authorize = service('authorization');

        if (! $auth->check()) {
            return redirect()->to(base_url('login'));
        }

        if (empty($arguments)) {
            return;
        }

        $result = true;
        foreach ($arguments as $permission) {
            $result = $result && $authorize->hasPermission($permission, $auth->id());
        }

        if (! $result) {
            throw new PermissionException(lang('Auth.notEnoughPrivilege'));
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param array|null $arguments
     *
     * @return void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
