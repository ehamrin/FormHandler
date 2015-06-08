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
                $messages[] = \Form\String::Get("Field_Empty");
            }

            foreach($this->validator as $regex){
                if(preg_match($regex, $this->value) == 0){
                    $messages[] = Validator::ErrorMessage($regex);
                }
            }

            if($this->maxLength && strlen($this->value) > $this->maxLength){
                $messages[] = \Form\String::Get("Field_Short", $this->maxLength);
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
    public function GetHTML()
    {

        $errormessage = $this->GetErrorMessageHTML();

        return <<<HTML

			<div class="form-group {$this->GetGroupClass()}">
				{$this->GetLabelHTML()}
				<textarea id="{$this->hashed_name}" class="{$this->GetClassString()}" maxlength="{$this->maxLength}" placeholder="{$this->placeholder}"  name="{$this->formName}[{$this->hashed_name}]">{$this->value}</textarea>
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
