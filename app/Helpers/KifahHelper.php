<?php

namespace App\Helpers;

class KifahHelper
{
    /**
     * Highlights a specific term within a text using a gradient span.
     * 
     * @param string $text
     * @param string|null $term
     * @return string
     */
    public static function highlight(string $text, ?string $term = null): string
    {
        if (!$term) {
            return $text;
        }

        return str_replace(
            $term,
            '<span class="bg-linear-to-r from-orange-400 to-violet-400 bg-clip-text text-transparent drop-shadow-md">' . $term . '</span>',
            $text
        );
    }

    /**
     * Renders the brand name with fixed styling and translation.
     * 
     * @return string
     */
    public static function brand(): string
    {
        if (app()->getLocale() === 'ar') {
            return '<span class="bg-linear-to-r from-orange-400 to-violet-400 bg-clip-text text-transparent drop-shadow-md">شركة الكفاح</span> <span class="text-white/90 font-light ml-1">العالمية</span>';
        }

        return '<span class="bg-linear-to-r from-orange-400 to-violet-400 bg-clip-text text-transparent drop-shadow-md">AL-KIFAH</span> <span class="text-white/90 font-light ml-1">Global</span>';
    }
}
