<?php

/**
 * Performer model class
 *
 * @author Ilja Häkkinen
 */
class Performer extends BaseModel {

    public $id, $name, $active_years, $country, $genre, $songs_ids, $songs;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_name',
            'validate_active_years',
            'validate_country',
            'validate_genre'
        );
    }

    // read
    public static function all() {
        $sql_string = 'SELECT * FROM Performer ORDER BY name ASC';
        $rows = parent::find_all_rows_from_table($sql_string);
        $performers = array();

        foreach ($rows as $row) {
            $performers[] = self::create_new_performer($row);
        }
        return $performers;
    }

    public static function find($id) {
        $sql_string = 'SELECT * FROM Performer WHERE id = :id LIMIT 1';
        $row = parent::find_row_from_table($sql_string, $id);

        if ($row) {
            $performer = self::create_new_performer($row);
            $performer->find_associated_songs();
            return $performer;
        }
        return null;
    }

    public static function find_all_performers_with_song_id($song_id) {
        $performers = array();
        $sql_string = 'SELECT Performer.* '
                . 'FROM PerfSong '
                . 'INNER JOIN Performer ON Performer.id = PerfSong.perf_id '
                . 'WHERE PerfSong.song_id = :id;';
        $rows = parent::find_all_rows_with_id($sql_string, $song_id);

        foreach ($rows as $row) {
            $performers[] = self::create_new_performer($row);
        }
        return $performers;
    }

    // create, update, destroy
    public function save() {
        $sql_string = 'INSERT INTO Performer ('
                . 'name, active_years, country, genre'
                . ') VALUES ('
                . ':name, :active_years, :country, :genre'
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

        $sql_string = 'UPDATE Performer SET name = :name, '
                . 'active_years = :active_years, '
                . 'country = :country, '
                . 'genre = :genre '
                . 'WHERE id = :id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($attributes);

        $this->update_perfsong();
    }

    public function destroy() {
        $query = DB::connection()->prepare(
                'DELETE FROM Performer WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    // private functions
    private static function create_new_performer($row) {
        return new Performer(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'active_years' => $row['active_years'],
            'country' => $row['country'],
            'genre' => $row['genre']
        ));
    }

    private function create_array() {
        return array(
            'name' => $this->name,
            'active_years' => $this->active_years,
            'country' => $this->country,
            'genre' => $this->genre
        );
    }

    private function add_to_perfsong() {
        if (!empty($this->songs_ids) && !is_null($this->songs_ids)) {
            $song_and_perf_ids = array('perf_id' => $this->id);

            foreach ($this->songs_ids as $song_id) {
                $sql_two = 'INSERT INTO PerfSong (song_id, perf_id) VALUES ('
                        . ':song_id, :perf_id'
                        . ')';
                $query = DB::connection()->prepare($sql_two);
                $song_and_perf_ids['song_id'] = $song_id;
                $query->execute($song_and_perf_ids);
            }
        }
    }

    private function update_perfsong() {
        $query = DB::connection()->prepare(
                'DELETE FROM PerfSong WHERE perf_id = :id');
        $query->execute(array('id' => $this->id));

        $this->add_to_perfsong();
    }

    private function find_associated_songs() {
        $this->songs = Song::find_all_songs_with_perf_id($this->id);
        foreach ($this->songs as $song) {
            $this->songs_ids[] = $song->id;
        }
    }

    // validators
    public function validate_name() {
        return $this->validate_string_length('Name', $this->name, 2, 50);
    }

    public function validate_active_years() {
        $errors = array();
        $left = substr($this->active_years, 0, 4);
        $right = substr($this->active_years, -4);

        if ($this->active_years != null) {
            if (strlen($this->active_years) != 9) {
                $errors[] = 'Active years must be 9 characters long.';
            }
            if (!is_numeric($left) || !is_numeric($right)) {
                $errors[] = 'The correct format for Active years is YYYY-YYYY.';
            } else {
                if ($left > $right) {
                    $errors[] = 'The ending year cannot be smaller than the '
                            . 'founding year.';
                }
            }
        }
        return $errors;
    }

    public function validate_country() {
        return $this->validate_string_length('Country', $this->country, 2, 50);
    }

    public function validate_genre() {
        return $this->validate_string_length('Genre', $this->genre, 2, 50);
    }

}
