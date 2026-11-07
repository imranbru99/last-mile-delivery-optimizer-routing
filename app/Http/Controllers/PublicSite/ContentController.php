<?php

declare(strict_types=1);

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class ContentController extends Controller
{
    public function contact(): View
    {
        return view('public.content.contact');
    }

    public function about(): View
    {
        return view('public.content.about');
    }

    public function faq(): View
    {
        return view('public.content.faq');
    }

    public function privacy(): View
    {
        return view('public.content.privacy');
    }

    public function terms(): View
    {
        return view('public.content.terms');
    }

    public function security(): View
    {
        return view('public.content.security');
    }

    public function blog(): View
    {
        return view('public.content.blog');
    }

    public function help(): View
    {
        return view('public.content.help');
    }
}
