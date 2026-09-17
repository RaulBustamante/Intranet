<?php

/*
| Asistente AI de la búsqueda global.
|
| provider: 'null' (apagado, por defecto) | 'claude' | 'openai'
| Poner la llave enciende el asistente SIN tocar código. Cero costo mientras
| sea 'null'. La búsqueda global funciona igual con o sin AI (SRCH-10).
*/

return [
    'provider' => env('AI_PROVIDER', 'null'),
    'api_key'  => env('AI_API_KEY'),
    'model'    => env('AI_MODEL'),
];
