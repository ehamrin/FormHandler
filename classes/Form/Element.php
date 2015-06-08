<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 9:54 AM
 */

namespace Form;


use Form\Element\Checkbox;

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
    public $inArray = array();
    /* @var $form Form */
    protected $form;

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
            if(!array_key_exists(implode('', $this->inArray) . $comparator["name"], $this->form->inputRepository)){
                throw new \Exception("Comparator cannot find element of name \"{$comparator["name"]}\"");
            }

            $element = $this->form->inputRepository[implode('', $this->inArray) . $comparator["name"]];
            $a = $this->value;
            $b = $element->value;
            $message = !empty($b) ? $b : $element->prompt;

            switch($comparator["type"]){
                case Comparator::GREATER_THAN:
                    if(!($a > $b)){
                        $errorMessage[] = String::Get("Comparator_Greater_Than", $message);
                    }
                    break;
                case Comparator::GREATER_THAN_EQUAL:
                    if(!($a >= $b)){
                        $errorMessage[] = String::Get("Comparator_Greater_Than_Equal", $message);
                    }
                    break;
                case Comparator::LESS_THAN:
                    if(!($a < $b)){
                        $errorMessage[] = String::Get("Comparator_Less_Than", $message);
                    }
                    break;
                case Comparator::LESS_THAN_EQUAL:
                    if(!($a <= $b)){
                        $errorMessage[] = String::Get("Comparator_Less_Than_Equal", $message);
                    }
                    break;
                case Comparator::EQUALS:
                    if(!($a == $b)){
                        $errorMessage[] = String::Get("Comparator_Equals", $message);
                    }
                    break;
                default:
                    throw new \BadFunctionCallException("Comparator not found");
                    break;

            }

        }

        return $errorMessage;
    }

    public function GetComparatorAsDataAttr(){
        $string = array();

        if(!count($this->compareElements)){
            return "";
        }

        foreach($this->compareElements as $comparator){
            $compare = "";

            switch($comparator["type"]){
                case Comparator::GREATER_THAN:
                    $compare = 'data-greater-than';
                    break;
                case Comparator::GREATER_THAN_EQUAL:
                    $compare = 'data-greater-than-equal';
                    break;
                case Comparator::LESS_THAN:
                    $compare = 'data-less-than';
                    break;
                case Comparator::LESS_THAN_EQUAL:
                    $compare = 'data-less-than-equal';
                    break;
                case Comparator::EQUALS:
                    $compare = 'data-equals';
                    break;
                default:
                    throw new \BadFunctionCallException("Comparator not found");
                    break;

            }

            if(!empty($compare)){
                if(!array_key_exists(implode('', $this->inArray) . $comparator["name"], $this->form->inputRepository)){
                    throw new \Exception("Comparator cannot find element of name \"{$comparator["name"]}\"");
                }

                $element = $this->form->inputRepository[implode('', $this->inArray) . $comparator["name"]];

                $string[] = $compare . '="' . $element->hashed_name . '"';
            }

        }
        return implode(' ', $string);

    }

    public function AddToArray(){
        if(func_num_args()){
            $this->inArray = func_get_args();
            return $this;
        }else {
            throw new \BadMethodCallException("You need to specify at least one parameter");
        }
    }

    public function GetLocationAsArray(){
        $ret = array($this->name => $this->value);
        for($i = count($this->inArray)-1; $i > 0; $i--){
            $ret = array($this->inArray[$i] => $ret);
        }
        return $ret;
    }

    public function GetArrayPadding(){
        $ret = '';
        foreach($this->inArray as $array){
            $ret .= '[' . $array . ']';
        }

        return $ret;
    }

    public function UpdateValue($data){
        if(count($this->inArray)){
            foreach($this->inArray as $array){
                if(isset($data[$array])){
                    $data = $data[$array];
                }
            }
        }

        if(isset($data[$this->hashed_name])){
            $this->value = $data[$this->hashed_name];
        }

    }

    public function Sanitize($ignored = array()){

        if($this instanceof \Form\Element\File){
            return;
        }

        foreach($ignored as $name){
            if($name == $this->name){
                return;
            }
        }
        $this->value = htmlentities(strip_tags(trim($this->value)));
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

    public function AddForm($form){
        $this->form = $form;
    }

    public function SetFormName($string){
        $this->formName = $string;

        $this->hashed_name = 'a' . hash('sha256', \Form\Form::SALT . hash('sha256', $this->formName . '_' . implode('_', $this->inArray) . $this->name));
    }

    public function SetValidator($regex){
        $this->validator[] = $regex;

        return $this;
    }

    public function SetComparator($type, $element){
        $comparator = array();
        $comparator["type"] = $type;
        $comparator["name"] = $element;

        $this->compareElements[] = $comparator;
        return $this;
    }

    public function GetHTML(){
        return "";
    }

    public function GetClassString(){
        return implode(' ', $this->class);
    }

    public function GetRequiredHTML(){
        return $this->required && $this->showRequired ? '<span class="required">*</span>' : '';

    }

    public function GetLabelHTML(){
        return ($this->prompt != "") ? '<label for="' . $this->hashed_name . '">' . $this->prompt . $this->GetRequiredHTML() . '</label>' : '';
    }

    public function GetErrorMessageHTML(){
        if(count($this->form->GetMethodArray())) {
            $ret = "";

            //Check validation
            $result = $this->IsValid(true);

            //Loop through error messages
            if (is_array($result)) {
                $ret .= '<ul class="form-group-error">';

                foreach ($result as $inputError) {
                    $ret .= '<li>' . $inputError . '</li>';
                }

                $ret .= '</ul>';

            }

            return $ret;
        }
        return null;
    }
}