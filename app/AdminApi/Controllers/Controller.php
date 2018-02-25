<?php
/**
 * Created by PhpStorm.
 * User: 6666
 * Date: 2018/2/25
 * Time: 17:32
 */

namespace App\AdminApi\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $guard = 'admin_api';

    public function __construct()
    {
        $this->middleware('auth:admin_api', ['except' => ['login']]);
//        $this->middleware('', '');
    }
}
