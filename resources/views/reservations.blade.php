<!-- resources/views/reservations.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas de Salas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Reservas de Salas</h1>
        <form action="{{ url('/reservations') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="room_id">Sala de Reuniones</label>
                <select class="form-control" id="room_id" name="room_id" required>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="start_time">Hora de Inicio</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="form-group">
                <label for="end_time">Hora de Fin</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time" required>
            </div>
            <div class="form-group">
                <label for="reserver_name">Nombre del Reservante</label>
                <input type="text" class="form-control" id="reserver_name" name="reserver_name" required>
            </div>
            <div class="form-group">
                <label for="meeting_title">Título de la Reunión</label>
                <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
            </div>
            <button type="submit" class="btn btn-primary">Reservar</button>
        </form>
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>
</body>
</html>
