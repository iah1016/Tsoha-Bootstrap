<?php

/**
 * Chant model class
 *
 * @author Ilja Häkkinen
 */
class Chant extends BaseModel {

    public $id, $name, $lyrics, $song, $song_object, $clubs;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_name',
            'validate_lyrics'
        );
    }

    public static function all() {
        $rows = parent::all_rows_from_table('Chant');
        $chants = array();

        foreach ($rows as $row) {
            $chants[] = self::create_new_chant($row);
        }
        return $chants;
    }

    public static function find($id) {
        $row = parent::find_row_from_table('Chant', $id);

        if ($row) {
            return self::create_new_chant($row);
        }
        return null;
    }

    public static function find_associated_chants($id) {
        $query_string = 'SELECT id, name FROM Chant WHERE song = :id';
        $query = DB::connection()->prepare($query_string);
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $chants[] = new Chant(array(
                'id' => $row['id'],
                'name' => $row['name']
            ));
        }
        return $chants;
    }

    public function save() {
        $sql_string = 'INSERT INTO Chant (name, lyrics, song) '
                . 'VALUES (:name, :lyrics, :song) '
                . 'RETURNING id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($this->create_array());
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public function update() {
        $attributes = $this->create_array();
        $attributes['id'] = $this->id;

        $sql_string = 'UPDATE Chant SET name = :name, '
                . 'lyrics = :lyrics, '
                . 'song = :song '
                . 'WHERE id = :id';
        $query = DB::connection()->prepare($sql_string);

        $query->execute($attributes);
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Chant WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

// private functions
    private static function create_new_chant($row) {
        return new Chant(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'lyrics' => $row['lyrics'],
            'song' => $row['song'],
            'song_object' => Song::find($row['song'])
        ));
    }

    private function create_array() {
        return array(
            'name' => $this->name,
            'lyrics' => $this->lyrics,
            'song' => $this->song
        );
    }

// validators
    public function validate_name() {
        return $this->validate_string_length('Name', $this->name, 2, 50);
    }

    public function validate_lyrics() {
        return $this->validate_string_length('Lyrics', $this->lyrics, 2, 500);
    }

}
