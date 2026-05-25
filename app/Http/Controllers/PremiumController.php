<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function show()
    {
        return view('premium');
    }

    public function buy(Request $request)
    {
        $user = $request->user();

        $currentPremiumUntil = $user->premium_until && $user->premium_until->isFuture()
            ? $user->premium_until
            : now();

        $user->update([
            'premium_until' => $currentPremiumUntil->copy()->addDays(30),
        ]);

        return redirect()
            ->route('premium.show')
            ->with('success', 'Premium zostało aktywowane na 30 dni.');
    }
}