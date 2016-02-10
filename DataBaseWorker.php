<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 27.01.2016
 * Time: 11:10
 */
class DataBaseWorker
{
    protected $_mySqlConnect;
    protected $_currentTable;
    protected $_where;

    /**
     * @param $dataBaseHost
     * @param string $user
     * @param string $password
     * @param $dataBaseName
     */
    public function __construct($dataBaseHost, $user = 'root', $password = '', $dataBaseName)
    {
        $this->_mySqlConnect = mysqli_connect($dataBaseHost, $user, $password, $dataBaseName);
    }

    public function __destruct()
    {
        mysqli_close($this->_mySqlConnect);
        $this->_mySqlConnect = null;
    }

    public function setCurrentTable($tableName)
    {
        $this->_currentTable = $tableName;
    }

    public function getCurrentTable()
    {
        return $this->_currentTable;
    }

    protected function _getTableName($tableName)
    {
        if ($tableName == null){
            $tableName = $this->getCurrentTable();
        }
        return $tableName;
    }

    /**
     * @param $tableName
     * @param array $insertData
     * @return bool|mysqli_result
     */
    public function insert($insertData = array(), $tableName = null)
    {
        $result = false;

        if (!empty($insertData)) {
            $columns = implode(', ', array_keys($insertData));
            $values = implode(', ', $this->_validationArray($insertData));
            $query = "INSERT INTO {$this->_getTableName($tableName)} ($columns) VALUES ($values)";

            $result = mysqli_query($this->_mySqlConnect, $query);
//        return $query;
        }

        return $result;
    }

    public function select($columns = array(), $tableName = null)
    {

        $where = (!empty($this->where)) ? $this->where : 1;

        $columns = empty($columns) ? array('*') : $columns;

        $columnsData = implode(', ', $columns);
        $query = "SELECT $columnsData FROM {$this->_getTableName($tableName)}WHERE $where";
        $result = mysqli_query($this->_mySqlConnect, $query);
        $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }

    public function delete($tableName = null)
    {

        $where = (!empty($this->where)) ? $this->where : 1;

        $query = "DELETE FROM {$this->_getTableName($tableName)}WHERE $where";
        $result = mysqli_query($this->_mySqlConnect, $query);
        $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }

    public function where($str)
    {
        $this->_where=$this->_where.' '.$str.' ';
    }

    /**
     * @param $insertData
     * @return mixed
     */
    protected function _validationArray ($insertData)
    {
        foreach($insertData as $key=>$value){
            if (!is_int($value)){
                $insertData[$key] = "'".$value."'";
            }
        }
        return $insertData;
    }

}



$a = new DataBaseWorker('localhost', 'root', '', 'progectFormdb');
$a->setCurrentTable('names');
$inData = array(
    'name'=>'Hi'
);
echo $a->insert($inData);

$inData = array('name', 'email');
var_dump($a->select($inData, 'names'));





