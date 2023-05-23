<?php
//$dictionary = json_decode(file_get_contents("dev.godtnok.xxi.dictionary.json"), true);
//$ISOlanguage = "nb-NO";

require 'dev.godtnok.xxi.master.variables.php';
require 'dev.godtnok.xxi.master.head.php';

//echo "<pre>";
//foreach ($dictionary as $page) {
//    print_r($page["home"]);
//    if ($page == "home") {
//        foreach ($lang as $word) {
//            echo $word;
//        }
//    }
//}
//die;
?>


<style>
    html, body {
        height: 100%;
        background: rgb(91,216,217);
        background: linear-gradient(195deg, rgba(91,216,217,1) 0%, rgba(108,115,234,1) 63%, rgba(116,74,200,1) 100%);
        /* https://cssgradient.io/ */

    }

    .xxiFrame {
        background-color: none;
        top: 15px;
        bottom: 15px;
        right: 15px;
        left: 15px;
        position: fixed;

        border-width: 2px;
        border-style: solid;
        border-image: linear-gradient(195deg, rgba(144,143,149,1) 0%, rgba(170,169,173,1) 63%, rgba(102,101,120,1) 100%);
        /* https://cssgradient.io/ */
        border-image-slice: 1;
        padding: 20px;
    }

    .xxiIcon {
        top: 5px;
        position: relative;
        width: 60px;
        height: 60px;
        filter: drop-shadow( 3px 3px 2px rgba(0, 0, 0, .6));
    }

    .xxiButtonFace {
        height: 100px; 
        width: 120px; 
        font-size: 18px; 
        line-height: 35px; 

        color: rgb(255, 255, 255); 
        text-shadow: rgb(64, 95, 152) 0px 0px 0px, rgb(66, 98, 157) 1px 1px 0px, rgb(68, 101, 162) 2px 2px 0px, rgb(70, 104, 167) 3px 3px 0px, rgb(72, 107, 172) 4px 4px 0px, rgb(74, 110, 177) 5px 5px 0px, rgb(76, 113, 182) 6px 6px 0px, rgb(78, 116, 187) 7px 7px 0px, rgb(81, 119, 192) 8px 8px 0px, rgb(83, 123, 197) 9px 9px 0px, rgb(85, 126, 202) 10px 10px 0px, rgb(87, 129, 207) 11px 11px 0px, rgb(89, 132, 212) 12px 12px 0px;
        /* https://mdbootstrap.com/tools/logo-generator-text/ */
        border-radius: 0%;
        text-align: center; 
        background: linear-gradient(225deg, rgba(91,135,217,1) 0%, rgba(108,152,234,1) 63%, rgba(74,118,200,1) 100%); 
        /* https://cssgradient.io/ */
        overflow: hidden;

        border-width: 1px;
        margin: -1px;
        border-style: solid;
        border-color: #bbbbbb;
        border-image-slice: 1; 
    }

    a:link, a:visited {
        text-decoration: none;
        color: inherit;
    }

    a:hover, a:active
    {
        text-decoration: none;
        color: yellow;
    }
</style>
</head>

<body>
    <div class="container-fluid bg-info">
        <div class="xxiFrame">

            <div class="container-fluid">
                <div class="row d-flex justify-content-center flex-nowrap">
                    <div class="col-md-4 xxiButtonFace"> 
                        <a href="/dev.godtnok.xxi.page001_1.php">
                            <img src="/resources/images/menu/001-cooking.svg" class="xxiIcon"><br>Kjøkken
                        </a>
                    </div>

                    <div class="col-md-4 xxiButtonFace"> <img src="/resources/images/menu/007-housekeeping.svg" class="xxiIcon"><br>Renhold </div> 

                    <div class="xxiButtonFace"> 
                        <a href="/dev.godtnok.xxi.page002.php">
                            <img src="/resources/images/menu/011-conference.svg" class="xxiIcon"><br>Konferanse
                        </a>
                    </div>
                </div>
                <!--div class="row d-flex justify-content-center flex-nowrap">
                    <div class="col-md-4 xxiButtonFace"> <img src="001-cooking.svg" class="xxiIcon"><br>Kjøkken </div>
                    <div class="col-md-4 xxiButtonFace"> <img src="007-housekeeping.svg" class="xxiIcon"><br>Renhold </div> 
                    <div class="xxiButtonFace"> <img src="011-conference.svg" class="xxiIcon"><br>Konferanse </div> 
                </div-->
            </div>
        </div>


    </div>

</body>

</html>