<?php
namespace Form;


class Validator {

    const SWEDISH_PID = "/^(19|20)?[0-9]{6}(-)?[0-9pPtTfF][0-9]{3}$/";
    // 19930730-1234, 930730-1234, 930730-P123(foreigners)

    const US_SOCIAL_SECURITY = "/^([0-9]{3}[-]*[0-9]{2}[-]*[0-9]{4})*$/";

    const SWEDISH_POSTAL_CODE = "/^\d{3}(\s)?\d{2}$/";
    // 12345, 123 45

    const EMAIL = "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
    // test@test.com

    const INT = "/^[-+]?\d+$/";
    // 1234

    const FLOAT = "/^[-+]?[0-9]*(\.|\,)[0-9]+$/";
    // 0.23, 0,23

    const DATE = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
    // YYYY-MM-DD

    const HEXA_DECIMAL = "/^#?([a-f0-9]{6}|[a-f0-9]{3})$/";
    // #a3c113

    const URL = "/^(((http|https|ftp):\/\/)?([[a-zA-Z0-9]\-\.])+(\.)([[a-zA-Z0-9]]){2,4}([[a-zA-Z0-9]\/+=%&_\.~?\-]*))*$/";
    // #a3c113

    const IP_ADDRESS = "/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";
    //73.60.124.136

    const CREDIT_CARD = "/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})*$/";

    public static function ErrorMessage($validator){
        switch($validator){
            case self::SWEDISH_PID:
                return String::Get("Validator_SWE_PID");
                break;
            case self::US_SOCIAL_SECURITY:
                return String::Get("Validator_US_SSN");
                break;
            case self::SWEDISH_POSTAL_CODE:
                return String::Get("Validator_US_Postal");
                break;
            case self::EMAIL:
                return String::Get("Validator_Email");
                break;
            case self::INT:
                return String::Get("Validator_Int");
                break;
            case self::FLOAT:
                return String::Get("Validator_Float");
                break;
            case self::DATE:
                return String::Get("Validator_Date");
                break;
            case self::HEXA_DECIMAL:
                return String::Get("Validator_HEX");
                break;
            case self::URL:
                return String::Get("Validator_URL");
                break;
            case self::IP_ADDRESS:
                return String::Get("Validator_IP");
                break;
            case self::CREDIT_CARD:
                return String::Get("Validator_Credit_Card");
                break;
            default:
                return String::Get("Validator_Default");
        }
    }

    public static function GetAsArray(){
        return array(
            "1" => array("regex" => self::SWEDISH_PID, "message" => self::ErrorMessage(self::SWEDISH_PID)),
            "2" => array("regex" => self::US_SOCIAL_SECURITY, "message" => self::ErrorMessage(self::US_SOCIAL_SECURITY)),
            "3" => array("regex" => self::SWEDISH_POSTAL_CODE, "message" => self::ErrorMessage(self::SWEDISH_POSTAL_CODE)),
            "4" => array("regex" => self::EMAIL, "message" => self::ErrorMessage(self::EMAIL)),
            "5" => array("regex" => self::INT, "message" => self::ErrorMessage(self::INT)),
            "6" => array("regex" => self::FLOAT, "message" => self::ErrorMessage(self::FLOAT)),
            "7" => array("regex" => self::DATE, "message" => self::ErrorMessage(self::DATE)),
            "8" => array("regex" => self::HEXA_DECIMAL, "message" => self::ErrorMessage(self::HEXA_DECIMAL)),
            "9" => array("regex" => self::URL, "message" => self::ErrorMessage(self::URL)),
            "10" => array("regex" => self::IP_ADDRESS, "message" => self::ErrorMessage(self::IP_ADDRESS)),
            "11" => array("regex" => self::CREDIT_CARD, "message" => self::ErrorMessage(self::CREDIT_CARD))
        );
    }

    public static function GetPositionInArray($regex){
        foreach(self::GetAsArray() as $key => $value){
            if($regex == $value["regex"]){
                return $key;
            }
        }
        return null;
    }
}