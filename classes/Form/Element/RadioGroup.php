<?php
/**
 * Created by PhpStorm.
 * User: Erik
 * Date: 5/23/2015
 * Time: 8:11 PM
 */

namespace Form\Element;


class RadioGroup extends ElementBase{

    private $options;

    public function __construct($name, $options = array()){
        parent::__construct($name);

        $this->options = $options;
    }

    public function isValid($errorMessage = false){
        $messages = array();

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

        $options = '';

        foreach($this->options as $val => $option){
            $options .= '<input id="' . $val . $this->hashed_name . '" name="' . $this->formName . '[' . $this->hashed_name . ']' . '" type="radio" value="' . $val . '"' . ($this->value == $val ? ' checked="checked"': '') . '/>';
            $options .= '<label for="' . $val . $this->hashed_name . '" class="radio-label">' . $option . '</label>';
        }

        $errormessage = $this->getErrorMessageHTML($data);

        return <<<HTML
		<div class="form-group">
			{$this->getLabelHTML()}
			<div class="radio-group">
			    {$options}
			</div>
			{$this->getRequiredHTML()}
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