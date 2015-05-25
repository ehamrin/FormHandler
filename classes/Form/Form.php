<?php


namespace Form;


include 'Method.php';
include 'Validator.php';
include 'String.php';
include 'Element/InputType.php';
include 'Element.php';
include 'Element/Input.php';
include 'Element/Textarea.php';
include 'Element/Select.php';
include 'Element/Checkbox.php';
include 'Element/RadioGroup.php';


class Form{
	
	private $formName;
	private $method;
	private $inputHTML = "";
	private $saveText;
	private $successText = "";
	private $errorText = "";
	private $inputRepository = array();
	public static $SessionLocation = "FormHandler";
	
	const SavePadding = "Save_Button";
	
	public function __construct($name = "FormHandler", $method){
		$this->formName = $name;
		$this->method = $method;


		if($this->WasSubmitted(true)){

			$_SESSION[self::$SessionLocation][$this->formName] = $this->GetMethodArray(true);
			header('Location: ' . $_SERVER['REQUEST_URI']);
			die();

		}

		$this->saveText = String::Get("Save_Button");

	}
	
	public function GenerateOutput(){
		$message = null;

		if($this->wasSubmitted()){
			if($this->isValid() && !empty($this->successText)){

				$message = '<p class="success">' . $this->successText . '</p>';
				$this->ClearSession();

			}else if(!$this->isValid() && !empty($this->errorText)){

				$message = '<p class="error">' . $this->errorText . '</p>';

			}

		}

		return <<<HTML

	<form method="{$this->method}" id="{$this->formName}">
			{$message}
			{$this->inputHTML}

	</form>

HTML;
	}


	protected function GetMethodArray($ignoreSession = false){

		if(isset($_SESSION[self::$SessionLocation][$this->formName]) && $ignoreSession == false){

			return 	$_SESSION[self::$SessionLocation][$this->formName];

		}else if($this->method == Method::POST && isset($_POST[$this->formName])){

			return $_POST[$this->formName];

		}else if($this->method == Method::GET && isset($_GET[$this->formName])){

			return $_GET[$this->formName];

		}else{
			return array();
		}
	}
	
	public function WasSubmitted($ignoreSession = false){
		$data = $this->GetMethodArray($ignoreSession);
		return isset($data[self::SavePadding]);
	}

	protected function GetSaveButtonName(){
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

	public function AddInput(Element $input){

		$this->inputRepository[$input->name] = $input;

		$input->SetFormName($this->formName);

		$this->inputHTML .= $input->GetHTML($this->GetMethodArray());

		return $this;
	}

	public function AddSubmit(){
		$this->inputHTML .= '<button name="' . $this->getSaveButtonName() . '" value="1">' . $this->saveText . '</button>';

		return $this;
	}


	public function AddCustomHTML($html){

		$this->inputHTML .= PHP_EOL . $html;

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

	protected function ClearSession(){
		if(isset($_SESSION[self::$SessionLocation][$this->formName])){
			unset($_SESSION[self::$SessionLocation][$this->formName]);
		}
	}

}