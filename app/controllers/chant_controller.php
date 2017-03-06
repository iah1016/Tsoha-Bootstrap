<?php

class ChantController extends BaseController {

    public static function index() {
        $chants = Chant::all();
        View::make('chant/chant_list.html', array('chants' => $chants));
    }

    public static function show($id) {
        $chant = Chant::find($id);
        View::make('chant/chant_show.html', array('chant' => $chant));
    }

    public static function create() {
        self::check_logged_in();

        $songs = Song::all();
        $clubs = Club::all();
        View::make('chant/chant_new.html', array('songs' => $songs,
            'clubs' => $clubs));
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;
        $clubs = self::clubs_isset_check($params);

        $attributes = self::create_attribute_array($params, $clubs);
        $chant = new Chant($attributes);

        self::try_adding_or_updating($chant, $attributes, 'new', 'added');
    }

    public static function edit($id) {
        self::check_logged_in();

        $chant = Chant::find($id);
        $songs = Song::all();
        $clubs = Club::all();
        View::make('chant/chant_edit.html', array('attributes' => $chant,
            'songs' => $songs, 'clubs' => $clubs));
    }

    public static function update($id) {
        self::check_logged_in();

        $params = $_POST;
        $clubs = self::clubs_isset_check($params);

        $attributes = self::create_attribute_array($params, $clubs);
        $attributes['id'] = $id;
        $chant = new Chant($attributes);

        self::try_adding_or_updating($chant, $attributes, 'edit', 'edited');
    }

    private static function clubs_isset_check($params) {
        if (isset($params['clubs'])) {
            return $params['clubs'];
        } else {
            return array();
        }
    }

    private static function try_adding_or_updating
    ($chant, $attributes, $redirect_on_fail, $action_string) {
        $errors = $chant->errors();

        if (count($errors) == 0) {
            if ($action_string == 'added') {
                $chant->save();
            } elseif ($action_string == 'edited') {
                $chant->update();
            }
            Redirect::to('/chant/' . $chant->id, array(
                'message' => 'Chant ' . $action_string . ' successfully'));
        } else {
            $songs = Song::all();
            $clubs = Club::all();
            View::make('chant/chant_' . $redirect_on_fail . '.html', array(
                'errors' => $errors, 'attributes' => $attributes,
                'songs' => $songs, 'clubs' => $clubs));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();

        $chant = new Chant(array('id' => $id));
        $chant->destroy();

        Redirect::to('/chant', array(
            'message' => 'Chant removed successfully'));
    }

    //
    private static function create_attribute_array
    (array $params, array $clubs) {
        $attributes = array(
            'name' => $params['name'],
            'lyrics' => $params['lyrics'],
            'song' => $params['song'],
            'clubs_ids' => array()
        );
        foreach ($clubs as $club) {
            $attributes['clubs_ids'][] = $club;
        }

        return $attributes;
    }

}
