
<nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white fixed-top" style="height: 50px; padding-left: 5px;">

    <div class="col-4" id="XXI_navbar_commands">

        <div style="display: inline-block; cursor: pointer">
            <!-- Home button -->
            <a href="/index.php" style="color: inherit; text-decoration: inherit" onclick="XXIf_spinner()" onMouseOver="toolTip('Home')" onMouseOut="toolTip('')">
                <i class="bi bi-house" style="font-size: 1.2rem;" id="XXI_navbar_commands_home">&nbsp;</i>
            </a>

            <!-- Print putton -->
            <i class="bi bi-printer" style="font-size: 1.2rem" id="XXI_navbar_commands_print"  onclick="printDiv('overview-main', 'XXI_page_inner_left')" onMouseOver="toolTip('Print')" onMouseOut="toolTip('')">&nbsp;</i>

            <!-- Pause/resume (NoRefresh) toggle -->
            <a href="<?php echo XXIf_toggleRefresh('NoRefresh'); ?>" style="color: inherit; text-decoration: inherit" onclick="XXIf_spinner()" onMouseOver="toolTip('Pause')" onMouseOut="toolTip('')">
                <i class="<?php echo $XXI_navbar_command_pauseIcon; ?>" style="font-size: 1.2rem" id="XXI_navbar_commands_pause"></i>
            </a>&nbsp;

            <!-- Location select button - mpehotel -->
            <span class="dropdown">
                <i class="bi bi-building" style="font-size: 1.2rem" id="XXI_navbar_commands_location" data-bs-toggle="dropdown" aria-expanded="false" onMouseOver="toolTip('Location')" onMouseOut="toolTip('')">&nbsp;</i>
                <ul class="dropdown-menu" aria-labelledby="XXI_navbar_commands_location">
                    <?php
                    foreach ($XXIv_Kitchen_mpehotel_enabled as $location) {
                        $XXI_newUrl = XXIf_rebuildUrl("Location", $location);
                        echo '<li><a class="dropdown-item" href="' . $XXI_newUrl . '" onclick="XXIf_spinner()">' . $location . ' - ' . XXIf_HotelName($location, 'short') . "</a></li>\n";
                    }
                    ?>
                </ul>
            </span>

            </script>
            <!-- Date selector button -->
            <span class="bi bi-calendar3" style="font-size: 1.2rem" id="XXI_navbar_commands_date" onclick="datepicker.show()"  onMouseOver="toolTip('Calendar')" onMouseOut="toolTip('')"></span>

            <!-- Expand/collapse button (Expand) -->
            <a href="<?php echo XXIf_toggleRefresh('Expand'); ?>" style="color: inherit; text-decoration: inherit" onclick="XXIf_spinner()" onMouseOver="toolTip('Expand')" onMouseOut="toolTip('')">
                <i class="<?php echo $XXI_navbar_command_expandIcon; ?>" style="font-size: 1.2rem;" id="XXI_navbar_commands_expand"></i>
            </a>

            <!-- Show/hide past activity past history limit -->
            <a href="<?php echo XXIf_toggleRefresh('History'); ?>" style="color: inherit; text-decoration: inherit" onclick="XXIf_spinner()" onMouseOver="toolTip('History')" onMouseOut="toolTip('')">
                <i class="<?php echo $XXI_navbar_command_historyIcon; ?>" style="font-size: 1.2rem" id="XXI_navbar_commands_visibility">&nbsp;</i>
            </a>
        </div>
        <div style="display: inline-block; cursor: help">
            <span class="text-info" style="font-size: 1.2rem" id="menuTooltip" style="font-size: 1.2rem; left: 5px"></span>
        </div>

    </div>

    <div class="col-4 fs-2 text-center fw-bold" id="XXI_navbar_title">
        Today
    </div>
    <div class="col-3">
    </div>
    <div class="col-1 fs-4 text-end pull-right align-right">
        <img class="img-fluid" style="max-width: 40px; margin-right: 5px;" src ="002/Logo-02.png" id="XXI_page_inner_left_logo_image" onclick="printDiv('XXI_page_inner_main', 'XXI_page_inner_left')">
    </div>

    <script>

        infoIcon = "<i class='bi bi-info-circle'>&nbsp;</i>";

        function toolTip(tipText) {
            if (tipText == '') {
                tipText = '';
            } else {
                tipText = infoIcon + tipText;
            }
            tipText = '&nbsp;&nbsp;' + tipText;

            document.getElementById('menuTooltip').innerHTML = tipText;
        }

    </script>

</nav>
