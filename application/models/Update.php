<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 27.02.13
 * Time: 20:52
 * To change this template use File | Settings | File Templates.
 */

class Application_Model_Update extends CD_Model {
    protected $_tableName = 'updates';

    protected $_foreignKeys = array(
        'states_id'         =>  'Application_Model_Status'
    );

    public function getFormIgnoreKeys() {
        return array_merge(array('tickets_id'), parent::getFormIgnoreKeys());
    }

    public function getTicket() {
        return new Application_Model_Ticket($this->tickets_id);
    }

    public function __toString() {
        return $this->getTicket()->__toString();
    }

    public function getState() {
        if(!$this->states_id OR $this->states_id == '') return null;
        return new Application_Model_Status($this->states_id);
    }
}