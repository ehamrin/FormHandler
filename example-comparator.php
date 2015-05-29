<?php
include 'classes/Form/Form.php';
session_start();


\Form\String::SetLanguage(\Form\String::ENGLISH);




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
        ->SetPrompt("End date(greater than Start Date)")
        ->SetValidator(\Form\Validator::DATE)
        ->SetComparator(\Form\Comparator::GREATER_THAN, "first_date")
    )

    ->AddSubmit("Compare");

if($form->WasSubmitted() && $form->IsValid()){

    echo '<pre>';
    var_dump($form->GetDataAsObject());
    echo '</pre>';

}

$form2 = (new Form\Form("MyForm2", Form\Method::POST))
    ->SetSuccessMessage("You successfully submitted the form")
    ->SetErrorMessage("You submitted the form, but there were errors validating.")

    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "first_num"))
        ->SetPlaceholder("Number 1")
        ->SetPrompt("Start number")
        ->SetValidator(\Form\Validator::FLOAT)
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "second_num"))
        ->SetPlaceholder("Number 2")
        ->SetPrompt("End number(greater than Start number)")
        ->SetValidator(\Form\Validator::FLOAT)
        ->SetComparator(\Form\Comparator::GREATER_THAN, "first_num")
    )
    ->AddSubmit("Compare");

if($form2->WasSubmitted() && $form2->IsValid()){

    echo '<pre>';
    var_dump($form2->GetDataAsObject());
    echo '</pre>';

}

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Form Wrapper and Validator</title>
    <link href="style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <?php echo $form->GenerateJavaScript(); ?>
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

<?php
        echo $form->GenerateOutput();
        echo $form2->GenerateOutput();
?>

    <div class="gist">
        <h2>Source code</h2>
        <span><em>(<a href="http://gist-it.appspot.com/">http://gist-it.appspot.com/</a>)</em></span>
        <script src="http://gist-it.appspot.com/https://github.com/ehamrin/FormHandler/blob/master/example-comparator.php?footer=0"></script>
    </div>
    <div class="source-code"><a href="https://github.com/ehamrin/FormHandler" target="_blank">See it on GitHub!</a></div>
</body>
</html>


