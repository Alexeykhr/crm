<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('validate');
    }

    /**
     * Get access.
     *
     * @param int $access
     * @param string $action
     *
     * @return bool
     */
    protected function access($access, $action)
    {
        if ($action === 'create') {
            return $access > 3;
        }

        if ($action === 'edit') {
            return in_array($access, [2, 3, 6, 7]);
        }

        if ($action === 'view') {
            return $access % 2 == 1;
        }

        return false;
    }
}
