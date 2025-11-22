<?php

/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Http\Controllers;

use Illuminate\View\View;

class WebtechController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): View
    {
        return view('webtech-solutions');
    }

    /**
     * Display the terms and conditions page.
     */
    public function terms(): View
    {
        return view('webtech-terms');
    }

    /**
     * Display the privacy policy page.
     */
    public function privacy(): View
    {
        return view('webtech-privacy');
    }

    /**
     * Display the AI solutions page.
     */
    public function aiSolutions(): View
    {
        return view('webtech-ai-solutions');
    }
}
