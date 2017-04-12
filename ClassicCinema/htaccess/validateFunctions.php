<?php
function isDigits($str) {
    $pattern='/^[0-9]+$/';
    return preg_match($pattern, $str);
}


function isEmpty($str) {
    return strlen(trim($str)) == 0;
}

function isEmail($str) {
    // There's a built in PHP tool that has a go at this
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}


function checkLength($str, $len) {
    return strlen(trim($str)) === $len;
}

function checkCardNumber($cardType, $cardNumber) {
    if (!isDigits($cardNumber)) {
        return false;
    }

    switch ($cardType) {
        case 'amex':
            return checkLength($cardNumber, 15) && (int)$cardNumber[0] === 3;
            break;
        case 'mcard':
            return checkLength($cardNumber, 16) && (int)$cardNumber[0] === 5;
            break;
        case 'visa':
            return checkLength($cardNumber, 16) && (int)$cardNumber[0] === 4;
            break;
        default:
            return false;
    }
}


function checkCardVerification($cardType, $cardVerify) {
    if (!isDigits($cardVerify)) {
        return false;
    }

    switch ($cardType) {
        case 'amex':
            return checkLength($cardVerify, 4);
            break;
        case 'mcard':
            return checkLength($cardVerify, 3);
            break;
        case 'visa':
            return checkLength($cardVerify, 3);
            break;
        default:
            return false;
    }
}

function checkCardDate($cardMonth, $cardYear) {
    $year = (int) date('Y');
    $month = (int) date('n');
    $cardYear = (int) $cardYear;
    $cardMonth = (int) $cardMonth;

    if ($year > $cardYear) {
        return false;
    } elseif ($year === $cardYear && $month >= $cardMonth) {
        return false;
    } else {
        return true;
    }

}

function setValue($formVal){
    if (isset($_SESSION[$formVal])) {
        $name = $_SESSION[$formVal];
        echo "value='$name'";
    }
}

?>