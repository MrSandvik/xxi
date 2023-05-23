/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.datePicker.js
 * Purpose: Bootstrap-like date picker
 * Changelog:
 *  2022-04-23: Created
 *  2022-04-24: Added event listener to reload page with selected date
 *              Added XXIf_spinner() to display growing spinner in front of asset name when reloading due to switching dates
 *  2022-11-21: Locale management. Change both in variables and XXI_locale='xx' in this file
 */

const XXI_locale = 'no';
const elem = document.getElementById('XXI_visibleDate');
const datepicker = new Datepicker(elem, {
    autohide: true,
    orientation: 'bottom middle',
    calendarWeeks: true,
    daysOfWeekHighlighted: [0, 6],
    todayBtn: true,
    todayHighlight: true,
    todayBtnMode: 1,
    format: 'yyyy-mm-dd',
    language: XXI_locale
});

function XXIf_spinner() {
    document.getElementById("XXI_loadingSpinner").style.visibility = 'visible';
};

elem.addEventListener('changeDate', (ev) => {
    const {date} = ev.detail;
    XXI_loadNewDate(date);
    //const message = `You selected:    
    //Year: ${date.getFullYear()}
    //Month: ${date.getMonth() + 1}
    //Day: ${date.getDate()}`;

    //alert(message);
});

function XXI_loadNewDate(dateString) {
    XXI_date = dateString.toLocaleDateString('sv');

    var XXI_url = window.location.toString();

    if (XXI_url.includes('Date=')) {
        XXI_url = XXI_url.replace(/Date=\d{4}-\d{2}-\d{2}/, "Date=" + XXI_date);
    } else {
        if (XXI_url.includes('?')) {
            XXI_url = XXI_url + '&Date=' + XXI_date;
        } else {
            XXI_url = XXI_url + '?Date=' + XXI_date;
        }
    }
    document.getElementById('XXI_visibleDate').innerHTML = 'Please wait..';
    window.location.href = XXI_url;
    
    XXIf_spinner();
}