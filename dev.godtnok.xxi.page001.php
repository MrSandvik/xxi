<?php
/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.php
 * Purpose: Kitchen activity report
 * Changelog:
 *  2021-05-08: Created
 *  2021-09-09: Grouping items
 *              Filter department
 *  2021-09-15: Renamed global variables to XXIv_..
 *              New URL parameters:
 *                Date=yyyy-mm-dd
 *                Location=x (mpehotel)
 *                Expand      - Do not collapse Sprado_Combine items
 *                All         - Show all bq regardless of booking status
 *                NoRefresh   - No automatic page refresh
 *                GetQuery    - Debugging; Display SQL query
 *                GetArray    - Debugging; Display return data in JSON array
 *  2022-02-23: Added date picker dev.godtnok.xxi.simplePicker.js & dev.godtnok.xxi.datePicker.js
 *  2022-02-25: Added counting children (option in Variables)
 *  2022-03-24: Added print functionality - click logo
 *              Logo image is now read from master variables
 *  2022-03-25: Improved error handling/logging
 *  2022-04-01: Added ?n=rand() to <script src> tags to prevent scripts being stuck in cache
 *  2022-04-03: Summary sorted by article group sort value
 *              Various bug fixes and code improvements
 *  2022-04-05: Added counting of children. See variables for combining or splitting (currently not in overview table)
 *  2022-04-11: If sum of children = 0, hide value from overview (when combined = 1)
 *  2022-04-11: Added counting of children in summary table
 *  2022-04-21: Added cookie for Location retention
 *  2022-04-23: Replaced datepicker with https://github.com/mymth/vanillajs-datepicker
 *              Added iconized menu bar. Most mirror existing functionality elsewhere on the page
 *                - Home (inactive)
 *                - Print
 *                - Pause/resume auto refresh
 *                - Location/mpehotel
 *                - Date picker 
 *              Added location selector by clicking current location name
 *  2022-04-24: Added icon to menu bar
 *                - Expand/collapse
 *              Added spinner to indicate page loading onclick="XXIf_spinner()"
 */
require 'dev.godtnok.xxi.master.variables.php';
require 'dev.godtnok.xxi.master.functions.php';
require 'dev.godtnok.xxi.page001.functions.php';
require 'dev.godtnok.xxi.master.head.php';

//Error handling (suppressing warnings)
set_exception_handler('my_exception_handler');
error_reporting(E_ERROR);

function my_exception_handler($e) {
  error_log("SQL Error: (" . basename(__FILE__) . "): Data read error. $e");
}

// Read [Date] variable from URL and parse:
if (isset($_REQUEST["Date"])) {
  $XXI_ymdDate = htmlspecialchars($_REQUEST["Date"]);
} else {
  $XXI_ymdDate = "";
}
// If Date variable is valid, assign to SQL replace parameter.
//   Otherwise, use $XXI_Kitchen_date from dev.godtnok.xxi.master.variables.php
if ($XXI_ymdDate <> "") {
  if (XXIf_CheckYMD($XXI_ymdDate)) {
    $XXIv_Kitchen_date = $XXI_ymdDate;
  }
} else {
  $XXI_ymdDate = $XXIv_Kitchen_date;
}

$XXI_ymdYesterday = date('Y-m-d', strtotime($XXI_ymdDate . '-1 day'));
$XXI_ymdTomorrow = date('Y-m-d', strtotime($XXI_ymdDate . '+1 day'));

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
  $XXI_url = "https://";
else
  $XXI_url = "http://";
// Append the host(domain name, ip) to the URL.   
$XXI_url .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL   
$XXI_url .= $_SERVER['REQUEST_URI'];

if (strpos($XXI_url, "Date=")) {
  $XXI_urlYesterday = preg_replace('/Date=\d{4}-\d{2}-\d{2}/', 'Date=' . $XXI_ymdYesterday, $XXI_url);
  $XXI_urlTomorrow = preg_replace('/Date=\d{4}-\d{2}-\d{2}/', 'Date=' . $XXI_ymdTomorrow, $XXI_url);
} else {
  if (strpos($XXI_url, '?')) {
    $XXI_urlYesterday = $XXI_url . '&Date=' . $XXI_ymdYesterday;
    $XXI_urlTomorrow = $XXI_url . '&Date=' . $XXI_ymdTomorrow;
    $XXI_url = $XXI_url . '&Date=' . $XXI_ymdDate;
  } else {
    $XXI_urlYesterday = $XXI_url . '?Date=' . $XXI_ymdYesterday;
    $XXI_urlTomorrow = $XXI_url . '?Date=' . $XXI_ymdTomorrow;
    $XXI_url = $XXI_url . '?Date=' . $XXI_ymdDate;
  }
}

