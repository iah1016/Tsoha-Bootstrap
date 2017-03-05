<?php

/**
 * Chant model class
 *
 * @author Ilja Häkkinen
 */
class Chant extends BaseModel {

    public $id, $name, $lyrics, $song, $song_object, $clubs_ids, $clubs;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_name',
            'validate_lyrics'
        );
    }

    // read
    public static function all() {
        $rows = parent::find_all_rows_from_table('Chant');
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

    public static function find_associated_chants_with_song_id($id) {
        $sql_string = 'SELECT id, name FROM Chant WHERE song = :id';
        $rows = parent::find_all_rows_with_id($sql_string, $id);
        $chants = array();

        foreach ($rows as $row) {
            $chants[] = new Chant(array(
                'id' => $row['id'],
                'name' => $row['name']
            ));
        }
        return $chants;
    }

    public static function find_all_chants_with_club_id($club_id) {
        $chants = array();
        $sql_string = 'SELECT Chant.* '
                . 'FROM ClubChant '
                . 'INNER JOIN Chant ON Chant.id = ClubChant.chant_id '
                . 'WHERE ClubChant.club_id = :id;';
        $rows = parent::find_all_rows_with_id($sql_string, $club_id);

        foreach ($rows as $row) {
            $chants[] = self::create_new_chant($row);
        }
        return $chants;
    }

    // create, update, destroy
    public function save() {
        $sql_string = 'INSERT INTO Chant ('
                . 'name, lyrics, song'
                . ') VALUES ('
                . ':name, :lyrics, :song'
                . ') RETURNING id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($this->create_array());
        $row = $query->fetch();
        $this->id = $row['id'];

        $this->add_to_clubchant();
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
        
        $this->update_clubchant();
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
            'song_object' => Song::find($row['song']),
            'clubs' => Club::find_all_clubs_with_chant_id($row['id'])
        ));
    }

    private function create_array() {
        return array(
            'name' => $this->name,
            'lyrics' => $this->lyrics,
            'song' => $this->song
        );
    }
    
    private function add_to_clubchant() {
        if (!empty($this->clubs_ids) && !is_null($this->clubs_ids)) {
            $chant_and_club_ids = array('chant_id' => $this->id);

            foreach ($this->clubs_ids as $club_id) {
                $sql_two = 'INSERT INTO ClubChant (chant_id, club_id) VALUES ('
                        . ':chant_id, :club_id'
                        . ')';
                $query = DB::connection()->prepare($sql_two);
                $chant_and_club_ids['club_id'] = $club_id;
                $query->execute($chant_and_club_ids);
            }
        }
    }

    private function update_clubchant() {
        if (!empty($this->clubs_ids) && !is_null($this->clubs_ids)) {
            $query = DB::connection()->prepare(
                    'DELETE FROM ClubChant WHERE chant_id = :id');
            $query->execute(array('id' => $this->id));
            
            $this->add_to_clubchant();
        }
    }

    // validators
    public function validate_name() {
        return $this->validate_string_length('Name', $this->name, 2, 50);
    }

    public function validate_lyrics() {
        return $this->validate_string_length('Lyrics', $this->lyrics, 2, 500);
    }

}
