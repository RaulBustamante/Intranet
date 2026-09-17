<?php

namespace App\Domain\Rooms\Services;

use RuntimeException;

/** El horario pedido choca con una reserva confirmada. */
class RoomUnavailableException extends RuntimeException
{
}
