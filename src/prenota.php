<?php
/* Atelier Tang - prenota.php
   Processes the booking form (Post/Redirect/Get): validates the data on the server,
   saves the request and sends the visitor back to the form with the outcome. */

declare(strict_types=1);

require __DIR__ . '/includes/bootstrap.php';

if (!is_post()) {
    redirect('index.php#prenota');
}

$fields = ['full_name', 'email', 'phone', 'service', 'preferred_date', 'message', 'privacy_consent'];
$input = [];
foreach ($fields as $field) {
    $input[$field] = post_string($field);
}

if (!csrf_valid()) {
    remember_form('booking', [], $input);
    flash('errore', 'La sessione è scaduta. Controlla i dati e invia di nuovo la richiesta.');
    redirect('index.php#esito');
}

list($data, $errors) = validate_fields($fields, $input);
if ($errors !== []) {
    remember_form('booking', $errors, $input);
    redirect('index.php#booking-form');
}

$user = current_user();

try {
    $statement = db()->prepare(
        'INSERT INTO prenotazioni (utente_id, servizio, nome, email, telefono, data_preferita, messaggio)
         VALUES (:utente_id, :servizio, :nome, :email, :telefono, :data_preferita, :messaggio)'
    );
    $statement->execute([
        ':utente_id' => $user !== null ? $user['id'] : null,
        ':servizio' => $data['service'],
        ':nome' => $data['full_name'],
        ':email' => $data['email'],
        ':telefono' => $data['phone'] !== '' ? $data['phone'] : null,
        ':data_preferita' => $data['preferred_date'],
        ':messaggio' => $data['message'],
    ]);
} catch (PDOException $exception) {
    error_log('Atelier Tang - booking not saved: ' . $exception->getMessage());
    remember_form('booking', [], $input);
    flash('errore', 'Non è stato possibile inviare la richiesta per un problema tecnico. Riprova più tardi oppure telefonaci allo 049 000 0000.');
    redirect('index.php#esito');
}

$message = 'Grazie, ' . $data['full_name'] . '! Abbiamo ricevuto la tua richiesta: ti ricontatteremo entro due giorni lavorativi.';
if ($user !== null) {
    $message .= ' Puoi seguirla dalla tua area riservata.';
}
flash('successo', $message);
redirect('index.php#esito');
