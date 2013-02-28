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
        'states_id'         =>  'Application_Model_Status',
        'attached_to'       =>  'Application_Model_User'
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

    public function getDescriptionParsed() {
        return nl2br($this->description);
    }

    public function getAttachedTo() {
        return new Application_Model_User($this->attached_to);
    }
}