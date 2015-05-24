<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 7:34 PM
 */

namespace Form\Element;


class Checkbox extends ElementBase{

    public function isValid($errorMessage = false){
        $messages = array();

        var_dump($this->value);

        if($this->required && empty($this->value)){

            $messages[] = "Must be checked";

        }

        $has_error = count($messages);

        if($has_error){
            $this->setClass("error");

            if($errorMessage){
                return $messages;
            }
        }

        return !$has_error;
    }


    /*****************************
     *
     *     HTML Generators
     *
     ****************************/
    public function getHTML($data)
    {
        //Was the data posted?
        if (isset($data[$this->hashed_name])) {
            $this->value = $data[$this->hashed_name];
        }

        $errormessage = $this->getErrorMessageHTML($data);
        $checked = $this->value == 'on' ? 'checked="checked"' : '' ;
        return <<<HTML
		<div class="form-group">
			{$this->getLabelHTML()}
			<input id="{$this->hashed_name}" class="{$this->getClassString()}" type="checkbox" name="{$this->formName}[{$this->hashed_name}]"  $checked/>{$this->getRequiredHTML()}
			{$errormessage}
		</div>
HTML;
    }

    public function getErrorMessageHTML($data){

        $ret = "";
        if(count($data)) {
            //Check validation
            $result = $this->isValid(true);

            //Loop through error messages
            if (is_array($result)) {
                $ret .= '<ul class="form-group-error">';

                foreach ($result as $inputError) {
                    $ret .= '<li>' . $inputError . '</li>';
                }

                $ret .= '</ul>';
                return $ret;
            }

            return null;
        }
        return null;

    }

}