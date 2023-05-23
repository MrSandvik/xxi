<?php
/*
 * Sprado XXI
 * Copyright 2021 - AS Godtnok.com
 * All rights reserved
 * 
 * File: log.php
 * Purpose: Echo last 50 lines in Apache error log
 * Changelog:
 *  2021-05-26: Created
 */
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

        <title>Sprado XXI Log</title>
        <link rel="icon" href="/resources/icons/favicon.ico?v=1.1">

    </head>
    <body>
        <div class="rounded p-1 text-light" style="width: 200px; position: fixed; right: 10px; bottom: 10px; background-color: #333333 ;background-image: url('resources/images/godtnokA.png'); background-size: contain; background-repeat: no-repeat; background-position: right;">
            <a href="mailto:support@godtnok.com" style="color: white; hover: white; visited: white; active: white; text-decoration: none;">
                <b>Godtnok.com</b><br>
                (+47) 403 11 664</a>
        </div>
        <?php
        $regex = "/(.*\s)(.*\s\d.\s)(\d{2}:\d{2}:\d{2})(.*\s)(\d{4})/";
        $file = file("log");
        $fileLen = count($file);
        $dayMo = "xx";
        $counter = 1;
        $tone = "bg-light";
//echo  "<input type='button' value='Clear'><br>";
        echo '<div class="container p-1">';
        echo '    <div class="row bg-dark text-white p-1">';

        echo '        <div class="col-12 fs-4 text-center">';
        echo "            $_SERVER[SERVER_NAME] ($_SERVER[SERVER_ADDR])";
        echo '        </div>';
        echo '    </div>';
        
        for ($i = max(0, count($file) - 50); $i < count($file); $i++) {
            if ($dayMo <> preg_replace($regex, "[$2]  ", explode(']', $file[$i])[0]) && $dayMo <> "xx") {
                if ($tone == "bg-white") {
                    $tone = "bg-light";
                } else {
                    $tone = "bg-white";
                }
            }
            $dayMo = preg_replace($regex, "[$2]  ", explode(']', $file[$i])[0]);

        echo "    <div class=\"row bg-gradient border-bottom $tone\">";
        echo '        <div class="col-2 text-secondary">';
        echo              substr("0".$counter,-2,2)."&nbsp;&nbsp;".preg_replace($regex, "[$2$5 $3]  ", explode(']', $file[$i])[0]);
        echo '        </div>';
        echo '        <div class="col-10 text-danger font-monospace">';
        echo              isset(explode(']', $file[$i])[4]) ? explode(']', $file[$i])[4] : null;
        echo '        </div>';
        echo '    </div>';
            $counter++;
        }
        echo '</div></body></html>';