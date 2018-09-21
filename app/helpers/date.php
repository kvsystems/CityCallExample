<?php

function roundToHour( $timestamp, $greater = false )  {

    return !$greater
        ? strtotime( date( 'Y-m-d H:00', $timestamp ))
        : strtotime( date( 'Y-m-d H:00', $timestamp ));

}

function roundToEndOfDay( $timestamp )    {

    return strtotime( date( 'Y-m-d 00:00', $timestamp  ) . '+1 day' );

}

function roundToStartOfDay( $timestamp )    {

    return strtotime( date( 'Y-m-d', $timestamp ) );

}

function secondsToHours( $seconds )   {

    return round( $seconds / 3600 );

}