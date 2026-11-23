<?php
/* Atelier Tang - content.php
   Content shared by the pages and by the site search (cerca.php): page titles and
   descriptions, the working steps, the frequently asked questions and the fabrics.
   Keeping the text here means the search always finds what the pages show. */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

/* Public pages: title (document title), heading (h1), lead, description and keywords. */
function site_pages(): array
{
    return [
        'home' => [
            'url' => 'index.php',
            'title' => 'Home',
            'heading' => 'Atelier Tang, sartoria artigianale a Padova',
            'lead' => 'Ogni capo nasce da una misura, da un tessuto scelto con cura e da molte ore di lavoro a mano. Abiti, giacche, camicie e capi da cerimonia cuciti su di te, nel cuore di Padova.',
            'description' => 'Atelier Tang a Padova: sartoria artigianale su misura per abiti, giacche, camicie e abiti da cerimonia. Prenota una consulenza e una prova.',
            'keywords' => 'sartoria Padova, abiti su misura, sartoria artigianale, abito da sposo, riparazioni sartoriali, atelier Padova',
        ],
        'atelier' => [
            'url' => 'atelier.php',
            'title' => 'L\'atelier e il metodo',
            'heading' => 'L\'atelier e il metodo',
            'lead' => 'Una piccola sartoria artigianale nel centro di Padova: lavoriamo su appuntamento, con pochi clienti alla volta, perché ogni capo richiede tempo, ascolto e attenzione ai dettagli.',
            'description' => 'Scopri l\'atelier sartoriale di Padova e le fasi di realizzazione di un capo su misura, dalla consulenza alla consegna.',
            'keywords' => 'atelier Padova, sartoria artigianale, metodo sartoriale, capo su misura, prove abito',
        ],
        'servizi' => [
            'url' => 'servizi.php',
            'title' => 'Servizi sartoriali',
            'heading' => 'Servizi sartoriali',
            'lead' => 'Dal capo completamente nuovo alla piccola riparazione: ti seguiamo in ogni fase, dalla scelta del tessuto alla prova finale.',
            'description' => 'Abiti, giacche e camicie su misura, capi da cerimonia, modifiche e consulenza sui tessuti a Padova, con tempi indicativi e numero di prove.',
            'keywords' => 'servizi sartoriali, abiti su misura Padova, camicie su misura, abito da sposo, riparazioni sartoriali, consulenza tessuti',
        ],
        'realizzazioni' => [
            'url' => 'realizzazioni.php',
            'title' => 'Realizzazioni e tessuti',
            'heading' => 'Realizzazioni e tessuti',
            'lead' => 'Ogni progetto viene definito insieme: modello, tessuto, vestibilità e dettagli cambiano in base a chi lo indosserà.',
            'description' => 'Uno smoking blu notte realizzato da Atelier Tang a Padova e il campionario dei tessuti tra cui scegliere.',
            'keywords' => 'realizzazioni sartoriali, smoking su misura, tessuti, lana, lino, cotone, abito da sposo',
        ],
        'contatti' => [
            'url' => 'contatti.php',
            'title' => 'Contatti e orari',
            'heading' => 'Contatti e orari',
            'lead' => 'Riceviamo su appuntamento, così possiamo dedicare a ogni capo il tempo necessario. Scrivici, telefonaci o prenota una consulenza dal sito.',
            'description' => 'Contatta Atelier Tang a Padova: indirizzo, telefono, email, orari di apertura e come raggiungere il laboratorio.',
            'keywords' => 'contatti sartoria Padova, orari atelier, indirizzo sartoria, prenotare sarto Padova',
        ],
        'privacy' => [
            'url' => 'privacy.php',
            'title' => 'Informativa sulla privacy',
            'heading' => 'Informativa sulla privacy',
            'lead' => 'Come trattiamo i dati che ci invii con il modulo di prenotazione e con l\'area riservata.',
            'description' => 'Come Atelier Tang tratta i dati personali inviati con il modulo di prenotazione e con l\'area riservata.',
            'keywords' => 'privacy, informativa, GDPR, dati personali, cookie tecnici',
        ],
        'accessibilita' => [
            'url' => 'accessibilita.php',
            'title' => 'Dichiarazione di accessibilità',
            'heading' => 'Dichiarazione di accessibilità',
            'lead' => 'Vogliamo che il sito sia utilizzabile da tutte le persone, anche da chi usa tecnologie assistive, solo la tastiera o un ingrandimento dello schermo.',
            'description' => 'Stato di conformità del sito Atelier Tang ai requisiti di accessibilità WCAG 2.1 livello AA, tasti di scelta rapida e come segnalare un problema.',
            'keywords' => 'accessibilità, WCAG 2.1, EN 301 549, Legge Stanca, dichiarazione di accessibilità, tasti di scelta rapida',
        ],
    ];
}