// Read [Location] variable from URL and parse:
if (isset($_REQUEST["Location"])) {
  $XXI_mpeHotel = htmlspecialchars($_REQUEST["Location"]);

  if (!in_array($XXI_mpeHotel, $XXIv_Kitchen_mpehotel_enabled)) {
    $XXI_mpeHotel = $XXIv_Kitchen_mpehotel_default;
  }

  setcookie('XXI_locationCookie', $XXI_mpeHotel, $XXIv_Kitchen_locationCookie_expire);
} else {
  if (isset($_COOKIE['XXI_locationCookie'])) {
    $XXI_mpeHotel = $_COOKIE['XXI_locationCookie'];
    setcookie('XXI_locationCookie', $XXI_mpeHotel, $XXIv_Kitchen_locationCookie_expire);
  } else {
    $XXI_mpeHotel = $XXIv_Kitchen_mpehotel_default;
    setcookie('XXI_locationCookie', $XXI_mpeHotel, $XXIv_Kitchen_locationCookie_expire);
  }
}

// Check if [Expand] variable is present in URL
//   If true: Inject #SELECT module for returning all individual records
//   If false: Inject #SELECT module for combining flagged items
if (isset($_REQUEST["Expand"])) {
  $XXI_Select = file_get_contents('dev.godtnok.xxi.page001.main.injectExpanded.sql');
} else {
  $XXI_Select = file_get_contents('dev.godtnok.xxi.page001.main.injectCombined.sql');
  $XXI_Select = str_replace("#BKFSTNAME", $XXIv_Kitchen_CombinedBkfstName, $XXI_Select);
}

if (isset($_REQUEST["All"])) {
  $XXIv_Kitchen_bqHideStatus = 2;
}

$tsql = file_get_contents('dev.godtnok.xxi.page001.main.sql');
$tsql = str_replace("#DATABASE", $XXIv_DB_Database, $tsql);
$tsql = str_replace("#SCHEMA", $XXIv_DB_Schema, $tsql);
$tsql = str_replace("#DATE", $XXI_ymdDate, $tsql);
$tsql = str_replace("#MPEHOTEL", $XXI_mpeHotel, $tsql);
$tsql = str_replace("#BQHIDESTATUS", $XXIv_Kitchen_bqHideStatus, $tsql);
$tsql = str_replace("#BUHIDESTATUS", $XXIv_Kitchen_buHideResstatus, $tsql);
$tsql = str_replace("#ZIHIDEKAT", $XXIv_Kitchen_ziHideCategories, $tsql);
$tsql = str_replace("#RESULT", $XXI_Select, $tsql);

if (isset($_REQUEST["GetQuery"])) {
  // Load SqlFormatter library
  require_once 'resources/php/lib/SqlFormatter.php';
  // Break queries into array
  $xxi_queries = SqlFormatter::splitQuery($tsql);

  echo '    </head>' . "\n";
  echo '    <body>' . "\n";
  echo '        <div class="container">' . "\n";

  foreach ($xxi_queries as $xxi_query) {
    echo '            <div class="bg-light border border-2 border-white rounded-1">' . "\n";
    echo '                ' . SqlFormatter::highlight($xxi_query);
    echo '            </div>' . "\n";
  }

  echo '        </div>' . "\n";
  echo '    </body>' . "\n";
  echo '</html>';
  die;
}

try {
  $sqlConnection = new PDO("odbc:$XXIv_DB_DSN", $XXIv_DB_User, $XXIv_DB_Password);
} catch (\Exception $e) {
  $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
  require 'dev.godtnok.xxi.master.errorHandler.php';
}

try {
  $sqlStatement_Details = $sqlConnection->query($tsql);

  if ($sqlConnection->errorInfo()[0] <> '00000') {
    echo "<pre>/*";
    print_r($sqlConnection->errorInfo());
    echo "<hr>*/";
    echo $tsql;
    die;
  }

  $sqlRecords = $sqlStatement_Details->fetchAll(PDO::FETCH_ASSOC);

  if (isset($_REQUEST["GetArray"])) {
    echo "<pre>";
    echo(json_encode($sqlRecords, JSON_PRETTY_PRINT));
    echo "</pre>";
    die;
  }
} catch (Exception $e) {
  $XXI_errorLocation = basename(__FILE__, '.php') . " (Line: " . __LINE__ . "): ";
  require 'dev.godtnok.xxi.master.errorHandler.php';
}

