<?php


namespace Form;


include 'Method.php';
include 'Input.php';
include 'Validator.php';
include 'InputType.php';

class Controller{
	
	private $formName;
	private $method;
	private $inputHTML;
	private $validation;
	private $inputRepository = array();
	
	const SavePadding = "Save_";
	
	public function __construct($name, $method){
		$this->formName = $name;
		$this->method = $method;
		$this->inputHTML = "";
		$this->validation = true;
	}
	
	public function GenerateOutput(){
		return <<<HTML
		<form method="{$this->method}">
			{$this->inputHTML}
			<button name="{$this->getSaveButtonName()}" value="1">Save</button>
		</form>
HTML;
	}
	
	private function getMethodArray(){
		if($this->method == Method::POST){
			return $_POST;
		}else if($this->method == Method::GET){
			return $_GET;
		}else{
			return array();
		}
	}
	
	public function wasSubmitted(){
		$data = $this->getMethodArray();
		return isset($data[$this->getSaveButtonName()]);
	}
	
	private function getSaveButtonName(){
		return sha1(self::SavePadding . $this->formName);
	}
	
	public function AddInput(Input $input){

		$this->inputRepository[$input->name] = $input;

		$input->setFormName($this->formName);

		$this->inputHTML .= $input->getHTML($this->getMethodArray());

		return $this;
	}

	public function isValid(){
		foreach ($this->inputRepository as $input){
			if(!$input->isValid()){
				return false;
			}
		}

		return true;
	}

	public function PopulateObject($object, $sanitize = true, $ignored = array()){
		foreach ($this->inputRepository as $input){
			if($input->isValid()){
				$value = $input->value;

				if($sanitize && $input->type != InputType::Password){
					$doSanitize = true;
					foreach($ignored as $name){
						if($name == $input->name){
							$doSanitize = false;
						}
					}
					if($doSanitize){
						$value = htmlentities($value);
					}
				}
				$object->{$input->name} = $value;
			}else{
				throw new \Exception("Form/Controller::PopulateObject() - An unvalid input was discovered");
			}
		}
	}
}