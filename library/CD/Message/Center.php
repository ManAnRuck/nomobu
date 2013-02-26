<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 22.02.13
 * Time: 23:29
 * To change this template use File | Settings | File Templates.
 */

class CD_Message_Center {
    protected $_messages = array();

    /**
     * @return CD_Message_Center
     */
    public static function getInstance() {
        if(!Zend_Registry::getInstance()->isRegistered('messageCenter')) {
            $me = new CD_Message_Center();
            Zend_Registry::getInstance()->set('messageCenter', $me);
        }

        return Zend_Registry::getInstance()->get('messageCenter');
    }

    public function addMessage(CD_Message $message) {
        $this->_messages[] = $message;
    }

    public function getMessages() {
        return $this->_messages;
    }

    public function renderMessages() {
        $return = '';
        /** @var $message CD_Message */
        foreach($this->_messages as $message) {
            $return.= $message->render();
        }

        return $return;
    }


    public function flush() {
        $return = $this->renderMessages();
        $this->_messages = array();
        return $return;
    }
}