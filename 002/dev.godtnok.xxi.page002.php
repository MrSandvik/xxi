<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Percent horizontal</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="../resources/css/dev.godtnok.xxi.splitPanes.css">
        <link rel="stylesheet" href="../pretty-split-pane.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="../split-pane.js"></script>

        <style type="text/css">

            html, body {
                height: 100%;
                min-height: 100%;
                margin: 0;
                padding: 0;
            }

            #top-component {
                bottom: 70%;
                margin-bottom: 5px;
                min-height: 5em;
            }

            #my-divider {
                bottom: 70%; 
                height: 5px;
            }

            #bottom-component {
                height: 70%;
                min-height: 10em;
            }

        </style>

        <script>
            $(function () {
                $('div.split-pane').splitPane();
                $('button:first').on('click', function () {
                    $('div.split-pane').splitPane('lastComponentSize', 200);
                });
                $('button:last').on('click', function () {
                    $('div.split-pane').splitPane('firstComponentSize', 0);
                });
            });
        </script>
    </head>
    <body>

            <?php require 'dev.godtnok.xxi.main.navbarTop.php'; ?>
            <?php require 'dev.godtnok.xxi.main.navbarBottom.php'; ?>
            
        <div class="container-fluid" style="height: calc(100% - 87px); top: 51px; position: relative;">

            <div class="split-pane horizontal-percent"> <!-- REQUIRED -->
                <div class="split-pane-component" id="top-component"> <!-- REQUIRED -->
                    <div class="container-fluid bg-light">

                        <?php require 'filler.php'; ?>

                    </div>
                </div>

                <div class="split-pane-divider bg-success" id="my-divider"></div> <!-- REQUIRED -->


                <div class="split-pane-component" id="bottom-component"> <!-- REQUIRED -->
                    <div class="container-fluid bg-light">

                        <?php require 'filler2.php'; ?>

                    </div>
                </div>
            </div>

        </div>

    </body>
</html>
