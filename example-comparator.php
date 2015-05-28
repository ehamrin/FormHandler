<?php
include 'classes/Form/Form.php';
session_start();

if(isset($_GET['lang'])){
    \Form\String::SetLanguage($_GET['lang']);
}



$form = (new Form\Form("MyForm", Form\Method::POST))
    ->SetSuccessMessage("You successfully submitted the form")
    ->SetErrorMessage("You submitted the form, but there were errors validating.")

    ->AddInput((new Form\Element\Input(Form\Element\InputType::Date, "first_date"))
        ->SetPlaceholder("YYYY-MM-DD")
        ->SetPrompt("Start date")
        ->SetValidator(\Form\Validator::DATE)
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Date, "second_date"))
        ->SetPlaceholder("YYYY-MM-DD")
        ->SetPrompt("End date(greater than first)")
        ->SetValidator(\Form\Validator::DATE)
        ->SetComparator(\Form\Comparator::GREATER_THAN, "first_date")
    )->AddSubmit("Compare");

if($form->WasSubmitted() && $form->IsValid()){

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

<h1>Comparators</h1>
<nav>
    <li><a href="index.php">Default</a></li>
    <li><a href="example-swedish.php">Swedish form</a></li>
    <li><a href="example-login.php">Login form example</a></li>
    <li><a href="example-file.php">File upload example</a></li>
    <li><a href="example-comparator.php">Field comparator example</a></li>
</nav>

<?php echo $form->GenerateOutput(); ?>
<div class="source-code"><a href="https://github.com/ehamrin/FormHandler" target="_blank">Source code</a></div>
</body>
</html>


