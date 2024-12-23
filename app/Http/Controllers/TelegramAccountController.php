<?php

namespace App\Http\Controllers;

use App\Models\TelegramAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TelegramAccountController extends Controller
{
    public function connect(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
        ]);

        // First create record in database
        $account = TelegramAccount::create([
            'phone_number' => $validated['phone_number'],
            'status' => false,
        ]);

        $response = Http::post('http://localhost:3000', [
            'phone_number' => $validated['phone_number']
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Telegram authentication started');
        }

        return back()->with('error', 'Failed to connect Telegram account');
    }
}
