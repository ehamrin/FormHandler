<?php

namespace Form;

include 'FormControl.php';

class Input extends FormControl{

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

	public function isValid($errorMessage = false){
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

				if(($this->value < $this->range["min"] || $this->value > $this->range["max"]) &&
				(!preg_match(Validator::INT, $this->value) || !preg_match(Validator::FLOAT, $this->value))
				){
					$messages[] = "Must be between " . $this->range["min"] . " and " . $this->range["max"];
				}
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

		//Was the data posted?
		if (isset($data[$this->hashed_name])) {
			$this->value = $data[$this->hashed_name];

			//Check validation
			$result = $this->isValid(true);

			//Loop through error messages
			if (is_array($result)){
				$error .= '<ul class="form-group-error">';

				foreach($result as $inputError){
					$error .= '<li>' . $inputError . '</li>';
				}

				$error .= '</ul>';
			}
		}

		//Prepare class names
		$class = implode(' ', $this->class);

		//Set required
		$required = $this->required ? '<span class="required">*</span>' : '';

		//Prepare label
		$label = ($this->prompt != "") ? '<label for="' . $this->hashed_name . '">' . $this->prompt . ':</label>' : '';

		return <<<HTML
		<div class="form-group">
			{$label}
			<input id="{$this->hashed_name}" class="{$class}" type="{$this->type}"  placeholder="{$this->placeholder}"  name="{$this->hashed_name}"  value="{$this->value}"/>{$required}
			{$error}
		</div>
HTML;
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
}