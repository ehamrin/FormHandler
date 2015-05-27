<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 12:13 PM
 */

namespace Form\Element;


class Select extends \Form\Element{
    public $placeholder = "";
    private $options;
    private $optionPadding;

    public function __construct($name, $options = array()){
        parent::__construct($name);

        $this->options = $options;
    }

    public function IsValid($errorMessage = false){
        $message = $this->ValidateComparators();

        if($this->required && empty($this->value)){
            $message[] = \Form\String::Get("Field_Empty_Select");
        }

        if($errorMessage && count($message)){
            return $message;
        }

        return !count($message);
    }

    public function GetHTML($data){

        if (isset($data[$this->hashed_name])) {
            $this->value = $data[$this->hashed_name];
        }

        $options = $this->placeholder ? '<option value="">' . $this->placeholder . '</option>' . PHP_EOL : '';

        foreach($this->options as $val => $option){
            $value = $this->optionPadding ? $this->optionPadding + $val : $val;
            $options .= '                   <option value="' . $value . '"' . ($this->value == $value ? ' selected="selected"': '') . '>' . $option . '</option>' . PHP_EOL;
        }

        return <<<HTML

            <div class="form-group form-select">
                {$this->GetLabelHTML()}
                <select id="{$this->hashed_name}" class="{$this->GetClassString()}" name="{$this->formName}[{$this->hashed_name}]" >
                        {$options}
                </select>
                {$this->GetRequiredHTML()}
                {$this->GetErrorMessageHTML($data)}
            </div>
HTML;
    }

    public function SetPlaceholder($string){
        $this->placeholder = $string;

        return $this;
    }

    public function SetOptionPadding($int){
        $this->optionPadding = $int;

        return $this;
    }

    public function AddOption($option){
        if(is_array($option)){
            foreach($option as $key => $value) {
                $this->options[$key] = $value;
            }
        }else{
            $this->options[] = $option;
        }

        return $this;
    }
}