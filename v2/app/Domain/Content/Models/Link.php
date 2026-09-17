<?php

namespace App\Domain\Content\Models;

use App\Domain\Content\Models\Concerns\HasLocalizedName;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasLocalizedName;

    protected $fillable = [
        'title_es', 'title_en', 'url', 'icon_path', 'category',
        'is_internal_only', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_internal_only' => 'boolean', 'is_active' => 'boolean'];
    }

    public function getTitleAttribute(): string
    {
        return $this->localized('title');
    }

    /** El host del enlace (para favicon y color). */
    public function host(): string
    {
        return parse_url($this->url, PHP_URL_HOST) ?: '';
    }

    /**
     * URL del logo del sitio.
     *   - icon_path explícito si RH subió uno
     *   - favicon del sitio (servicio de Google) para dominios públicos
     *   - null para IPs internas / localhost (se usa la inicial + color)
     */
    public function faviconUrl(): ?string
    {
        if ($this->icon_path) {
            return $this->icon_path;
        }

        $host = $this->host();
        if ($host === '' || $this->isPrivateHost($host)) {
            return null;   // interno: sin favicon, cae a inicial coloreada
        }

        return "https://www.google.com/s2/favicons?domain={$host}&sz=64";
    }

    /** Color de acento estable, derivado del host. Da colorido sin configurar. */
    public function accentColor(): string
    {
        $palette = ['#3b82f6', '#0f9d58', '#ea8600', '#7c3aed', '#ed2228', '#0d9488', '#db2777', '#4f46e5', '#0891b2', '#65a30d'];
        $key = $this->host() ?: $this->title_es;

        return $palette[crc32($key) % count($palette)];
    }

    public function initial(): string
    {
        return mb_strtoupper(mb_substr(trim($this->title_es), 0, 1)) ?: '·';
    }

    private function isPrivateHost(string $host): bool
    {
        if (in_array($host, ['localhost', '127.0.0.1'], true)) {
            return true;
        }
        // Rangos privados y el 191.168.x que Ariel usa internamente
        return (bool) preg_match('/^(10\.|172\.(1[6-9]|2\d|3[01])\.|192\.168\.|191\.168\.)/', $host);
    }
}
