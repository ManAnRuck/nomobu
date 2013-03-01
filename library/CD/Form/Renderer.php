<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cem
 * Date: 22.02.13
 * Time: 22:20
 * To change this template use File | Settings | File Templates.
 */

class CD_Form_Renderer {
    public static function render(Zend_Form $form) {
        $return = '';
        /** @var $element Zend_Form_Element */
        foreach($form->getElements() as $element) {
            $error = '';
            if(count($element->getErrors()) > 0) $error = 'error';
            $add = '
            <div class="control-group '.$error .'">
                '.self::renderLabel($element).'
                <div class="controls">
                    '.self::renderElement($element).'
                </div>
            </div>';
            $return.= $add.PHP_EOL;
        }

        $return = '<form class="form-horizontal" method="post" action="" enctype="multipart/form-data">'.PHP_EOL. $return .PHP_EOL. '</form>';

        return $return;
    }

    public static function renderElement(Zend_Form_Element $element) {
        $return = 'NO TYPE';
        if($element->getType() == 'Zend_Form_Element_Text') {
            $return = '<input '.self::renderAttributes($element).' value="'.$element->getValue().'" placeholder="'.$element->getLabel().'">';
        } elseif($element->getType() == 'Zend_Form_Element_Password') {
            $return = '<input '.self::renderAttributes($element).' value="'.$element->getValue().'" placeholder="'.$element->getLabel().'">';
        } elseif($element->getType() == 'Zend_Form_Element_Submit') {
            $return = '<button class="btn btn-primary">'.$element->getLabel().'</button>';
        } elseif($element->getType() == 'Zend_Form_Element_Textarea') {
            $return = '<textarea '.self::renderAttributes($element).' placeholder="'.$element->getLabel().'">'.$element->getValue().'</textarea>';
        } elseif($element->getType() == 'Zend_Form_Element_Select') {
            // $return = print_r($element->getMultiOptions(), true);
            $options = array();
            // $options[] = '<option value="0">Bitte wählen</option>';
            foreach($element->getMultiOptions() as $key => $value) {
                $selected = '';
                if($element->getValue() == $key) {
                    $selected = 'selected="selected"';
                }
                $add = '<option value="'. $key.'" '.$selected.'>'. $value.'</option>';
                $options[] = $add;
            }
            $return = '<select '. self::renderAttributes($element).'>'. implode($options).'</select>';
        } elseif($element->getType() == 'Zend_Form_Element_File') {
            $return = '<input type="file" '. self::renderAttributes($element).'>';
        } elseif($element->getType() == 'CD_Form_Element_Datetime') {
            $value = '';
            if($element->getValue() != '0000-00-00 00:00:00') $value = $element->getValue();
            $return = '<div id="'.$element->getName().'-picker" class="input-append">
                        <input '. self::renderAttributes($element) .'data-format="yyyy-MM-dd HH:mm:ss PP" type="text" value="'.$value.'"></input>
                        <span class="add-on">
                          <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                          </i>
                        </span>
                      </div>';

            $return.= " <script type=\"text/javascript\">
                            $(function() {
                                $('#".$element->getName()."-picker').datetimepicker({
                                  language: 'en'
                                });
                              });
                        </script>";

        } elseif($element->getType() == 'Zend_Form_Element_Checkbox') {
            $checked = '';
            if($element->getValue() == '1') {
                $checked = 'checked="checked"';
            }
            return '<input type="hidden" name="'.$element->getName().'" value="0"><input type="checkbox" '. self::renderAttributes($element).' value="1" '.$checked.'>';
        }
        return $return;
    }

    public function renderAttributes(Zend_Form_Element $element) {
        if($element->getType() == 'Zend_Form_Element_Textarea' AND $element->getValue() != '') {
            $return = 'id="'. $element->getName() .'" rows="'. (strlen($element->getValue()) / 100) .'" class="input-block-level" type="text" name="'.$element->getName().'"';
        } elseif($element->getType() == 'Zend_Form_Element_Password') {
            $return = 'id="'. $element->getName() .'" class="input-block-level" type="password" name="'.$element->getName().'"';
        }else {
            $return = 'id="'. $element->getName() .'" class="input-block-level" type="text" name="'.$element->getName().'"';
        }

        return $return;
    }

    public static function renderLabel(Zend_Form_Element $element) {
        $return = '<label class="control-label" for="'. $element->getName() .'">'.$element->getLabel().'</label>';

        if($element->getType() == 'Zend_Form_Element_Submit') {
            $return = '<label class="control-label"></label>';
        }

        return $return;
    }
}