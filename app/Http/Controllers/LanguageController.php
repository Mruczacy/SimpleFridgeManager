<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ChangeLanguageRequest;

class LanguageController extends Controller
{
    public function changeLanguage(ChangeLanguageRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        session()->put('lang', $validated['lang']);
        return redirect()->back();
    }
}
