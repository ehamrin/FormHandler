# FormHandler
A PHP form generator and validator

##Get started
###Form creation
```php
//Set language  if you don't want English, which is default, this will translate error messages.
\Form\String::SetLanguage(\Form\String::ENGLISH);

$form = new Form\Form("SignInForm", Form\Method::POST) // Name of your form (placed as ID on form-tag) and Form-Method

//Add message when validation fails
$form->setErrorMessage("Ooops. Check errors and try again")

//Add message when successfully submitted
$form->SetSuccessMessage("Hooray")

//Add an input
$form->AddInput((new Form\Element\Input(Form\Element\InputType::Text, "name"))
  ->SetPlaceholder("Enter your full name")
  ->SetPrompt("Full name")
)

//Add submit button
$form->AddSubmit("Sign in"); //Text parameter is optional, default is "Save"
```

###What to do when the form has been submitted?
Check the following methods and write you own code to handle the output
```php
if($form->wasSubmitted() && $form->isValid()){
  //Extract the data as an object
  $obj = $form->GetDataAsObject();
  
  //Do your thing here
  ...
}
```

###Generate HTML
```php
echo $form->GenerateOutput();
```

##What inputs can I add?
Using 
```php
$form->addInput();
```
the following are supported:

1. Input (Class Form\Element\Input)

  Input support the following types
  * Text
  *	Password
  *	Tel
  *	Number
  *	Date
  *	Email

1. Checkboxes

1. RadioGroups

and using
```php
$form->addFile();
```
you can add an input of object \Form\Element\File


##Validation?
Yes!

The framework supports validation, that are managed with regex-expressions. 
```php
  ->AddInput((new Form\Element\Input(Form\Element\InputType::Email, "email"))
        ->SetPrompt("Email")
        ->SetPlaceholder("test@test.com")
        ->SetRequired(true)
        ->SetValidator(Form\Validator::EMAIL)
    )
```
The SetValidator() method supports:
* SWEDISH_PID
* US_SOCIAL_SECURITY
* SWEDISH_POSTAL_CODE
* EMAIL
* INT
* FLOAT
* DATE
* HEXA_DECIMAL
* URL
* IP_ADDRESS
* CREDIT_CARD

However, you can add a custom regex instead, but the error message will only say "Wrong format".
