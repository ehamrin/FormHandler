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
    public $compareElements = array();

    public function __construct($name){
        $this->name = $name;
    }

    public function IsValid($errorMessage = false){

        if($errorMessage && $this->required && empty($this->value)){
            return array(String::Get("Field_Empty"));
        }

        return !($this->required && empty($this->value));
    }

    public function ValidateComparators(){
        $errorMessage = array();

        if(!count($this->compareElements)){
            return $errorMessage;
        }

        foreach($this->compareElements as $comparator){
            $a = $this->value;
            $b = $comparator["element"]->value;

            switch($comparator["type"]){
                case Comparator::GREATER_THAN:
                    if(!($a > $b)){
                        $errorMessage[] = String::Get("Comparator_Greater_Than", $b);
                    }
                    break;
                case Comparator::GREATER_THAN_EQUAL:
                    if(!($a >= $b)){
                        $errorMessage[] = String::Get("Comparator_Greater_Than_Equal", $b);
                    }
                    break;
                case Comparator::LESS_THAN:
                    if(!($a < $b)){
                        $errorMessage[] = String::Get("Comparator_Less_Than", $b);
                    }
                    break;
                case Comparator::LESS_THAN_EQUAL:
                    if(!($a <= $b)){
                        $errorMessage[] = String::Get("Comparator_Less_Than_Equal", $b);
                    }
                    break;
                case Comparator::EQUALS:
                    if(!($a == $b)){
                        $errorMessage[] = String::Get("Comparator_Equals", $b);
                    }
                    break;
                default:
                    throw new \BadFunctionCallException("Comparator not found");
                    break;

            }

        }

        return $errorMessage;
    }

    public function Sanitize($ignored = array()){

        if(property_exists($this, "type") && $this->type == \Form\Element\InputType::Password){
            return;
        }

        if($this instanceof \Form\Element\File){
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

    public function SetComparator($type, $element){
        $comparator = array();
        $comparator["type"] = $type;
        $comparator["name"] = $element;
        $comparator["element"] = "";

        $this->compareElements[] = $comparator;
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

        if (isset($data[$this->hashed_name]) || isset($data[Form::$FileLocation][$this->hashed_name])) {
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