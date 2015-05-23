<?php

namespace Form\Element;

class InputType{
	const Text = "text";
	const Password = "password";
	const Tel = "tel";
	const Number = "number";
	const Date = "date";
	const Email = "email";

	public static function HasSupport($input){
		switch($input) {
			case self::Password:
			case self::Tel:
			case self::Text:
			case self::Date:
			case self::Email:
			case self::Number:
				return true;
			default:
				return false;
		}
	}
}