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

        $chants = Chant::all();
        View::make('club/club_new.html', array('chants' => $chants));
    }

    public static function store() {
        self::check_logged_in();

        $params = $_POST;

        $chants = $params['chants'];
        $attributes = self::create_attribute_array($params, $chants);

        $club = new Club($attributes);

        self::try_adding_or_updating($club, $attributes, 'new', 'added');
    }

    public static function edit($id) {
        self::check_logged_in();

        $club = Club::find($id);
        $chants = Chant::all();
        View::make('club/club_edit.html', array(
            'attributes' => $club, 'chants' => $chants));
    }

    public static function update($id) {
        self::check_logged_in();

        $params = $_POST;
        $attributes = self::create_attribute_array($params);
        $attributes['id'] = $id;
        $club = new Club($attributes);

        self::try_adding_or_updating($club, $attributes, 'edit', 'edited');
    }

    private static function try_adding_or_updating
    ($club, $attributes, $redirect_on_fail, $action_string) {
        $errors = $club->errors();

        if (count($errors) == 0) {
            if ($action_string == 'added') {
                $club->save();
            } elseif ($action_string == 'edited') {
                $club->update();
            }
            Redirect::to('/club/' . $club->id, array(
                'message' => 'Club ' . $action_string . ' successfully'));
        } else {
            $chants = Chant::all();
            View::make('club/club_' . $redirect_on_fail . '.html', array(
                'errors' => $errors, 'attributes' => $attributes,
                'chants' => $chants));
        }
    }

    public static function destroy($id) {
        self::check_logged_in();

        $club = new Club(array('id' => $id));
        $club->destroy();

        Redirect::to('/club', array(
            'message' => 'Club removed successfully'));
    }

    private static function create_attribute_array
    (array $params, array $chants) {
        return array(
            'name' => $params['name'],
            'country' => $params['country'],
            'league' => $params['league'],
        );
    }

    private static function add_chants_to_attributes
    (array $attributes, array $chants) {
        $attributes['chants_ids'] = array();
        if (is_null($chants)) {
            return $attributes;
        }

        foreach ($chants as $chant) {
            $attributes['chants_ids'][] = $chant;
        }

        return $attributes;
    }

}
