<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 8:11 PM
 */

namespace Form\Element;


class RadioGroup extends \Form\Element{

    private $options;

    public function __construct($name, $options = array()){
        parent::__construct($name);

        $this->options = $options;
    }

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
    public function GetHTML()
    {

        $options = '';

        foreach($this->options as $val => $option){
            $options .= '<input id="' . $val . $this->hashed_name . '" name="' . $this->formName . '[' . $this->hashed_name . ']' . '" type="radio" value="' . $val . '"' . ($this->value == $val ? ' checked="checked"': '') . '/>';
            $options .= '<label for="' . $val . $this->hashed_name . '" class="radio-label">' . $option . '</label>';
        }

        $errormessage = $this->GetErrorMessageHTML();

        return <<<HTML

            <div class="form-group  {$this->GetGroupClass()}">
                {$this->GetLabelHTML()}
                <div class="radio-group">
                    {$options}
                </div>
                {$errormessage}
            </div>
HTML;
    }

    public function GetErrorMessageHTML(){

        $ret = "";
        if(count($this->form->GetMethodArray())) {
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

            return null;
        }
        return null;

    }

}