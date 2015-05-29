<?php

namespace Form\Element;

use Form\Validator;

class Input extends \Form\Element{

	public $type;
	public $placeholder = "";
	public $maxLength = false;
	public $minLength = false;
	public $range = array();

	public function __construct($type, $name){
		parent::__construct($name);

		if(!InputType::HasSupport($type)){
			throw new \Exception("Form/Input::Constructor() - The input type is not supported");
		}

		$this->type = $type;
	}

	public function IsValid($errorMessage = false){

		$messages = $this->ValidateComparators();

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

			if($this->minLength && strlen($this->value) < $this->minLength){
				$messages[] = \Form\String::Get("Field_Long", $this->minLength);
			}

			if(	count($this->range)){
				$this->value = intval($this->value);

				if((!preg_match(\Form\Validator::INT, $this->value) || !preg_match(\Form\Validator::FLOAT, $this->value)) &&
					($this->value < $this->range["min"] || $this->value > $this->range["max"])
				){
					$messages[] = \Form\String::Get("Field_Range", $this->range["min"], $this->range["max"]);
				}
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

		$required = $this->required ? ' data-required="true"' : '';

		$validators = array();

		foreach($this->validator as $regex){
			$validators[] = Validator::GetPositionInArray($regex);
		}

		$validators = implode(',', $validators);
		$validators = !empty($validators) ? ' data-validators="' . $validators . '" ': '';
		$validators .= $this->GetComparatorAsDataAttr();
		return <<<HTML

			<div class="form-group">
				{$this->GetLabelHTML()}
				<input id="{$this->hashed_name}" {$this->GetComparatorAsDataAttr()} class="{$this->GetClassString()}" type="{$this->type}" maxlength="{$this->maxLength}"  placeholder="{$this->placeholder}"  name="{$this->formName}[{$this->hashed_name}]"  value="{$this->value}" {$required} {$validators}/>{$this->GetRequiredHTML()}
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

	public function SetMinLength($number){
		$this->minLength = $number;

		return $this;
	}

	public function SetRange($min, $max){
		$this->range['min'] = $min;
		$this->range['max'] = $max;

		return $this;
	}
}