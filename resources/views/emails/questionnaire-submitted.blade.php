<x-mail::message>
# Cuestionario Completado

El cuestionario **"{{ $response->questionnaire->title }}"** ha sido completado por **{{ $response->user->name }} ({{ $response->user->email }})**.

Fecha de envío: {{ $response->submitted_at->format('d/m/Y H:i') }}

Adjunto encontrarás el reporte en formato PDF.

Gracias,
<br>
Estrategias y Soluciones Minmer Global.
</x-mail::message>
