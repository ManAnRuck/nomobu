<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 26.02.13
 * Time: 22:30
 * To change this template use File | Settings | File Templates.
 */

class Application_Model_User extends CD_Model {
    protected $_tableName = 'users';

    protected $_fieldLabels = array(
        'name'  => 'Name'
    );

    public function save() {
        if(trim($this->password) == '') {
            unset($this->password);
        } elseif(!isset($this->_original['password']) OR $this->password != $this->_original['password']) {
            $this->password = md5($this->password);
        }

        parent::save();
    }
}