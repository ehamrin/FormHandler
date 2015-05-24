<?php

namespace Form\Element;

use Form\Validator;


class Textarea extends \Form\Element{

    public $placeholder = "";
    public $maxLength = false;

    public function IsValid($errorMessage = false){
        $messages = array();

        if($this->required || !empty($this->value)){

            if(empty($this->value)){
                $messages[] = "Field cannot be empty";
            }

            foreach($this->validator as $regex){
                if(preg_match($regex, $this->value) == 0){
                    $messages[] = Validator::ErrorMessage($regex);
                }
            }

            if($this->maxLength && strlen($this->value) > $this->maxLength){
                $messages[] = "Must be shorter than " . $this->maxLength . " characters";
            }
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

        return <<<HTML

			<div class="form-group">
				{$this->GetLabelHTML()}
				<textarea id="{$this->hashed_name}" class="{$this->GetClassString()}" maxlength="{$this->maxLength}" placeholder="{$this->placeholder}"  name="{$this->formName}[{$this->hashed_name}]">{$this->value}</textarea>{$this->GetRequiredHTML()}
				{$errormessage}
			</div>
HTML;
    }



    /***********************
     *
     *    Setters
     *
     **********************/

    public function SetPlaceholder($string){
        $this->placeholder = $string;

        return $this;
    }

    public function SetMaxLength($number){
        $this->maxLength = $number;

        return $this;
    }
}
