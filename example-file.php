<?php
include 'classes/Form/Form.php';

session_start();

\Form\String::SetLanguage(\Form\String::ENGLISH);

$form = (new Form\Form("FileForm", Form\Method::POST))
    ->SetSuccessMessage("Hooray")
    ->SetErrorMessage("Ooops. Check errors and try again")

    ->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
        ->SetPlaceholder("Firstname Lastname")
        ->SetPrompt("Full name")
    )
    ->AddFile((new Form\Element\File("fileUpload"))
        ->SetPrompt("Logo")
        ->SetRequired(true)
        ->SetMaxSize(1.5)
        ->SetFileType(
            \Form\Element\FileType::JPG,
            \Form\Element\FileType::PNG,
            \Form\Element\FileType::GIF
        )
    )
    ->AddCustomHTML('<div class="small">
					File is required and max size is set in mb, currently set to 1,5. Valid file types are set to JPG, PNG and GIF
					</div>')
    ->AddSubmit("Upload");
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

<?php
if($form->wasSubmitted() && $form->isValid()){
    $obj = $form->GetDataAsObject();
    echo '<pre>';
    echo 'Image (returned as base64-encoded string): <img src="data: ' . $obj->fileUpload->type . ';base64,' . $obj->fileUpload->data . '" width="150px" alt="' . $obj->fileUpload->name . '"/>' . PHP_EOL;
    $obj->fileUpload->data = substr($obj->fileUpload->data, 0, 75). "...(cut-off at 75 characters for preview of data)";
    var_dump($obj);
    echo '</pre>';
}
?>

    <h1>Upload form</h1>
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


