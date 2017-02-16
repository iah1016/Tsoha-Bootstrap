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
//        $this->validators = array(
//            'validate_name'
//        );
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Club');
        $query->execute();
        $rows = $query->fetchAll();
        $clubs = array();

        foreach ($rows as $row) {
            $clubs[] = self::create_new_club($row);
        }
        return $clubs;
    }

    public static function find($id) {
        $sql_string = 'SELECT * FROM Club WHERE id = :id LIMIT 1';
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $club = self::create_new_club($row);

            return $club;
        }

        return null;
    }

// save
// update

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

//    private function create_array() {
//        return array(
//            'name' => $this->name,
//            '$country' => $this->$country,
//            '$league' => $this->$league
//        );
//    }
//    
// validators
}
