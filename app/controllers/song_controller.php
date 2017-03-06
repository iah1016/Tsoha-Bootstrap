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

        $performers = Performer::all();
        View::make('song/song_new.html', array(
            'performers' => $performers
        ));
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;
        $performers = self::performers_isset_check($params);

        $attributes = self::create_attribute_array($params, $performers);
        $song = new Song($attributes);

        self::try_adding_or_updating($song, $attributes, 'new', 'added');
    }

    public static function edit($id) {
        self::check_logged_in();

        $song = Song::find($id);
        $performers = Performer::all();
        View::make('song/song_edit.html', array('attributes' => $song,
            'performers' => $performers));
    }

    public static function update($id) {
        self::check_logged_in();

        $params = $_POST;
        $performers = self::performers_isset_check($params);

        $attributes = self::create_attribute_array($params, $performers);
        $attributes['id'] = $id;
        $song = new Song($attributes);

        self::try_adding_or_updating($song, $attributes, 'edit', 'edited');
    }

    private static function performers_isset_check($params) {
        if (isset($params['performers'])) {
            return $params['performers'];
        } else {
            return array();
        }
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
            $performers = Performer::all();
            View::make('song/song_' . $redirect_on_fail . '.html', array(
                'errors' => $errors, 'attributes' => $attributes,
                'performers' => $performers));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();

        $song = new Song(array('id' => $id));
        $song->destroy();

        Redirect::to('/song', array(
            'message' => 'Song removed successfully'));
    }

    private static function create_attribute_array
    (array $params, array $performers) {
        $attributes = array(
            'name' => $params['name'],
            'written_by' => $params['written_by'],
            'year' => $params['year'],
            'country' => $params['country'],
            'genre' => $params['genre'],
            'ytube_id' => $params['ytube_id'],
            'perfs_ids' => array()
        );
        foreach ($performers as $performer) {
            $attributes['perfs_ids'][] = $performer;
        }

        return $attributes;
    }

}
