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
        return new Application_Model_Status($this->states_id);
    }

    public function getAuthor() {
        return new Application_Model_User($this->author);
    }

    public function getAttachedTo() {
        return new Application_Model_User($this->attached_to);
    }
}