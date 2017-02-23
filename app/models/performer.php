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

    public static function all() {
        $rows = parent::all_rows_from_table('Performer');
        $clubs = array();

        foreach ($rows as $row) {
            $clubs[] = self::create_new_performer($row);
        }
        return $clubs;
    }

    public static function find($id) {
        $row = parent::find_row_from_table('Performer', $id);

        if ($row) {
            return self::create_new_performer($row);
        }
        return null;
    }

// save
// update

    public function destroy() {
        $query = DB::connection()->prepare(
                'DELETE FROM Performer WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

// private functions

    private static function create_new_performer($row) {
        return new Club(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'active_years' => $row['active_years'],
            'country' => $row['country'],
            'genre' => $row['genre']
        ));
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
            if (strlen($this->ytube_id) != 9) {
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
