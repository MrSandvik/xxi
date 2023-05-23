/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.page001.printDiv.js
 * Purpose: Generate printer friendly page (used by clicking logo)
 * Changelog:
 *  2022-03-25: Created
 *  2022-04-12: Added 500ms delay before printing to allow page rendering first
 *  2022-04-23: Incorporated Datepicker CSS <link>
 */

function printDiv(divID1, divID2) {
    var divContents1 = document.getElementById(divID1).innerHTML;
    var divContents2 = document.getElementById(divID2).innerHTML;
    divContents1 = divContents1.replace('style="overflow-y: scroll; height: 85vh;"', '');
    var a = window.open('', '_blank');

    a.document.write('<!doctype html>');
    a.document.write('<html lang="en">');
    a.document.write('  <head>');
    a.document.write('      <meta charset=utf-8>');
    a.document.write('      <meta name="viewport" content="width=device-width, initial-scale=1">');
    a.document.write('');
    a.document.write('      <link rel="icon" href="/resources/icons/favicon.ico?v=1.1">');
    a.document.write('      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">');
    a.document.write('      <link rel="stylesheet" href="/resources/css/dev.godtnok.xxi.master.datePicker.css?v=1.1">');
    a.document.write('      <link rel="stylesheet" href="/resources/datatables/datatables.min.css?v=1.1">');
    a.document.write('');
    a.document.write('      <title>Sprado XXI</title>');
    a.document.write('');
    a.document.write('</head>');
    a.document.write('<body>');
    a.document.write(divContents2);
    a.document.write('<div style="page-break-after: always"></div>');
    a.document.write(divContents1);
    a.document.write('</body></html>');
    setTimeout(function () {
        // Give page 500ms to finish loading before printing and closing
        a.print();
        a.close();
        a.document.close();
    }, 500);
}