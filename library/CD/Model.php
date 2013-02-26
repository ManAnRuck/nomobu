<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 14.02.13
 * Time: 05:23
 * To change this template use File | Settings | File Templates.
 */

class CD_Model {
    /**
     * @var String Table where the models are stored in
     */
    protected $_tableName = null;

    /**
     * @var string Mapper class
     */
    protected $_mapperClass = 'CD_Model_Mapper';
    /**
     * @var CD_Model_Mapper
     */
    protected $_mapper = null;

    /**
     * @var array Array with keys who will be ignored for forms
     */
    protected $_ignoreKeys = array('id', 'urlshortcut');

    /**
     * @var array Fieldname mapper
     */
    protected $_fieldLabels = array();

    /**
     * @var array field->model
     */
    protected $_foreignKeys = array();

    protected $_specialFields = array();

    /**
     * Ctor
     *
     * @param null $id ID of Model
     */
    public function __construct($id = null) {
    	if($id) {
    		$result = $this->getMapper()->getTable()->fetchRow(array('id = ?' => $id));
            if($result) {
                foreach($result as $key => $value) {
                    $this->{$key} = $value;
                }
            } else {
                throw new Exception('Item not found');
            }
    	}
    }

    /**
     * Returns the mapper
     * @return CD_Model_Mapper|null
     */
    public function getMapper() {
        if(!$this->_mapper) {
            $mapperClass = $this->_mapperClass;
            $this->_mapper = new $mapperClass($this->_tableName, $this);
        }

        return $this->_mapper;
    }

    /**
     * Saves the model
     */
    public function save() {
    	$data = array();
    	foreach($this as $key => $value) {
    		if(substr($key, 0, 1) != '_' AND !in_array($key, $this->_ignoreKeys)) {
    			$data[$key] = $value;		
    		}
    	}

    	if($this->id AND $this->id !== null AND $this->id != '') {
    		$this->getMapper()->getTable()->update($data, array('id = ?' => $this->id));
    	} else {
    		$this->id = $this->getMapper()->getTable()->insert($data);
    	}
    }

    /**
     * Deletes the model
     */
    public function delete() {
    	$this->getMapper()->getTable()->delete(array('id = ?' => $this->id));
    }

    /**
     * @return string String representation
     */
    public function __toString() {
        if(isset($this->name)) return $this->name;
        return get_class($this);
    }

    /**
     * @return Zend_Form
     */
    public function getForm() {

        $form = new Zend_Form();
        $metadata = $this->getMapper()->getTable()->info(Zend_Db_Table_Abstract::METADATA);
        // print_r($metadata);
        // die();

        foreach($this->getMapper()->getTable()->info(Zend_Db_Table_Abstract::COLS) as $id => $key) {
            if(!in_array($key, $this->_ignoreKeys) AND substr($key, 0, 1) != '_') {
                $label = $key;
                $required = false;
                if(isset($this->_fieldLabels[$key])) $label = $this->_fieldLabels[$key];
                if($metadata[$key]['NULLABLE'] != 1) $required = true;

                if(isset($this->_specialFields[$key])) {
                    $className = $this->_specialFields[$key];
                    $form->addElement(new $className(array(
                        'name'      =>  $key,
                        'value'     =>  $this->{$key},
                        'label'     =>  $label,
                        'required'  =>  $required
                    )));
                } elseif($metadata[$key]['DATA_TYPE'] == 'mediumtext') {
                    $form->addElement(new Zend_Form_Element_Textarea(array(
                        'name'      =>  $key,
                        'value'     =>  $this->{$key},
                        'label'     =>  $label,
                        'required'  =>  $required
                    )));
                } elseif(isset($this->_foreignKeys[$key])) {
                    $subitemClass = $this->_foreignKeys[$key];
                    $subitemModel = new $subitemClass;
                    $subitems = $subitemModel->getMapper()->fetchAll();

                    $options = array();
                    $options['NULL'] = 'Please select';
                    foreach($subitems as $subitem) {
                        $options[$subitem->id] = $subitem->__toString();
                    }
                    $form->addElement(new Zend_Form_Element_Select(array(
                        'name'      =>  $key,
                        'value'     =>  $this->{$key},
                        'label'     =>  $label,
                        'required'  =>  $required,
                        'multiOptions'   =>  $options
                    )));
                } else {
                    $form->addElement(new Zend_Form_Element_Text(array(
                        'name'      =>  $key,
                        'value'     =>  $this->{$key},
                        'label'     =>  $label,
                        'required'  =>  $required
                    )));
                }
            }
        }

        $form->addElement(new Zend_Form_Element_Submit(array(
            'name'      =>  'Submit',
            'label'     =>  'Save'
        )));

        return $form;
    }
}