$sqlCount = count($sqlRecords);

$XXI_SummaryAdults = array();
$XXI_SummaryChildren = array();
for ($x = 0; $x < $sqlCount; $x++) {
  $XXI_SummaryName = $sqlRecords[$x]["sortorder"] . '¤' . $sqlRecords[$x]["arttext"];
  $XXI_Adults = $sqlRecords[$x]["adults"];
  $XXI_Children = $sqlRecords[$x]["children1"] + $sqlRecords[$x]["children2"] + $sqlRecords[$x]["children3"] + $sqlRecords[$x]["children4"];

  if (!isset($XXI_SummaryAdults[$XXI_SummaryName])) {
    $XXI_SummaryAdults[$XXI_SummaryName] = 0;
  }

  if (!isset($XXI_SummaryChildren[$XXI_SummaryName])) {
    $XXI_SummaryChildren[$XXI_SummaryName] = 0;
  }

  $XXI_SummaryAdults[$XXI_SummaryName] += $XXI_Adults;
  $XXI_SummaryChildren[$XXI_SummaryName] += $XXI_Children;
}
ksort($XXI_SummaryAdults);
ksort($XXI_SummaryChildren);

// Toggle play or pause button based on current NoRefresh parameter
$XXI_navbar_command_pauseIcon = "bi bi-play-btn";
if (!isset($_REQUEST["NoRefresh"])) {
  echo '<meta http-equiv="refresh" id="XXI_metaRefresh" content="' . $XXIv_Refresh . '">';
  $XXI_navbar_command_pauseIcon = "bi bi-pause-btn";
}

// Toggle expand or collapse button based on current Expand parameter
$XXI_navbar_command_expandIcon = "bi bi-arrows-collapse";
if (!isset($_REQUEST["Expand"])) {
  $XXI_navbar_command_expandIcon = "bi bi-arrows-expand";
}

// Start rendering page
?>
<link rel="stylesheet" href="/resources/css/dev.godtnok.xxi.master.datePicker.css?n=<?php //echo rand();                  ?>">

<script src="/resources/js/dev.godtnok.xxi.master.datePicker.js?n=<?php echo rand(); ?>"></script>
<script src="/resources/js/dev.godtnok.xxi.master.datePicker_locales/<?php echo $XXIv_DatePicker['language']; ?>.js?n=<?php echo rand(); ?>"></script>
<script src="/resources/js/dev.godtnok.xxi.page001.printDiv.js?n=<?php echo rand(); ?>"></script>
<script src="/resources/js/dev.godtnok.xxi.page001.popupDetails.js?n=<?php echo rand(); ?>"></script>

</head>

