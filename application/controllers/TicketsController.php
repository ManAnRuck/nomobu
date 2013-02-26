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

    }

    public function editAction() {
        if($this->getRequest()->getParam('id')) return $this->forward('view');
        parent::editAction();
    }
}