<?php

function acortartexto($text, $longitud)
{
    $text = strip_tags($text);
    if (substr($text, $longitud - 1, 1) != ' ') {
        $text = substr($text, '0', $longitud);
        $array = explode(' ', $text);
        array_pop($array);
        $new_string = implode(' ', $array);
        return $new_string . ' ...';
    } else {
        return substr($text, '0', $longitud) . ' ...';
    }
}

