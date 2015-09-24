<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 3/13/15
 * Time: 11:44 AM
 */
$market = new Ing_Models_Ing();
?>
<html>
<head>
    <script type="text/javascript"
            src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Year', 'Sales']
                <?php foreach ($market->getGraph() as $i => $v) :?>
                <?php echo sprintf(",['%s',%s]" . PHP_EOL, $i, $v); ?>
                <?php endforeach; ?>
            ]);
            var options = {
                title: 'Company Performance',
                legend: { position: 'bottom' }
            };
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
<div id="curve_chart" style="width: 900px; height: 500px"></div>
</body>
</html>