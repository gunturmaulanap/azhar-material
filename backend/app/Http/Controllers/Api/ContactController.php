<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you can add logic to save to database or send email
        // For now, we'll just return success response

        return response()->json([
            'success' => true,
            'message' => 'Pesan Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.'
        ]);
    }
}
