<?php

namespace App\Traits;

trait IconTrait
{
    public function getIconAttribute(): string
    {
        $filename = (string) ($this->filename ?? $this->hashname ?? '');
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'], true)) {
            return 'images';
        }

        return match ($extension) {
            'pdf' => 'fa-file-pdf-o',
            'doc', 'docx' => 'fa-file-word-o',
            'xls', 'xlsx', 'csv' => 'fa-file-excel-o',
            'zip', 'rar', '7z' => 'fa-file-archive-o',
            'mp4', 'mov', 'avi', 'mkv' => 'fa-file-video-o',
            'mp3', 'wav', 'ogg' => 'fa-file-audio-o',
            default => 'fa-file-o',
        };
    }
}
