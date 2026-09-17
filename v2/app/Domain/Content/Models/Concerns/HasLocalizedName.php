<?php

namespace App\Domain\Content\Models\Concerns;

/** Resuelve un campo *_es/*_en al idioma activo, cayendo al otro si falta (UX-07). */
trait HasLocalizedName
{
    public function localized(string $base): string
    {
        $loc = app()->getLocale();

        return $this->{"{$base}_{$loc}"} ?: $this->{"{$base}_es"} ?: $this->{"{$base}_en"} ?: '';
    }
}
