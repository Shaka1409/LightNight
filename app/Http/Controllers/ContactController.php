<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    
    public function contact()
    {
        return view('contact');
    }
    public function sendContact(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

           // Gửi mail đến admin
    Mail::to('trinh14092004z@gmail.com')->send(new ContactMail($data));

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Tin nhắn của bạn được gửi đi thành công!');
    }
}
