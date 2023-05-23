<?php
/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.html.head.master.php
 * Purpose: HTML master header
 * Changelog:
 *  2021-05-08: Created
 *  2022-04-24: Changed from hybrid to pure PHP
 *              Changed load priority - called from main page
 *  2022-04-28: Switched from CDN sources to local files under /resources (ref master.variables)
 *              Bootstrap 5.1.3
 *              Bootstrap Icons 1.5.0
 *              jQuery 3.6.0 (new)
 *  2023-01-28: Disabled caching <meta>
 * 
 */


echo '<!DOCTYPE html>' . "\n";
echo '<html>' . "\n";
echo '    <head>' . "\n";
echo '        <meta charset="' . $XXIv_HTMLEncoding .'">' . "\n";
echo '        <meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";

echo '        <title>' . $XXIv_HTMLTitle . '</title>' . "\n";

echo '        <link rel="icon" href="' . $XXIv_favicon . '?v=1.1">' . "\n";
echo '        <link href="' . $XXIv_BootstrapCSS . '?v=1.1" rel="stylesheet">' . "\n";
echo '        <link href="' . $XXIv_BootstrapIcons . '?v=1.1" rel="stylesheet">' . "\n";

echo '        <script src="' . $XXIv_Popper . '?v=1.1"></script>' . "\n";
echo '        <script src="' . $XXIv_BootstrapJS . '?v=1.1"></script>' . "\n";
echo '        <script src="' . $XXIv_jQuery . '?v=1.1"></script>' . "\n";

echo '        <meta http-equiv="cache-control" content="no-cache">' . "\n";
echo '        <meta http-equiv="expires" content="0">' . "\n";
echo '        <meta http-equiv="pragma" content="no-cache">' . "\n";
        