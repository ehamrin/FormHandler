<?php
include 'classes/Form/Form.php';
session_start();

\Form\String::SetLanguage(\Form\String::SWEDISH);

$form = (new Form\Form("MyForm_Swedish", Form\Method::POST))
    ->SetSuccessMessage("Du lyckades fylla i formuläret korrekt")
    ->SetErrorMessage("Det uppstod fel i valideringen. Åtgärda felen och försök igen.")

    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
        ->SetPlaceholder("För- och Efternamn")
        ->SetPrompt("Namn")
        ->SetValue("")
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "pid"))
        ->SetPlaceholder("ÅÅMMDD-XXXX")
        ->SetPrompt("Personnummer")
        ->SetRequired(true)
        ->SetValidator(Form\Validator::SWEDISH_PID)
    )

    ->AddCustomHTML('<div class="small">
					Du kan lägga till HTML kod,
					<a href="#">som den här</a>
					</div>')

    ->AddInput((new Form\Element\Textarea("extra"))
        ->SetPlaceholder("Skriv info här..")
        ->SetPrompt("Extra")
        ->SetMaxLength(150)
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "postal"))
        ->SetPlaceholder("123 45")
        ->SetPrompt("Postkod")
        ->SetRequired(true, true)
        ->SetValidator(Form\Validator::SWEDISH_POSTAL_CODE)
    )
    ->AddCustomHTML('<div class="small">
					Måste fyllas i, men har dold asterisk
					</div>')
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Email, "email"))
        ->SetPrompt("Email")
        ->SetPlaceholder("test@test.com")
        ->SetRequired(true)
        ->SetValidator(Form\Validator::EMAIL)
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Password, "password"))
        ->SetPrompt("Lösenord")
        ->SetMinLength(6)
        ->SetMaxLength(15)
    )
    ->AddInput((new Form\Element\Select("category", array(
        "Val 1",
        "Val 2",
        "Val 3",
        "Val 4",
    )))
        ->SetPrompt("Kategori")
        ->SetPlaceholder("Välj kategori")
        ->SetOptionPadding(1)
        ->AddOption("Tillagd efter konstruktor")
    )
    ->AddInput((new Form\Element\Select("car", array(
        "1354" => "Audi",
        "1355" => "Volkswagen",
        "135654" => "Fiat",
        "13" => "BMW"
    )))
        ->SetPrompt("Bil (förvalt ID)")
        ->SetPlaceholder("Välj bil")
        ->SetValue(13)
    )
    ->AddInput((new Form\Element\Checkbox('is_human'))
        ->SetPrompt("Kan du klicka på denna?")
        ->SetRequired(true)
    )
    ->AddInput((new Form\Element\RadioGroup("gender", array(
        "m" => "Man",
        "f" => "Kvinna"
    )))
        ->SetRequired(true, true)
        ->SetValue('m')
        ->SetPrompt(' ')
    )
    ->AddSubmit("Skapa");

if($form->WasSubmitted() && $form->IsValid()){

    echo 'Returnerad som objekt och saniterad (förrutom lösenord)';
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>
<body>

<h1>My form wrapper</h1>
<nav>
    <li><a href="index.php">Default</a></li>
    <li><a href="example-swedish.php">Swedish form</a></li>
    <li><a href="example-login.php">Login form example</a></li>
    <li><a href="example-file.php">File upload example</a></li>
    <li><a href="example-comparator.php">Field comparator example</a></li>
</nav>
<?php echo $form->GenerateOutput(); ?>
<?php echo $form->GenerateJavaScript(); ?>
<div class="source-code"><a href="https://github.com/ehamrin/FormHandler" target="_blank">Source code</a></div>
</body>
</html>


