/*
 * Sprado XXI
 * Copyright 2021 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.scrollToStrip.js
 * Purpose: Scroll to highlighted strip in overview
 * Changelog:
 *  2021-05-14: Created
 *  2022-04-01: Changed the offsetTop to decrease by the height of the stripHeader div
 */

var myElement = document.getElementById('XXI_page_inner_main_stripX');
var topElement = document.getElementById('XXI_page_inner_main_stripHeader');

window.onload = function () {

    var topPos = myElement.offsetTop;
    var topSpacing = topElement.offsetHeight;

    topPos -= topSpacing;

    document.getElementById('overview').scrollTop = topPos;
};
