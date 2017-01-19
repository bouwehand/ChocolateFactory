<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 22-12-2016
 * Time: 16:07
 */

namespace ChocolateFactory\coreTest;

use ChocolateFactory\Core\ChocolateFactory;
use PHPUnit\Framework\TestCase;


class coreTest extends TestCase {

    /**
     *
     */
    public function testInitialisation()
    {
        $core = ChocolateFactory::run();
        return $core;
    }

}