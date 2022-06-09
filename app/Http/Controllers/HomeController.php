<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('myfridges.indexOwn');
    }
}
