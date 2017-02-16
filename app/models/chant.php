<?php

/**
 * Chant model class
 *
 * @author Ilja Häkkinen
 */
class Chant extends BaseModel {

    public $id, $name, $lyrics, $song, $clubs;

    public function __construct($attributes) {
        parent::__construct($attributes);
//        $this->validators = array(
//            'validate_name'
//        );
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Chant');
        $query->execute();
        $rows = $query->fetchAll();
        $chants = array();

        foreach ($rows as $row) {
            $chants[] = self::create_new_chant($row);
        }
        return $chants;
    }

    public static function find($id) {
        $sql_string = 'SELECT * FROM Chant WHERE id = :id LIMIT 1';
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $chant = self::create_new_chant($row);
//            $chant->clubs = Clubs::;
            
            return $chant;
        }

        return null;
    }

//    public function save() {
//        $sql_string = 'INSERT INTO Chant (name, lyrics, song) '
//                . 'VALUES (:name, :lyrics, :song) '
//                . 'RETURNING id';
//        $query = DB::connection()->prepare($sql_string);
//        $query->execute($this->create_array());
//        $row = $query->fetch();
//
//        $this->id = $row['id'];
//    }
//
//    public function update() {
//        $attributes = $this->create_array();
//        $attributes['id'] = $this->id;
//
//        $sql_string = 'UPDATE Chant SET name = :name jne.';
//        $query = DB::connection()->prepare($sql_string);
//
//        $query->execute($attributes);
//    }
//
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
            'song' => Song::find($row['song'])
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
}
