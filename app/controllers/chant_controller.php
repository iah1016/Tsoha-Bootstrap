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
        View::make('chant/chant_new.html', array('songs' => $songs));
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;
        $attributes = self::create_attribute_array($params);

        $chant = new Chant($attributes);
        $errors = $chant->errors();

        if (count($errors) == 0) {
            $chant->save();

            Redirect::to('/chant/'
                    . $chant->id, array(
                'message' => 'Chant added successfully'));
        } else {
            $songs = Song::all();
            View::make('chant/chant_new.html', array(
                'errors' => $errors, 'attributes' => $attributes,
                'songs' => $songs));
        }
    }

    public static function edit($id) {
        self::check_logged_in();

        $chant = Chant::find($id);
        $songs = Song::all();
        View::make('chant/chant_edit.html', array('attributes' => $chant,
            'songs' => $songs));
    }

    public static function update($id) {
        self::check_logged_in();

        $params = $_POST;
        $attributes = self::create_attribute_array($params);
        $attributes['id'] = $id;

        $chant = new Chant($attributes);
        $errors = $chant->errors();

        if (count($errors) > 0) {
            $songs = Song::all();
            View::make('chant/chant_edit.html', array(
                'errors' => $errors, 'attributes' => $attributes,
                'songs' => $songs));
        } else {
            $chant->update();

            Redirect::to('/chant/' . $chant->id, array(
                'message' => 'Chant edited successfully'));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();

        $chant = new Chant(array('id' => $id));
        $chant->destroy();

        Redirect::to('/chant', array(
            'message' => 'Chant removed successfully'));
    }

    private static function create_attribute_array(array $params) {
        return array(
            'name' => $params['name'],
            'lyrics' => $params['lyrics'],
            'song' => $params['song'],
        );
    }

}
