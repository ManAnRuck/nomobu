<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // check for db
        $front = $this->getFrontController();
        $bootstrap = $front->getParam('bootstrap');
        if (null === $bootstrap) {
            throw new Exception('Unable to find bootstrap');
        }
        $options = $bootstrap->getOptions();
        if(!isset($options['resources']['db'])) {
            return $this->forward('index', 'install');
        }

        // check for at least one (admin) user
        $userModel = new Application_Model_User();
        $users = $userModel->getMapper()->fetchAll();
        if(count($users) <= 0) {
            return $this->forward('index', 'install');
        }

        // action body
        $this->forward('index', 'auth');
    }


}

