<?php 
include('octoContactForm.php');

$form = new octoContactForm();

$form->addInput('email', 'p.etienney@gmail.com', true, 'email');
$form->addInput('name', 'paul', false, 'text');

$form->setEmailSubject('Salut');
$form->setEmailSender('contact@pauletienney.fr');
$form->setEmailReceivers('p.etienney@gmail.com');
$form->setEmailContent('<p>Salut [name]</p><p>[email]</p>');


$formProcess = $form->process();

echo '<hr /><pre>';
echo '<h2>Errors</h2>';
var_dump($formProcess->errors);
echo '</pre><hr />';

echo '<hr /><pre>';
echo '<h2>Attributs</h2>';
var_dump($formProcess->attributes);
echo '</pre><hr />';

echo '<hr /><pre>';
echo '<h2>Email</h2>';
var_dump($formProcess->emailContent);
echo '</pre><hr />';




?>