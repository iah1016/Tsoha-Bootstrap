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

        $perf_ids = array();

        foreach ($rows as $row) {
            $perf_ids[] = $row['perf_id'];
        }

        return $perf_ids;
    }

    public static function find_song_ids_with_perf_id($perf_id) {
        $sql_string = 'SELECT song_id FROM PerfSong WHERE perf_id = :perf_id';
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('perf_id' => $perf_id));
        $rows = $query->fetchAll();

        $song_ids = array();

        foreach ($rows as $row) {
            $song_ids[] = $row['song_id'];
        }

        return $song_ids;
    }

}
