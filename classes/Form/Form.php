<?php


namespace Form;


include 'Method.php';
include 'Validator.php';
include 'Element/ElementBase.php';
include 'Element/Input.php';
include 'Element/InputType.php';
include 'Element/Select.php';
include 'Element/Checkbox.php';
include 'Element/RadioGroup.php';


class Form{
	
	private $formName;
	private $method;
	private $inputHTML = "";
	private $saveText = "Save";
	private $successText = "";
	private $errorText = "";
	private $inputRepository = array();
	
	const SavePadding = "Save_Button";
	
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
	
	private function GetMethodArray(){
		if($this->method == Method::POST && isset($_POST[$this->formName])){
			return $_POST[$this->formName];
		}else if($this->method == Method::GET && isset($_GET[$this->formName])){
			return $_GET[$this->formName];
		}else{
			return array();
		}
	}
	
	public function WasSubmitted(){
		$data = $this->GetMethodArray();
		return isset($data[self::SavePadding]);
	}
	
	private function GetSaveButtonName(){
		return $this->formName . '[' . self::SavePadding . ']';
	}

	public function SetButtonText($string){
		$this->saveText = $string;
		return $this;
	}

	public function SetSuccessMessage($string){
		$this->successText = $string;
		return $this;
	}

	public function SetErrorMessage($string){
		$this->errorText = $string;
		return $this;
	}

	public function AddInput(Element\ElementBase $input){

		$this->inputRepository[$input->name] = $input;

		$input->SetFormName($this->formName);

		$this->inputHTML .= $input->GetHTML($this->GetMethodArray());

		return $this;
	}


	public function AddCustomHTML($html){

		$this->inputHTML .= $html;

		return $this;
	}

	public function IsValid(){
		foreach ($this->inputRepository as $input){
			if(!$input->IsValid()){
				return false;
			}
		}

		return true;
	}

	public function PopulateObject($object, $sanitize = true, $ignored = array()){

		foreach ($this->inputRepository as $input){

			if($input->IsValid()){

				if($sanitize){
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