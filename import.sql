CREATE USER IF NOT EXISTS 'Isselwearde'@'%' IDENTIFIED BY 'URsMW9Uiq0NIIw*k';

GRANT ALL PRIVILEGES ON `Isselwearde`.* TO 'Isselwearde'@'%';

DROP DATABASE IF EXISTS `Isselwearde`;

CREATE DATABASE `Isselwearde`;

USE `Isselwearde`;

CREATE TABLE `Bewoners` (
    id MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(100) NOT NULL,
    woningnummer DECIMAL(5) NULL,
    activiteiten varchar(3),
    bijzonderheden TEXT NULL
);

INSERT INTO `Bewoners` (naam, woningnummer, activiteiten, bijzonderheden) VALUES
    ('Piet Andersma', 84, 'ja', NULL),
    ('Ricardo van Orange', 1, 'nee', 'dementerend en valgevaar'),
    ('Aarie de Jong', 54, 'ja', NULL),
    ('Minke de Bloem', 23, 'nee', 'dementerend'),
    ('Johnny Flip', 65, 'ja', NUll),
    ('Jan Alleman', 44, 'nee', 'valgevaar'),
    ('Thomas van Dijk', 110, 'ja', NULL),
    ('Lisa Hendriks', 120, 'nee', NULL),
    ('Johan Brouwer', 130, 'ja', 'dementerend'),
    ('Caroline van der Meer', 140, 'nee', 'valgevaar'),
    ('Richard Koning', 150, 'ja', NULL),
    ('Anne de Ruiter', 160, 'nee', 'dementerend'),
    ('Mark van den Berg', 170, 'ja', 'valgevaar'),
    ('Sandra van Dijk', 180, 'nee', NULL),
    ('Erik Visser', 190, 'ja', 'dementerend'),
    ('Nathalie Vos', 200, 'nee', NULL),
    ('Alexandra de Boer', 210, 'ja', 'valgevaar'),
    ('Willem Bakker', 220, 'nee', NULL),
    ('Laura van der Linden', 230, 'ja', NULL),
    ('Arjen Hoekstra', 240, 'nee', 'valgevaar'),
    ('Melissa Jansen', 250, 'ja', 'dementerend'),
    ('Steven de Vries', 260, 'nee', NULL),
    ('Cynthia van der Horst', 270, 'ja', 'valgevaar'),
    ('Ramon Janssen', 280, 'nee', NULL),
    ('Anouk van Dijk', 290, 'ja', 'dementerend'),
    ('Martijn Hendriks', 300, 'nee', NULL),
    ('Sylvia Bakker', 310, 'ja', 'valgevaar'),
    ('Vincent de Jong', 320, 'nee', NULL),
    ('Sophie van der Meer', 330, 'ja', 'dementerend'),
    ('Jeroen Koning', 340, 'nee', NULL),
    ('Marieke Jansen', 350, 'ja', NULL),
    ('Robin de Vries', 360, 'nee', 'valgevaar'),
    ('Manon van Leeuwen', 370, 'ja', NULL),
    ('Robert Bakker', 380, 'nee', NULL),
    ('Anja Jacobs', 390, 'ja', 'dementerend'),
    ('Erik van Dijk', 400, 'nee', NULL),
    ('Mariska Smit', 410, 'ja', 'valgevaar'),
    ('Peter de Boer', 420, 'nee', NULL),
    ('Maartje de Jong', 430, 'ja', NULL),
    ('Martijn Vos', 440, 'nee', 'valgevaar'),
    ('Laura Bakker', 450, 'ja', 'dementerend');


CREATE TABLE Gebruikers (
    ID INT AUTO_INCREMENT,
    Gebruikersnaam VARCHAR(255) NOT NULL,
    Wachtwoord VARCHAR(255) NOT NULL,
    PRIMARY KEY (ID)
);

INSERT INTO Gebruikers (Gebruikersnaam, Wachtwoord)
VALUES ('test-user', 'wachtwoord');

CREATE TABLE activiteiten (
    activiteit_id INT AUTO_INCREMENT PRIMARY KEY,
    Activiteit VARCHAR(255) NOT NULL,
    Deelnemers TEXT
);

INSERT INTO Activiteiten (Activiteit, deelnemers) VALUES
('Cricket', '["Piet Andersma", "Ramon Janssen", "Steven de Vries"]'),
('Golf', '["Piet Andersma", "Ricardo van Orange", "Alexandra de Boer"]');
