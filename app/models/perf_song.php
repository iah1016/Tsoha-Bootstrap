<?php

/**
 * PerfSong model class
 *
 * @author Ilja Häkkinen
 */
class PerfSong extends BaseModel {

    public $perf_id, $song_id;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function find_perf_ids_with_song_id($song_id) {
        $sql_string = 'SELECT perf_id FROM PerfSong WHERE song_id = :song_id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('song_id' => $song_id));
        $rows = $query->fetchAll();

        return self::make_array_of_ids($rows, 'perf_id');
    }

    public static function find_song_ids_with_perf_id($perf_id) {
        $sql_string = 'SELECT song_id FROM PerfSong WHERE perf_id = :perf_id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('perf_id' => $perf_id));
        $rows = $query->fetchAll();

        return self::make_array_of_ids($rows, 'song_id');
    }

    protected static function make_array_of_ids($rows, $attribute) {
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row[$attribute];
        }
        return $ids;
    }
}
