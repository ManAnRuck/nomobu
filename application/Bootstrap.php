<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initUser() {
        Zend_Registry::set('user', null);

        $session = new Zend_Session_Namespace('nomobu');
        if(isset($session->user)) {
            Zend_Registry::set('user', $session->user);
        }
    }

}

