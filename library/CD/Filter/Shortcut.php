<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 24.02.13
 * Time: 23:13
 * To change this template use File | Settings | File Templates.
 */

class CD_Filter_Shortcut implements Zend_Filter_Interface {
    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value
     * @throws Zend_Filter_Exception If filtering $value is impossible
     * @return mixed
     */
    public function filter($value) {
        $value = strtolower(trim($value));
        $value = str_replace('ä', 'ae', $value);
        $value = str_replace('ö', 'oe', $value);
        $value = str_replace('ü', 'ue', $value);
        $value = str_replace('ß', 'ss', $value);
        $value = str_replace('Ä', 'ae', $value);
        $value = str_replace('Ö', 'oe', $value);
        $value = str_replace('Ü', 'ue', $value);
        $return = '';

        $allowedString = 'abcdefghijklmnopqrstuvwxyz1234567890-';
        $allowed = array();
        for($i = 0; $i < strlen($allowedString); $i++) {
            $allowed[] = $allowedString[$i];
        }

        for($i = 0; $i < strlen($value); $i++) {
            if(in_array($value[$i], $allowed)) {
                $return.= $value[$i];
            } else {
                $return.= '-';
            }
        }

        while(strstr($return, '--')) {
            $return = str_replace('--', '-', $return);
        }

        if(substr($return, 0, 1) == '-') $return = substr($return, 1);
        if(substr($return, -1, 1) == '-') $return = substr($return, 0, strlen($return) -1);

        return $return;
    }
}