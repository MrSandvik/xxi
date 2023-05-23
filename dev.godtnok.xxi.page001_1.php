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
 *  2022-10-03: Added DIV overview-main to better contain table in print layout
 *  2022-10-23: Replaced #DATABASE.#SCHEMA. with #PREFIX
 *  2022-11-21: datePicker locale management; Change both in variables and dev.godtnok.xxi.page001.datePicker.js
 *  2022-12-08: utf8encode()) all output strings
 *  2022-12-19: Replaced utf8_encode($var) with utf8_encode($var )
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
$tsql = str_replace("#PREFIX", $XXIv_DB_Prefix, $tsql);
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
    $XXI_Sort = [$x]["sortorder"];
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
//array_multisort($XXI_SummaryAdults, SORT_DESC, $XXI_Sort);
// Toggle play or pause button based on current NoRefresh parameter
$XXI_navbar_command_pauseIcon = "bi bi-play-btn";
if (!isset($_REQUEST["NoRefresh"])) {
    echo '        <meta http-equiv="refresh" id="XXI_metaRefresh" content="' . $XXIv_Refresh . '">' . "\n";
    $XXI_navbar_command_pauseIcon = "bi bi-pause-btn";
}

// Toggle expand or collapse button based on current Expand parameter
$XXI_navbar_command_expandIcon = "bi bi-arrows-collapse";
if (!isset($_REQUEST["Expand"])) {
    $XXI_navbar_command_expandIcon = "bi bi-arrows-expand";
}

// Toggle view/hide button based on current viewHistory parameter
$XXI_navbar_command_historyIcon = "bi bi-eye-slash";
if (!isset($_REQUEST["History"])) {
    $XXI_navbar_command_historyIcon = "bi bi-eye";
}

// Start rendering page
?>
<link rel="stylesheet" href="/resources/css/dev.godtnok.xxi.master.datePicker.css?v=1.1">
<link rel="stylesheet" href="/resources/datatables/datatables.min.css?v=1.1">

<script src="/resources/js/dev.godtnok.xxi.master.datePicker.js?v=1.1"></script>
<script src="/resources/js/dev.godtnok.xxi.master.datePicker_locales/<?php echo $XXIv_DatePicker['language']; ?>.js?v=1.1"></script>
<script src="/resources/js/dev.godtnok.xxi.page001.printDiv.js?v=1.1"></script>
<script src="/resources/js/dev.godtnok.xxi.page001.popupDetails.js?v=1.1"></script>
<script src="/resources/datatables/datatables.min.js?v=1.1"></script>


<style>
    table tbody tr:nth-child(even):hover td{
        background-color: lightyellow !important;
    }

    table tbody tr:nth-child(odd):hover td {
        background-color: lightyellow !important;
    }

    .summary1 {
        width: 4rem;
    }

</style>
</head>

