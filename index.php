<?php 
include 'classes/Form/Controller.php';



$form = (new Form\Controller("MyForm", Form\Method::POST))
		->AddInput((new Form\Input(Form\InputType::Text, "name"))
			->setPlaceholder("Firstname Lastname")
			->setPrompt("Full name")
			->setValue("Testnamn")
		)
		->AddInput((new Form\Input(Form\InputType::Text, "pid"))
			->setPlaceholder("YYMMDD-XXXX")
			->setPrompt("Personal ID (SWE)")
			->setRequired(true)
			->setValidator(Form\Validator::SWEDISH_PID)
		)
		->AddInput((new Form\Input(Form\InputType::Text, "int"))
			->setPlaceholder("1234")
			->setPrompt("Int")
			->setRequired(true)
			->setRange(10,500)
			->setValidator(Form\Validator::INT)
		)
		->AddInput((new Form\Input(Form\InputType::Text, "float"))
			->setPlaceholder("12.59")
			->setPrompt("Float")
			->setRequired(true)
			->setValidator(Form\Validator::FLOAT)
		)
		->AddInput((new Form\Input(Form\InputType::Text, "postal"))
			->setPlaceholder("123 45")
			->setPrompt("Postal Code (SWE)")
			->setRequired(true)
			->setValidator(Form\Validator::SWEDISH_POSTAL_CODE)
		)
		->AddInput((new Form\Input(Form\InputType::Text, "email"))
			->setPrompt("Email")
			->setPlaceholder("test@test.com")
			->setRequired(true)
			->setValidator(Form\Validator::EMAIL)
		)
		->AddInput((new Form\Input(Form\InputType::Password, "password"))
			->setPrompt("Password")
			->setMinLength(6)
			->setMaxLength(15)
		);

if($form->wasSubmitted() && $form->isValid()){
	$object = new stdClass();

	$form->PopulateObject($object);
	echo '<pre>';
	var_dump($object);
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
	<?php echo $form->GenerateOutput(); ?>
</body>
</html>


