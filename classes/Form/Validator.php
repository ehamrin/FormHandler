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

    const DATE = "/^[0-9]{4}(-|/|\\)(0[1-9]|1[0-2])(-|/|\\)(0[1-9]|[1-2][0-9]|3[0-1])$/";
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
                return "Personal Identification Number is not valid";
                break;
            case self::US_SOCIAL_SECURITY:
                return "Social Security Number is not in a valid format";
                break;
            case self::SWEDISH_POSTAL_CODE:
                return "Postal code should match: 123 45";
                break;
            case self::EMAIL:
                return "Email address is not valid";
                break;
            case self::INT:
                return "Value can only contain numbers";
                break;
            case self::FLOAT:
                return "Value have to be a decimal number";
                break;
            case self::DATE:
                return "Date doesn't match YYYY-MM-DD";
                break;
            case self::HEXA_DECIMAL:
                return "Not a hexadecimal value";
                break;
            case self::URL:
                return "Not a valid URL";
                break;
            case self::IP_ADDRESS:
                return "No a valid IP address";
                break;
            case self::CREDIT_CARD:
                return "Not a valid credit card number";
                break;
            default:
                return "Wrong format";
        }
    }
}