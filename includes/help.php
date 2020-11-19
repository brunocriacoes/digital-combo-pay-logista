<?php

function set_log( $message )
{
    file_put_contents( __DIR__ . "/../.log", date( "d-m-Y H:i" ) . " $message \n", FILE_APPEND);
}

function is_option_valid( $tipos, $tipo )
{
    return stripos( $tipos , $tipo )  !== false ? '' : 'hidden';
}
