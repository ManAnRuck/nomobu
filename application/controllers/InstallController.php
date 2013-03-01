<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 01.03.13
 * Time: 12:40
 * To change this template use File | Settings | File Templates.
 */

class InstallController extends Zend_Controller_Action {
    public function init() {
        $this->_helper->layout->setLayout('installer');
    }

    public function indexAction() {
        $this->forward('check');
    }
    public function checkAction() {
        $continue = true;

        // check if config file can bw written
        if(!file_exists('../application/configs/application.ini') OR !is_writable('../application/configs/application.ini')) {
            $this->view->configWritable = false;
            $continue = false;
        } else {
            $this->view->configWritable = true;
        }

        $this->view->continue = $continue;
    }
    public function databaseAction() {
        $form = new Zend_Form();

        $form->addElement(new Zend_Form_Element_Text(array(
            'name'      =>  'dbhost',
            'label'     =>  'Host',
            'required'  =>  'true'
        )));

        $form->addElement(new Zend_Form_Element_Text(array(
            'name'      =>  'dbuser',
            'label'     =>  'User',
            'required'  =>  'true'
        )));

        $form->addElement(new Zend_Form_Element_Password(array(
            'name'      =>  'dbpassword',
            'label'     =>  'Password'
        )));

        $form->addElement(new Zend_Form_Element_Text(array(
            'name'      =>  'dbdb',
            'label'     =>  'Database',
            'required'  =>  'true'
        )));

        if($this->getRequest()->getPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                // check connection
                $db = new Zend_Db_Adapter_Pdo_Mysql(array(
                    'host'     => $form->getValue('dbhost'),
                    'username' => $form->getValue('dbuser'),
                    'password' => $form->getValue('dbpassword'),
                    'dbname'   => $form->getValue('dbdb')
                ));

                try {
                    $db->getConnection();
                    $writeToConfig = '
resources.db.adapter = "PDO_MYSQL"
resources.db.params.host = "'.$form->getValue('dbhost').'"
resources.db.params.username = "'.$form->getValue('dbuser').'"
resources.db.params.password = "'.$form->getValue('dbpassword').'"
resources.db.params.dbname = "'.$form->getValue('dbdb').'"
resources.db.isDefaultTableAdapter = true
                    ';

                    $config = file_get_contents('../application/configs/application.ini');
                    $config = str_replace('placeholder.dbconfig = "this is a placeholder, please don\'t delete"', $writeToConfig, $config);
                    file_put_contents('../application/configs/application.ini', $config);

                    Zend_Registry::set('tempAdapter', $db);

                    return $this->forward('dbrollout');
                } catch(Exception $e) {
                    CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Error connecting database'));
                }
            } else {
                CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Please check the highlighted fields'));
            }
        }

        $this->view->form = $form;
    }

    public function dbrolloutAction() {
        $front = $this->getFrontController();
        $bootstrap = $front->getParam('bootstrap');
        if (null === $bootstrap) {
            throw new Exception('Unable to find bootstrap');
        }
        $options = $bootstrap->getOptions();


        $files = scandir('../dbmigrate/');
        foreach($files as $file) {
            if($file == '.' OR $file == '..') continue;
            $content = file_get_contents('../dbmigrate/'. $file);
            $lines = explode(PHP_EOL, $content);
            foreach($lines as $line) {
                if(trim($line) == '' OR substr(trim($line), 0, 2) == '/*') continue;
                Zend_Registry::get('tempAdapter')->query(trim($line));
            }
        }

        // print_r($options);
        $this->forward('admin');
    }
    public function adminAction() {
        $form = new Zend_Form();

        $form->setAction($this->getFrontController()->getRouter()->assemble(array(
            'controller'        =>  'install',
            'action'            =>  'admin'
        )));

        $form->addElement(new Zend_Form_Element_Text(array(
            'name'      =>  'username',
            'label'     =>  'Name',
            'required'  =>  'true',
            'validators'=>  array('NotEmpty')
        )));

        $form->addElement(new Zend_Form_Element_Text(array(
            'name'      =>  'email',
            'label'     =>  'email',
            'required'  =>  'true',
            'validators'=> array('EmailAddress')
        )));

        $form->addElement(new Zend_Form_Element_Password(array(
            'name'      =>  'password',
            'label'     =>  'Password',
            'required'  =>  'true'
        )));

        $form->addElement(new Zend_Form_Element_Password(array(
            'name'      =>  'passwordrepeat',
            'label'     =>  'Repeat password',
            'required'  =>  'true',
            'validators'=>  array(
                new Zend_Validate_Identical('password')
            )
        )));

        if($this->getRequest()->getPost()) {
            if($form->isValid($this->getRequest()->getPost())) {
                // valid, save
                $user = new Application_Model_User();
                $user->name = $form->getValue('username');
                $user->email = $form->getValue('email');
                $user->password = $form->getValue('password');
                $user->save();
                $this->forward('done');
            } else {
                CD_Message_Center::getInstance()->addMessage(new CD_Message_Error('Please check the highlighted fields'));
            }
        }

        $this->view->form = $form;

    }
    public function doneAction() {}
}