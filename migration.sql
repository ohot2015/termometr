USE kotel;

CREATE TABLE temperature (
    id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(id),
    temperature VARCHAR(8) NOT NULL,
    date DATETIME NOT NULL,
    rele boolean not null default 0
);
