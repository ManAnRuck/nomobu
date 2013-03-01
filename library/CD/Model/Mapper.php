<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 14.02.13
 * Time: 05:24
 * To change this template use File | Settings | File Templates.
 */

class CD_Model_Mapper {
    protected $_tableClass = 'Zend_Db_Table';
    protected $_tableName = null;
    /**
     * @var Zend_Db_Table
     */
    protected $_table = null;
    
    protected $_model = null;

    public function __construct($tableName, $model) {
        $this->_tableName = $tableName;
        $this->_model = $model;
    }

    public function getTable() {
        if(!$this->_table) {
            if(!$this->_tableName) throw new Exception('No table name');
            $tableClass = $this->_tableClass;
            $this->_table = new $tableClass($this->_tableName);
        }

        return $this->_table;
    }
    
    public function fetchAll($where = null, $order = null, $count = null) {
    	$modelName = get_class($this->_model);
    	$items = $this->getTable()->fetchAll($where, $order, $count);

    	$return = array();
    	foreach($items as $item) {
    		$add = new $modelName;
    		foreach($item as $key => $value) {
    			$add->{$key} = $value;
    		}
    		
    		$return[] = $add;
    	}
    	
    	return $return;
    }

    public function fetchRow($where = null, $order = null, $count = null) {
        $modelName = get_class($this->_model);
        $item = $this->getTable()->fetchRow($where, $order, $count);

        if(!$item) return null;

        $return = new $modelName;
        foreach($item as $key => $value) {
            $return->{$key} = $value;
        }

        return $return;
    }
}