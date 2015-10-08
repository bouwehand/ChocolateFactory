ChocolateFactory
================

Chocolate Factory Framework

Because i wanna be in the chocolate factory with Willy Fuckin Wonka, Dancing with the Oompa-Loompas like this

@autor       Bastiaan Jeroen Ouwehand <b.j.ouwehand@gmail.com>
@version     0.1.0

Really simple Framework. I develop while programing. Each time I think, would it not be cool if... and then i add



I say never be complete

evolve

and let the chips fall where they may

Cli
---
configure cli script in json file of the module
`
"cli" : {
    "darwin" : "darwin.php"
}
`

run with:
 `php index.php -s <scriptname>`

Composer Install
----------------

Install composer
`curl -sS https://getcomposer.org/installer | php`

run composer.phar update

Documentation
=============

Lava Charts
-----------

Example:

Controller code

`
 $lava = new Khill\Lavacharts\Lavacharts;
         $stocksTable = $lava->DataTable();  // Lava::DataTable() if using Laravel

         $stocksTable
             ->addDateColumn('Date')
             ->addNumberColumn('Close');

         foreach($rates as $i => $rate) {
             $rowData = array(
                 $dates[$i], log($rate)
             );
             $stocksTable->addRow($rowData);
         }
         $lava->LineChart('Stocks')
             ->setOptions(array(
                     'datatable' => $stocksTable,
                     'title' => 'AAPL CLOSE'
                 ));
         $this->lava = $lava;
`

view code

`
$lava = $this->lava;
echo $lava->render('LineChart', 'Stocks', 'stocks-div', array('width'=>1024, 'height'=>768));
`

Mysql Tables
------------

shorthand data types for tables:

* string
* int
* float
* timestamp

Using tables example:

`$csv = ChocolateFactory_Core_Csv::init(CHOCOLATE_FACTORY_DOC .'/snp500.csv');`

`$columns = array (`

    `array(`

        `'name' => 'symbol',`

        `'type' => 'string',`

    `),`

    `array(`
        `'name' => 'security',`
        `'type'  => 'string',`
    `),`
    `array(`
        `'name' => 'gicssector',`
        `'type'  => 'string',`
    `),`
    `array(`
        `'name' => 'gicsindustry',`
        `'type'  => 'string',`
    `),`
    `array(`
        `'name' => 'headquarters',`
        `'type' => 'string'`
    `),`
    `array(`
        `'name' => 'added',`
        `'type'  => 'timestamp',`
    `),`
    `array(`
        `'name' => 'cik',`
        `'type' => 'int'`
    `)`
`);`

`$table = ChocolateFactory_Mysql_Table::createTable("snp500", $columns, $csv->getData());`

Functions:

* getAll() get whole table
* getId()  get an entry by id