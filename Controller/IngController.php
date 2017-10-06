<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 3/13/15
 * Time: 11:26 AM
 */

use \Khill\Lavacharts\Format\DateFormat;
use \Carbon\Carbon;

class IngController extends ChocolateFactory_MVC_Controller {

    /**
     * home controller
     */
    public function home() {
        $csv = Ing_Models_Ing::init(CHOCOLATE_FACTORY_DOC . '/NL16INGB0006602425_01-01-2017_05-10-2017.csv')
            ->dropColumn('Mededelingen')
            ->dropColumn('MutatieSoort')
            ->dropColumn('NaamOmschrijving')
            ->dropColumn('Rekening')
            ->dropColumn('Tegenrekening')
            ->dropColumn('Code')
            ->reverse()
            ->formatColumnDate('Datum')
            ->formatColumnFloat('BedragEUR')
            ->GroupAndFormatIng();
        
        $lava = new Khill\Lavacharts\Lavacharts;
        $stocksTable = $lava
            ->DataTable()
            ->addDateColumn('Date')
            ->addNumberColumn('rate')
            ->addNumberColumn('average');

        foreach ($csv->getData() as $i => $row)
        {
            $data[] = $row['BedragEUR'];
            $stocksTable->addRow(array(
                    $row['Datum'],
                    $row['BedragEUR'],
                    Tools::average($data)
                )
            );
        }

        $lava->LineChart('Stocks')
            ->setOptions(array(
                'datatable' => $stocksTable,
                'title'     => 'Stock Market Trends'
            ));
        $this->lava = $lava;
    }
}