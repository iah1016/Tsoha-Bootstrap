<?php

/**
 * Club model class
 *
 * @author Ilja Häkkinen
 */
class Club extends BaseModel {

    public $id, $name, $country, $league, $chants_ids, $chants;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_name',
            'validate_country',
            'validate_league'
        );
    }

    // read
    public static function all() {
        $sql_string = 'SELECT * FROM Club ORDER BY name ASC';
        $rows = parent::find_all_rows_from_table($sql_string);
        $clubs = array();

        foreach ($rows as $row) {
            $clubs[] = self::create_new_club($row);
        }
        return $clubs;
    }

    public static function find($id) {
        $sql_string = 'SELECT * FROM Club WHERE id = :id LIMIT 1';
        $row = parent::find_row_from_table($sql_string, $id);

        if ($row) {
            $club = self::create_new_club($row);
            $club->find_associated_chants();
            return $club;
        }
        return null;
    }

    public static function find_all_clubs_with_chant_id($chant_id) {
        $clubs = array();
        $sql_string = 'SELECT Club.* '
                . 'FROM ClubChant '
                . 'INNER JOIN Club ON Club.id = ClubChant.club_id '
                . 'WHERE ClubChant.chant_id = :id;';
        $rows = parent::find_all_rows_with_id($sql_string, $chant_id);

        foreach ($rows as $row) {
            $clubs[] = self::create_new_club($row);
        }
        return $clubs;
    }

    // create, update, destroy
    public function save() {
        $sql_string = 'INSERT INTO Club ('
                . 'name, country, league'
                . ') VALUES ('
                . ':name, :country, :league'
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

        $sql_string = 'UPDATE Club SET name = :name, '
                . 'country = :country, '
                . 'league = :league '
                . 'WHERE id = :id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute($attributes);

        $this->update_clubchant();
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

    private function add_to_clubchant() {
        if (!empty($this->chants_ids) && !is_null($this->chants_ids)) {
            $chant_and_club_ids = array('club_id' => $this->id);

            foreach ($this->chants_ids as $chant_id) {
                $sql_two = 'INSERT INTO ClubChant (chant_id, club_id) VALUES ('
                        . ':chant_id, :club_id'
                        . ')';
                $query = DB::connection()->prepare($sql_two);
                $chant_and_club_ids['chant_id'] = $chant_id;
                $query->execute($chant_and_club_ids);
            }
        }
    }

    private function update_clubchant() {
        $query = DB::connection()->prepare(
                'DELETE FROM ClubChant WHERE club_id = :id');
        $query->execute(array('id' => $this->id));

        $this->add_to_clubchant();
    }

    private function find_associated_chants() {
        $this->chants = Chant::find_all_chants_with_club_id($this->id);
        foreach ($this->chants as $chant) {
            $this->chants_ids[] = $chant->id;
        }
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
