/*
 * Sprado XXI
 * Copyright 2021 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.showdatetime.js
 * Purpose: Shows live time and date on page
 * Changelog:
 *  2021-05-13: Created
 */

function showTime() {
    var dt = new Date();
    var hr = dt.getHours(); // 0 - 23
    var mn = dt.getMinutes(); // 0 - 59
    var yr = dt.getFullYear();
    var mo = dt.getMonth() + 1;
    var dy = dt.getDate();

    hr = (hr < 10) ? "0" + hr : hr;
    mn = (mn < 10) ? "0" + mn : mn;
    mo = (mo < 10) ? "0" + mo : mo;
    dy = (dy < 10) ? "0" + dy : dy;

    document.getElementById("XXI_navbar_datetime_time").innerText = hr + ":" + mn;
    document.getElementById("XXI_navbar_datetime_time").textContent = hr + ":" + mn;
    document.getElementById("XXI_navbar_datetime_date").innerText = yr + "-" + mo + "-" + dy;
    document.getElementById("XXI_navbar_datetime_date").textContent = yr + "-" + mo + "-" + dy;

    setTimeout(showTime, 1000);
}
showTime();
