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
        ->SetComparator(\Form\Comparator::LESS_THAN, 'second_date')
    )
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Date, "second_date"))
        ->SetPlaceholder("YYYY-MM-DD")
        ->SetPrompt("End date")
        ->SetValidator(\Form\Validator::DATE)
        ->SetComparator(\Form\Comparator::GREATER_THAN, "first_date")
    )

    ->AddSubmit("Compare");

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

    <h1>Comparators</h1>
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


<?php
        echo $form->GenerateOutput();
?>

    <div class="gist">
        <h2>Source code</h2>
        <span><em>(<a href="http://gist-it.appspot.com/">http://gist-it.appspot.com/</a>)</em></span>
        <script src="http://gist-it.appspot.com/https://github.com/ehamrin/FormHandler/blob/master/example-comparator.php?footer=0"></script>
    </div>
    <div class="source-code"><a href="https://github.com/ehamrin/FormHandler" target="_blank">See it on GitHub!</a></div>
</body>
</html>


