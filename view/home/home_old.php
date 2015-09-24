<?php
/**
 * Goed, het eerste idee was dus een success. Het is waar dat wanneer we altijd voor de beste positie bieden
 * het bedrag alleen maar toe neemt, met hier en daar een paar schommelingen.
 *
 * Alleen: er is geen rekening gehouden met de initial-margin-fee. De betaling om te handelen.
 * Wanneer deze wordt toegevoegd is er alleen nog maar verlies.
 *
 * De winstmarge van de eerste opzet is alleen zo verschrikkelijk hoog, dat je moet afvragen of deze marge niet te
 * overkomen is. Hoe zou dit moeten?
 *
 * Idee 2: het voortschrijdende gemiddelde.
 *
 * ----------------
 *
 * 1. Elke ronde kies de trader het grootste positieve verschil tussen voortschrijdend gemiddelde en dagstand.
 * 2. Deze valuta wordt gekocht en de marge wordt betaald.
 *
 *
 */
$market = new Market();
$market->run();

$graph = new Graph();


foreach ($market->getMarketData() as $i => $marketData) {
    if($i == 0) $graph->setTitles( array( 'date' , $marketData->currency ,  'avarage', 'lip') );
    //  draw step in

    if($marketData->rate != 0){
        $graph->addRow(array(
            $marketData->step,
            $marketData->rate,
            $marketData->beak,
            $marketData->lip,
        ));
    }
}
?>
<html>
<head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.0', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.setOnLoadCallback(drawChart);


        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('number', 'step'); // Implicit domain label col.
            data.addColumn('number', 'rates'); // Implicit series 1 data col.
            data.addColumn({type:'string', role:'annotation'}); // annotation role col.
            data.addColumn({type:'string', role:'annotationText'}); // annotationText col.
            data.addColumn('number', 'lip'); // Implicit series 1 data col.
            data.addColumn('number', 'teeth'); // Implicit series 1 data col.
            data.addColumn('number', 'jaw'); // Implicit series 1 data col.
            data.addRows([
            <?php foreach($market->getMarketData()  as $marketData) : ?>
                <?php if($marketData->rate != 0) :
                         $annotationText = "'step : $marketData->step rate: $marketData->rate lip: $marketData->lip beak: $marketData->beak '";
                         if($marketData->buy) {
                                $annotation = "'B'";
                            }elseif($marketData->sell) {
                                $annotation = "'S'";

                            }else {
                               $annotation = 'null';
                               $annotationText = 'null';
                            }
                    ?>
                    [
                        <?php echo $marketData->step; ?> ,
                        <?php echo $marketData->rate; ?> ,
                        <?php echo $annotation; ?> ,
                        <?php echo $annotationText; ?>,
                        <?php echo $marketData->lip; ?>,
                        <?php echo $marketData->teeth; ?>,
                        <?php echo $marketData->jaw; ?>
                    ],
                    <?php endif;?>
                <?php endforeach; ?>
            ]);

            var options = {
                title: 'Company Performance'
            };

            var chart = new  google.visualization.LineChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <div id="chart_div" style="width: <?php echo count($market->getMarketData()) * 5; ?> ; height: 500px;"></div>
<?php

echo  "step \t";
echo  "rate \t";
echo  "lip \t";
echo  "teeth \t";
echo  "jaw \t";
echo  "currency \t";
echo  "account \t";
echo "<br/>";
    foreach($market->getMarketData()  as $i => $marketState) {
            echo $marketState->step . "\t";
            echo number_format($marketState->rate, 8). "\t";
            echo number_format($marketState->lip, 8) . "\t";
            echo number_format($marketState->teeth, 8) . "\t";
            echo number_format($marketState->jaw, 8) . "\t";
            echo $marketState->currency. "\t";
            echo $marketState->account. "\t";
            echo "<br/>";
    }
?>
</body>
</html>