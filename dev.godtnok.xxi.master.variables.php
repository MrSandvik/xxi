<?php

/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.master.variables.php
 * Purpose: Root level variables
 * Changelog:
 *  2021-05-08: Created
 *  2022-04-04: Added variables for adult/children1-4 counters
 *  2022-04-05: Added variable to optionally combine all counted children to one or sum per children1-4
 *  2022-04-13: Added variables for left and right arrows to show before and after the date
 *  2022-04-22: Added variable $XXIv_Kitchen_pastTime to define cut-off time for visibility of past events
 *  2022-04-25: 0.1.94 - Fixed the $XXI_ymdTomorrow assignment in page001.php
 *  2022-10-23: 0.2.76 - Added $XXIv_DB_Prefix to replace $XXIv_DB_Database.$XXIv_DB_Schema
 *  2023-01-28: pkg install php82-mbstring mod_php82 php82-pdo_odbc
 *              Remove reference to php74 in /usr/local/etc/apache24/httpd.conf
 *  2023-05-11: Fixed date selector translation to force yyyy-mm-dd
 *              utf8_encode on all output data
 */

# Versioning
$XXIv_Version = "0.3.27";

# System
date_default_timezone_set("Europe/Amsterdam");
$XXIv_ROOT = "/www/anubis";

# Database
$XXIv_DB_DSN = "protel"; // Defined in /usr/local/etc/freetds.conf
$XXIv_DB_Database = "protel";
$XXIv_DB_User = "proteluser";
$XXIv_DB_Password = "protel915930";
$XXIv_DB_Schema = $XXIv_DB_User;
$XXIv_DB_Prefix = '[protel].[proteluser].';

# Protel
$XXIv_Protel_FirstHour = 4; // Protel day change (offset hours past midnight - 4 = 04:00)

# SpradoXXI
$XXIv_Refresh = 120; // Page refresh timer in seconds
$XXIv_Refresh_OnError = 3; // Page refresh timer in seconds on connection error

# SpradoXXI - Kitchen
$XXIv_Kitchen_date = date("Y-m-d"); // Don't change this
$XXIv_Kitchen_prepTime = 3600; // Time ahead of current when items get highlighted for preparation (in seconds) - Default: Orange
$XXIv_Kitchen_pastTime = 3600; // How many seconds of prior activities to show in activity list
$XXIv_Kitchen_mpehotel_enabled = [2,3,4,5,6,7,8]; // Available locations available in activity list 
$XXIv_Kitchen_mpehotel_default = 3; // Default location
$XXIv_Kitchen_bqHideStatus = "2,4";  // 2=Deleted, 8=NoShow, 5=Prelim (2,5,8)
$XXIv_Kitchen_buHideResstatus = "0,4,5,6,7,11";  // 0=, 1=Confirmed, 2=Sent, 3=Prelim, 4=Waitlist, 5=Cancelled, 9=NoGuarantee, 11=ToBeMoved, 12=Moved (0,4,5,8)
$XXIv_Kitchen_ziHideCategories = "2";  // 1=KTO, 2=GM
$XXIv_Kitchen_CombinedBkfstName = "Frokostgjest"; // Viser som samlenavn for kombinerte frokoster
$XXIv_Kitchen_combineChildren = 1; // 1 slå sammen til én verdi - 0 summér pr. child 1-4
$XXIv_Kitchen_adultIcon = '<i class="bi-dice-6" style="font-size: 0.8rem;"></i>&nbsp;';
$XXIv_Kitchen_child1Icon = '<i class="bi-dice-1" style="font-size: 0.8rem;"></i>&nbsp;';
$XXIv_Kitchen_child2Icon = '<i class="bi-dice-2" style="font-size: 0.8rem;"></i>&nbsp;';
$XXIv_Kitchen_child3Icon = '<i class="bi-dice-3" style="font-size: 0.8rem;"></i>&nbsp;';
$XXIv_Kitchen_child4Icon = '<i class="bi-dice-4" style="font-size: 0.8rem;"></i>&nbsp;';
$XXIv_Kitchen_leftIcon = '<i class="bi bi-arrow-left-circle fs-5" style="opacity: 0.75;" id="XXI_goYesterday"></i>';
$XXIv_Kitchen_rightIcon = '<i class="bi bi-arrow-right-circle fs-5" style="opacity: 0.75;" id="XXI_goTomorrow"></i>';
$XXIv_Kitchen_locationCookie_expire = time() + (365 * 24 * 60 * 60); // 1 år

# SpradoXXI - Date picker
$XXIv_DatePicker = array('language' => 'no');

# HTML HEAD section
$XXIv_HTMLLogo = "/resources/images/logo.png";
$XXIv_HTMLTitle = "Sprado XXI";
$XXIv_HTMLEncoding = "utf-8";

$XXIv_favicon = "/resources/icons/favicon.ico";  # ?v=1.1 ensures refresh

$XXIv_BootstrapCSS = "/resources/bootstrap/css/bootstrap.min.css";
$XXIv_BootstrapIcons = "/resources/icons/font/bootstrap-icons.css";

$XXIv_jQuery = "/resources/jquery/jquery-3.6.4.min.js";
$XXIv_jQuery_ui = "/resources/jquery/jquery-ui.min.js";
$XXIv_Popper = "/resources/popper/popper.min.js";
$XXIv_BootstrapJS = "/resources/bootstrap/js/bootstrap.bundle.min.js";

# CSS visuals
$XXI_ProfileColor = "#ae9a63"; # "SMS gold"
$XXI_Raster1Primary = "#0d6efd"; #blue-500
$XXI_Raster2Primary = "#3d8bfd"; #blue-400
$XXI_Raster1Secondary = "#6c757d"; #gray-600
$XXI_Raster2Secondary = "#adb5bd"; #gray-500
$XXI_Raster1Success = "#198754"; #green-500
$XXI_Raster2Success = "#479f76"; #green-400
$XXI_Raster1Danger = "#dc3545"; #red-500
$XXI_Raster2Danger = "#e35d6a"; #red-400
$XXI_Raster1Warning = "#ffc107"; #yellow-500
$XXI_Raster2Warning = "#ffcd39"; #yellow-400
$XXI_Raster1Info = "#0dcaf0"; #cyan-500
$XXI_Raster2Info = "#3dd5f3"; #cyan-400
$XXI_Raster1Light = "#e9ecef"; #gray-200
$XXI_Raster2Light = "#f8f9fa"; #gray-100
$XXI_Raster1Dark = "#212529"; #gray-900
$XXI_Raster2Dark = "#343a40"; #gray-800
