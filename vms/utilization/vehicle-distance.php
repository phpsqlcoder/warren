<?php
include("../charts/chart/fusioncharts.php");
include("../config.php");

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../js/themes/fusioncharts.theme.fusion.css"></link>        
    <title></title>
    <script type="text/javascript" src="../js/fusioncharts.js"></script>
    <script type="text/javascript" src="../js/themes/fusioncharts.theme.fusion.js"></script>
</head>
<body>

    <?php

    $strQuery = sqlsrv_query($conn,"SELECT type,odometer_start,odometer_end,odometer_end - odometer_start AS sub FROM dispatch ORDER BY sub DESC");

    if ($strQuery) {

        $arrData = array(
            "chart" => array(
                "caption" => "VEHICLE DISTANCE TRAVELLED",
                "xAxisName" => "",
                "showValues" => "1",
                "theme" => "fusion"
            )
        );

        $arrData["data"] = array();

        while($row = sqlsrv_fetch_array($strQuery)) {
            array_push($arrData["data"], array(
                "label" => strtoupper($row['type']),
                "value" => $row["sub"]
            )
        );
        }

        $jsonEncodedData = json_encode($arrData);
        $columnChart = new FusionCharts("column2D", "myFirstChart" , 880, 400, "chart-1", "json", $jsonEncodedData);

        $columnChart->render();
    }

    ?>
    <div class="col-md-6" id="chart-1"> </div>

</body>
</html>