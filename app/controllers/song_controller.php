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
        self::check_logged_in();
        
        View::make('song/song_new.html');
    }

    public static function store() {
        self::check_logged_in();
        
        $params = $_POST;           
        $attributes = self::create_attribute_array($params);

        $song = new Song($attributes);
        $errors = $song->errors();

        if (count($errors) == 0) {
            $song->save();

            Redirect::to('/song/'
                    . $song->id, array('message' => 'Song added successfully'));
        } else {
            View::make('song/song_new.html', array(
                'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        self::check_logged_in();
        
        $song = Song::find($id);
        View::make('song/song_edit.html', array('attributes' => $song));
    }

    public static function update($id) {
        self::check_logged_in();
        
        $params = $_POST;
        $attributes = self::create_attribute_array($params);
        $attributes['id'] = $id;

        $song = new Song($attributes);
        $errors = $song->errors();

        if (count($errors) > 0) {
            View::make('song/song_edit.html', array(
                'errors' => $errors, 'attributes' => $attributes));
        } else {
            $song->update();

            Redirect::to('/song/' . $song->id, array(
                'message' => 'Song edited successfully'));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();
        
        $song = new Song(array('id' => $id));
        $song->destroy();

        Redirect::to('/song', array(
            'message' => 'Song removed successfully'));
    }

    private static function create_attribute_array(array $params) {
        return array(
            'name' => $params['name'],
            'written_by' => $params['written_by'],
            'year' => $params['year'],
            'country' => $params['country'],
            'genre' => $params['genre'],
            'ytube_id' => $params['ytube_id']
        );
    }
}
