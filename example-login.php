<?php
include 'classes/Form/Form.php';

session_start();

$form = (new Form\Form("SignInForm", Form\Method::POST))
    ->setButtonText("Sign in")
    ->setErrorMessage("Ooops. Check errors and try again")
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "username"))
        ->SetPrompt("Username")
        ->SetRequired(true, true)
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Password, "password"))
        ->SetPrompt("Password")
        ->SetRequired(true, true)
    )
    ->AddSubmit();

if($form->wasSubmitted() && $form->isValid()){
    //This would be were you set you session data and redirect the user
    echo '<pre>';
    var_dump($form->GetDataAsObject());
    echo '</pre>';
}

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Login example</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<h1>Login form</h1>
<nav><li><a href="index.php">Default</a></li><li><a href="example-login.php">Login form example</a></li></nav>
<?php echo $form->GenerateOutput(); ?>
</body>
</html>


