<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 7:34 PM
 */

namespace Form\Element;


class Checkbox extends \Form\Element{

    public function IsValid($errorMessage = false){
        $messages = array();

        if($this->required && empty($this->value)){

            $messages[] = \Form\String::Get("Field_Empty_Check");

        }

        $has_error = count($messages);

        if($has_error){
            $this->SetClass("error");

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
    public function GetHTML($data)
    {
        //Was the data posted?
        if (isset($data[$this->hashed_name])) {
            $this->value = $data[$this->hashed_name];
        }

        $errormessage = $this->GetErrorMessageHTML($data);
        $checked = $this->value == 'on' ? 'checked="checked"' : '' ;
        return <<<HTML

            <div class="form-group">
                {$this->GetLabelHTML()}
                <input id="{$this->hashed_name}" class="{$this->GetClassString()}" type="checkbox" name="{$this->formName}[{$this->hashed_name}]"  $checked/>{$this->GetRequiredHTML()}
                {$errormessage}
            </div>

HTML;
    }

    public function GetErrorMessageHTML($data){

        $ret = "";
        if(count($data)) {
            //Check validation
            $result = $this->IsValid(true);

            //Loop through error messages
            if (is_array($result)) {
                $ret .= '<ul class="form-group-error">';

                foreach ($result as $inputError) {
                    $ret .= '<li>' . $inputError . '</li>';
                }

                $ret .= '</ul>';
                return $ret;
            }

        }
        return null;

    }

}