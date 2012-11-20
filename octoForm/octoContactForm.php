<?php

class octoContactForm {

    public $attributes = array();
    public $ajax;
    public $success;
    public $errors = array();
    public $errorsHtmlList = '';
    public $emailSubject;
    public $emailSender;
    public $emailReceivers;
    public $emailContent;

    function __construct($ajax = true) {
        $this->ajax = (is_bool($ajax)) ? $ajax : false;
    }

    // Register input of the form 
    public function addInput($name = 'attrName', $value = '', $required = false, $type = 'text') {
        $attribute = array();
        $attribute['name'] = $name;
        $attribute['value'] = $value;
        $attribute['required'] = (is_bool($required)) ? $required : false;
        $attribute['type'] = $type;
        $this->attributes[$name] = $attribute;
        return true;
    }

    public function process() {
        $this->checkAttributes();
        if (empty($this->errors)) {

            if (!empty($this->emailSender) && ($this->emailReceivers)) {
                // If we have a send and a receiver we process the email
                $this->gearUpEmailContent();

                // We set the email header to make it an html email
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
                $headers .= 'From: ' . $this->emailSender . "\r\n";

                // We send the message
                $sendMail = mail($this->emailReceivers, $this->emailSubject, $this->emailContent, $headers);
                if (!$sendMail) {
                    $this->errors['Message not sent'] = 'The server had a problem. Please try later.';
                    $this->success = false;
                } else {
                    $this->success = true;
                }
            }
        }

        if (!empty($this->errors)) {
            $this->errorsHtmlList = '<ul>';
            foreach ($this->errors as $key => $error) {
                $this->errorsHtmlList .= '<li>' . $key . ': ' . $error . '</li>';
            }
            $this->errorsHtmlList .= '</ul>';
        }

        return $this;
    }

    public function checkAttributes() {

        if ($this->attributes and !empty($this->attributes)) {
            foreach ($this->attributes as $attr) {

                // First we check input patterns
                switch ($attr['type']) {
                    case 'email':
                        $isEmail = octoContactForm::hasEmailPattern($attr['value']);
                        if (!$isEmail) {
                            $this->errors[$attr['name']] = 'Your email is not correct';
                        }
                        break;
                    case 'url':
                        $isUrl = octoContactForm::hasUrlPattern($attr['value']);
                        if (!$isUrl) {
                            $this->errors[$attr['name']] = 'Your address is not correct';
                        }
                        break;
                    default:
                        break;
                }

                // We check if the required attributes are filled in
                if ($attr['required']) {
                    $hasContent = octoContactForm::hasContent($attr['value']);
                    if (!$hasContent) {
                        $this->errors[$attr['name']] = 'This field is required';
                    }
                }
            }
            if (empty($this->errors)) {
                $this->success = true;
            }
        } else {
            // In case we have noothing to check.
            $this->errors['emptyForm'] = 'The form is empty';
            $this->success = false;
            return false;
        }
    }

    /////
    /////
    // EMAIL FUNCTIONS
    /////
    /////
    // Setters
    public function setEmailSubject($subject) {
        $this->emailSubject = $subject;
    }

    public function setEmailSender($sender) {
        $this->emailSender = $sender;
    }

    public function setEmailReceivers($receivers) {
        $this->emailReceivers = $receivers;
    }

    public function setEmailContent($emailContent) {
        $this->emailContent = $emailContent;
    }

    private function gearUpEmailContent() {
        $emailContent = $this->emailContent;
        foreach ($this->attributes as $attr) {
            $value = $attr['value'];
            $emailContent = str_replace('[' . $attr['name'] . ']', $attr['value'], $emailContent);
        }
        $this->emailContent = $emailContent;
        return $emailContent;
    }

    /////////
    /////////
    /////////
    // Internal library to check attribute validity
    /////////
    /////////
    /////////
    public static function hasEmailPattern($string) {
        $emailRegex = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
        if (preg_match($emailRegex, $string)) {
            return true;
        } else {
            return false;
        }
    }

    public static function hasUrlPattern($string) {
        $urlRegex = '|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i';
        if (preg_match($urlRegex, $string)) {
            return true;
        } else {
            return false;
        }
    }

    public static function hasContent($toCheck) {
        if (empty($toCheck) || $toCheck == '  ') {
            return false;
        } else {
            return true;
        }
    }

}

?>
