<x-mail::message>
# {{ __('rooms.mail_title') }}

**{{ $booking->title }}**

- **{{ __('rooms.title') }}:** {{ $booking->room?->name }}
- **{{ __('rooms.from') }}:** {{ $booking->starts_at->locale(app()->getLocale())->isoFormat('dddd D MMMM, HH:mm') }}
- **{{ __('rooms.to') }}:** {{ $booking->ends_at->format('H:i') }}

{{ __('rooms.mail_ics_hint') }}

{{ config('app.name') }}
</x-mail::message>
