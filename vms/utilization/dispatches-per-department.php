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

    $strQuery = sqlsrv_query($conn,"SELECT deptId,count(deptId) AS total FROM dispatch GROUP BY deptId ORDER BY total DESC");

    if ($strQuery) {

        $arrData = array(
            "chart" => array(
                "caption" => "DISPATCH DISTRIBUTION PER DEPARTMENT",
                "xAxisName" => "",
                "showValues" => "1",
                "theme" => "fusion"
            )
        );

        $arrData["data"] = array();

        while($row = sqlsrv_fetch_array($strQuery)) {
            array_push($arrData["data"], array(
                "label" => strtoupper($row['deptId']),
                "value" => $row["total"]
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