/* Page metadata merged with the page-specific settings (nav, breadcrumb...). */
function page_meta(string $key, array $settings = []): array
{
    $pages = site_pages();
    return array_merge(['id' => $key], $pages[$key], $settings);
}

/* The five working steps (atelier page). */
function atelier_steps(): array
{
    return [
        ['title' => 'Consulenza', 'text' => 'Ci racconti cosa ti serve e quando. Guardiamo insieme modelli e campioni di tessuto.', 'time' => 'Circa un\'ora, in laboratorio'],
        ['title' => 'Misure e modello', 'text' => 'Prendiamo una trentina di misure e disegniamo il cartamodello personale.', 'time' => 'Nella prima settimana'],
        ['title' => 'Prima prova', 'text' => 'Il capo viene imbastito e provato addosso per correggere linee e proporzioni.', 'time' => 'Dopo due o tre settimane'],
        ['title' => 'Finitura', 'text' => 'Dopo le ultime correzioni cuciamo a mano asole e rifiniture, poi stiriamo il capo.', 'time' => 'Nelle ultime due settimane'],
        ['title' => 'Consegna', 'text' => 'Ritiri il capo finito dopo un\'ultima prova. Per ogni ritocco successivo siamo a disposizione.', 'time' => 'Il giorno concordato'],
    ];
}

/* Frequently asked questions (services page). */
function faqs(): array
{
    return [
        ['question' => 'Quanto tempo serve per un abito su misura?', 'answer' => 'In media da quattro a sei settimane, con due o tre prove. Per cerimonie ed eventi conviene contattarci con almeno due mesi di anticipo.'],
        ['question' => 'Quanto costa un capo su misura?', 'answer' => 'Il prezzo dipende dal modello e dal tessuto. Dopo la consulenza ricevi un preventivo chiaro, senza impegno.'],
        ['question' => 'Fate anche riparazioni di capi non cuciti da voi?', 'answer' => 'Sì, ci occupiamo di modifiche e riparazioni su capi di qualsiasi provenienza.'],
        ['question' => 'Posso portare un tessuto mio?', 'answer' => 'Sì. Lo valutiamo insieme durante la consulenza per capire se è adatto al capo che desideri.'],
    ];
}

/* Fabric sample book (realizzazioni page). The key also names the CSS swatch. */
function fabrics(): array
{
    return [
        'lana' => ['name' => 'Lana grigia', 'composition' => '100% lana vergine', 'season' => 'Autunno e inverno', 'use' => 'Giacche e completi'],
        'cotone' => ['name' => 'Popeline azzurro', 'composition' => '100% cotone', 'season' => 'Tutto l\'anno', 'use' => 'Camicie'],
        'notte' => ['name' => 'Blu notte gessato', 'composition' => 'Lana fresca', 'season' => 'Tutto l\'anno', 'use' => 'Abiti da cerimonia e da sposo'],
        'lino' => ['name' => 'Lino naturale', 'composition' => '100% lino', 'season' => 'Primavera ed estate', 'use' => 'Giacche leggere e tailleur'],
    ];
}

/* ---------- Site search ---------- */

