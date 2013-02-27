<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 27.02.13
 * Time: 20:53
 * To change this template use File | Settings | File Templates.
 */

class UpdatesController extends CD_Controller_Admin {
    protected $_itemClass = 'Application_Model_Update';
    protected $_mapForTo = 'tickets_id';

    public function forwardActionOnSaveSuccess() {
        return 'view';
    }

    public function forwardControllerOnSaveSuccess() {
        return 'tickets';
    }

    public function forwardParamsOnSaveSuccess() {
        return array(
            'id'        =>  $this->getRequest()->getParam('for')
        );
    }
}