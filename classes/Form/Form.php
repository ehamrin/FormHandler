<?php


namespace Form;


include 'Method.php';
include 'Validator.php';
include 'Element/Input.php';
include 'Element/InputType.php';


class Form{
	
	private $formName;
	private $method;
	private $inputHTML = "";
	private $saveText = "Save";
	private $successText = "";
	private $errorText = "";
	private $inputRepository = array();
	
	const SavePadding = "Save_";
	
	public function __construct($name, $method){
		$this->formName = $name;
		$this->method = $method;
	}
	
	public function GenerateOutput(){
		$message = null;

		if($this->wasSubmitted()){
			if($this->isValid() && !empty($this->successText)){

				$message = '<p class="success">' . $this->successText . '</p>';

			}else if(!$this->isValid() && !empty($this->errorText)){

				$message = '<p class="error">' . $this->errorText . '</p>';

			}
		}

		return <<<HTML
		<form method="{$this->method}" id="{$this->formName}">
			{$message}
			{$this->inputHTML}
			<button name="{$this->getSaveButtonName()}" value="1">{$this->saveText}</button>
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

	public function setButtonText($string){
		$this->saveText = $string;
		return $this;
	}

	public function setSuccessMessage($string){
		$this->successText = $string;
		return $this;
	}

	public function setErrorMessage($string){
		$this->errorText = $string;
		return $this;
	}

	public function AddInput(Element\Input $input){

		$this->inputRepository[$input->name] = $input;

		$input->setFormName($this->formName);

		$this->inputHTML .= $input->getHTML($this->getMethodArray());

		return $this;
	}

	public function AddCustomHTML($html){

		$this->inputHTML .= $html;

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

				if($sanitize && $input->type != InputType::Password){
					$input->Sanitize($ignored);
				}
				$object->{$input->name} = $input->value;

			}else{

				throw new \Exception("Form/Controller::PopulateObject() - An unvalid input was discovered");

			}
		}
	}

	public function GetDataAsObject($sanitize = true, $ignored = array()){
		$object = new \stdClass();

		$this->PopulateObject($object, $sanitize, $ignored);

		return $object;
	}
}