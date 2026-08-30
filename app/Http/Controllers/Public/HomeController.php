<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\{Setting, Menu, Service, PortfolioItem, TechStack, Page};


class HomeController extends Controller
{
    public function __invoke()
    {
        $site = Setting::where('key', 'site')->first();
        $menu = \App\Models\Menu::where('name', 'main')->with('items')->first();


        // About ambil dari Page 'about' (json)
        $about = Page::where('slug', 'about')->where('published', true)->value('content') ?? [];

        // Process steps simpan di settings['process'] (array langkah)
        $process = Setting::where('key', 'site')->value('value')['process'] ?? [];

        return view('public.home', [
            'site' => $site?->value ?? [],
            'menu' => $menu,
            'services' => Service::orderBy('order')->get(),
            'stacks' => TechStack::orderBy('order')->get(),
            'portfolios' => PortfolioItem::orderBy('order')->get(),
            'about'      => $about,      // ← { visi: string, misi:[], nilai: string }
            'process'    => $process,    // ← [ {no,label,desc}, ... ]
        ]);
    }
}
