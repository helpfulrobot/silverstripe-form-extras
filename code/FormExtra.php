<?php

/**
 * FormExtra
 * 
 * Extend your forms from this class instead of Form to add extra functionalities
 * - Support for Unvalidated actions
 * - Shortcut for returning messages or errors
 *
 * @author lekoala
 */
class FormExtra extends Form {
	
	const MSG_NOTICE = 'notice';
	const MSG_WARNING = 'warning';
	const MSG_BAD = 'bad';
	const MSG_GOOD = 'good';

	public function __construct($controller = null, $name = null, FieldList $fields = null, FieldList $actions = null, $validator = null) {
		if ($controller === null) {
			$controller = Controller::curr();
		}
		if ($name === null) {
			$name = get_called_class();
		}
		if ($fields === null) {
			$fields = new FieldList;
		}
		if ($actions === null) {
			$actions = new FieldList;
		}
		parent::__construct($controller, $name, $fields, $actions);
	}

	public function validate() {
		//add support for unvalidated actions
		$action = $this->Actions()->dataFieldByName('action_' . Form::current_action());
		if (get_class($action) === 'FormActionUnvalidated') {
			return true;
		}
		return parent::validate();
	}

	/**
	 * Shortcut for an error
	 * 
	 * @param string $msg
	 * @param string $url
	 * @return SS_HTTPResponse
	 */
	protected function err($msg, $url = null) {
		return $this->msg($msg, self::MSG_BAD, $url);
	}
	
	/**
	 * Shortcut for a success
	 * 
	 * @param string $msg
	 * @param string $url
	 * @return SS_HTTPResponse
	 */
	protected function success($msg, $url = null) {
		return $this->msg($msg, self::MSG_GOOD, $url);
	}
	
	/**
	 * Return a response with a message for your form
	 * 
	 * @param string $msg
	 * @param string $type good,bad,notice,warning
	 * @param string $url
	 * @return SS_HTTPResponse
	 */
	protected function msg($msg, $type, $url = null) {
		$this->sessionMessage($msg, $type);
		if($url) {
			return $this->Controller()->redirect($url);
		}
		return $this->Controller()->redirectBack();
	}

}
