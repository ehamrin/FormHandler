<?php

namespace Form\Element;

use Form\Validator;

class File extends \Form\Element{

    public $file_name = "";
    public $file_type = "";
    public $file_data = "";
    public $file_size = "";

    public $maxSize = false;
    public $validMime = array();

    public function IsValid($errorMessage = false){

        $messages = array();

        if($this->required || !empty($this->file_name)){
            if(empty($this->file_name)){
                $messages[] = \Form\String::Get("File_Required");
            }

            if($this->maxSize != false && $this->file_size > $this->maxSize*1024*1024){
                $messages[] = \Form\String::Get("File_Too_Large", $this->maxSize);
            }

            $validMime = false;
            $messageString = array();
            foreach($this->validMime as $mime){
                if($this->file_type == $mime){
                    $validMime = true;
                }
                $ext = explode('/', $mime);
                $ext = end($ext);
                $messageString[] = "'." . $ext . "'";
            }

            if(!$validMime){
                $string = "";
                $end = "";
                if(count($messageString > 1)){
                    $end = ' or ' . array_pop($messageString);
                }

                $messageString = implode(', ', $messageString) . $end;

                $messages[] = \Form\String::Get("File_Unvalid_Format", $messageString);
            }
        }



        $has_error = count($messages);

        if($has_error){
            $this->SetClass("error");

            if($errorMessage){
                return $messages;
            }
        }

        return !$has_error;
    }


    /*****************************
     *
     *     HTML Generators
     *
     ****************************/
    public function GetHTML($data)
    {
        //Was the data posted?
        if (isset($data[\Form\Form::$FileLocation][$this->hashed_name])) {
            $file = $data[\Form\Form::$FileLocation][$this->hashed_name];
            $this->file_name = $file['name'];
            $this->file_type = $file['type'];
            $this->file_data = $file['file_data'];
            $this->file_size = $file['size'];
        }

        $errormessage = $this->GetErrorMessageHTML($data);
        $required = $this->required ? ' data-required="true"' : '';

        return <<<HTML

			<div class="form-group">
				{$this->GetLabelHTML()}
				<input id="{$this->hashed_name}" class="{$this->GetClassString()}" type="file" name="{$this->hashed_name}" {$required}/>{$this->GetRequiredHTML()}
				{$errormessage}
			</div>
HTML;
    }


    public function GetFileData(){
        $obj = new \stdClass();
        $obj->data = $this->file_data;
        $obj->name = $this->file_name;
        $obj->type = $this->file_type;
        $obj->size = $this->file_size;

        return $obj;
    }

    public function SetMaxSize($int){
        $this->maxSize = $int;

        return $this;
    }

    public function SetFileType(){
        $this->validMime = func_get_args();

        return $this;
    }

}