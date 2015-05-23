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
    private $formName = "";
    public $required = false;
    public $showRequired = true;

    public function __construct($name){
        $this->name = $name;
    }

    public function Sanitize($ignored = array()){
        foreach($ignored as $name){
            if($name == $this->name){
                return;
            }
        }
        $this->value = htmlentities($this->value);
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
}