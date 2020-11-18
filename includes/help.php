<?php

function set_log( $message )
{
    file_put_contents( __DIR__ . "/../.log", date( "d-m-Y H:i" ) . " $message", FILE_APPEND);
}