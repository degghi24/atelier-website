-- Atelier Tang - database schema and test data (MariaDB / MySQL, utf8mb4).
--
-- Docker imports this file automatically the first time the database starts.
-- With XAMPP: create an empty database named "atelier_tang" in phpMyAdmin
-- (collation utf8mb4_unicode_ci) and import this file into it.
--
-- The script does not drop existing tables: importing it twice stops with an
-- error instead of deleting data.
--
-- Test accounts: admin / admin (administrator) and user / user (customer).

SET NAMES utf8mb4;

CREATE TABLE utenti (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  nome VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  ruolo ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  creato_il TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_utenti_username (username),
  UNIQUE KEY uq_utenti_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services that can be booked. codice matches the keys of src/includes/services.php,
-- which also holds the descriptions shown on the services page.
CREATE TABLE servizi (
  codice VARCHAR(30) NOT NULL,
  nome VARCHAR(80) NOT NULL,
  PRIMARY KEY (codice),
  UNIQUE KEY uq_servizi_nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- utente_id is NULL for requests sent without logging in. nome, email and telefono
-- are the contact details given for this request, which may differ from the account.
CREATE TABLE prenotazioni (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  utente_id INT UNSIGNED NULL,
  servizio VARCHAR(30) NOT NULL,
  nome VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  telefono VARCHAR(20) NULL,
  data_preferita DATE NOT NULL,
  messaggio TEXT NOT NULL,
  stato ENUM('in_attesa', 'confermata', 'annullata') NOT NULL DEFAULT 'in_attesa',
  creata_il TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_prenotazioni_utente (utente_id),
  KEY idx_prenotazioni_servizio (servizio),
  KEY idx_prenotazioni_stato_data (stato, data_preferita),
  CONSTRAINT fk_prenotazioni_utente FOREIGN KEY (utente_id)
    REFERENCES utenti (id) ON DELETE CASCADE,
  CONSTRAINT fk_prenotazioni_servizio FOREIGN KEY (servizio)
    REFERENCES servizi (codice) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Passwords are stored with PHP password_hash() (bcrypt).
INSERT INTO utenti (id, username, nome, email, password_hash, ruolo) VALUES
  (1, 'admin', 'Amministrazione Atelier', 'admin@ateliertang.example',
   '$2y$10$wFUZ7vybs2J6XPQNae52HuJwfu1eShoh.nj9K9L9HX/aL.wowgGSS', 'admin'),
  (2, 'user', 'Giulia Rossi', 'user@ateliertang.example',
   '$2y$10$gr7iillWxkeE.a/jBaieKOXA8NN.N7HSkwhVLM3UgItMVhJEcXyEy', 'user');

INSERT INTO servizi (codice, nome) VALUES
  ('suit', 'Abiti e giacche su misura'),
  ('shirt', 'Camicie su misura'),
  ('ceremony', 'Abiti da cerimonia e da sposo'),
  ('womenswear', 'Abiti da donna'),
  ('alteration', 'Modifiche e riparazioni'),
  ('fabric-consultation', 'Consulenza sui tessuti');

INSERT INTO prenotazioni (utente_id, servizio, nome, email, telefono, data_preferita, messaggio, stato) VALUES
  (2, 'womenswear', 'Giulia Rossi', 'user@ateliertang.example', '+39 333 000 0000', '2027-01-19',
   'Vorrei un tailleur blu in lana leggera per la primavera, da indossare al lavoro.', 'in_attesa'),
  (2, 'alteration', 'Giulia Rossi', 'user@ateliertang.example', NULL, '2026-12-15',
   'Devo accorciare due pantaloni e stringere una giacca in vita.', 'confermata'),
  (NULL, 'ceremony', 'Marco Bianchi', 'marco.bianchi@example.com', '049 000 1111', '2027-03-06',
   'Mi sposo a giugno: cerco un abito da sposo blu notte con gilet coordinato.', 'in_attesa');
