<?php

/*
 * Sprado XXI
 * Copyright 2022 - AS Godtnok.com
 * All rights reserved
 * 
 * File: dev.godtnok.xxi.master.errorHandler.php
 * Purpose: Output and log errors from try/catch
 * Changelog:
 *  2022-03-25: Created
 */

$XXI_errorMessage = str_replace(']', '}', str_replace('[', '{', $e->getMessage()));

echo "<meta http-equiv=\"refresh\" content=\"$XXIv_Refresh_OnError\">";
echo '<link rel="icon" href="' . $XXIv_faviconError . '">';
echo "</head><body><p class='text-danger'>";
echo $XXI_errorLocation . $XXI_errorMessage . "<br><br>";
echo "Retrying in $XXIv_Refresh_OnError seconds..<br>";
echo "</p></body></html>";
error_log($XXI_errorLocation . $XXI_errorMessage . "\n");
die;