<?php
/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.functions.php
 * Purpose: Various embedded functions related to page001
 * Changelog:
 *  2021-05-12: Created
 *  2022-04-20: Modal popup functionality added to XXI_Strip()
 *              New parameter: $XXIf_ref = "-1" // JSON array with popup data
 *              New parameter: $XXIf_popup = 0  // Enable onClick modal popup event for strip
 *  2022-04-23: New function XXIf_toggleRefresh to toggle page reloading
 *  2022-04-24: bq01 data5/xtext -- Only add <li></li> if len() > 1 to remove empty bullets
 */

function XXI_Summary(
        $XXIf_Quantity = "",
        $XXIf_Article = "") {
    
}

function XXI_Strip(
        $XXIf_counter = 0,
        $XXIf_time = "08:01",
        $XXIf_activity = "",
        $XXIf_location = "",
        $XXIf_guest = "",
        $XXIf_quantity = "0¤0¤0¤0¤0",
        $XXIf_note1 = "",
        $XXIf_note2 = "",
        $XXIf_note3 = "",
        $XXIf_color = "Light",
        $XXIf_tone = 1,
        $XXIf_text = "text-dark",
        $XXIf_source = "unknown",
        $XXIf_ref = "-1",
        $XXIf_popup = 0) {

    require 'dev.godtnok.xxi.master.variables.php';

    $XXIf_color = strtoupper(trim($XXIf_color));

    switch ($XXIf_tone) {
        case 1:
            switch ($XXIf_color) {
                case "PRIMARY":
                    $XXIf_color = $XXI_Raster1Primary;
                    break;
                case "SECONDARY":
                    $XXIf_color = $XXI_Raster1Secondary;
                    break;
                case "SUCCESS":
                    $XXIf_color = $XXI_Raster1Success;
                    break;
                case "DANGER":
                    $XXIf_color = $XXI_Raster1Danger;
                    break;
                case "WARNING":
                    $XXIf_color = $XXI_Raster1Warning;
                    break;
                case "INFO":
                    $XXIf_color = $XXI_Raster1Info;
                    break;
                case "LIGHT":
                    $XXIf_color = $XXI_Raster1Light;
                    break;
                case "DARK":
                    $XXIf_color = $XXI_Raster1Dark;
                    break;
                default:
                    break;
            }
            break;
        case 2:
            switch ($XXIf_color) {
                case "PRIMARY":
                    $XXIf_color = $XXI_Raster2Primary;
                    break;
                case "SECONDARY":
                    $XXIf_color = $XXI_Raster2Secondary;
                    break;
                case "SUCCESS":
                    $XXIf_color = $XXI_Raster2Success;
                    break;
                case "DANGER":
                    $XXIf_color = $XXI_Raster2Danger;
                    break;
                case "WARNING":
                    $XXIf_color = $XXI_Raster2Warning;
                    break;
                case "INFO":
                    $XXIf_color = $XXI_Raster2Info;
                    break;
                case "LIGHT":
                    $XXIf_color = $XXI_Raster2Light;
                    break;
                case "DARK":
                    $XXIf_color = $XXI_Raster2Dark;
                    break;
                default:
                    break;
            }
            break;
        default:
            $XXIf_color = $XXI_Raster1Light;
    }
    ?>

    <div class="row card p-2 bg-gradient border" style="background-color: <?php echo $XXIf_color; ?>;" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>">

        <div class="row fs-4" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row1">
            <div class="col-1" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row1_col1">
                <?php
                if ($XXIf_popup == 1) {
                    echo '<a class="stretched-link" data-bs-toggle="modal" data-bs-target="#XXI_popup" onclick="populatePopup(\'' . $XXIf_ref . '\', \'' . $XXIf_location . '\')"></a>' . $XXIf_time . "\n";
                } else {
                    echo $XXIf_time;
                }
                ?>
            </div>
            <div class="col-1" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row1_col2">
                <?php echo explode('¤', $XXIf_quantity)[0]; ?>
            </div>
            <div class="col-4" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row1_col2">
                <?php echo $XXIf_activity; ?>
            </div>
            <div class="col-3" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row1_col3">
                <?php echo $XXIf_location; ?>
            </div>
            <div class="col-3" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row1_col4">
                <?php echo $XXIf_guest; ?>
            </div>
        </div>
        <div class="row" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row2">
            <!-- NEEDS WORK -->
            <div class="col-2" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row2_col1">
                <?php if ($XXIv_Kitchen_combineChildren == 0) { ?>
                    <?php echo explode('¤', $XXIf_quantity)[1]; ?>
                    <?php echo explode('¤', $XXIf_quantity)[2]; ?>
                    <?php echo explode('¤', $XXIf_quantity)[3]; ?>
                    <?php echo explode('¤', $XXIf_quantity)[4]; ?>
                <?php } ?>
            </div>
            <div class="col-4" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row2_col2">
                <?php echo $XXIf_note1; ?>
            </div>
            <div class="col-3" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row2_col3">
                <?php echo $XXIf_note2; ?>
            </div>
            <div class="col-3 fst-italic" id="XXI_page_inner_main_strip<?php echo $XXIf_counter; ?>_row2_col4">
                <?php echo $XXIf_note3; ?>
            </div>
        </div>

    </div>

    <?php
}

// function XXI_Strip

function XXIf_toggleRefresh($XXI_parameter) {

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $XXI_url = "https://";
    else
        $XXI_url = "http://";
// Append the host(domain name, ip) to the URL.   
    $XXI_url .= $_SERVER['HTTP_HOST'];

// Append the requested resource location to the URL   
    $XXI_url .= $_SERVER['REQUEST_URI'];
    if (strpos($XXI_url, $XXI_parameter)) {
        if (strpos($XXI_url, '?' . $XXI_parameter . '&')) {
            $XXI_newUrl = preg_replace('/\b\?' . $XXI_parameter . '&\b/', '?', $XXI_url);
        } else if (strpos($XXI_url, '&' . $XXI_parameter . '&')) {
            $XXI_newUrl = preg_replace('/\b\&' . $XXI_parameter . '&\b/', '&', $XXI_url);
        } else {
            $XXI_newUrl = preg_replace('/\b\&' . $XXI_parameter . '\b/', '', $XXI_url);
        }
    } else {
        if (strpos($XXI_url, '?')) {
            $XXI_newUrl = $XXI_url . '&' . $XXI_parameter;
        } else {
            $XXI_newUrl = $XXI_url . '?' . $XXI_parameter;
        }
    }

    return $XXI_newUrl;
}
