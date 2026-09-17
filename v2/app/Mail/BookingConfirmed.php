<?php

namespace App\Mail;

use App\Domain\Calendar\Services\IcsBuilder;
use App\Domain\Rooms\Models\RoomBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Confirmación de reserva de sala con archivo de calendario adjunto (ROOM-07).
 * Quien reserva puede abrir el .ics y agregarlo a su calendario con un clic.
 */
class BookingConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RoomBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: __('rooms.mail_subject', ['room' => $this->booking->room?->name]));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.booking-confirmed');
    }

    public function attachments(): array
    {
        $ics = (new IcsBuilder())
            ->add(
                uid: "booking-{$this->booking->id}@arielhub",
                title: $this->booking->title,
                start: $this->booking->starts_at,
                end: $this->booking->ends_at,
                description: $this->booking->room?->name ?? '',
            )
            ->build();

        return [
            Attachment::fromData(fn () => $ics, 'reserva.ics')->withMime('text/calendar'),
        ];
    }
}
