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
        $this->validators = array(
            'validate_name',
            'validate_written_by',
            'validate_year',
            'validate_country',
            'validate_genre');
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
        $query->execute($this->create_array());
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public function update() {
        $attributes = $this->create_array();
        $attributes['id'] = $this->id;

        $query = DB::connection()->prepare('UPDATE Song SET '
                . 'name = :name, '
                . 'written_by = :written_by, '
                . 'year = :year, '
                . 'country = :country, '
                . 'genre = :genre '
                . 'WHERE id = :id');
        
        $query->execute($attributes);        
//        $row = $query->fetch();
//        Kint::dump($row);
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Song WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

    private function create_array() {
        return array(
            'name' => $this->name,
            'written_by' => $this->written_by,
            'year' => $this->year,
            'country' => $this->country,
            'genre' => $this->genre
        );
    }

    public function validate_name() {
        return $this->validate_string_length('Name', $this->name, 2);
    }

    public function validate_written_by() {
        return $this->validate_string_length('Composer', $this->written_by, 2);
    }

    public function validate_year() {
        return $this->validate_numeric('Year', $this->year);
    }

    public function validate_country() {
        return $this->validate_string_length('Country', $this->country, 2);
    }

    public function validate_genre() {
        return $this->validate_string_length('Genre', $this->genre, 2);
    }

}
