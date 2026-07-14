<x-mail::message>
# Nuevo mensaje desde el portfolio

**De:** {{ $senderName }} ({{ $senderEmail }})

**Asunto:** {{ $subjectLine }}

---

{{ $body }}

<x-mail::button :url="'mailto:'.$senderEmail">
Responder a {{ $senderName }}
</x-mail::button>

Enviado desde el formulario de contacto de {{ config('app.name') }}.
</x-mail::message>
