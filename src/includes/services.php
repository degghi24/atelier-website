<?php
/* Atelier Tang - services.php
   Services offered by the atelier. The same list feeds the services page, the
   booking form and the server-side validation. Keys are stored in the database. */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

/* Each service has a short description and practical details (label => value)
   shown on the services page. */
function services(): array
{
    return [
        'suit' => [
            'name' => 'Abiti e giacche su misura',
            'description' => 'Completi, giacche e pantaloni disegnati sulla tua figura, con rifiniture e fodere scelte insieme.',
            'details' => ['Tempi indicativi' => 'Da 4 a 6 settimane', 'Prove' => 'Due o tre'],
        ],
        'shirt' => [
            'name' => 'Camicie su misura',
            'description' => 'Colli, polsini e tessuti selezionati con te. Vestibilità precisa e cuciture curate.',
            'details' => ['Tempi indicativi' => 'Da 2 a 3 settimane', 'Prove' => 'Una, sulla prima camicia'],
        ],
        'ceremony' => [
            'name' => 'Abiti da cerimonia e da sposo',
            'description' => 'Per matrimoni ed eventi importanti, con tempi pianificati e più prove dell\'abito.',
            'details' => ['Tempi indicativi' => 'Da 8 a 10 settimane', 'Prove' => 'Almeno tre'],
        ],
        'womenswear' => [
            'name' => 'Abiti da donna',
            'description' => 'Tailleur, abiti e pantaloni su misura, dal modello al capo finito.',
            'details' => ['Tempi indicativi' => 'Da 4 a 6 settimane', 'Prove' => 'Due o tre'],
        ],
        'alteration' => [
            'name' => 'Modifiche e riparazioni',
            'description' => 'Orli, strette, cambi di cerniera e rinnovo di capi che vuoi continuare a indossare.',
            'details' => ['Tempi indicativi' => 'Da 3 a 7 giorni', 'Prove' => 'Una, se serve'],
        ],
        'fabric-consultation' => [
            'name' => 'Consulenza sui tessuti',
            'description' => 'Ti aiutiamo a scegliere peso, trama e colore in base alla stagione, all\'uso e al budget.',
            'details' => ['Durata' => 'Circa un\'ora', 'Cosa portare' => 'Foto o capi che ti piacciono'],
        ],
    ];
}


/* Request statuses and their visible labels. */
function booking_statuses(): array
{
    return [
        'in_attesa' => 'In attesa di conferma',
        'confermata' => 'Confermata',
        'annullata' => 'Annullata',
    ];
}