/* Every searchable entry: title, link (with an anchor to the exact section),
   the page it belongs to and the text to search. */
function search_index(): array
{
    $pages = site_pages();
    $items = [];

    foreach ($pages as $page) {
        $items[] = [
            'title' => $page['heading'],
            'url' => $page['url'],
            'page' => $page['title'],
            'text' => $page['lead'],
            'extra' => $page['description'] . ' ' . $page['keywords'],
        ];
    }

    $items[] = ['title' => 'Prenota una consulenza', 'url' => 'index.php#prenota', 'page' => 'Home',
        'text' => 'Compila il modulo di prenotazione: ti ricontatteremo entro due giorni lavorativi per fissare l\'appuntamento.',
        'extra' => 'prenotazione appuntamento modulo richiesta consulenza'];
    $items[] = ['title' => 'Il laboratorio', 'url' => 'atelier.php#laboratorio', 'page' => $pages['atelier']['title'],
        'text' => 'Lane, lini, sete e cotoni da tessitori italiani ed europei, taglio e cucitura eseguiti a mano, laboratorio al piano terra senza gradini.',
        'extra' => 'tessitori misure imbastitura'];

    foreach (atelier_steps() as $index => $step) {
        $items[] = ['title' => 'Fase ' . ($index + 1) . ': ' . $step['title'], 'url' => 'atelier.php#fase-' . ($index + 1),
            'page' => $pages['atelier']['title'], 'text' => $step['text'] . ' ' . $step['time'] . '.', 'extra' => 'come lavoriamo metodo'];
    }
    foreach (services() as $key => $service) {
        $details = [];
        foreach ($service['details'] as $label => $value) {
            $details[] = $label . ': ' . $value;
        }
        $items[] = ['title' => $service['name'], 'url' => 'servizi.php#servizio-' . $key, 'page' => $pages['servizi']['title'],
            'text' => $service['description'] . ' ' . implode('. ', $details) . '.', 'extra' => 'servizio prezzo preventivo'];
    }
    foreach (faqs() as $index => $faq) {
        $items[] = ['title' => $faq['question'], 'url' => 'servizi.php#domanda-' . ($index + 1), 'page' => $pages['servizi']['title'],
            'text' => $faq['answer'], 'extra' => 'domande frequenti'];
    }

    $items[] = ['title' => 'Smoking blu notte', 'url' => 'realizzazioni.php#smoking-blu-notte', 'page' => $pages['realizzazioni']['title'],
        'text' => 'Capo da cerimonia per un matrimonio serale: giacca monopetto con revers in raso, asole cucite a mano, sette settimane e tre prove.',
        'extra' => 'abito sposo cerimonia realizzazione'];
    foreach (fabrics() as $key => $fabric) {
        $items[] = ['title' => $fabric['name'], 'url' => 'realizzazioni.php#tessuto-' . $key, 'page' => $pages['realizzazioni']['title'],
            'text' => $fabric['composition'] . ', ' . mb_strtolower($fabric['season']) . '. Ideale per: ' . mb_strtolower($fabric['use']) . '.',
            'extra' => 'tessuto campionario'];
    }

    $site = site_info();
    $hours = [];
    foreach (opening_hours() as $day => $time) {
        $hours[] = $day . ': ' . mb_strtolower($time);
    }
    $items[] = ['title' => 'Recapiti', 'url' => 'contatti.php#recapiti', 'page' => $pages['contatti']['title'],
        'text' => $site['street'] . ', ' . $site['city'] . '. Telefono ' . $site['phone_label'] . ', email ' . $site['email'] . '.',
        'extra' => 'indirizzo telefono email contatti'];
    $items[] = ['title' => 'Orari di apertura', 'url' => 'contatti.php#orari', 'page' => $pages['contatti']['title'],
        'text' => implode('. ', $hours) . '.', 'extra' => 'orario aperto chiuso appuntamento'];
    $items[] = ['title' => 'Come raggiungerci', 'url' => 'contatti.php#come-raggiungerci', 'page' => $pages['contatti']['title'],
        'text' => 'A piedi dal centro storico, con tram e autobus urbani, ingresso al piano terra senza gradini adatto alla sedia a rotelle.',
        'extra' => 'tram autobus parcheggio accessibile'];
    $items[] = ['title' => 'Tasti di scelta rapida', 'url' => 'accessibilita.php#tasti-rapidi', 'page' => $pages['accessibilita']['title'],
        'text' => 'L\'elenco dei tasti per raggiungere velocemente le funzioni principali del sito.',
        'extra' => 'accesskey scorciatoie tastiera'];
    $items[] = ['title' => 'Area riservata', 'url' => 'login.php', 'page' => 'Area riservata',
        'text' => 'Accedi per vedere, modificare o eliminare le tue richieste di appuntamento, oppure crea un account.',
        'extra' => 'login accesso registrazione account richieste'];

    return $items;
}

