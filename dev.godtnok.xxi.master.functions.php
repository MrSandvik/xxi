<?php

/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.functions.master.php
 * Purpose: System-wide functions
 * Changelog:
 *  2021-05-15: Created
 *  2021-09-09: XXIf_CheckYMD() function
 *              XXIf_HotelName() function returns -1 if no data found
 *  2022-04-21: XXIf_rebuildUrl($parameter, $value) - Quickly rebuild URL/URI
 *  2022-05-04: Disabled logging PHP Notice messages in the server log
 *  2022-10-23: Replaced #DATABASE.#SCHEMA. with #PREFIX
 */


// Disable PHP Notice messages in server log - Log errors only
error_reporting (E_ALL ^ E_NOTICE);

function XXIf_MinutesBetween($XXIf_HHmmStart, $XXIf_HHmmEnd = "") {
    if ($XXIf_HHmmEnd == "") {
        $XXIf_HHmmEnd = date("H:i");
    }

    try {
        $XXIf_RetVal = (strtotime($XXIf_HHmmEnd->format("H:i")) - strtotime($XXIf_HHmmStart->format("H:i"))) / 60;
    } catch (Exception $ex) {
        $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
        require_once 'dev.godtnok.xxi.master.errorHandler.php';
    }
}

// function XXIf_MinutesBetwee

function XXIf_HotelName($mpehotel = 0, $field = 'short') {
    require 'dev.godtnok.xxi.master.variables.php';

    try {
        $tsql = file_get_contents('dev.godtnok.xxi.master.hotels.sql');
        #$tsql = str_replace("#DATABASE", $XXIv_DB_Database, $tsql);
        #$tsql = str_replace("#SCHEMA", $XXIv_DB_Schema, $tsql);
        $tsql = str_replace("#PREFIX", $XXIv_DB_Prefix, $tsql);
        $tsql = str_replace("#MPEHOTEL", $mpehotel, $tsql);

        $sqlConnection = new PDO("odbc:$XXIv_DB_DSN", $XXIv_DB_User, $XXIv_DB_Password);
    } catch (Exception $e) {
        $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
        require_once 'dev.godtnok.xxi.master.errorHandler.php';
    }

    $sqlStatement = $sqlConnection->query($tsql);
    $sqlRecords = $sqlStatement->fetchAll();

    if (count($sqlRecords) == 0) {
        $retVal = -1;
    } else {
        $retVal = $sqlRecords[0][$field];
    }
    $sqlConnection = null;

    return utf8_encode($retVal);
}

function XXIf_CheckYMD($ymdDate) {
    $ymdYear = substr($ymdDate, 0, 4);
    $ymdMonth = substr($ymdDate, 5, 2);
    $ymdDay = substr($ymdDate, 8, 2);
    // If Date variable is valid, assign to SQL replace parameter.
    //   Otherwise, use $XXI_Kitchen_date from dev.godtnok.xxi.master.variables.php

    if (checkdate($ymdMonth, $ymdDay, $ymdYear)) {
        return true;
    }
}

function XXIf_rebuildUrl($parameter, $value) {

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $XXI_url = "https://";
    else
        $XXI_url = "http://";
// Append the host(domain name, ip) to the URL.   
    $XXI_url .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL   
    $XXI_url .= $_SERVER['REQUEST_URI'];
    if (strpos($XXI_url, $parameter . "=")) {
//        $XXI_urlTomorrow = preg_replace('/Date=\d{4}-\d{2}-\d{2}/', 'Date=' . $XXI_ymdTomorrow, $XXI_url);
        $XXI_newUrl = preg_replace('/(\b' . $parameter . '\b)(=*)(\d*)/', $parameter . '=' . $value, $XXI_url);
    } else {
        if (strpos($XXI_url, '?')) {
            //$XXI_urlYesterday = $XXI_url . '&Date=' . $XXI_ymdYesterday;
            //$XXI_urlTomorrow = $XXI_url . '&Date=' . $XXI_ymdYesterday;
            $XXI_newUrl = $XXI_url . "&$parameter=$value";
        } else {
            //$XXI_urlYesterday = $XXI_url . '?Date=' . $XXI_ymdYesterday;
            //$XXI_urlTomorrow = $XXI_url . '?Date=' . $XXI_ymdYesterday;
            $XXI_newUrl = $XXI_url . "?$parameter=$value";
        }
    }
    return $XXI_newUrl;
}
