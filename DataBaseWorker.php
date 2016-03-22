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
    protected $_sampleData = null;


    public function getData()
    {
        if (!$this->_sampleData) {
            $this->_sampleData = $this->_generation();
        }

        return $this->_sampleData;
    }

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

    public function select($columns = array(), $tableName = null, $where = array())
    {
        $columns = empty($columns) ? array('*') : $columns;

        $columnsData = implode(', ', $columns);
        $query = "SELECT $columnsData FROM {$this->_getTableName($tableName)} {$this->_where($where)}";
        $result = mysqli_query($this->_mySqlConnect, $query);
        $data =  mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }

    public function delete($tableName = null, $where = array())
    {
        $query = "DELETE FROM {$this->_getTableName($tableName)} {$this->_where($where)}";
        $result = mysqli_query($this->_mySqlConnect, $query);
        return $result;
    }

    public function update($tableName = null, $set = array(), $where = array())
    {
        $setData = '';
        foreach ($set as $key => $values){
            $setData .= $key . '="' . $values . '", ';
        }
        $a = rtrim($setData, ', ');

        $query = "UPDATE {$this->_getTableName($tableName)} SET $a {$this->_where($where)}";
        $result = mysqli_query($this->_mySqlConnect, $query);
        return $result;
    }

    protected function _where(array $arrayWhere)
    {
        $conditionData = array();

        foreach ($arrayWhere as $column => $condition) {
            $columnWhereData = array();

            if (!is_array($condition)) {
                $condition = array("=" => $condition);
            }

            foreach ($condition as $operation => $value) {
                $columnWhereData[] = $this ->_getMappedCondition($column, $operation, $value);
            }

            $conditionData[] = implode(' AND ', $columnWhereData);
        }

        $conditionString = "WHERE " . (count($conditionData) ? " (" . implode(') AND (', $conditionData) . ")" : '1');

        return $conditionString;
    }

    protected function _getMappedCondition($column, $operation, $value)
    {

        $mapping = array(
            "=" => "$column = '$value'",
            ">" => "$column > '$value'",
            "<" => "$column < '$value'",
            ">=" => "$column >= '$value'",
            "<=" => "$column <= '$value'",
            "!=" => "$column <> '$value'",
            "in" => $column . ( is_array($value) ? " IN ('" . implode("','", $value) . "')" : " = '$value'" ),
            "not in" => $column . ( is_array($value) ? " NOT IN ('" . implode("','", $value) . "')" : " = '$value'" ),
            "null" => $column . ($value == true ? " IS NULL" : " IS NOT NULL"),
        );

        return array_key_exists($operation, $mapping) ? $mapping[$operation] : "";
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
$a->update('names', array('name'=>'Yoyo'), array('email'=>'fghfg'));


//$select = array(
//    "name" => "John",
//    "email" => array(
//        '!=' => 'Jhoy@mail.ua'
//    )
//);

//$third = array(
//    "name" => "Taras",
//    "age" => array(
//        ">" => "26",
//        "<" => "36",
//        "null" => false,
//    ),
//);

//echo $a->insert($inData);

//$inData = array('name', 'email');







