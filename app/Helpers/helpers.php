<?php

function isValid($obj)
{
    if (isset($obj) && $obj != '') {
        if (count($obj) > 0) {
            return true;
        }
    }
    return false;
}

function parseByFormat($date, $format='d/m/Y h:i A')
{
    $date = date_create($date);
    return date_format($date,$format);
}
