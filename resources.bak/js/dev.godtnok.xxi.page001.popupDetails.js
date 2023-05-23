/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.popupDetails.js
 * Purpose: Generate modal div to show/edit activity details
 * Changelog:
 *  2022-04-13: Created
 */

function populatePopup(XXI_Source, XXI_eventId) {
    var popup = document.getElementById('XXI_popup');

    document.getElementById('eventID').innerHTML = XXI_eventId;
    document.getElementById('MytextBox').value = XXI_Source;

}