<body>
    <div class="container-fluid" id="XXI_page">

        <nav class="navbar navbar-dark bg-dark text-white fixed-bottom" id="XXI_navbar">
            <div class="col-2 p-1 text-start" id="XXI_navbar_branding">&nbsp;<?php echo $XXIv_HTMLTitle . ' v' . $XXIv_Version; ?></div>
            <div class="col-2" id="XXI_navbar_commands">

                <!-- Home button -->
                <i class="bi bi-house" style="font-size: 1.2rem;" id="XXI_navbar_commands_home">&nbsp;</i>

                <!-- Print putton -->
                <i class="bi bi-printer" style="font-size: 1.2rem;" id="XXI_navbar_commands_print"  onclick="printDiv('XXI_page_inner_main', 'XXI_page_inner_left')">&nbsp;</i>

                <!-- Pause/resume (NoRefresh) toggle -->
                <a href="<?php echo XXIf_toggleRefresh('NoRefresh'); ?>" style="color: inherit; text-decoration: inherit;" onclick="XXIf_spinner()">
                    <i class="<?php echo $XXI_navbar_command_pauseIcon; ?>" style="font-size: 1.2rem;" id="XXI_navbar_commands_pause"" ></i>
                </a>&nbsp;

                <!-- Location select button - mpehotel -->
                <span class="dropup">
                    <i class="bi bi-building" style="font-size: 1.2rem;" id="XXI_navbar_commands_location" data-bs-toggle="dropdown" aria-expanded="false">&nbsp;</i>
                    <ul class="dropdown-menu" aria-labelledby="XXI_navbar_commands_location">
                        <?php
                        foreach ($XXIv_Kitchen_mpehotel_enabled as $location) {
                          $XXI_newUrl = XXIf_rebuildUrl("Location", $location);
                          echo '<li><a class="dropdown-item" href="' . $XXI_newUrl . '" onclick="XXIf_spinner()">' . $location . ' - ' . XXIf_HotelName($location, 'short') . "</a></li>\n";
                        }
                        ?>
                    </ul>
                </span>

                <!-- Date selector button -->
                <span class="bi bi-calendar3" style="font-size: 1.2rem;" id="XXI_navbar_commands_date" onclick="datepicker.show()"></span>

                <!-- Expand/collapse button (Expand) -->
                <a href="<?php echo XXIf_toggleRefresh('Expand'); ?>" style="color: inherit; text-decoration: inherit;" onclick="XXIf_spinner()">
                    <i class="<?php echo $XXI_navbar_command_expandIcon; ?> " style="font-size: 1.2rem;" id="XXI_navbar_commands_expand"></i>
                </a>

            </div>
            <div class="col-4 fs-5 text-center fw-bold" id="XXI_navbar_title">Kitchen</div>
            <div class="col-4 p-1 text-end" id="XXI_navbar_datetime"><span id="XXI_navbar_datetime_time">00:00</span>&nbsp;&nbsp;<span id="XXI_navbar_datetime_date">2021-01-01</span>&nbsp;</div>
        </nav>

        <div class="row p-1" id="XXI_page_inner">   

            <div class="col-2" id="XXI_page_inner_left">

                <div class="row m-1 border">

                    <!-- Logo -->
                    <div class="col-12 p-1 text-center" id="XXI_page_inner_left_logo">
                        <img class="img-fluid" style="max-width: 200px;" src ="<?php echo $XXIv_HTMLLogo; ?>" id="XXI_page_inner_left_logo_image" onclick="printDiv('XXI_page_inner_main', 'XXI_page_inner_left')">
                    </div>

                    <div class="col-12 fs-4 text-center" id="XXI_page_inner_left_asset">

                        <!-- Hidden loader spinner -->
                        <div class="spinner-grow spinner-grow-sm text-success align-baseline" role="status" id="XXI_loadingSpinner" style="visibility: hidden;">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                        <!-- Current location name -->
                        <span class="dropdown dropdown-menu-end" id="XXI_page_inner_left_asset_location" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo XXIf_HotelName($XXI_mpeHotel, 'short'); ?>
                        </span>

                        <!-- Hidden loader spinner (filler use only) -->
                        <div class="spinner-grow spinner-grow-sm text-success align-baseline" role="status" style="visibility: hidden;">
                            <span class="visually-hidden">Filler...</span>
                        </div>

                        <!-- Location dropdown menu -->
                        <ul class="dropdown-menu" aria-labelledby="XXI_page_inner_left_asset_location">
                            <?php
                            foreach ($XXIv_Kitchen_mpehotel_enabled as $location) {
                              $XXI_newUrl = XXIf_rebuildUrl("Location", $location);
                              echo '<li><a class="dropdown-item" href="' . $XXI_newUrl . '" onclick="XXIf_spinner()">' . $location . ' - ' . XXIf_HotelName($location, 'short') . "</a></li>\n";
                            }
                            ?>
                        </ul>
                        <br>

                        <!-- Arrow-left -->
                        <?php echo '<a href="' . $XXI_urlYesterday . '" style="color: inherit; text-decoration: inherit;" onclick="XXIf_spinner()">' . $XXIv_Kitchen_leftIcon . '</a>'; ?>

                        <!-- Display current date -->
                        <input readonly class="btn text-center fs-5" type="button" id="XXI_visibleDate" value="<?php echo $XXI_ymdDate; ?>" style="padding: 0px; border: hidden; margin: 0px; space: 0px; max-width: 170px; background-color: inherit;">

                        <!-- Arrow-right -->
                        <?php echo '<a href="' . $XXI_urlTomorrow . '" style="color: inherit; text-decoration: inherit;" onclick="XXIf_spinner()">' . $XXIv_Kitchen_rightIcon . '</a>'; ?>

                    </div>

                    <div class="col-12">
                        &nbsp;
                    </div>

                    <?php
                    $XXI_Counter = 0;
                    foreach ($XXI_SummaryAdults as $XXI_Article => $XXI_Quantity) {
                      if (($XXI_Counter++ % 2) + 1 == 1) {
                        $XXI_Raster = $XXI_Raster1Light;
                      } else {
                        $XXI_Raster = $XXI_Raster2Light;
                      }

                      if ($XXI_SummaryChildren[$XXI_Article] > 0) {
                        $XXI_QuantityShow = $XXI_Quantity . ' (' . $XXI_SummaryChildren[$XXI_Article] . ')';
                      } else {
                        $XXI_QuantityShow = $XXI_Quantity;
                      }
                      ?>
                      <div class="col-3 text-start" style="background-color: <?php echo $XXI_Raster; ?>;">
                          <?php echo $XXI_QuantityShow; ?>
                      </div>
                      <div class="col-9 text-truncate" style="background-color: <?php echo $XXI_Raster; ?>;">
                          <?php echo explode('¤', $XXI_Article)[1]; ?>
                      </div>
                      <?php
                    }
                    ?>
                </div>

                <!-- Test area -->


                <!-- Test area end -->



            </div>

            <div class="col-10 border" id="XXI_page_inner_main" >
                <div class="row">
                    <div class="col-12 text-white" id="XXI_page_inner_listheader" style="overflow-y: scroll;">
                        <?php XXI_Strip("Header", "Time", "Order", "Location", "Guest", "Serve", "", "", "", $XXI_ProfileColor, 2); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" id="XXI_page_inner_list" style="overflow-y: scroll; height: 85vh;">
                        <?php
                        $tagFound = false;

                        for ($x = 0; $x < $sqlCount; $x++) {

                          $y = $x;

                          if (strtotime($sqlRecords[$x]['time1']) < strtotime(date("H:i"))) {
                            $XXI_StripColor = "light";
                          } elseif ((strtotime($sqlRecords[$x]['time1']) - strtotime(date("H:i"))) <= $XXIv_Kitchen_prepTime) {
                            $XXI_StripColor = "success";
                          } else {
                            $XXI_StripColor = "warning";
                          }

                          if (($XXI_StripColor == "success" || $XXI_StripColor == "warning") && $tagFound == false) {
                            $y = 'X';
                            $tagFound = true;
                          }

                          if (substr($sqlRecords[$x]["subquery"], 0, 2) == "bu" || substr($sqlRecords[$x]["subquery"], 0, 2) == "fl") {
                            $XXI_Location = "Room " . $sqlRecords[$x]['location'];
                          } else {
                            $XXI_Location = $sqlRecords[$x]['location'];
                          }

                          $XXI_combinedChildren = 0;
                          if ($XXIv_Kitchen_combineChildren == 1) {
                            $XXI_combinedChildren = ($sqlRecords[$x]['children1'] +
                                    $sqlRecords[$x]['children2'] +
                                    $sqlRecords[$x]['children3'] +
                                    $sqlRecords[$x]['children4']);
                            if ($XXI_combinedChildren > 0) {
                              $XXI_guestCount = $sqlRecords[$x]['adults'] . ' (' . $XXI_combinedChildren . ')';
                            } else {
                              $XXI_guestCount = $sqlRecords[$x]['adults'];
                            }
                          } else {
                            $XXI_guestCount = $XXIv_Kitchen_adultIcon . $sqlRecords[$x]['adults'] . '¤' .
                                    $XXIv_Kitchen_child1Icon . $sqlRecords[$x]['children1'] . '¤' .
                                    $XXIv_Kitchen_child2Icon . $sqlRecords[$x]['children2'] . '¤' .
                                    $XXIv_Kitchen_child3Icon . $sqlRecords[$x]['children3'] . '¤' .
                                    $XXIv_Kitchen_child4Icon . $sqlRecords[$x]['children4'];
                          }

                          XXI_Strip(
                                  $y,
                                  $sqlRecords[$x]['time1'],
                                  $sqlRecords[$x]['arttext'],
                                  $XXI_Location,
                                  $sqlRecords[$x]['guest1'] . " " . $sqlRecords[$x]['guest2'],
                                  $XXI_guestCount,
                                  $sqlRecords[$x]['data1'],
                                  $sqlRecords[$x]['data2'],
                                  $sqlRecords[$x]['data5'],
                                  $XXI_StripColor,
                                  ($x % 2) + 1,
                                  "",
                                  $sqlRecords[$x]['subquery'],
                                  $sqlRecords[$x]['bookref'],
                                  1);
                        }
                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal modal-xl fade" id="XXI_popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Event details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Event ID: <span id="eventID"></span><br><br>
                    <input type="text" id="MytextBox">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/resources/js/dev.godtnok.xxi.master.navbarDateTime.js?n=<?php echo rand(); ?>"></script>
    <script src="/resources/js/dev.godtnok.xxi.page001.scrollToStrip.js?n=<?php echo rand(); ?>"></script>
    <script src="/resources/js/dev.godtnok.xxi.page001.datePicker.js?n=<?php echo rand(); ?>"></script>
</body>
</html>


