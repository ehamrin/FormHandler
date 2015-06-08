<?php
include 'classes/Form/Form.php';
session_start();


\Form\String::SetLanguage(\Form\String::ENGLISH);




$form = (new Form\Form("MyForm", Form\Method::POST))
    ->SetSuccessMessage("You successfully submitted the form")
    ->SetErrorMessage("You submitted the form, but there were errors validating.")
    ->AddInput((new Form\Element\Input(Form\Element\InputType::Date, "Date"))
        ->SetPlaceholder("YYYY-MM-DD")
        ->SetPrompt("Date")
    );

for($i = 1; $i <= 3; $i++){
    $form->AddCustomHTML('<fieldset><legend>Employee ' . $i . '</legend>')
        ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
            ->SetPlaceholder("Name")
            ->SetPrompt("Name")
            ->AddToArray('Employee', $i)
        )
        ->AddInput((new Form\Element\Input(Form\Element\InputType::Tel, "age"))
            ->SetPlaceholder("18+")
            ->SetPrompt("Age")
            ->SetValidator(\Form\Validator::INT)
            ->SetRange(18, 99)
            ->AddToArray('Employee', $i)
        )
        ->AddCustomHTML('<fieldset><legend>Security access</legend>');
    for($j = 1; $j <= 3; $j++) {
        $form->AddInput((new Form\Element\Checkbox("enabled"))
            ->SetPrompt("Level " . $j)
            ->SetLabelPositionRight(true)
            ->SetGroupClass("inline-1-3")
            ->AddToArray('Employee', $i, 'SecurityAccess', $j)
        )
        ->AddInput((new Form\Element\Input(Form\Element\InputType::Date, "start"))
            ->SetPlaceholder("Start date")
            ->SetValidator(\Form\Validator::DATE)
            ->SetGroupClass("inline-1-3")
            ->AddToArray('Employee', $i, 'SecurityAccess', $j)
        )
        ->AddInput((new Form\Element\Input(Form\Element\InputType::Date, "end"))
            ->SetPlaceholder("End date")
            ->SetValidator(\Form\Validator::DATE)
            ->SetComparator(\Form\Comparator::GREATER_THAN, "start")
            ->SetGroupClass("inline-1-3")
            ->AddToArray('Employee', $i, 'SecurityAccess', $j)
        );

    }

    $form->AddCustomHTML('</fieldset>')
        ->AddCustomHTML('</fieldset>');
}

    $form->AddSubmit();

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
    <script src="http://gist-it.appspot.com/https://github.com/ehamrin/FormHandler/blob/master/example-array.php?footer=0"></script>
</div>
<div class="source-code"><a href="https://github.com/ehamrin/FormHandler" target="_blank">See it on GitHub!</a></div>
</body>
</html>


