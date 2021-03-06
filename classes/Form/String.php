<?php

namespace Form;


class String {

    const SESSION_LOCATION = "lang";
    const ENGLISH = "eng";
    const SWEDISH = "sv";
    const GERMAN = "de";

    public static function Get($string){
        if(!func_num_args()){
            throw new \BadFunctionCallException("You have to specify at least one parameter");
        }

        $lang = self::ENGLISH;

        if(isset($_SESSION[Form::$SessionLocation][self::SESSION_LOCATION]) && !empty($_SESSION[Form::$SessionLocation][self::SESSION_LOCATION])){
            $lang = $_SESSION[Form::$SessionLocation][self::SESSION_LOCATION];
        }

        $arg_list = func_get_args();
        $string = array_shift($arg_list);

        if(isset(self::$strings[$lang][$string])){
            $translated = self::$strings[$lang][$string];
        }else if(isset(self::$strings[self::ENGLISH][$string])){
            $translated = self::$strings[self::ENGLISH][$string];
        }else{
            throw new \ErrorException("String \"" . $string . "\" doesn't exist");
        }

        foreach($arg_list as $key => $argument){
            $translated = str_replace('{' . $key . '}', $argument, $translated);
        }

        if(preg_match("/{\d+}/", $translated)){
            throw new \ErrorException("String \"" . $translated . "\" expects arguments");
        }

        return $translated;
    }

    public static function SetLanguage($string){
        $_SESSION[Form::$SessionLocation][self::SESSION_LOCATION] = $string;
    }

    public static function GetCurrentLanguageStrings(){
        $lang = self::ENGLISH;

        if(isset($_SESSION[Form::$SessionLocation][self::SESSION_LOCATION]) && !empty($_SESSION[Form::$SessionLocation][self::SESSION_LOCATION])){
            $lang = $_SESSION[Form::$SessionLocation][self::SESSION_LOCATION];
        }

        return self::$strings[$lang];
    }

    private static $strings = array(
        self::ENGLISH => array(
            "Save_Button" => "Save",

            "Validator_SWE_PID" => "Personal Identification Number is not valid",
            "Validator_US_SSN" => "Social Security Number is not in a valid format",
            "Validator_US_Postal" => "Postal code should match: 123 45",
            "Validator_Email" => "Email address is not valid",
            "Validator_Int" => "Value can only contain numbers",
            "Validator_Float" => "Value have to be a decimal number",
            "Validator_Date" => "Date doesn't match YYYY-MM-DD",
            "Validator_HEX" => "Not a hexadecimal value",
            "Validator_URL" => "Not a valid URL",
            "Validator_IP" => "No a valid IP address",
            "Validator_Credit_Card" => "Not a valid credit card number",
            "Validator_Default" => "Wrong format",

            "Comparator_Greater_Than" => "Value must be greater than {0}",
            "Comparator_Greater_Than_Equal" => "Value must be greater than or equal to {0}",
            "Comparator_Less_Than" => "Value must be less than {0}",
            "Comparator_Less_Than_Equal" => "Value must be less than or equal to {0}",
            "Comparator_Equals" => "Value must be equal to {0}",

            "Field_Empty" => "Field cannot be empty",
            "Field_Empty_Check" => "Must be checked",
            "Field_Empty_Select" => "You must select an option",
            "Field_Short" => "Must be shorter than {0} characters",
            "Field_Long" => "Must be longer than {0} characters",
            "Field_Range" => "Must be between {0} and {1}",

            "File_Uploaded" => "File \"{0}\" uploaded.",
            "File_Required" => "A file must be uploaded",
            "File_Too_Large" => "File is too large, must be smaller than {0}mb",
            "File_Unvalid_Format" => "File is not in a valid format, try a file that ends in {0}",
        ),
        self::SWEDISH => array(
            "Save_Button" => "Spara",

            "Validator_SWE_PID" => "Personnummret är inte giltigt",
            "Validator_US_SSN" => "Det amerikanska personnummret (SSN) är inte giltigt",
            "Validator_US_Postal" => "Postnumret är inte giltigt",
            "Validator_Email" => "Eposten är inte giltig",
            "Validator_Int" => "Värdet kan enbart innehålla siffror",
            "Validator_Float" => "Värdet måste vara ett decimaltal",
            "Validator_Date" => "Datumet kan inte tolkas som ÅÅÅÅ-MM-DD",
            "Validator_HEX" => "Värdet är inte ett hexadecimalt värde",
            "Validator_URL" => "Inte en giltig URL",
            "Validator_IP" => "Inte en giltig IP adress",
            "Validator_Credit_Card" => "Inte ett giltigt kreditkort",
            "Validator_Default" => "Fel format",

            "Comparator_Greater_Than" => "Värdet måste vara större än {0}",
            "Comparator_Greater_Than_Equal" => "Värdet måste vara större än eller lika med {0}",
            "Comparator_Less_Than" => "Värdet måste vara mindre än  {0}",
            "Comparator_Less_Than_Equal" => "Värdet måste vara mindre än eller lika med {0}",
            "Comparator_Equals" => "Värdet måste vara lika med {0}",

            "Field_Empty" => "Fältet får inte vara tomt",
            "Field_Empty_Check" => "Måste vara ikryssad",
            "Field_Empty_Select" => "Du måste välja ett alternativ",
            "Field_Short" => "Måste vara kortare än {0} tecken",
            "Field_Long" => "Måste vara längre än {0} tecken",
            "Field_Range" => "Måste vara mellan {0} och {1}",

            "File_Uploaded" => "Filen \"{0}\" laddades upp.",
            "File_Required" => "En fil måste laddas upp",
            "File_Too_Large" => "Filen får inte vara större än {0}mb",
            "File_Unvalid_Format" => "Filen är inte i ett giltigt format, försök med en fil som slutar på {0}",
        ),
        self::GERMAN => array(
            "Save_Button" => "Speichern",

            "Validator_SWE_PID" => "Persönliche Identifikationsnummer ist ungültig",
            "Validator_US_SSN" => "Die US persönliche Nummer ( SSN) ist ungültig",
            "Validator_US_Postal" => "Die Postleitzahl ist ungültig",
            "Validator_Email" => "Die E-Mail ist ungültig",
            "Validator_Int" => "Der Wert kann nur Zahlen enthalten",
            "Validator_Float" => "Der Wert muss eine Dezimalzahl sein",
            "Validator_Date" => "Das Datum kann nicht als YYYY-MM-DD interpretiert werden",
            "Validator_HEX" => "Nicht ein Hexadezimalwert",
            "Validator_URL" => "Keine gültige URL",
            "Validator_IP" => "Keine gültige IP-Adresse",
            "Validator_Credit_Card" => "Nicht gültige Kreditkarte",
            "Validator_Default" => "Falsches Format",

            "Field_Empty" => "Feld darf nicht leer sein",
            "Field_Empty_Check" => "Muss geprüft werden",
            "Field_Empty_Select" => "Sie müssen eine Option aus",
            "Field_Short" => "Darf nicht mehr als {0} Zeichen lang sein",
            "Field_Long" => "Muss länger als {0} Zeichen lang sein",
            "Field_Range" => "Muss zwischen {0} uns {1} sein",
        )
    );
}