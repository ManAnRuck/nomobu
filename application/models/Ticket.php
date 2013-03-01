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
        'projects_id'       =>  'Project',
        'attached_to'       =>  'Attached to',
        'author'            =>  'Author',
        'types_id'          =>  'Type'
    );

    protected $_foreignKeys = array(
        'states_id'         =>  'Application_Model_Status',
        'projects_id'       =>  'Application_Model_Project',
        'author'            =>  'Application_Model_User',
        'attached_to'       =>  'Application_Model_User',
        'types_id'          =>  'Application_Model_Type'
    );

    public function getProject() {
        return new Application_Model_Project($this->projects_id);
    }

    public function getState() {
        return new Application_Model_Status($this->states_id);
    }

    public function getAuthor() {
        return new Application_Model_User($this->created_by);
    }

    public function getAttachedTo() {
        return new Application_Model_User($this->attached_to);
    }

    public function getUpdates() {
        $updateModel = new Application_Model_Update();
        return $updateModel->getMapper()->fetchAll(array('tickets_id = ?' => $this->id));
    }

    public function getUpdatedAsTimestamp() {
        return parent::getUpdatedAsTimestamp();
    }

    public function getDescriptionParsed() {
        return nl2br($this->description);
    }

    public function getType() {
        return new Application_Model_Type($this->types_id);
    }
}