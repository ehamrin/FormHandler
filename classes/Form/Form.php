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

	public $inputRepository = array();

	private $has_files = false;

	public static $SessionLocation = "FormHandler";

	public static $FileLocation = "FileUpload";

	const SALT = "Ent3r_Y0ur_0Wn_Str!ng_H3r3";

	const SavePadding = "Save_Button";

	/**
	 * @param string $name
	 * @param $method
	 * @throws \ErrorException
     */
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

	/**
	 * @return string
     */
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
		$enctype = $this->has_files ? 'enctype="multipart/form-data"' : '' ;

		$html = '';

		foreach($this->inputRepository as $input){
			if($input instanceof Element){
				/* @var $input Element */
				$html .= $input->GetHTML();
			}else{
				$html .= $input;
			}
		}

		$this->ClearSession();

		return <<<HTML

	<form action="" method="{$this->method}" id="{$this->formName}" class="form-wrapper" {$enctype}>
			{$message}
			{$html}

	</form>

HTML;
	}


	/**
	 * @param bool $ignoreSession
	 * @return array
     */
	public function GetMethodArray($ignoreSession = false){
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

	/**
	 * @param bool $ignoreSession
	 * @return int
     */
	public function WasSubmitted($ignoreSession = false){
		$data = $this->GetMethodArray($ignoreSession);
		return count($data);
	}

	/**
	 * @return string
     */
	protected function GetSaveButtonName(){
		return $this->formName . '_' . self::SavePadding;
	}


	/**
	 * @param $string
	 * @return $this
     */
	public function SetButtonText($string){
		$this->saveText = $string;
		return $this;
	}

	/**
	 * @param $string
	 * @return $this
     */
	public function SetSuccessMessage($string){
		$this->successText = $string;
		return $this;
	}

	/**
	 * @param $string
	 * @return $this
     */
	public function SetErrorMessage($string){
		$this->errorText = $string;
		return $this;
	}

	/**
	 * @param Element $input
	 * @return $this
     */
	public function AddInput(Element $input){

		$this->inputRepository[implode('', $input->inArray) . $input->name] = $input;

		$input->SetFormName($this->formName);
		$input->UpdateValue($this->GetMethodArray());
		$input->AddForm($this);
		return $this;
	}

	/**
	 * @param Element\File $input
	 * @return $this
     */
	public function AddFile(Element\File $input){
		$this->has_files = true;

		$this->inputRepository[$input->name] = $input;

		$input->SetFormName($this->formName);
		$input->AddForm($this);
		$input->UpdateValue($this->GetMethodArray());

		return $this;
	}

	/**
	 * @param string $string
	 * @return $this
     */
	public function AddSubmit($string = ""){
		if(!empty($string)){
			$this->saveText = $string;
		}

		$this->inputRepository[] = '<button name="' . $this->getSaveButtonName() . '" value="1">' . $this->saveText . '</button>';

		return $this;
	}


	/**
	 * @param $html
	 * @return $this
     */
	public function AddCustomHTML($html){

		$this->inputRepository[] = PHP_EOL . $html;

		return $this;
	}

	/**
	 * @return bool
     */
	public function IsValid(){
		foreach ($this->inputRepository as $input){
			if($input instanceof Element){
				/* @var $input Element */
				if(!$input->IsValid()){
					return false;
				}
			}
		}

		return true;
	}

	public function array_merge_recursive_new() {

		$arrays = func_get_args();
		$base = array_shift($arrays);

		foreach ($arrays as $array) {
			reset($base); //important
			while (list($key, $value) = @each($array)) {
				if (is_array($value) && @is_array($base[$key])) {
					$base[$key] = $this->array_merge_recursive_new($base[$key], $value);
				} else {
					$base[$key] = $value;
				}
			}
		}

		return $base;
	}
	/**
	 * @param $object
	 * @param bool $sanitize
	 * @param array $ignored
	 * @throws \Exception
     */
	public function PopulateObject($object, $sanitize = true, $ignored = array()){

		foreach ($this->inputRepository as $input) {
			if ($input instanceof Element) {

				/* @var $input Element */

				if ($input->IsValid()) {

					if ($sanitize) {
						$input->Sanitize($ignored);
					}

					if ($input instanceof Element\File) {
						/* @var $input Element\File */

						$object->{$input->name} = $input->GetFileData();
					} else {
						if(count($input->inArray)) {
							$base = $input->inArray[0];
							if (!property_exists($object, $base)) {
								$object->{$base} = array();
							}

							if(count($input->inArray) > 1){
								//var_dump($input->GetLocationAsArray());
								$object->{$base} = $this->array_merge_recursive_new($object->{$base}, $input->GetLocationAsArray());
							}else{
								$object->{$base}[$input->name] = $input->value;
							}


						}else{
							$object->{$input->name} = $input->value;
						}
					}
				}else {

					throw new \Exception("Form/Controller::PopulateObject() - An unvalid input was discovered");

				}
			}
		}
	}


	/**
	 * @param bool $sanitize
	 * @param array $ignored
	 * @return \stdClass
	 * @throws \Exception
     */
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

	public function GenerateJavaScript(){
		$errormessages = json_encode(String::GetCurrentLanguageStrings());
		$regex = json_encode(Validator::GetAsArray());
		return <<<JS

	<script type="text/javascript">

	$(document).ready(function(){

		var Messages = {$errormessages};
		var Validator = {$regex};
		var ErrorMessage = '{$this->errorText}';
		var GenerateErrorString = function(string){
			return '<li>' + string + '</li>'
		};

		var validateForm = function(form, showMessage){
			form = $(form);

			form.find('p.error').remove();
			form.find('p.success').remove();

			var inputs = form.find('input');
			for(var i = 0; i < inputs.length; i++){
				validateInput(form.find('input')[i]);
			}

			if(form.find('.form-group-error').length > 0){
				if(ErrorMessage != "" && showMessage === true){
					form.prepend('<p class="error">' + ErrorMessage + '</p>');
				}
				return false;
			}

			return true;
		};

		var validateComparator = function(element){
			var errors = "";
			var compareToInitial;
			var compareTo;
			var thisValue;

			var setComparatorValues = function(compareElement){
				compareToInitial = compareElement.val();
				compareTo = compareElement.val();
				if(!isNaN(Date.parse(compareTo))){
					compareTo = Date.parse(compareTo);
				}

				thisValue = element.val();
				if(!isNaN(Date.parse(thisValue))){
					thisValue = Date.parse(thisValue);
				}

				if(compareToInitial == ""){

					compareToInitial = compareElement.siblings('label').html();
				}
			};

			if(element.data("greater-than")){
				setComparatorValues($('#' + element.data("greater-than")));

				if(!(thisValue > compareTo) || compareTo == ""){
					errors += GenerateErrorString(Messages["Comparator_Greater_Than"].replace('{0}', compareToInitial));
				}
			}

			if(element.data("greater-than-equal")){
				setComparatorValues($('#' + element.data("greater-than-equal")));

				if(!(thisValue >= compareTo) || compareTo == ""){
					errors += GenerateErrorString(Messages["Comparator_Greater_Than_Equal"].replace('{0}', compareToInitial));
				}
			}

			if(element.data("less-than")){
				setComparatorValues($('#' + element.data("less-than")));

				if(!(thisValue < compareTo) || compareTo == ""){
					errors += GenerateErrorString(Messages["Comparator_Less_Than"].replace('{0}', compareToInitial));
				}
			}

			if(element.data("less-than-equal")){
				setComparatorValues($('#' + element.data("less-than-equal")));

				if(!(thisValue <= compareTo) || compareTo == ""){
					errors += GenerateErrorString(Messages["Comparator_Less_Than_Equal"].replace('{0}', compareToInitial));
				}
			}

			if(element.data("equals")){
				setComparatorValues($('#' + element.data("equals")));

				if(!(thisValue == compareTo) || compareTo == ""){
					errors += GenerateErrorString(Messages["Comparator_Equals"].replace('{0}', compareToInitial));
				}
			}


			return errors;
		};

		var validateInput = function(element){

			element = $(element);

			var errors = "";
			element.parent().find(".form-group-error").remove();

			if(element[0].type == "file"){
				var size = element[0].files[0].size;
				var type = element[0].files[0].type;

				if(element.data('mime')){
					var arr = element.data('mime').split(';');
					var outcome = false;
					var message = [];

					for(var i = 0; i < arr.length; i++){
						if(arr[i] == type){
							outcome = true;
						}

						message.push('"' + arr[i].split('/')[1] + '"');
					}

					if(outcome == false){
						errors += GenerateErrorString(Messages["File_Unvalid_Format"].replace('{0}', message.join(' or, ')));

					}
				}

				if(element.data('maxSize') &&  ((size/1024)/1024) > element.data('maxSize')){
					errors += GenerateErrorString(Messages["File_Too_Large"].replace('{0}', element.data('maxSize')));

				}

			}

			if(element.data("required") || element.val() != ""){

				if(element.val() == ""  || (element.attr("type") == "checkbox" && !element.is(':checked') && element.data("required"))){
					errors += GenerateErrorString(Messages["Field_Empty"]);
				}

				if(element.data("validators")){
					var validator = element.data("validators").toString().split(',');

					validator.forEach(function(index){
						var regex = Validator[index]['regex'].substring(1, Validator[index]['regex'].length-1);
						regex = new RegExp(regex);

						if(element.val().match(regex) == null){
							errors += GenerateErrorString(Validator[index]['message']);
						}
					});
				}

				errors += validateComparator(element);

			}



			if(errors != ""){
				element.parent().append('<ul class="form-group-error">' + errors + '</ul>');
				element.addClass("error");
			}else{
				element.removeClass("error");
			}
		};

		var validateInputEvent = function(){
			validateInput(this);
		};

		$('.form-group input').on("change", validateInputEvent).blur(validateInputEvent);

		$('.form-wrapper').submit(function(e){
			if(validateForm(this, true) == false){
				e.preventDefault();
			}
		});

	});
	</script>

JS;

	}

}