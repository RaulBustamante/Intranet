{{-- Sidebar Blade Template --}}

<!-- Sidebar CSS -->
<style>
    .sidebar {
        background-color: #333;
        color: #fff;
        width: 60px;
        height: 100vh;
        position: fixed;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: start;
        padding-top: 20px;
    }

    .sidebar .menu-item {
        color: #fff;
        padding: 15px 0;
        text-decoration: none;
        font-size: 20px;
    }

    .sidebar .menu-item:hover {
        background-color: #555;
    }
</style>

<!-- Sidebar HTML -->
<div class="sidebar">
    <a href="#" class="menu-item" data-tooltip="Noticias"><i class="fas fa-newspaper"></i></a>
    <a href="#" class="menu-item" data-tooltip="Nuevos miembros"><i class="fas fa-user-friends"></i></a>
    <a href="{{ url('/calendar') }}" class="menu-item" data-tooltip="Calendario"><i class="fas fa-calendar-alt"></i></a>
    <!-- Add more sidebar items here -->
</div>

<!-- Tooltip JS -->
<script>
    // Example Tooltip Initialization if you're using Bootstrap tooltips
    $(function () {
        $('[data-tooltip="tooltip"]').tooltip();
    });
</script>
