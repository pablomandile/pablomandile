<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function __invoke(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Mail::to(config('profile.email'))->send(new ContactMessage(
            senderName: $data['name'],
            senderEmail: $data['email'],
            subjectLine: $data['subject'],
            body: $data['message'],
        ));

        return back()->with('status', 'contact-sent');
    }
}
