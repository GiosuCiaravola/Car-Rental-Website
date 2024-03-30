--Creazione utente per accesso web
REVOKE ALL PRIVILEGES ON ALL TABLES IN SCHEMA public FROM www; 
REVOKE ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public FROM www;
DROP USER IF EXISTS www;
CREATE USER www WITH PASSWORD 'tsw2023';

--Creazione tabella UTENTE
DROP TABLE IF EXISTS UTENTE;

CREATE TABLE UTENTE (
    nome VARCHAR(30) NOT NULL,
    cognome VARCHAR(30) NOT NULL,
    email VARCHAR(100) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    numero_patente VARCHAR(20) NOT NULL,
    patente_auto VARCHAR(7) NOT NULL,
	patente_moto VARCHAR(7) NOT NULL,
    numero_carta VARCHAR(16),
    data_scadenza_carta DATE,
    codice_carta VARCHAR(3)
);

--Creazione tabella UTENTE
DROP TABLE IF EXISTS VEICOLO;

CREATE TABLE VEICOLO (
    nome VARCHAR(50) NOT NULL,
    targa VARCHAR(7) PRIMARY KEY,
	data_inserimento DATE NOT NULL,
    patente VARCHAR(2) NOT NULL,
    descrizione VARCHAR(255) NOT NULL,
    prezzo_al_giorno NUMERIC(5, 2),
    tipo_veicolo VARCHAR(4) NOT NULL CHECK (Tipo_veicolo IN ('Auto', 'Moto')),
    immagine VARCHAR(255) NOT NULL,
    numero_posti INTEGER NOT NULL
);

--Creazione tabella NOLEGGIO
DROP TABLE IF EXISTS NOLEGGIO;

CREATE TABLE NOLEGGIO (
    utente VARCHAR(100),
	veicolo VARCHAR(7),
    data_inizio DATE NOT NULL,
    data_fine DATE NOT NULL,
	PRIMARY KEY (utente, veicolo, data_inizio),
	FOREIGN KEY (utente) REFERENCES UTENTE(email),
	FOREIGN KEY (veicolo) REFERENCES VEICOLO(targa) ON DELETE CASCADE
);

--Assegnzione privilegi utente www
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO www; 
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO www;
