<?php 
include 'classes/Form/Form.php';
session_start();

if(isset($_GET['lang'])){
	\Form\String::SetLanguage($_GET['lang']);
}



$form = (new Form\Form("MyForm", Form\Method::POST))
	->SetSuccessMessage("You successfully submitted the form")
	->SetErrorMessage("You submitted the form, but there were errors validating.")

	->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
		->SetPlaceholder("Firstname Lastname")
		->SetPrompt("Full name")
		->SetValue("Testnamn")
	)
	->AddInput((new Form\Element\Input(Form\Element\InputType::Tel, "pid"))
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
	->AddInput((new Form\Element\Input(Form\Element\InputType::Tel, "postal"))
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
	)->AddSubmit();

?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Form Wrapper and Validator</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="style.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<?php echo $form->GenerateJavaScript(); ?>
</head>
<body>

	<h1>My form wrapper</h1>
	<nav>
		<li><a href="index.php">Default</a></li>
		<li><a href="example-swedish.php">Swedish form</a></li>
		<li><a href="example-login.php">Login form example</a></li>
		<li><a href="example-file.php">File upload example</a></li>
		<li><a href="example-comparator.php">Field comparator example</a></li>
		<li><a href="example-array.php">Array example</a></li>
	</nav>

<?php if($form->WasSubmitted() && $form->IsValid()): ?>
	<div class="result"><!--
		--><pre><!--
			--><?php var_dump($form->GetDataAsObject()); ?><!--
		--></pre><!--
	--></div>
<?php endif; ?>

	<?php echo $form->GenerateOutput(); ?>


	<nav><li><a href="?lang=<?php echo \Form\String::ENGLISH; ?>">English</a></li><li><a href="?lang=<?php echo \Form\String::SWEDISH; ?>">Swedish</a></li><li><a href="?lang=<?php echo \Form\String::GERMAN; ?>">German</a></li></nav>

	<div class="gist">
		<h2>Source code</h2>
		<span><em>(<a href="http://gist-it.appspot.com/">http://gist-it.appspot.com/</a>)</em></span>
		<script src="http://gist-it.appspot.com/https://github.com/ehamrin/FormHandler/blob/master/index.php?footer=0"></script>
	</div>

	<div class="source-code"><a href="https://github.com/ehamrin/FormHandler" target="_blank">See it on GitHub!</a></div>
</body>
</html>


