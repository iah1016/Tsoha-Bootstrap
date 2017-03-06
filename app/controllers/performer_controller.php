<?php

class PerformerController extends BaseController {

    public static function index() {
        $performers = Performer::all();
        View::make('performer/performer_list.html', array(
            'performers' => $performers));
    }

    public static function show($id) {
        $performer = Performer::find($id);
        View::make('performer/performer_show.html', array(
            'performer' => $performer));
    }

    public static function create() {
        self::check_logged_in();

        $songs = Song::all();
        View::make('performer/performer_new.html', array(
            'songs' => $songs
        ));
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;
        $songs = self::songs_isset_check($params);

        $attributes = self::create_attribute_array($params, $songs);
        $performer = new Performer($attributes);

        self::try_adding_or_updating($performer, $attributes, 'new', 'added');
    }

    public static function edit($id) {
        self::check_logged_in();

        $performer = Performer::find($id);
        $songs = Song::all();
        View::make('performer/performer_edit.html', array(
            'attributes' => $performer, 'songs' => $songs));
    }

    public static function update($id) {
        self::check_logged_in();

        $params = $_POST;
        $songs = self::songs_isset_check($params);

        $attributes = self::create_attribute_array($params, $songs);
        $attributes['id'] = $id;
        $performer = new Performer($attributes);

        self::try_adding_or_updating($performer, $attributes, 'edit', 'edited');
    }

    private static function songs_isset_check($params) {
        if (isset($params['songs'])) {
            return $params['songs'];
        } else {
            return array();
        }
    }

    private static function try_adding_or_updating
    ($performer, $attributes, $redirect_on_fail, $action_string) {
        $errors = $performer->errors();

        if (count($errors) == 0) {
            if ($action_string == 'added') {
                $performer->save();
            } elseif ($action_string == 'edited') {
                $performer->update();
            }
            Redirect::to('/performer/' . $performer->id, array(
                'message' => 'Performer ' . $action_string . ' successfully'));
        } else {
            $songs = Song::all();
            View::make(
                    'performer/performer_' . $redirect_on_fail . '.html', array(
                'errors' => $errors, 'attributes' => $attributes,
                'songs' => $songs));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();

        $performer = new Performer(array('id' => $id));
        $performer->destroy();

        Redirect::to('/performer', array(
            'message' => 'Performer removed successfully'));
    }

    private static function create_attribute_array
    (array $params, array $songs) {
        $attributes = array(
            'name' => $params['name'],
            'active_years' => $params['active_years'],
            'country' => $params['country'],
            'genre' => $params['genre'],
            'songs_id' => array()
        );
        foreach ($songs as $song) {
            $attributes['songs_ids'][] = $song;
        }

        return $attributes;
    }

}
