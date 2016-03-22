<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 25.02.2016
 * Time: 18:03
 */

class Singleton
{
    static private $_instance = null;

    private function __construct()
    {

    }

    static public function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new Singleton();
        }

        return self::$_instance;
    }
}

class Sample
{
    protected $_sampleData = null;

    public function setSampleData($data)
    {
        $this->_sampleData = $data;
    }

    public function getSampleData()
    {
        return $this->_sampleData;
    }
}

class Adapter
{
    protected $_sampleObject;

    public function setSampleObject(Sample $sampleObject)
    {
        $this->_sampleObject = $sampleObject;
    }

    public function getFormattedData()
    {
        $data = $this->_sampleObject->getSampleData();

        return $this->format($data);
    }

    protected function _format($data)
    {
        //...
        return $data;
    }

}

function _instantiate($name = 'var3')
{
    $var3 = 'qwerty';
    $var = $$name; // $var3
    $var = $var3;
    return $var;
}
