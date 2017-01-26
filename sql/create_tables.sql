
CREATE TABLE SignedUser(
    id SERIAL PRIMARY KEY,
    username varchar(50) NOT NULL,
    password varchar(50) NOT NULL
);

CREATE TABLE Song(
    id SERIAL PRIMARY KEY,
    name varchar(50) NOT NULL,
    written_by varchar(50),
    year integer,
    country varchar(50),
    genre varchar(50)
);

CREATE TABLE Performer(
    id SERIAL PRIMARY KEY,
    name varchar(50) NOT NULL,
    active_years varchar(9),
    country varchar(50),
    genre varchar(50)
);

CREATE TABLE Club(
    id SERIAL PRIMARY KEY,
    name varchar(50) NOT NULL,
    country varchar(50),
    league varchar(50)
);

CREATE TABLE Chant(
    id SERIAL PRIMARY KEY,
    name varchar(50) NOT NULL,
    lyrics varchar(500),
    song integer REFERENCES Song(id)
);

CREATE TABLE PerfSong(
    id SERIAL PRIMARY KEY,
    song_id integer REFERENCES Song(id),
    perf_id integer REFERENCES Performer(id)
);

CREATE TABLE ClubChant(
    id SERIAL PRIMARY KEY,
    chant_id integer REFERENCES Chant(id),
    club_id integer REFERENCES Club(id)
);