<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 22.02.13
 * Time: 21:37
 * To change this template use File | Settings | File Templates.
 */

class CD_Controller_Admin extends Zend_Controller_Action {
    protected $_itemClass = null;

    protected $_mapForTo = null;

    public function init() {
        // check for item class
        if(!$this->_itemClass) throw new Exception('No item class defined');
    }

    public function indexAction() {
        $this->forward('list');
    }

    public function listAction() {
        $modelClass = $this->_itemClass;
        $model = new $modelClass;
        $mapper = $model->getMapper();
        $this->view->items = $mapper->fetchAll();
    }

    public function addAction() {
        $this->forward('edit');
    }

    public function editAction() {
        $className = $this->_itemClass;
        $item = new $className($this->getRequest()->getParam('id'));

        if($this->getRequest()->getParam('for') && $this->_mapForTo) {
            $item->{$this->_mapForTo} = $this->getRequest()->getParam('for');
        }

        $form = $item->getForm();

        /** @var $item CD_Model */
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                // save
                foreach($this->getRequest()->getPost() as $key => $value) {
                    $item->{$key} = $value;
                }

                if(count($_FILES) > 0) {
                    foreach($_FILES as $fieldname => $info) {
                        if($info['error'] == 0 && $this->getParam('originaltitle') != '') {
                            $filter = new CD_Filter_Shortcut();
                            $filename = $filter->filter($this->getParam('originaltitle'));
                            $target = getcwd(). '/-assets/'. $filename;
                            $pathinfo = pathinfo($_FILES[$fieldname]['name']);
                            $target.= '.'.$pathinfo['extension'];
                            move_uploaded_file($_FILES[$fieldname]['tmp_name'], $target);
                        }
                    }
                }

                // $f = new CD_Filter_Shortcut();
                // echo $f->filter($item->__toString());

                try {
                    $item->save();
                    CD_Message_Center::getInstance()->addMessage(new CD_Message_Success('<strong>'. $item. '</strong> saved'));
                    $this->forward('list');
                } catch(Exception $e) {
                    CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Save error: '. $e->getMessage()));
                }

            } else {
                // add error message to center
                CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Please check the highlighted fields.'));
            }
        }

        $this->view->item = $item;
        $this->view->form = $form;
    }

    public function deleteAction() {
        try {
            $className = $this->_itemClass;
            $item = new $className($this->getRequest()->getParam('id'));
            $itemName = $item->__toString();
            $item->delete();
            CD_Message_Center::getInstance()->addMessage(new CD_Message_Success('<strong>'. $itemName. '</strong> deleted'));
        } catch(Exception $e) {
            CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Error: '. $e->getMessage()));
        }

        $this->forward('list');
    }

    public function deactivateAction() {
        $this->forward('delete');
    }
}