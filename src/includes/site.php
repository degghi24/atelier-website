<?php
/* Atelier Tang - site.php
   Business details shown in several places (contacts page, footer, booking
   section). Keeping them here means every page shows the same address and
   opening hours. Decorative icons are drawn by the stylesheet. */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

/* Example data for the university project. */
function site_info(): array
{
    return [
        'name' => 'Atelier Tang',
        'street' => 'Via Esempio 12',
        'city' => '35121 Padova',
        'phone_label' => '049 000 0000',
        'phone_link' => '+390490000000',
        'email' => 'info@ateliertang.example',
    ];
}

/* Opening hours: day label => hours, written in full so screen readers read them naturally. */
function opening_hours(): array
{
    return [
        'Lunedì' => 'Chiuso',
        'Da martedì a venerdì' => 'Dalle 9.30 alle 12.30 e dalle 15.00 alle 19.00',
        'Sabato' => 'Dalle 9.30 alle 13.00',
        'Domenica' => 'Chiuso',
    ];
}
