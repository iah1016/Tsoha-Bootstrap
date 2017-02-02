<?php

/**
 * Song model class
 *
 * @author Ilja Häkkinen
 */
class Song extends BaseModel {

    public $id, $name, $written_by, $year, $country, $genre;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Song');
        $query->execute();
        $rows = $query->fetchAll();
        $songs = array();

        foreach ($rows as $row) {
            $songs[] = new Song(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'written_by' => $row['written_by'],
                'year' => $row['year'],
                'country' => $row['country'],
                'genre' => $row['genre']
            ));
        }
        return $songs;
    }

    public static function find($id) {
        $query = DB::connection()->prepare(
                'SELECT * FROM Song WHERE id = :id LIMIT 1'
        );
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $song = new Song(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'written_by' => $row['written_by'],
                'year' => $row['year'],
                'country' => $row['country'],
                'genre' => $row['genre']
            ));

            return $song;
        }
        return null;
    }

    public function save() {
        $query = DB::connection()->prepare(
                'INSERT INTO Song (name, written_by, year, country, genre) '
                . 'VALUES (:name, :written_by, :year, :country, :genre) '
                . 'RETURNING id');
        $query->execute(array(
            'name' => $this->name,
            'written_by' => $this->written_by,
            'year' => $this->year,
            'country' => $this->country,
            'genre' => $this->genre
        ));
        $row = $query->fetch();

//        Kint::trace();
//        Kint::dump($row);
        $this->id = $row['id'];
    }

}
