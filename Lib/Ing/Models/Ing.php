<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 3/13/15
 * Time: 11:49 AM
 */
class Ing_Models_Ing extends ChocolateFactory_Core_Csv
{
    const INIT_RATE = -1428.12;
    //const INIT_RATE = 500;

    const COLUMN_DATE       = 'Datum';
    const COLUMN_MUTATION   = 'AfBij';
    const COLUMN_RATE       = 'BedragEUR';

    /**
     * @return mixed
     * @internal param $csv
     */
    public function GroupAndFormatIng()
    {
        // group on date
        $datums = $this->getColumn(self::COLUMN_DATE);
        $data = $this->getData();
        foreach ($datums as $i => $datum)
        {
            $groups[$datum][] = $data[$i];
        }

        // calculate rate per day
        $data = array();
        $rate = self::INIT_RATE;
        foreach($groups as $datum => $rows) {
            foreach($rows as $row) {
                if ($row[self::COLUMN_MUTATION] == 'Af') {
                    $rate -= $row[self::COLUMN_RATE];
                } else {
                    $rate += $row[self::COLUMN_RATE];
                }
            }
            $newRow = array (
                self::COLUMN_RATE => $rate,
                self::COLUMN_DATE => $datum
            );
            $data[] = $newRow;
        }
        $this->setData($data);
        return $this;
    }
}