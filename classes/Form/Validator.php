<?php
namespace Form;


class Validator {

    const SWEDISH_PID = "/^(19|20)?[0-9]{6}(-)?[0-9pPtTfF][0-9]{3}$/";      // 19930730-1234, 930730-1234, 930730-P123(foreigners)
    const SWEDISH_POSTAL_CODE = "/^\d{3}(\s)?\d{2}$/";                      // 12345, 123 45
    const EMAIL = "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";       // test@test.com
    const INT = "/^[-+]?\d+$/";                                             // 1234
    const FLOAT = "/^[-+]?[0-9]*(\.|\,)[0-9]+$/";                           // 0.23, 0,23
    const DATE = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"; // YYYY-MM-DD

}