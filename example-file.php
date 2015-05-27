<?php
include 'classes/Form/Form.php';

session_start();

\Form\String::SetLanguage(\Form\String::ENGLISH);

$form = (new Form\Form("FileForm", Form\Method::POST))
    ->SetSuccessMessage("Hooray")
    ->SetErrorMessage("Ooops. Check errors and try again")
    ->AddFile((new Form\Element\File("fileUpload"))
        ->SetPrompt("Logo")
        ->SetRequired(true)
        ->SetMaxSize(1)
    )->AddFile((new Form\Element\File("fileUpload2"))
        ->SetPrompt("Logo")
        ->SetRequired(true)
        ->SetMaxSize(1)
    )
    ->AddSubmit("Upload");



?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Upload example</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<h1>Login form</h1>
<?php
if($form->wasSubmitted() && $form->isValid()){
//This would be were you set you session data and redirect the user

    $obj = $form->GetDataAsObject();
    echo '<h2>' . $obj->fileUpload->name . '</h2>';
    echo '<h2>' . $obj->fileUpload->size . '</h2>';
    echo '<img src="data: ' . $obj->fileUpload->type . ';base64,' . $obj->fileUpload->data . '" width="150px" alt="' . $obj->fileUpload->name . '"/>';
    echo '<img src="data: ' . $obj->fileUpload2->type . ';base64,' . $obj->fileUpload2->data . '" width="150px" alt="' . $obj->fileUpload2->name . '"/>';

}
?>
<nav><li><a href="index.php">Default</a></li><li><a href="example-swedish.php">Default(Swedish)</a></li><li><a href="example-login.php">Login form example</a></li></nav>
<?php echo $form->GenerateOutput(); ?>
</body>
</html>


