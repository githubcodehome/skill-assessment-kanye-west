<?php

namespace App\Http;

use Inertia\Inertia;

class QuoteController
{
    /**
     * Show the profile for a given user.
     *
     */
    public function main(): \Inertia\Response
    {
        return Inertia::render('Profile/Quote', [
           // 'user' => []
        ]);
    }

    public function favorites(): \Inertia\Response
    {
        return Inertia::render('Profile/Favorites', [
            // 'user' => []
        ]);
    }
}
