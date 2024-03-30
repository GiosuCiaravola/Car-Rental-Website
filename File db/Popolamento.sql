--Query per svuotare le tabelle
DELETE FROM NOLEGGIO
DELETE FROM VEICOLO
DELETE FROM UTENTE

--Inserimento Auto
INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Toyota Camry 2023', 'AB594HE', '2023-02-08', 'B', 'Un''elegante berlina che coniuga stile e affidabilità. Dotata di tecnologie innovative, comfort premium e un''efficienza straordinaria, la Camry offre un''esperienza di guida raffinata e sicura. Un''icona dell''equilibrio tra prestazioni ed economia.', 50.00, 'Auto', 'Immagini autonoleggio/toyota camry 2023.png', 5);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Ford Mustang', 'CJ554JE', '2023-03-08', 'B', 'Un''iconica muscle car che unisce potenza e fascino senza compromessi. Con il suo design aggressivo e le prestazioni eccezionali, la Mustang offre un''esperienza di guida emozionante e unica. Un simbolo di libertà e adrenalina sulla strada.', 90.00, 'Auto', 'Immagini autonoleggio/fordmustang.png', 4);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Mini Morris Mark II', 'RNT996H', '1990-01-01', 'B', 'Un''auto iconica e divertente che ha affascinato il pubblico mondiale. Con il suo carattere unico e le stravaganti avventure di Mr. Bean al volante, la Mark II ha conquistato il cuore di tutti.', 200.00, 'Auto', 'Immagini autonoleggio/mini morris mark 2.png', 4);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Millennium Falcon', 'ST300AR', '1999-09-17', 'B', 'L''epico velivolo spaziale della saga di Star Wars su ruote. Con il suo design distintivo e la sua reputazione di astronave veloce e resistente. Un''icona della ribellione e delle avventure intergalattiche, simbolo di coraggio e audacia.', 999.99, 'Auto', 'Immagini autonoleggio/millennium falcon.png', 500);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Volkswagen Golf', 'JD306NS', '2023-03-17', 'B', 'compatta, elegante e versatile. Un''icona dell''automobilismo con prestazioni affidabili, tecnologia all''avanguardia e un design intramontabile. Il perfetto equilibrio tra stile e funzionalità.', 50.00, 'Auto', 'Immagini autonoleggio/wolksvagen golf.png', 5);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Saetta MC Queen', 'SM095QA', '2006-08-23', 'B', 'Affascinante e veloce bolide rosso, protagonista dell''amato film d''animazione "Cars". Con il suo numero 95 e i dettagli affusolati, rappresenta la quintessenza della velocità e dell''audacia. Determinato a vincere le gare, Saetta non si arrende mai', 30.00, 'Auto', 'Immagini autonoleggio/saetta mcqueen.png', 1);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('313', 'NV313AG', '1939-08-09', 'B', 'Vecchia auto piena di carattere. Con la sua carrozzeria malconcia e sgangherata, è la fedele compagna di avventure di Paperino. Nonostante i suoi difetti, la 313 è sempre pronta a portare il suo proprietario in giro per il mondo', 313.00, 'Auto', 'Immagini autonoleggio/313 paperino.png', 4);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Fiat Panda', 'GV142KM', '2023-05-12', 'B', 'Iconica city car italiana, compatta e versatile. Con il suo design è perfetta per la guida urbana. La Panda offre praticità, economia di carburante e un''ampia visibilità, garantendo comfort sia per il conducente che per i passeggeri.', 40.00, 'Auto', 'Immagini autonoleggio/panda.png', 4);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Ferrari', 'PD492DC', '2001-09-11', 'B', 'Epitome della performance e dell''eleganza nel mondo dell''automobilismo. Con il suo design sinuoso, la potenza del suo motore e la sua inconfondibile grinta, la Ferrari incarna la passione per la velocità e l''arte dell''ingegneria.', 250.00, 'Auto', 'Immagini autonoleggio/ferrari.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Ford Anglia', 'TD790HP', '1999-12-12', 'B', 'Un''auto magica e affascinante. Con la sua carrozzeria blu brillante e gli elementi retrò, incarna l''avventura e il mistero. Dotata di ali pieghevoli e capacità di volo, trasporta Harry e Ron in incredibili avventure nel mondo dei maghi.', 250.00, 'Auto', 'Immagini autonoleggio/fordanglia.png', 2);

