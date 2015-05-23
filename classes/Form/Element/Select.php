<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 12:13 PM
 */

namespace Form\Element;


class Select extends ElementBase{
    public $placeholder = "";
    private $options;
    private $optionPadding;

    public function __construct($name, $options = array()){
        parent::__construct($name);

        $this->options = $options;
    }

    public function isValid($errorMessage = false){

        if($errorMessage && $this->required && empty($this->value)){
            return array("You must select an option");
        }

        return !($this->required && empty($this->value));
    }

    public function getHTML($data){

        if (isset($data[$this->hashed_name])) {
            $this->value = $data[$this->hashed_name];
        }

        $options = $this->placeholder ? '<option value="">' . $this->placeholder . '</option>' : '';

        foreach($this->options as $val => $option){
            $value = $this->optionPadding ? $this->optionPadding + $val : $val;
            $options .= '<option value="' . $value . '"' . ($this->value == $value ? ' selected="selected"': '') . '>' . $option . '</option>';
        }

        return <<<HTML
		<div class="form-group">
            {$this->getLabelHTML()}
			<select id="{$this->hashed_name}" class="{$this->getClassString()}" name="{$this->hashed_name}" >
                {$options}
			</select>
			{$this->getRequiredHTML()}
			{$this->getErrorMessageHTML($data)}
		</div>
HTML;
    }

    public function setPlaceholder($string){
        $this->placeholder = $string;

        return $this;
    }

    public function setOptionPadding($int){
        $this->optionPadding = $int;

        return $this;
    }

    public function addOption($option){
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