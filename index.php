<?php 
include 'classes/Form/Form.php';



$form = (new Form\Form("MyForm", Form\Method::POST))
	->setButtonText("Create")
	->setSuccessMessage("You successfully submitted the form")
	->setErrorMessage("You submitted the form, but there were errors validating it")
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
		->setPlaceholder("Firstname Lastname")
		->setPrompt("Full name")
		->setValue("Testnamn")
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "pid"))
		->setPlaceholder("YYMMDD-XXXX")
		->setPrompt("Personal ID (SWE)")
		->setRequired(true)
		->setValidator(Form\Validator::SWEDISH_PID)
	)
	->AddCustomHTML("<fieldset>")
	->AddCustomHTML("<legend>You can add custom HTML</legend>")
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "int"))
		->setPlaceholder("1234")
		->setPrompt("Int (range, numeric)")
		->setRequired(true)
		->setRange(10,500)
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "float"))
		->setPlaceholder("12.59")
		->setPrompt("Float")
		->setRequired(true)
		->setValidator(Form\Validator::FLOAT)
	)
	->AddCustomHTML("</fieldset>")
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "postal"))
		->setPlaceholder("123 45")
		->setPrompt("Postal Code (SWE), required but hidden asterisk")
		->setRequired(true, true)
		->setValidator(Form\Validator::SWEDISH_POSTAL_CODE)
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Email, "email"))
		->setPrompt("Email")
		->setPlaceholder("test@test.com")
		->setRequired(true)
		->setValidator(Form\Validator::EMAIL)
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Password, "password"))
		->setPrompt("Password")
		->setMinLength(6)
		->setMaxLength(15)
	)
	->AddInput((new Form\Element\Select("category", array(
			"Option 1",
			"Option 2",
			"Option 3",
			"Option 4",
		)))
		->setPrompt("Category")
		->setPlaceholder("Choose Category")
		->setOptionPadding(1)
		->addOption("Added after contructor")
	);

if($form->wasSubmitted() && $form->isValid()){

	echo 'Returned as object and sanitized (except for password)';
	echo '<pre>';
	var_dump($form->GetDataAsObject());
	echo '</pre>';
}

?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<h1>My form wrapper</h1>
	<hr/>
	<?php echo $form->GenerateOutput(); ?>
</body>
</html>


