<?php 
include 'classes/Form/Form.php';
session_start();

$form = (new Form\Form("MyForm", Form\Method::POST))
	->SetButtonText("Create")

	->SetSuccessMessage("You successfully submitted the form")
	->SetErrorMessage("You submitted the form, but there were errors validating.")

	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
		->SetPlaceholder("Firstname Lastname")
		->SetPrompt("Full name")
		->SetValue("Testnamn")
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "pid"))
		->SetPlaceholder("YYMMDD-XXXX")
		->SetPrompt("Personal ID (SWE)")
		->SetRequired(true)
		->SetValidator(Form\Validator::SWEDISH_PID)
	)

	->AddCustomHTML('<div class="small">
					You can add you own HTML in between elements to place extra content that you want to have in you form,
					<a href="#">like this link</a>
					</div>')

	->AddInput((new Form\Element\Textarea("extra"))
		->SetPlaceholder("Write your content here")
		->SetPrompt("Info")
		->SetMaxLength(150)
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "postal"))
		->SetPlaceholder("123 45")
		->SetPrompt("Postal Code (SWE), required but hidden asterisk")
		->SetRequired(true, true)
		->SetValidator(Form\Validator::SWEDISH_POSTAL_CODE)
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Email, "email"))
		->SetPrompt("Email")
		->SetPlaceholder("test@test.com")
		->SetRequired(true)
		->SetValidator(Form\Validator::EMAIL)
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Password, "password"))
		->SetPrompt("Password")
		->SetMinLength(6)
		->SetMaxLength(15)
	)
	->AddInput((new Form\Element\Select("category", array(
			"Option 1",
			"Option 2",
			"Option 3",
			"Option 4",
		)))
		->SetPrompt("Category")
		->SetPlaceholder("Choose Category")
		->SetOptionPadding(1)
		->AddOption("Added after contructor")
	)
	->AddInput((new Form\Element\Select("car", array(
			"1354" => "Audi",
			"1355" => "Volkswagen",
			"135654" => "Fiat",
			"13" => "BMW"
		)))
		->SetPrompt("Car (loaded ID)")
		->SetPlaceholder("Choose Car")
		->SetValue(13)
	)
	->AddInput((new Form\Element\Checkbox('is_human'))
		->SetPrompt("Can you check it?")
		->SetRequired(true)
	)
	->AddInput((new Form\Element\RadioGroup("gender", array(
			"m" => "Male",
			"f" => "Female"
		)))
		->SetRequired(true, true)
		->SetValue('m')
		->SetPrompt(' ')
	)
	->AddSubmit();

if($form->WasSubmitted() && $form->IsValid()){

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
	<title>Example form</title>
	<link href="style.css" rel="stylesheet">
</head>
<body>
	<h1>My form wrapper</h1>
	<nav><li><a href="index.php">Default</a></li><li><a href="example-login.php">Login form example</a></li></nav>
	<?php echo $form->GenerateOutput(); ?>
</body>
</html>


