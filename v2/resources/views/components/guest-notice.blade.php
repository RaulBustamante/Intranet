@props(['action' => 'request'])

{{--
  Aviso para invitados: la página se navega sin sesión, pero para CAPTURAR
  (enviar, reservar, publicar) hay que firmarse. Se muestra solo a invitados.
  $action: request | book | kudo → arma la frase "Inicia sesión para …".
--}}
@guest
    @php
        $accion = __('app.guest.act_' . $action);
    @endphp
    <div class="mb-4 flex flex-wrap items-center gap-x-2 gap-y-1 rounded-lg border px-3.5 py-2.5 text-sm"
         style="border-color: var(--line); background: var(--paper-2); color: var(--ink-70);">
        <x-icon name="info" class="w-4 h-4 shrink-0" style="color: var(--ink-50);" />
        <span>{{ __('app.guest.browsing') }}</span>
        <a href="{{ route('login') }}" class="ah-link font-medium">
            {{ __('app.guest.sign_in_to_act', ['accion' => $accion]) }}
        </a>
    </div>
@endguest