--Inserimento Moto
INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Yamaha YZF-R1', 'GH72047', '2023-05-16', 'A', 'Una supersportiva leggendaria. Linee aggressive, potenza sorprendente e prestazioni senza rivali. Tecnologia all''avanguardia per una guida emozionante. Un''esperienza di corsa pura che cattura l''anima dei veri appassionati di motociclismo.', 70.00, 'Moto', 'Immagini autonoleggio/yamahayzfr1.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Honda CBR1000RR', 'ND73943', '2023-04-16', 'A', 'Una superbike di classe mondiale. Design aerodinamico, potenza esplosiva e maneggevolezza precisa. Tecnologia avanzata per massimizzare le prestazioni su strada e pista. L''incarnazione dell''adrenalina pura e dell''eccellenza ingegneristica.', 75.00, 'Moto', 'Immagini autonoleggio/hondacbr1000rr.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Kawasaki Ninja ZX-10R', 'MS93341', '2023-04-16', 'A', 'Un''iper sportiva ineguagliabile. Linee affilate, potenza sbalorditiva e agilità straordinaria. Dotata di tecnologia di ultima generazione per una guida adrenalinica. Un''esperienza senza compromessi che mette in risalto il dominio di Kawasaki nel mondo.', 65.00, 'Moto', 'Immagini autonoleggio/kawasakininja.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Yamaha MT-03', 'SS92841', '2023-04-26', 'A2', 'Una naked bike dinamica ed emozionante. Design audace, prestazioni vivaci e maneggevolezza eccezionale. Equipaggiata con tecnologia avanzata per una guida agile e divertente. Un''esperienza di guida senza compromessi.', 40.00, 'Moto', 'Immagini autonoleggio/yamahamt03.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Benelli BN 302', 'MD92491', '2023-04-26', 'A2', 'Una naked bike dalle linee eleganti. Design italiano distintivo, prestazioni vivaci e maneggevolezza agile. Dotata di un motore potente e affidabile, garantisce un''esperienza di guida emozionante. Un connubio perfetto tra stile e prestazioni.', 35.00, 'Moto', 'Immagini autonoleggio/benellibn302.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Beverly 300', 'XT24953', '2023-01-26', 'A2', 'Un elegante scooter dalla personalità raffinata. Design italiano sofisticato, prestazioni fluide e agilità. Con un motore potente e affidabile, offre una guida comoda e dinamica. Dotato di ampi spazi di stivaggio, la scelta ideale per la città.', 30.00, 'Moto', 'Immagini autonoleggio/beverly300.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Yamaha R125', 'VW84828', '2023-04-02', 'A1', 'Supersportiva compatta, prestazioni vivaci, maneggevolezza precisa. Design aggressivo, tecnologia avanzata. L''esperienza di guida perfetta per i giovani appassionati delle due ruote.', 25.00, 'Moto', 'Immagini autonoleggio/yamahar125.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Honda SH125', 'MD59389', '2023-02-07', 'A1', 'Scooter elegante, prestazioni efficienti, maneggevolezza aggraziata. Design raffinato, tecnologia all''avanguardia. L''esperienza di guida ideale per la città, unendo stile e praticità in modo impeccabile.', 20.00, 'Moto', 'Immagini autonoleggio/hondash125.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Piaggio Liberty 50', 'PS59349', '2023-05-07', 'AM', 'Scooter agile, prestazioni affidabili, maneggevolezza semplice. Design classico, adatto per la città. Un compagno di viaggio pratico e conveniente per gli spostamenti urbani, garantendo comfort e facilità di guida.', 15.00, 'Moto', 'Immagini autonoleggio/liberty50.png', 2);

INSERT INTO VEICOLO (nome, targa, data_inserimento, patente, descrizione, prezzo_al_giorno, tipo_veicolo, immagine, numero_posti)
VALUES ('Piaggio Apecar 50', 'MD59493', '2023-02-07', 'AM', 'Veicolo compatto, versatilità pratica, facilità di manovra. Design funzionale, ideale per il trasporto leggero. Soluzione efficiente per le necessità di mobilità urbana, offrendo praticità e convenienza. Tony Tammaro incluso nel prezzo.', 10.00, 'Moto', 'Immagini autonoleggio/apecar.png', 10);


--In caso non dovesse funzionare il backup, di seguito è presente l'inserimento degli utenti con alcuni rispettivi noleggi
--Inserimento Utenti
INSERT INTO UTENTE (nome, cognome, email, password, numero_patente, patente_auto, patente_moto)
VALUES ('GMCNRent', 'GMCNRent', 'admin@gmail.com', '$2y$10$rrfqFySmTaM9jdoYLCikcutD7UJSopPinzyaL1a94uHfWhcfIxEpm', 'AA1111111A', 'B', 'A')

INSERT INTO UTENTE (nome, cognome, email, password, numero_patente, patente_auto, patente_moto, numero_carta, data_scadenza_carta, codice_carta)
VALUES ('Nunzio', 'Del Gaudio', 'nunziodelgaudio@libero.it', '$2y$10$zUPEgFvCq4jGNtjmq7uEfeINyATWd8KsuvNxxi9to7BF87IvtXy3C', 'AA2222222B', 'Nessuna', 'A2', '1111111111111111', '2023-12-01', '111')

INSERT INTO UTENTE (nome, cognome, email, password, numero_patente, patente_auto, patente_moto, numero_carta, data_scadenza_carta, codice_carta)
VALUES ('Giosuè', 'Ciaravola', 'giosueciaravola@gmail.com', '$2y$10$1YhaA7l4vUd0RjoRonEFV.cQy7co8fY3j3P0TiM4IrmEOtkHvL9gu', 'SA5819560J', 'B', 'A', '2222222222222222', '2023-12-01', '222')

INSERT INTO UTENTE (nome, cognome, email, password, numero_patente, patente_auto, patente_moto, numero_carta, data_scadenza_carta, codice_carta)
VALUES ('Christian', 'Conato', 'christianconato123@gmail.com', '$2y$10$U0fW00CYZ3c/jWG5HIuONu1A8gaHfzRSDckicZgFt0otCrN5f5snW', 'AC1234567G', 'B', 'Nessuna', '3333333333333333', '2023-12-01', '333')

--Inserimento Noleggi
INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('nunziodelgaudio@libero.it', 'MD59493', '2023-06-04', '2023-06-05')

INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('nunziodelgaudio@libero.it', 'SS92841', '2023-06-05', '2023-06-10')

INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('nunziodelgaudio@libero.it', 'MD59493', '2023-06-10', '2023-06-12')

INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('giosueciaravola@gmail.com', 'SM095QA', '2023-06-06', '2023-06-11')

INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('giosueciaravola@gmail.com', 'MS93341', '2023-06-06', '2023-06-08')

INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('christianconato123@gmail.com', 'CJ554JE', '2023-06-06', '2023-06-08')

INSERT INTO NOLEGGIO (utente, veicolo, data_inizio, data_fine)
VALUES ('christianconato123@gmail.com', 'ST300AR', '2023-06-04', '2023-06-06')























