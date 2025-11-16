<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Core\Traits\ApiResponseTrait;

class BaseController extends Controller
{
    use ApiResponseTrait;
}