/* Lowercase text without accents, so "liberta" also finds "libertà". */
function normalize_text(string $text): string
{
    $map = [
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
        'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
        'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', '’' => '\'',
    ];
    return strtr(mb_strtolower($text), $map);
}

/* Splits the query into search terms. Short Italian words such as "di" or "la" are
   ignored, and the final vowel of longer words is dropped so that singular and
   plural match each other ("camicia" also finds "camicie"). */
function search_terms(string $query): array
{
    $stopWords = ['di', 'da', 'in', 'su', 'il', 'lo', 'la', 'le', 'gli', 'un', 'una', 'uno', 'per', 'con', 'tra', 'fra',
        'del', 'della', 'dei', 'delle', 'al', 'alla', 'ai', 'agli', 'alle', 'come', 'che', 'non'];
    $words = preg_split('/[^\p{L}\p{N}]+/u', normalize_text($query), -1, PREG_SPLIT_NO_EMPTY);
    $terms = [];
    foreach ($words as $word) {
        if (mb_strlen($word) < 2 || in_array($word, $stopWords, true)) {
            continue;
        }
        if (mb_strlen($word) >= 5 && preg_match('/[aeiou]$/', $word)) {
            $word = mb_substr($word, 0, -1);
        }
        $terms[$word] = true;
    }
    return array_slice(array_keys($terms), 0, 8);
}

/* Returns the entries that contain every term, the best matches first. */
function search_site(array $terms): array
{
    $results = [];
    foreach (search_index() as $item) {
        $title = normalize_text($item['title']);
        $haystack = $title . ' ' . normalize_text($item['text'] . ' ' . $item['page'] . ' ' . $item['extra']);
        $score = 0;
        foreach ($terms as $term) {
            if (strpos($haystack, $term) === false) {
                continue 2;
            }
            $score += substr_count($haystack, $term) + (strpos($title, $term) !== false ? 5 : 0);
        }
        $item['score'] = $score;
        $results[] = $item;
    }
    usort($results, function (array $a, array $b): int {
        return $b['score'] <=> $a['score'];
    });
    return array_slice($results, 0, 30);
}

/* Escapes the text and marks the searched terms with <mark>, ignoring accents and case. */
function highlight_terms(string $text, array $terms): string
{
    if ($terms === []) {
        return e($text);
    }
    $accents = ['a' => '[aàáâä]', 'e' => '[eèéêë]', 'i' => '[iìíîï]', 'o' => '[oòóôö]', 'u' => '[uùúûü]'];
    $parts = [];
    foreach ($terms as $term) {
        $pattern = '';
        foreach (preg_split('//u', $term, -1, PREG_SPLIT_NO_EMPTY) as $char) {
            $pattern .= $accents[$char] ?? preg_quote($char, '/');
        }
        $parts[] = $pattern;
    }
    $pieces = preg_split('/(' . implode('|', $parts) . ')/iu', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
    $html = '';
    foreach ($pieces as $index => $piece) {
        $html .= $index % 2 === 1 ? '<mark>' . e($piece) . '</mark>' : e($piece);
    }
    return $html;
}
