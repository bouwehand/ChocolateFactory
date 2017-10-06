<?php
require_once(CHOCOLATE_FACTORY_LIB . "/Core/Query.php");
class ChocolateFactory_MVC_Model extends ChocolateFactory_Core_Query {

	protected $model;
	private $_data;

	public function __construct(){
		$this->model = lcfirst(get_class($this));
	   $query = self::getInstance();
    }

    /**
     * Dynamic database row fatching function
     */
    public function fetch($arg) {

        $dbh = self::getInstance();
        $sth = $dbh->prepare("SELECT * FROM $this->model LIMIT $arg");
        $sth->execute();
        return $sth->fetchAll();
    }

    public function __call($functionName, $arguments) {

        $functionType = substr($functionName, 0,3);

        switch($functionType) {
            case "set" :
                $name = strtolower(substr($functionName, 3));
                $this->_data->$name = current($arguments);;
            break;
            case "get" :
            break;
        }


        return $this;
    }

    public function jsonEncode() {
        return json_encode($this->_data);
    }

    public function save() {
         $query = ChocolateFactory_Core_Query::getInstance();
         $values = array('bob', 'alice', 'lisa', 'john');
// $name = '';
// $stmt = $db->prepare("INSERT INTO table(`name`) VALUES(:name)");
// $stmt->bindParam(':name', $name, PDO::PARAM_STR);
// foreach($values as $name) {
//   $stmt->execute();
// }
//          $stmt = $query->prepare("INSERT INTO table(
//         foreach($this->_data as $name => value) {
//             $this
//         }
//     }
    }
}