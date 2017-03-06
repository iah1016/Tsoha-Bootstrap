<?php

class BaseModel {

    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null) {
        // Käydään assosiaatiolistan avaimet läpi
        foreach ($attributes as $attribute => $value) {
            // Jos avaimen niminen attribuutti on olemassa...
            if (property_exists($this, $attribute)) {
                // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
                $this->{$attribute} = $value;
            }
        }
    }

    public function errors() {
        // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
        $errors = array();

        foreach ($this->validators as $validator) {
            // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
            $validator_errors = $this->{$validator}();
            $errors = array_merge($errors, $validator_errors);
        }

        return $errors;
    }

    public function validate_string_length($attrib_name, $string, $min, $max) {
        $errors = array();
        $string_length = strlen($string);

        if ($string == '' | $string == null) {
            $errors[] = $attrib_name . ' cannot be empty!';
        }
        if ($string_length < $min || $string_length > $max) {
            $errors[] = $attrib_name . ' should be between '
                    . $min . '-' . $max . ' characters long.';
        }
        return $errors;
    }

    public function validate_numeric($attrib_name, $string) {
        $errors = array();

        if (!is_numeric($string)) {
            $errors[] = $attrib_name . ' is not a number!';
        }

        return $errors;
    }

    protected static function find_all_rows_from_table($sql_string) {
        $query = DB::connection()->prepare($sql_string);
        $query->execute();
        return $query->fetchAll();
    }

    protected static function find_all_rows_with_id($sql_string, $id) {
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('id' => $id));
        return $query->fetchAll();
    }
    
    protected static function find_row_from_table($sql_string, $id) {
        $query = DB::connection()->prepare($sql_string);
        $query->execute(array('id' => $id));
        return $query->fetch();
    }

}
