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
        'projects_id'       =>  'Application_Model_Project'
    );
}