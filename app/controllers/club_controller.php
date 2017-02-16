<?php

class ClubController extends BaseController {

    public static function index() {
        $clubs = Club::all();
        View::make('club/club_list.html', array('clubs' => $clubs));
    }

    public static function show($id) {
        $club = Club::find($id);
        View::make('club/club_show.html', array('club' => $club));
    }

    public static function create() {
        self::check_logged_in();

        View::make('club/club_new.html');
    }

    public static function store() {
        self::check_logged_in();
        // not yet implemented
    }

    public static function edit($id) {
        self::check_logged_in();

        $club = Club::find($id);
        View::make('club/club_edit.html', array('attributes' => $club));
    }

    public static function update($id) {
        self::check_logged_in();
        // not yet implemented
    }

    public static function destroy($id) {
        self::check_logged_in();

        $club = new Club(array('id' => $id));
        $club->destroy();

        Redirect::to('/club', array(
            'message' => 'Club removed successfully'));
    }

}
