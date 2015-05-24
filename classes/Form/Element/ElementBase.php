<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 9:54 AM
 */

namespace Form\Element;


abstract class ElementBase {

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

    public function isValid($errorMessage = false){

        if($errorMessage && $this->required && empty($this->value)){
            return array("This element is required");
        }

        return !($this->required && empty($this->value));
    }

    public function Sanitize($ignored = array()){

        if(property_exists($this, "type") && $this->type == InputType::Password){
            return;
        }

        foreach($ignored as $name){
            if($name == $this->name){
                return;
            }
        }
        $this->value = htmlentities(trim($this->value));
    }


    public function setValue($string){
        $this->value = $string;

        return $this;
    }

    public function setClass($string){
        $this->class[] = $string;

        return $this;
    }

    public function setRequired($bool = true, $hide = false){
        $this->required = $bool;
        $this->showRequired = !$hide;

        return $this;
    }

    public function setPrompt($string){
        $this->prompt = $string;

        return $this;
    }

    public function setFormName($string){
        $this->formName = $string;
        $this->hashed_name = sha1($this->formName . '_' . $this->name);
    }

    public function setValidator($regex){
        $this->validator[] = $regex;

        return $this;
    }

    public function getHTML($data){
        return "";
    }

    public function getClassString(){
        return implode(' ', $this->class);
    }

    public function getRequiredHTML(){
        return $this->required && $this->showRequired ? '<span class="required">*</span>' : '';

    }

    public function getLabelHTML(){
        return ($this->prompt != "") ? '<label for="' . $this->hashed_name . '">' . $this->prompt . '</label>' : '';
    }

    public function getErrorMessageHTML($data){

        $ret = "";
        if (isset($data[$this->hashed_name])) {
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
        }
        return null;

    }
}