<?php

namespace Form\Element;

class Input extends ElementBase{

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
				(!preg_match(\Form\Validator::INT, $this->value) || !preg_match(\Form\Validator::FLOAT, $this->value))
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
		//Was the data posted?
		if (isset($data[$this->hashed_name])) {
			$this->value = $data[$this->hashed_name];
		}

		return <<<HTML
		<div class="form-group">
			{$this->getLabelHTML()}
			<input id="{$this->hashed_name}" class="{$this->getClassString()}" type="{$this->type}"  placeholder="{$this->placeholder}"  name="{$this->hashed_name}"  value="{$this->value}"/>{$this->getRequiredHTML()}
			{$this->getErrorMessageHTML($data)}
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