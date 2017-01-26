
INSERT INTO SignedUser (username, password) VALUES ('pekka', 'salasana');
INSERT INTO SignedUser (username, password) VALUES ('urpo', '1234');
INSERT INTO Song (name, written_by, year, country, genre) VALUES ('This Is How It Feels', 'Clint Boon', 1999, 'United Kingdom', 'alt. rock, Madchester');
INSERT INTO Performer (name, active_years, country, genre) VALUES ('Inspiral Carpets', '1983-1995', 'United Kingdom', 'alt. rock, Madchester');
INSERT INTO Club (name, country, league) VALUES ('Manchester United FC', 'United Kingdom', 'Premier League');
INSERT INTO Chant (name, lyrics, song) VALUES ('This Is How It Feels to Be City', 'diipa daapa', 1);
INSERT INTO PerfSong (song_id, perf_id) VALUES (1, 1);
INSERT INTO ClubChant (chant_id, club_id) VALUES (1, 1);