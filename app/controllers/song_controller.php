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

        self::try_adding_or_updating($song, $attributes, 'new', 'added');
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

        self::try_adding_or_updating($song, $attributes, 'edit', 'edited');
    }

    private static function try_adding_or_updating
    ($song, $attributes, $redirect_on_fail, $action_string) {
        $errors = $song->errors();

        if (count($errors) == 0) {
            if ($action_string == 'added') {
                $song->save();
            } elseif ($action_string == 'edited') {
                $song->update();
            }
            Redirect::to('/song/' . $song->id, array(
                'message' => 'Song ' . $action_string . ' successfully'));
        } else {
            View::make('song/song_' . $redirect_on_fail . '.html', array(
                'errors' => $errors, 'attributes' => $attributes));
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
