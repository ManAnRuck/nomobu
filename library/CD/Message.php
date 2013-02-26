<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 22.02.13
 * Time: 23:32
 * To change this template use File | Settings | File Templates.
 */

class CD_Message {
    protected $_message = null;
    protected $_cssClass = 'alert';

    public function __construct($message) {
        $this->_message = $message;
    }

    public function render() {
        $return = '';
        $return.= '<div class="'. $this->_cssClass.'">';
        $return.= $this->_message;
        $return.= '</div>';
        return $return;
    }
}