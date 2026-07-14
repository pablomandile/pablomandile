<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_an_email(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Juana Visitante',
            'email' => 'juana@example.com',
            'subject' => 'Consulta',
            'message' => 'Hola, me interesa tu trabajo.',
            'website' => '',
        ])->assertRedirect();

        Mail::assertSent(ContactMessage::class, function (ContactMessage $mail) {
            return $mail->hasTo(config('profile.email'))
                && $mail->senderEmail === 'juana@example.com';
        });
    }

    public function test_contact_form_validates_required_fields(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => '',
            'email' => 'no-es-un-email',
            'subject' => '',
            'message' => '',
        ])->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        Mail::assertNothingSent();
    }

    public function test_honeypot_blocks_bots(): void
    {
        Mail::fake();

        $this->post('/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'spam',
            'message' => 'spam spam',
            'website' => 'http://spam.example.com',
        ])->assertSessionHasErrors('website');

        Mail::assertNothingSent();
    }
}
