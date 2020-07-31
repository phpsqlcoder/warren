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

    $strQuery = sqlsrv_query($conn,"SELECT TOP(10) SUBSTRING(destination,CHARINDEX('-',destination),LEN(destination)) AS dest, COUNT(destination) AS total FROM dispatch GROUP BY destination ORDER BY total DESC");

    if ($strQuery) {

        $arrData = array(
            "chart" => array(
                "caption" => "FREQUENT DESTINATIONS",
                "xAxisName" => "",
                "showValues" => "1",
                "theme" => "fusion"
            )
        );

        $arrData["data"] = array();

        while($row = sqlsrv_fetch_array($strQuery)) {
            array_push($arrData["data"], array(
                "label" => strtoupper($row['dest']),
                "value" => str_replace('-', ' ',$row["total"])
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