<?php

namespace Form\Element;

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
		$messages = array();

		if($this->required && empty($this->value)){

			$messages[] = "Field cannot be empty";

		}else if(!empty($this->value)){

			$regex_mismatch = false;

			foreach($this->validator as $regex){
				if(preg_match($regex, $this->value) == 0 && $regex_mismatch == false){
					$messages[] = "The value is not in a valid format";
				}
				$regex_mismatch = true;
			}

			if($this->maxLength && strlen($this->value) > $this->maxLength){
				$messages[] = "Must be shorter than " . $this->maxLength . " characters";
			}

			if($this->minLength && strlen($this->value) < $this->minLength){
				$messages[] = "Must be longer than " . $this->minLength . " characters";
			}



			if(	count($this->range)){
				$this->value = intval($this->value);

				if((!preg_match(\Form\Validator::INT, $this->value) || !preg_match(\Form\Validator::FLOAT, $this->value)) &&
				($this->value < $this->range["min"] || $this->value > $this->range["max"])
				){
					$messages[] = "Must be between " . $this->range["min"] . " and " . $this->range["max"];
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

		return <<<HTML

			<div class="form-group">
				{$this->GetLabelHTML()}
				<input id="{$this->hashed_name}" class="{$this->GetClassString()}" type="{$this->type}" maxlength="{$this->maxLength}"  placeholder="{$this->placeholder}"  name="{$this->formName}[{$this->hashed_name}]"  value="{$this->value}"/>{$this->GetRequiredHTML()}
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