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
     * Get access [0-15].
     *
     * @param int    $acs
     * @param string $action
     *
     * @return bool
     */
    protected function access($acs, $action)
    {
        if ($action === 'view') {
            return $acs % 2 == 1;
        }

        if ($action === 'edit') {
            return in_array($acs % 4, [2, 3]);
        }

        if ($action === 'create') {
            return in_array($acs % 8, [4, 5, 6, 7]);
        }

        if ($action === 'delete') {
            return $acs > 7;
        }

        return false;
    }
}
