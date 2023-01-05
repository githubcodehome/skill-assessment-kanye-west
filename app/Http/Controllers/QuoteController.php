<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class QuoteController
{
    /**
     * Show the profile for a given user.
     *
     */
    public function main(): Response
    {
        return Inertia::render('Profile/Quotes');
    }

    public function favorites(): Response
    {
        return Inertia::render('Profile/Favorites');
    }
}
