<?php

class SongController extends BaseController {

    public static function index() {
        $songs = Song::all();
        View::make('song/song_list.html', array('songs' => $songs));
    }

    public static function show($id) {
        $song = Song::find($id);
        View::make('song/song_show.html', array('song' => $song));
    }

    public static function create() {
        View::make('song/song_new.html');
    }

    public static function store() {
        $params = $_POST;
//        lisää youtube-linkin toteutus!!
        $song = new Song(array(
            'name' => $params['name'],
            'written_by' => $params['written_by'],
            'year' => $params['year'],
            'country' => $params['country'],
            'genre' => $params['genre']
        ));

//        Kint::dump($params);

        $song->save();

        Redirect::to('/song/'
                . $song->id, array('message' => 'Song added successfully'));
    }

}
