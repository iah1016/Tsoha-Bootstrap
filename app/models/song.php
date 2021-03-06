<?php

/**
 * Song model class
 *
 * @author Ilja Häkkinen
 */
class Song extends BaseModel {

    public $id, $name, $written_by, $year, $country, $genre, $ytube_id,
            $chants, $perfs_ids, $performers;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_name',
            'validate_written_by',
            'validate_year',
            'validate_country',
            'validate_genre',
            'validate_ytube_string'
        );
    }

    // read
    public static function all() {
        $sql_string = 'SELECT * FROM Song ORDER BY name ASC';
        $rows = parent::find_all_rows_from_table($sql_string);
        $songs = array();

        foreach ($rows as $row) {
            $songs[] = self::create_new_song($row);
        }
        return $songs;
    }

    public static function find($id) {
        $sql_string = 'SELECT * FROM Song WHERE id = :id LIMIT 1';
        $row = parent::find_row_from_table($sql_string, $id);

        if ($row) {
            $song = self::create_new_song($row);
            $song->find_associated_chants_and_performers();
            return $song;
        }
        return null;
    }

    public static function find_all_songs_with_perf_id($perf_id) {
        $songs = array();
        $sql_string = 'SELECT Song.* '
                . 'FROM PerfSong '
                . 'INNER JOIN Song ON Song.id = PerfSong.song_id '
                . 'WHERE PerfSong.perf_id = :id;';
        $rows = parent::find_all_rows_with_id($sql_string, $perf_id);

        foreach ($rows as $row) {
            $songs[] = self::create_new_song($row);
        }
        return $songs;
    }

    // create, update, destroy
    public function save() {
        $sql_string = 'INSERT INTO Song ('
                . 'name, written_by, year, country, genre, ytube_id'
                . ') VALUES ('
                . ':name, :written_by, :year, :country, :genre, :ytube_id'
                . ') RETURNING id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($this->create_array());
        $row = $query->fetch();
        $this->id = $row['id'];

        $this->add_to_perfsong();
    }

    public function update() {
        $attributes = $this->create_array();
        $attributes['id'] = $this->id;

        $sql_string = 'UPDATE Song SET name = :name, '
                . 'written_by = :written_by, '
                . 'year = :year, '
                . 'country = :country, '
                . 'genre = :genre, '
                . 'ytube_id = :ytube_id '
                . 'WHERE id = :id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($attributes);

        $this->update_perfsong();
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Song WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    // private functions
    private static function create_new_song($row) {
        return new Song(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'written_by' => $row['written_by'],
            'year' => $row['year'],
            'country' => $row['country'],
            'genre' => $row['genre'],
            'ytube_id' => $row['ytube_id']
        ));
    }

    private function create_array() {
        $this->use_default_youtube_video_if_null();

        return array(
            'name' => $this->name,
            'written_by' => $this->written_by,
            'year' => $this->year,
            'country' => $this->country,
            'genre' => $this->genre,
            'ytube_id' => $this->ytube_id
        );
    }

    private function add_to_perfsong() {
        if (!empty($this->perfs_ids) && !is_null($this->perfs_ids)) {
            $song_and_perf_ids = array('song_id' => $this->id);

            foreach ($this->perfs_ids as $perf_id) {
                $sql_two = 'INSERT INTO PerfSong (song_id, perf_id) VALUES ('
                        . ':song_id, :perf_id'
                        . ')';
                $query = DB::connection()->prepare($sql_two);
                $song_and_perf_ids['perf_id'] = $perf_id;
                $query->execute($song_and_perf_ids);
            }
        }
    }

    private function update_perfsong() {
        $query = DB::connection()->prepare(
                'DELETE FROM PerfSong WHERE song_id = :id');
        $query->execute(array('id' => $this->id));

        $this->add_to_perfsong();
    }

    private function find_associated_chants_and_performers() {
        $this->chants = Chant::find_associated_chants_with_song_id($this->id);
        $this->performers = Performer::find_all_performers_with_song_id($this->id);
        foreach ($this->performers as $performer) {
            $this->perfs_ids[] = $performer->id;
        }
    }

    private function use_default_youtube_video_if_null() {
        if ($this->ytube_id == null) {
            $this->ytube_id = 'g4mHPeMGTJM';
        }
    }

    // validators

    public function validate_name() {
        return $this->validate_string_length('Name', $this->name, 2, 50);
    }

    public function validate_written_by() {
        return $this->validate_string_length(
                        'Composer', $this->written_by, 2, 50);
    }

    public function validate_year() {
        return $this->validate_numeric('Year', $this->year);
    }

    public function validate_country() {
        return $this->validate_string_length('Country', $this->country, 2, 50);
    }

    public function validate_genre() {
        return $this->validate_string_length('Genre', $this->genre, 2, 50);
    }

    public function validate_ytube_string() {
        $errors = array();

        if ($this->ytube_id != null) {
            if (strlen($this->ytube_id) != 11) {
                $errors[] = 'YouTube video ID must be 11 characters long.';
            }
            if (!preg_match("/[a-zA-Z0-9_-]{11}/", $this->ytube_id)) {
                $errors[] = 'Invalid YouTube video ID';
            }
        }
        return $errors;
    }

}
