<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 26.02.13
 * Time: 22:31
 * To change this template use File | Settings | File Templates.
 */

class TicketsController extends CD_Controller_Admin {
    protected $_itemClass = 'Application_Model_Ticket';

    public function viewAction() {
        $item = new Application_Model_Ticket($this->getRequest()->getParam('id'));

        $usersModel = new Application_Model_User();
        $users = $usersModel->getMapper()->fetchAll();
        $this->view->users = $users;

        $statesModel = new Application_Model_Status();
        $states = $statesModel->getMapper()->fetchAll();
        $this->view->states = $states;

        $this->view->item = $item;
    }

    public function editAction() {
        if($this->getRequest()->getParam('id')) return $this->forward('view');
        parent::editAction();
    }
}