<body>
    <?php
    require '002/dev.godtnok.xxi.main.navbarTop.php';
    require 'dev.godtnok.xxi.page001.navbar.bottom.php';
    ?>

    <div class="container-fluid m-0" id="XXI_page" style="top: 50px; height: calc(100vh - 108px); position: absolute;">


        <div class="row gx-1" id="XXI_page_inner">   

            <div class="col-2" id="XXI_page_inner_left" style="height: calc(100vh - 108px); overflow-y: auto; overflow-x: hidden; -webkit-scrollbar{
                    width: 6px;
                }">

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

                <div class="col col-12">
                    &nbsp;
                </div>



                <table id="summary" class="summary table table-striped table-bordered table-hover" style="table-layout: fixed; width: 100%">
                    <thead>
                        <tr>
                            <th class="p-1 fs-6 summary1">Serve</th>
                            <th class="p-1 fs-6">Order</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $XXI_Counter = 0;
                        foreach ($XXI_SummaryAdults as $XXI_Article => $XXI_Quantity) {
                            $XXI_Article = utf8_encode($XXI_Article);
                            if ($XXI_SummaryChildren[$XXI_Article] > 0) {
                                $XXI_QuantityShow = $XXI_Quantity . ' (' . $XXI_SummaryChildren[$XXI_Article] . ')';
                            } else {
                                $XXI_QuantityShow = $XXI_Quantity;
                            }
                            ?>

                            <tr> 
                                <td class="p-1 fs-6 summary1"> <!-- Quantity -->
                                    <?php echo $XXI_QuantityShow; ?>&nbsp;
                                </td> <!-- Quantity end -->

                                <td class="p-1 fs-6 text-truncate xxiArticle" style="cursor: zoom-in" onMouseOver="toolTip('Click to filter')" onMouseOut="toolTip('')"> <!-- Serve -->
                                    <?php echo explode('¤', $XXI_Article)[1]; ?>&nbsp;
                                </td> <!-- Serve end -->
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

            </div>




            <!-- Test area -->


            <!-- Test area end -->



            <div class="col-10" style="padding-left: 10px" id="XXI_page_inner_main">

                <div class="overview-div" style="height: calc(100vh - 108px); overflow-y: auto; overflow-x: hidden">

                    <div id="overview-main">
                        <table id="overview" class="table table-striped table-bordered table-hover">
                            <thead style="position: sticky; top: 0;">
                                <tr>
                                    <th></th>
                                    <th>Time</th>
                                    <th>Serve</th>
                                    <th>Order</th>
                                    <th>Location</th>
                                    <th>Guest</th>
                                </tr>
                            </thead>
                            <tbody> 



                                <?php
                                $tagFound = false;

                                for ($x = 0; $x < $sqlCount; $x++) {

                                    if (strtotime($sqlRecords[$x]['time1']) < strtotime(date("H:i"))) {
                                        $XXI_marker = "bg-light";
                                        if (!isset($_REQUEST["History"])) {
                                            continue;
                                        }
                                    } elseif ((strtotime($sqlRecords[$x]['time1']) - strtotime(date("H:i"))) <= $XXIv_Kitchen_prepTime) {
                                        $XXI_marker = "bg-success";
                                    } else {
                                        $XXI_marker = "bg-warning";
                                    }

                                    if (($XXI_marker == "success" || $XXI_marker == "warning") && $tagFound == false) {
                                        $y = 'X';
                                        $tagFound = true;
                                    }

                                    if (substr($sqlRecords[$x]["subquery"], 0, 2) == "bu" || substr($sqlRecords[$x]["subquery"], 0, 2) == "fl") {
                                        $XXI_Location = $sqlRecords[$x]['location'];
                                    } else {
                                        $XXI_Location = $sqlRecords[$x]['location'];
                                    }
                                    $XXI_Location = utf8_encode($XXI_Location);
                                    $XXI_ArtText = utf8_encode($sqlRecords[$x]['arttext']);
                                    $XXI_Data1 = utf8_encode($sqlRecords[$x]['data1']);
                                    $XXI_Data2 = utf8_encode($sqlRecords[$x]['data2']);
                                    $XXI_Data3 = utf8_encode($sqlRecords[$x]['data3']);
                                    $XXI_Data4 = utf8_encode($sqlRecords[$x]['data4']);
                                    $XXI_Data5 = utf8_encode($sqlRecords[$x]['data5']);
                                    $XXI_Guest = utf8_encode($sqlRecords[$x]['guest1'] . " " . $sqlRecords[$x]['guest2']);
                                    

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
                                    ?>

                                    <tr <?php
                                    if ($y === 'X') {
                                        echo 'id = "' . XXI_page_inner_main_stripX . '"';
                                    }
                                    ?>
                                        > <!-- Row 1 -->
                                        <td class="p-0 <?php echo $XXI_marker; ?>"></td>
                                        <td class="p-1"> <!-- Column 1 -->
                                            <div class="row fs-4"> <!-- Time -->
                                                <div class="col-12">
                                                    <?php echo $sqlRecords[$x]['time1']; ?>
                                                </div>
                                            </div>
                                            <div class="row fs-5"> <!-- Data1 -->
                                                <div class="col-12">
                                                    <?php echo $data1; ?>
                                                </div>
                                            </div>
                                        </td> <!-- Column 1 end -->

                                        <td class="p-1"> <!-- Column 2 -->
                                            <div class="row fs-4"> <!-- Serve -->
                                                <div class="col-12">
                                                    <?php echo $XXI_guestCount; ?>
                                                </div>
                                            </div>
                                            <div class="row fs-5"> <!-- Data2 -->
                                                <div class="col-12">
                                                    <?php echo $data2; ?>
                                                </div>
                                            </div>
                                        </td> <!-- Column 2 end -->

                                        <td class="p-1"> <!-- Column 3 -->
                                            <div class="row fs-4"> <!-- Order -->
                                                <div class="col-12">
                                                    <?php echo $XXI_ArtText; ?>
                                                </div>
                                            </div>
                                            <div class="row fs-5"> <!-- Data3 -->
                                                <div class="col-12">
                                                    <?php echo $XXI_Data1; ?>
                                                </div>
                                            </div>
                                        </td> <!-- Column 3 end -->

                                        <td class="p-1"> <!-- Column 4 -->
                                            <div class="row fs-4"> <!-- Location -->
                                                <div class="col-12">
                                                    <?php echo $XXI_Location; ?>
                                                </div>
                                            </div>
                                            <div class="row fs-5"> <!-- Data 4 -->
                                                <div class="col-12">
                                                    <?php echo $XXI_Data2; ?>
                                                </div>
                                            </div>
                                        </td> <!-- Column 4 end -->

                                        <td class="p-1"> <!-- Column 5 -->
                                            <div class="row fs-4"> <!-- Guest -->
                                                <div class="col-12">
                                                    <?php echo $XXI_Guest; ?>
                                                </div>
                                            </div>
                                            <div class="row fs-5"> <!-- Data 5 -->
                                                <div class="col-12">
                                                    <?php
                                                    if ($XXI_Data5 <> '') {
                                                        echo rtrim($XXI_Data5);
                                                    }
                                                    if ($XXI_Data5 <> '' && $XXI_Data3 <> '') {
                                                        echo "<br>\n";
                                                    }
                                                    if ($XXI_Data3 <> '') {
                                                        echo $XXI_Data3;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </td> <!-- Column 5 end -->

                                    </tr> <!-- Row 1 end -->



                                    <?php
                                }
                                $y = $x;
                                ?>
                        </table>
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

    <script src="/resources/js/dev.godtnok.xxi.master.navbarDateTime.js?v=1.1"></script>
    <script src="/resources/js/dev.godtnok.xxi.page001.datePicker.js?v=1.1"></script>
    <script>
                                oTable = $('#overview').DataTable({
                                    fixedHeader: true,
                                    paging: false,
                                    info: false,
                                    order: [],
                                    columnDefs: [
                                        {orderable: false, targets: [0]},
                                        {searchable: false, targets: [0]},
                                        {width: 2, targets: 0}
                                    ]
                                });

                                // Send clicked text to table search box and execute
                                $('.xxiArticle').click(function () {
                                    oTable.search('"' + $(this).text().trim() + '"').draw();
                                });

                                $(document).on('keydown', function (event) {
                                    // If ESC is pressed; Clear table search box and focus
                                    if (event.key === "Escape") {
                                        $('#myInputTextField').val('');
                                        oTable.search('').draw();
                                        $('div.dataTables_filter input').select();
                                    }

                                    // If F3 is pressed; Override browser search and place focus+select in table search box
                                    if (event.key === "F3") {
                                        $('div.dataTables_filter input').focus();
                                        $('div.dataTables_filter input').select();
                                        return false;
                                    }
                                });


    </script>



</body>
</html>


