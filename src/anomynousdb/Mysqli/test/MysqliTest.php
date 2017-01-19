<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 22-12-2016
 * Time: 16:07
 */

namespace Anomynousdb\MysqliTest;

use Anomynousdb\Mysqli\Config;
use Anomynousdb\Mysqli\Model;
use Anomynousdb\Mysqli\Mysqli;
use PHPUnit\Framework\TestCase;

class MysqliTest extends TestCase {

    protected $mysqli;
    

    public function testGetConfigFile()
    {
        $config = Config::init();
        $this->assertTrue(is_array($config->getConfig()));
    }

    /**
     *
     */
    public function testInitialisation()
    {
        $mysqli = Mysqli::init();
        $this->assertTrue(is_object($mysqli));
        return $mysqli;
    }

    /**
     *
     */
    public function testDummyConfig()
    {
        $testConfig = array(
            "EtDevices" => array(
                'dbTable'  => 'et_devices',
                'dbFields' => array(
                    'id'        => array('int'),
                    "imei"      => array("text"),
                    "plate"     => array("text"),
                    "number"    => array("text"),
                    "simnumber" => array("text"),
                ),
                "dbReplace" => array(
                    "name" => array("Device", "name")
                )
            )
        );

        // test the model layer
        $tableObject = Model::init($testConfig);
        $result = $tableObject::get(Array(0,1));
        $this->assertTrue(is_array($result));
        $row = current($result);
        $this->assertNotEmpty($row->data);

        // test the mysql iterator
        $mysqli = Mysqli::init($testConfig);
        $row = $mysqli->updateRowObject($row);
        $this->assertTrue(is_object($row));
        $this->assertNotEmpty($row->data);
    }

    /**
     * phpunit --filter testMatchReplaceConfig C:\repos\anominizer\src\anomynousdb\Mysqli\test\MysqliTest.php
     */
    public function testMatchReplaceConfig()
    {
        /**
         * et_vars.name == usercomment  => lorum ipsum ( 30 tekens)
         * et_vars.name == name  => lorum ipsum ( 3 worden)
         */
        $mysqli = Mysqli::init(array (
            "EtVars" => array(
                "dbTable"   => "et_vars",
                "dbFields"  => array(
                    "id"    => array("int"),
                    "name"  => array("text"),
                    "value" => array("text")
                ),
                "dbMatchReplace" => array(
                    "name" => array(
                        "name"       => array("value" => array("LoremIpsum", "threewords")),
                        "usercomment"=> array("value" => array("LoremIpsum", "tenwords"))
                    )
                )
            )
        ));

        $tableObject = $mysqli->initNextTableObject();
        $result = $tableObject::get(Array(4, 5));
        $this->assertTrue(is_array($result));
        $row = current($result);
        $this->assertNotEmpty($row->data);
        $row = $mysqli->updateRowObject($row);
        $this->assertTrue(is_object($row));
    }

    /**
     * @internal param Mysqli $mysqli
     */
    public function testAllConfig()
    {
        $mysqli = Mysqli::init();

        /** @var \dbObject $tableObject */
        while($tableObject = $mysqli->initNextTableObject()) {

           $result = $tableObject::get(Array(0, 1));
            // strip the array wrapper
           $this->assertTrue(is_array($result));
           foreach($result as $row) {
               $row = $mysqli->updateRowObject($row);
               $this->assertTrue(is_object($row));
           }

        }
    }

    /**
     * phpunit --filter testBulkUsage C:\repos\anominizer\src\anomynousdb\Mysqli\test\MysqliTest.php
     */
    public function testBulkUsage()
    {
        /**
         * et_vars.name == usercomment  => lorum ipsum ( 30 tekens)
         * et_vars.name == name  => lorum ipsum ( 3 worden)
         */
        $mysqli = Mysqli::init(array (
            "EtVars" => array(
                "dbTable"   => "et_vars",
                "dbFields"  => array(
                    "id"    => array("int"),
                    "name"  => array("text"),
                    "value" => array("text")
                ),
                "dbMatchReplace" => array(
                    "name" => array(
                        "name"       => array("value" => array("LoremIpsum", "threewords")),
                        "usercomment"=> array("value" => array("LoremIpsum", "tenwords"))
                    )
                )
            )
        ));
        $mysqli->run();
    }

    //
    // /**
    //  * @return \dbObject
    //  */
    // public function testGetRelationObjectThroughModels()
    // {
    //     $result = Model\EtFleets::with("fa_user")->getOne();
    //     $this->assertTrue(is_object($result->fa_user));
    //     return $result;
    // }
    //
    // /**
    //  * @depends testGetRelationObjectThroughModels
    //  */
    // public function testUpdateRelationshipObjectTroughModels(\dbObject $fleet)
    // {
    //     $person = new Person();
    //     $userName = $person->username();
    //     echo PHP_EOL . $userName . PHP_EOL;
    //     $fleet->name = $userName;
    //     $result = $fleet->save();
    //     $this->assertTrue($result);
    //
    //     $faUser = $fleet->fa_user;
    //     $faUser->user_name = $userName;
    //     $faUser->primaryKey = 'id';
    //     $result = $faUser->save();
    //     $this->assertTrue($result);
    // }



    // /**
    //  *
    //  */
    // public function testGetRelationObject()
    // {
    //
    //
    //     // get device changes ( has relationship with devices )
    //     $etDeviceChanges = Model::init(array(
    //         'EtDeviceChanges' => array(
    //             'dbTable' => 'et_device_changes',
    //             'primaryKey' => 'device_id',
    //             'dbFields'=> Array(
    //                 'device_id'  => Array('int'),
    //                 'user_id'    => Array('int'),
    //                 'insertdate' => Array('datetime'),
    //                 'action'     => Array('text'),
    //                 'old_imei'   => Array('text'),
    //                 'new_imei'   => Array('text'),
    //                 'old_number' => Array('text'),
    //                 'new_number' => Array('text'),
    //                 'old_plate'  => Array('text'),
    //                 'new_plate'  => Array('text'),
    //                 'utcd'       => Array('int'),
    //                 'utct'       => Array('int'),
    //                 'km'         => Array('int'),
    //                 'comment'    => Array('text'),
    //             ),
    //             'relations'=> array(
    //                 'et_devices' => array('hasOne', "EtDevices", "device_id")
    //             ),
    //             "dbReplace" => array(
    //                 "name" => array("Device", "name")
    //             )
    //         )
    //     ));
    //
    //     $result = $etDeviceChanges::with("et_devices")->getOne();
    //     $this->assertTrue(is_object($result->et_devices));
    //     return $result;
    // }

    // /**
    //  * @param \dbObject $etDeviceChanges
    //  * @depends testGetRelationObject
    //  */
    // public function testUpdateRelationship(\dbObject $etDeviceChanges)
    // {
    //     // generate imei
    //     $randomDevice = new Device();
    //     $imei = $randomDevice->imei();
    //     $name = $randomDevice->name();
    //     $date = $randomDevice->datetime();
    //
    //     $etDeviceChanges->imei = $imei;
    //     $etDeviceChanges->insertdate = $date;
    //     $result = $etDeviceChanges->save();
    //     $this->assertTrue($result);
    //     die(var_dump($etDeviceChanges));
    //
    //     die(var_dump($etDeviceChanges->data));
    //     $etDevices = $etDeviceChanges->et_devices;
    //     $etDevices->name = $name;
    //     $etDevices->save();
    //
    //
    // }
}