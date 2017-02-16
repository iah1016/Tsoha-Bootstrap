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

        View::make('chant/chant_new.html');
    }

    public static function store() {
        self::check_logged_in();
        // not yet implemented
    }

    public static function edit($id) {
        self::check_logged_in();

        $chant = Chant::find($id);
        View::make('chant/chant_edit.html', array('attributes' => $chant));
    }

    public static function update($id) {
        self::check_logged_in();
        // not yet implemented
    }

    public static function destroy($id) {
        self::check_logged_in();

        $chant = new Chant(array('id' => $id));
        $chant->destroy();

        Redirect::to('/chant', array(
            'message' => 'Chant removed successfully'));
    }

}
