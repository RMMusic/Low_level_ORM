<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 22.10.2015
 * Time: 17:43
 */

abstract class Connect
{
    protected $_mySqlConnect;
    protected $_host = 'localhost';
    protected $_user = 'root';
    protected $_pas = '';
    protected $_dbName = 'SeaBattle';

    public function __construct(){
        $this->_sqlConnect();
    }

    public function __destruct(){
        $this->_mySqlClose();
    }

    protected function _sqlConnect(){
        $this->_mySqlConnect = mysqli_connect($this->_host, $this->_user, $this->_pas, $this->_dbName);
    }

    protected function _mySqlClose(){
        mysqli_close($this->_mySqlConnect);
        $this->_mySqlConnect = null;
    }

    abstract public function Insert($sqlQuery);

    abstract public function Select($sqlQuery);

}

class Query extends Connect
{

    public function __construct(){
        parent :: __construct();
    }

    public function Insert($sqlQuery)
    {
        $result = mysqli_query($this->_mySqlConnect, $sqlQuery);
        return $result;
    }

    public function Select($sqlQuery){
        $result = mysqli_query($this->_mySqlConnect, $sqlQuery);
        $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }

    public function Delete($sqlQuery)
    {
        $result = mysqli_query($this->_mySqlConnect, $sqlQuery);
        return $result;
    }

}





require_once('DB.php');
require_once('Validation.php');

if(isset($_GET['coordinate'])) {
    var_dump($_GET['coordinate']);

}

else {

    class GameController
    {
        protected $_inData;

        public function __construct($inData)
        {
            $this->_inData = $inData;
            $this->_validation();
        }

        protected function _validation()
        {
            $valid = new Validation($this->_inData);
            if ($valid->isValid()) {
                $this->_insertDB();
//            $this->_shipsSelect();

            } else {
                $this->_insertDB();
//           echo '!!!';
            }
        }

        protected function _insertDB()
        {
            $insert = new Query();
            $insert->Delete("DELETE FROM ships_field WHERE user_id=1;");
            $coordinatesArray = array();
            $x = 0;
            foreach ($this->_inData as $key => $data) {
                $coordinatesArray[$x] = $key;
                $x++;
            }
            $insert->Insert("INSERT INTO ships_field (ships_coordinates, user_id)
                           VALUES ('" . implode("/", $coordinatesArray) . "', 1);");

        }

    }

    new GameController($_POST);

    header("Location: FieldBuilder.php");
    die();
}