<?php

/**
 * Performer model class
 *
 * @author Ilja Häkkinen
 */
class Performer extends BaseModel {

    public $id, $name, $active_years, $country, $genre;

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
        $rows = parent::all_rows_from_table('Performer');
        $performers = array();

        foreach ($rows as $row) {
            $performers[] = self::create_new_performer($row);
        }
        return $performers;
    }

    public static function find($id) {
        $row = parent::find_row_from_table('Performer', $id);

        if ($row) {
            return self::create_new_performer($row);
        }
        return null;
    }

    public static function find_all_where_id_in($ids) {
        $performers = array();
        if (empty($ids)) {
            return $performers;
        }
        $rows = parent::all_rows_where_id_in('Performer', $ids);

        foreach ($rows as $row) {
            $performers[] = self::create_new_performer($row);
        }
        return $performers;
    }

    // create, update, destroy
    public function save() {
        $sql_string = 'INSERT INTO Performer ('
                . 'name, active_years, country, genre) '
                . 'VALUES ('
                . ':name, :active_years, :country, :genre) '
                . 'RETURNING id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($this->create_array());
        $row = $query->fetch();

        $this->id = $row['id'];
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
