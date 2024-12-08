<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function setTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        
        session(['theme' => $theme]);
        cookie()->queue('theme', $theme, 43200);
        return response()->json(['success' => true]);
    }

    public function getTheme()
    {
        $theme = session('theme', cookie('theme', 'dark'));
        return response()->json(['theme' => $theme]);
    }
}