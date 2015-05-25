<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 9:54 AM
 */

namespace Form;


abstract class Element {

    public $name;
    public $hashed_name;
    public $prompt = "";
    public $value = "";
    public $validator = array();
    public $class = array();
    protected $formName = "";
    public $required = false;
    public $showRequired = true;

    public function __construct($name){
        $this->name = $name;
    }

    public function IsValid($errorMessage = false){

        if($errorMessage && $this->required && empty($this->value)){
            return array(String::Get("Field_Empty"));
        }

        return !($this->required && empty($this->value));
    }

    public function Sanitize($ignored = array()){

        if(property_exists($this, "type") && $this->type == \Form\Element\InputType::Password){
            return;
        }

        foreach($ignored as $name){
            if($name == $this->name){
                return;
            }
        }
        $this->value = htmlentities(trim($this->value));
    }


    public function SetValue($string){
        $this->value = $string;

        return $this;
    }

    public function SetClass($string){
        $this->class[] = $string;

        return $this;
    }

    public function SetRequired($bool = true, $hide = false){
        $this->required = $bool;
        $this->showRequired = !$hide;

        return $this;
    }

    public function SetPrompt($string){
        $this->prompt = $string;

        return $this;
    }

    public function SetFormName($string){
        $this->formName = $string;

        $this->hashed_name = 'a' . hash('sha256', \Form\Form::SALT . hash('sha256', $this->formName . '_' . $this->name));
    }

    public function SetValidator($regex){
        $this->validator[] = $regex;

        return $this;
    }

    public function GetHTML($data){
        return "";
    }

    public function GetClassString(){
        return implode(' ', $this->class);
    }

    public function GetRequiredHTML(){
        return $this->required && $this->showRequired ? '<span class="required">*</span>' : '';

    }

    public function GetLabelHTML(){
        return ($this->prompt != "") ? '<label for="' . $this->hashed_name . '">' . $this->prompt . '</label>' : '';
    }

    public function GetErrorMessageHTML($data){

        $ret = "";
        if (isset($data[$this->hashed_name])) {
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