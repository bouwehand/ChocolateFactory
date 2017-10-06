<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 3/13/15
 * Time: 11:26 AM
 */
class IngController extends \ChocolateFactory\MVC\Controller {

    /**
     * home controller
     */
    public function home() {


        require_once ("/home/thrynillan/vhost/ChocolateFactory/lib/Ing/Models/Ing.php");
        $model = new Ing_Models_Ing();
    }
}