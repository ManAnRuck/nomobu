<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 26.02.13
 * Time: 22:52
 * To change this template use File | Settings | File Templates.
 */

class Application_Model_Ticket extends CD_Model {
    protected $_tableName = 'tickets';

    protected $_fieldLabels = array(
        'name'              =>  'Name',
        'description'       =>  'Description',
        'states_id'         =>  'State',
        'projects_id'       =>  'Project'
    );

    protected $_foreignKeys = array(
        'states_id'         =>  'Application_Model_Status',
        'projects_id'       =>  'Application_Model_Project',
        'author'            =>  'Application_Model_User',
        'attached_to'       =>  'Application_Model_User'
    );

    public function getProject() {
        return new Application_Model_Project($this->projects_id);
    }

    public function getState() {
        if(count($this->getUpdates()) <= 0) return new Application_Model_Status($this->states_id);
        $updates = $this->getUpdates();
        $updates = array_reverse($updates);
        return $updates[0]->getState();
    }

    public function getAuthor() {
        return new Application_Model_User($this->author);
    }

    public function getAttachedTo() {
        return new Application_Model_User($this->attached_to);
    }

    public function getUpdates() {
        $updateModel = new Application_Model_Update();
        return $updateModel->getMapper()->fetchAll(array('tickets_id = ?' => $this->id));
    }

    public function getUpdatedAsTimestamp() {
        if(count($this->getUpdates()) <= 0) return parent::getUpdatedAsTimestamp();
        $updates = $this->getUpdates();
        $updates = array_reverse($updates);
        return $updates[0]->getUpdatedAsTimestamp();
    }

    public function getDescriptionParsed() {
        return nl2br($this->description);
    }
}