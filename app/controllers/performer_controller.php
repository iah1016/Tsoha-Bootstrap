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

        View::make('performer/performer_new.html');
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;
        $attributes = self::create_attribute_array($params);

        $performer = new Performer($attributes);
        $errors = $performer->errors();

        if (count($errors) == 0) {
            $performer->save();

            Redirect::to('/performer/'
                    . $performer->id, array(
                'message' => 'Performer added successfully'));
        } else {
            View::make('performer/performer_new.html', array(
                'errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        self::check_logged_in();

        $performer = Performer::find($id);
        View::make('performer/performer_edit.html', array(
            'attributes' => $performer));
    }

    public static function update($id) {
        self::check_logged_in();

        $params = $_POST;
        $attributes = self::create_attribute_array($params);
        $attributes['id'] = $id;

        $performer = new Performer($attributes);
        $errors = $performer->errors();

        if (count($errors) > 0) {
            View::make('performer/performer_edit.html', array(
                'errors' => $errors, 'attributes' => $attributes));
        } else {
            $performer->update();

            Redirect::to('/performer/' . $performer->id, array(
                'message' => 'Performer edited successfully'));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();

        $performer = new Performer(array('id' => $id));
        $performer->destroy();

        Redirect::to('/performer', array(
            'message' => 'Performer removed successfully'));
    }

    private static function create_attribute_array(array $params) {
        return array(
            'name' => $params['name'],
            'active_years' => $params['active_years'],
            'country' => $params['country'],
            'genre' => $params['genre']
        );
    }

}
