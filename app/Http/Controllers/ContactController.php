<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    
    public function contact()
    {
        return view('contact');
    }
    public function sendContact(ContactRequest $request)
{
    $now = now();
    $lastSent = session('last_contact_time');

    if ($lastSent && $now->diffInMinutes($lastSent) < 5) {
        return redirect()->back()->withErrors(['error' => 'Bạn chỉ có thể gửi tin nhắn mỗi 5 phút.']);
    }

    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:1000',
    ]);

    // Gửi mail
    Mail::to('trinh14092004z@gmail.com')->queue(new ContactMail($data));

    // Ghi lại thời gian gửi
    session(['last_contact_time' => $now]);

    return redirect()->back()->with('success', 'Tin nhắn của bạn được gửi đi thành công!');
}

}
