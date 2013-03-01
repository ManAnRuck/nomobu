<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 01.03.13
 * Time: 02:50
 * To change this template use File | Settings | File Templates.
 */
class AuthController extends Zend_Controller_Action {
    public function indexAction() {
        if(!Zend_Registry::get('user')) {
            return $this->forward('signin');
        }
    }

    public function signinAction() {
        $form = new Zend_Form();
        $form->addElement(new Zend_Form_Element_Text(array(
            'name'          =>  'email',
            'label'         =>  'E-Mail',
            'validators'    =>  array('EmailAddress'),
            'required'      =>  true
        )));

        $form->addElement(new Zend_Form_Element_Password(array(
            'name'          =>  'password',
            'label'         =>  'Password',
            'required'      =>  'true'
        )));

        $form->addElement(new Zend_Form_Element_Submit(array(
            'name'      =>  'Submit',
            'label'     =>  'Signin'
        )));


        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                // echo 'valid';
                $userModel = new Application_Model_User();
                $user = $userModel->getMapper()->fetchRow(array(
                    'email = ?'     => $this->getRequest()->getParam('email'),
                    'password = ?'  => md5($this->getRequest()->getParam('password'))
                ));

                if(!$user) {
                    CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Wrong credentials'));
                } else {
                    $session = new Zend_Session_Namespace('nomobu');
                    $session->user = $user;
                    Zend_Registry::set('user', $user);
                    return $this->forward('index', 'tickets');
                }

            } else {
                // echo 'invalid';
                CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Please check the highlighted fields'));
            }
        }

        $this->view->signinForm = $form;
    }

    public function signoutAction() {
        $session = new Zend_Session_Namespace('nomobu');
        unset($session->user);
        Zend_Registry::set('user', null);

        return $this->forward('index', 'tickets');
    }
}