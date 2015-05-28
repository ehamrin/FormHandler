<?php


namespace Form;

spl_autoload_register(function ($class) {
	$class = str_replace("\\", "/", $class);
	if(file_exists(dirname(__DIR__) . '/' . $class . '.php')){
		include dirname(__DIR__) . '/' . $class . '.php';
	}

});

class Form{
	
	private $formName;
	private $method;
	private $inputHTML = "";
	private $saveText;
	private $successText = "";
	private $errorText = "";
	private $inputRepository = array();
	private $has_files = false;
	public static $SessionLocation = "FormHandler";
	public static $FileLocation = "FileUpload";
	const SALT = "Ent3r_Y0ur_0Wn_Str!ng_H3r3";

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
			if($this->isValid()){
				if(!empty($this->successText)) {
					$message = '<p class="success">' . $this->successText . '</p>';
				}

			}else if(!$this->isValid() && !empty($this->errorText)){

				$message = '<p class="error">' . $this->errorText . '</p>';

			}

		}
		$enctype = "";

		if($this->has_files){
			$enctype = 'enctype="multipart/form-data"';
		}

		$this->ClearSession();

		return <<<HTML

	<form action="" method="{$this->method}" id="{$this->formName}" {$enctype}>
			{$message}
			{$this->inputHTML}

	</form>

HTML;
	}


	protected function GetMethodArray($ignoreSession = false){
		$data = array();

		if(isset($_SESSION[self::$SessionLocation][$this->formName]) && $ignoreSession == false){

			$data = $_SESSION[self::$SessionLocation][$this->formName];

		}else if($this->method == Method::POST && isset($_POST[$this->formName])){

			$data = $_POST[$this->formName];

		}else if($this->method == Method::GET && isset($_GET[$this->formName])){

			$data = $_GET[$this->formName];

		}
		$files = $_FILES;
		if(count($files)){
			foreach($files as $key => $file){
				$files[$key]["file_data"] = base64_encode(file_get_contents($file['tmp_name']));
				unset($file['tmp_name']);
			}
			$data[self::$FileLocation] = $files;
		}
		return $data;
	}
	
	public function WasSubmitted($ignoreSession = false){
		$data = $this->GetMethodArray($ignoreSession);
		return count($data);
	}

	protected function GetSaveButtonName(){
		return $this->formName . '_' . self::SavePadding;
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

		foreach($input->compareElements as $key => $comparator){
			if(isset($this->inputRepository[$comparator["name"]])){
				$input->compareElements[$key]["element"] = $this->inputRepository[$comparator["name"]];
			}else{
				throw new \RuntimeException("Input " . $comparator["name"] . " not found and cannot be compared with " . $input->name);
			}
		}
		$input->SetFormName($this->formName);

		$this->inputHTML .= $input->GetHTML($this->GetMethodArray());

		return $this;
	}

	public function AddFile(Element\File $input){
		$this->has_files = true;
		$this->inputRepository[$input->name] = $input;
		$input->SetFormName($this->formName);
		$this->inputHTML .= $input->GetHTML($this->GetMethodArray());

		return $this;
	}

	public function AddSubmit($string = ""){
		if(!empty($string)){
			$this->saveText = $string;
		}

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

					if($input instanceof Element\File){
						$object->{$input->name} = $input->GetFileData();
					}else{
						$object->{$input->name} = $input->value;
					}
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