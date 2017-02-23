<?php

/**
 * Club model class
 *
 * @author Ilja Häkkinen
 */
class Club extends BaseModel {

    public $id, $name, $country, $league;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_name',
            'validate_country',
            'validate_league'
        );
    }

    public static function all() {
        $rows = parent::all_rows_from_table('Club');
        $clubs = array();

        foreach ($rows as $row) {
            $clubs[] = self::create_new_club($row);
        }
        return $clubs;
    }

    public static function find($id) {
        $row = parent::find_row_from_table('Club', $id);

        if ($row) {
            return self::create_new_club($row);
        }
        return null;
    }

    public function save() {
        $sql_string = 'INSERT INTO Club ('
                . 'name, country, league) '
                . 'VALUES ('
                . ':name, :country, :league) '
                . 'RETURNING id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($this->create_array());
        $row = $query->fetch();

        $this->id = $row['id'];
    }

    public function update() {
        $attributes = $this->create_array();
        $attributes['id'] = $this->id;

        $sql_string = 'UPDATE Club SET name = :name, '
                . 'country = :country, '
                . 'league = :league '
                . 'WHERE id = :id';
        $query = DB::connection()->prepare($sql_string);

        $query->execute($attributes);
    }

    public function destroy() {
        $query = DB::connection()->prepare('DELETE FROM Club WHERE id = :id');
        $query->execute(array('id' => $this->id));
    }

// private functions
    private static function create_new_club($row) {
        return new Club(array(
            'id' => $row['id'],
            'name' => $row['name'],
            'country' => $row['country'],
            'league' => $row['league']
        ));
    }

    private function create_array() {
        return array(
            'name' => $this->name,
            'country' => $this->country,
            'league' => $this->league
        );
    }

// validators
    public function validate_name() {
        return $this->validate_string_length('Name', $this->name, 2, 50);
    }

    public function validate_country() {
        return $this->validate_string_length('Country', $this->country, 2, 50);
    }

    public function validate_league() {
        return $this->validate_string_length('League', $this->league, 2, 50);
    }

}
