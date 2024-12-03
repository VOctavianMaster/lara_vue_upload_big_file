<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TestController extends Controller
{
    /**
     * Display test page
     */
    public function testMethod(Request $request): Response
    {
        return Inertia::render('TestNewComponent', [
            'var1' => 1,
            'var2' => "asdasdasd",
            'status' => session('status'),
        ]);
    }

}
