<?php

/**
 * ClubChant model class
 *
 * @author Ilja Häkkinen
 */
class ClubChant extends BaseModel {
    
    public $club_id, $chant_id;    

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    
}
