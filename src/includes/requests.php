<?php
/* Atelier Tang - requests.php
   Loads a booking request that the current user is allowed to manage. */

declare(strict_types=1);

if (!defined('APP_ROOT')) {
    http_response_code(404);
    exit;
}

/* Returns the request when it exists and belongs to the user (administrators can
   manage every request). Otherwise it shows a message in the reserved area. */
function find_manageable_request(int $id, array $user): array
{
    $request = false;
    if ($id > 0) {
        try {
            $statement = db()->prepare(
                'SELECT p.*, s.nome AS servizio_nome
                 FROM prenotazioni p
                 JOIN servizi s ON s.codice = p.servizio
                 WHERE p.id = :id'
            );
            $statement->execute([':id' => $id]);
            $request = $statement->fetch();
        } catch (PDOException $exception) {
            error_log('Atelier Tang - request not loaded: ' . $exception->getMessage());
            flash('errore', 'Non è stato possibile caricare la richiesta per un problema tecnico. Riprova più tardi.');
            redirect('area.php');
        }
    }

    $isOwner = $request !== false && (int) $request['utente_id'] === (int) $user['id'];
    if ($request === false || (!$isOwner && ($user['ruolo'] ?? '') !== 'admin')) {
        flash('errore', 'La richiesta non esiste oppure non puoi gestirla.');
        redirect('area.php');
    }

    return $request;
}
