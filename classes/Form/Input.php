<?php

namespace Form;

class Input{
	public $type;
	public $name;
	public $hashed_name;
	public $prompt = "";
	public $placeholder = "";
	public $value = "";
	public $class = array();

	public $validator = array();
	public $required = false;
	public $maxLength = false;
	public $minLength = false;
	public $range = array();

	
	private $formName = "";

	public function __construct($type, $name){
		$this->type = $type;
		$this->name = $name;
	}

	public function isValid($errorMessage = false){
		$messages = array();

		if($this->required && empty($this->value)){

			$messages[1] = "Field cannot be empty";

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

			if(count($this->range) && ($this->value < $this->range["min"] || $this->value > $this->range["max"])){
				$messages[] = "Must be between " . $this->range["min"] . " and " . $this->range["max"];
			}
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

		$error = "";

		if (isset($data[$this->hashed_name])) {
			$this->value = $data[$this->hashed_name];

			$result = $this->isValid(true);

			if (is_array($result)){
				$error .= '<ul class="form-group-error">';

				foreach($result as $inputError){
					$error .= '<li>' . $inputError . '</li>';
				}

				$error .= '</ul>';
			}
		}

		$class = implode(' ', $this->class);
		$required = $this->required ? '<span class="required">*</span>' : '';

		return <<<HTML
		<div class="form-group">
			{$this->getLabel()}
			<input id="{$this->hashed_name}" class="{$class}" type="{$this->type}"  placeholder="{$this->placeholder}"  name="{$this->hashed_name}"  value="{$this->value}"/>{$required}
			{$error}
		</div>
HTML;
	}

	private function getLabel(){
		if($this->prompt != ""){
			return '<label for="' . $this->hashed_name . '">' . $this->prompt . ':</label>';
		}

		return null;
	}


	/***********************
	 *
	 *    Setters
	 *
	 **********************/

	public function setPlaceholder($string){
		$this->placeholder = $string;

		return $this;
	}

	public function setValue($string){
		$this->value = $string;

		return $this;
	}

	public function setClass($string){
		$this->class[] = $string;

		return $this;
	}

	public function setRequired($bool){
		$this->required = $bool;

		return $this;
	}

	public function setMaxLength($number){
		$this->maxLength = $number;

		return $this;
	}

	public function setMinLength($number){
		$this->minLength = $number;

		return $this;
	}

	public function setRange($min, $max){
		$this->range['min'] = $min;
		$this->range['max'] = $max;

		return $this;
	}

	public function setPrompt($string){
		$this->prompt = $string;

		return $this;
	}

	public function setValidator($regex){
		$this->validator[] = $regex;

		return $this;
	}

	public function setFormName($string){
		$this->formName = $string;
		$this->hashed_name = sha1($this->formName . '_' . $this->name);
	}
}