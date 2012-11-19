<?php
include('DEFINE_PHP.php');
/*
	name: functions.php
	for: rassemble un emsemble de fonctions outils
	pour les différentes fonctions php
	*/
	
	/**
	@param: $m, un mail, $p, un mot de passe
	@return: true si l'association des deux n'existe pas dans la base de donnée
			false sinon
	**/
	function existe($m,$p)
	{
		try
		{
			$pass = md5($p);
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->query('SELECT count(*) FROM users WHERE mail=\''.$m.'\' AND pass=\''.$pass.'\'');
			
			$res = $reponse->fetch();
			if($res[0] == 0)
				return false;
			else
				return true;
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
	}
	
	/**
	@param: $p, un pseudo
	@return: true si le pseudo n'existe pas dans la base de donnée
			false sinon
	**/
	function psexistepas($p)
	{
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->query('SELECT count(*) FROM users WHERE pseudo=\''.$p.'\'');
			$res = $reponse->fetch();
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
			
		if($res[0] == 0)
		{
			return true;}
		else{
			
			return false;
			}
	}

	/**
	@param: $m, une adresse mail
	@return: true si le mail n'existe pas dans la base de donnée
			false sinon
	**/
	function eexistepas($m)
	{
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$reponse = $bdd->query('SELECT count(*) FROM users WHERE mail=\''.$m.'\'');
		$bdd= NULL;
		$res = $reponse->fetch();
		if($res[0] == 0)
			return true;
		else
			return false;
	}
	
	/**
	@param: $id, un id généré aléatoirement
	@return: true si l'id n'existe pas dans la base de donnée
			false sinon
	**/
	function iexistepas($id){
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->query('SELECT count(*) FROM users WHERE identifier=\''.$id.'\'');
			$res = $reponse->fetch();
			if($res[0] == 0)
				return true;
			else
				return false;
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
	}
	
	/**
	@param: $mail chaine représentant le mail
	@return: retourne vrai si @param correspond a un mail du type mail@domaine.com

	**/
	function isValidMail($mail){
		return preg_match("#^[a-z0-9._-]{2,}+@[a-z0-9._-]{2,}\.[a-z]{2,6}$#", $mail);
	}

	/**
	@param: $pass chaine représentant le password
	@return: retourne vrai si @param correspond a un password valide de longueur minimum 6char max 255char

	**/
	function isValidPass($pass){
		return preg_match("#[a-z0-9A-Z]{6,255}#", $pass);
	}
	
	/**
	@param: $id, id de l'user
	@return: true si l'user est connecté d'après la base de donnée
			false sinon
	**/
	function isConnected($id)
	{
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->query('SELECT connected FROM users WHERE pseudo=\''.$id.'\'');
			$res = $reponse->fetch();
			if($res[0] == 1){
				$bdd= NULL;
				return true;
			}
				
			else{
				$bdd= NULL;
				return false;
			}
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		
	}
	
	/**
	@param: $mail de l'user
	@return : si le log s'est bien passé elle renvoie l'id de l'user
	"PROBLEM" sinon.
	**/
	function logIn($mail){
		$res = array();
		$date = time();
		//$mysqldate = date( 'Y-m-d H:i:s', $phpdate );$phpdate = strtotime( $mysqldate );
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare("UPDATE users SET key1 = :k1, key2 = :k2, connected = :co, lastConnection = :lc WHERE mail = :mail");
			$req->execute(array('k1' => re(10), 'k2' => re(10), 'co' => 1,'mail' => $mail, 'lc' => $date));

			$req = $bdd->prepare('SELECT pseudo, identifier, key1, key2 FROM users WHERE mail= :mail');
			$req->execute(array('mail' => $mail));
			$rep = $req->fetch();
			$res["pseudo"] = $rep["pseudo"];
			$res["identifier"] = $rep["identifier"];
			$res["key1"] = $rep["key1"];
			$res["key2"] = $rep["key2"];
			
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
		return $res;
	}

	/**
	@param: $id de l'user
	@return : null
	**/
	function logOut($id){
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET connected = :co WHERE identifier = :id');
			$req->execute(array('co' => 0,'id' => $id));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}

	/**
		@param: $k clé générée, $d date en md5
		return: possible ou non de reset le password
	**/
	function fromMailRequest($k, $d, $m){
		$now = md5(date('Y-m-d'));
		$b = false;
		$p = "";
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('SELECT requestMailKey FROM users WHERE md5Mail= :mail');
			$req->execute(array('mail' => $m));
			$rep = $req->fetch();

			if($rep['requestMailKey'] == $k && $d == $now){
				$b = true;
			}
			else{
				$req = $bdd->prepare('UPDATE users SET requestMailKey = :p WHERE md5Mail= :mail');
				$req->execute(array('p'=> $p,'mail' => $m));
			}
			
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
		return $b;
	}

	/**
		@param: $mail mail
		return: account validé ou pas
	**/
	function validedAccount($mail){
		$b = false;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('SELECT isValid FROM users WHERE mail= :mail');
			$req->execute(array('mail' => $mail));
			$rep = $req->fetch();

			if($rep['isValid'] == 1){
				$b = true;
			}
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
		return $b;
	}


	/**
		@param: $k clé générée, $d date en md5
		return: possible ou non de reset le password
	**/
	function validAccount($k, $d, $m){
		$now = md5(date('Y-m-d'));
		$b = false;
		$p = "";
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('SELECT requestMailKey FROM users WHERE md5Mail= :mail');
			$req->execute(array('mail' => $m));
			$rep = $req->fetch();

			if($rep['requestMailKey'] == $k && $d == $now){
				$req = $bdd->prepare('UPDATE users SET isValid = :v WHERE md5Mail= :mail');
				$req->execute(array('v'=> 1,'mail' => $m));
				//
				// creation des ddossiers perso ici
				//
				$b = true;
			}
			else{
				$req = $bdd->prepare('UPDATE users SET requestMailKey = :p WHERE md5Mail= :mail');
				$req->execute(array('p'=> $p,'mail' => $m));
			}
			
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
		return $b;
	}
	/**
		@param: $p password, $m mailmd5
		return: null
	**/
	function changePass($p, $m){
		$pass = md5($p);
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET pass = :p WHERE md5Mail= :mail');
			$req->execute(array('mail' => $m, 'p'=>$pass));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}

	/**
	@param: $taille, nombre de charactères de la clé générée
	return: une clé aléatoire de taille $taille.
	**/
	function re($taille)
	{
		$res = "#";
		$abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$abc1 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$res .= $abc[rand(0,51)];
		for($s = 0;  $s < $taille-1; $s++)
		{
			$res .= $abc1[rand(0,61)];
		}
		return $res;
	}
	
	/**
	@param: $taille, nombre de charactères de la clé générée
	return: une clé aléatoire de taille $taille.
	**/
	function re2($taille)
	{
		$res = "";
		$abc = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$abc1 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$res .= $abc[rand(0,51)];
		for($s = 0;  $s < $taille-1; $s++)
		{
			$res .= $abc1[rand(0,61)];
		}
		return $res;
	}
	
/**

**/
function isLoged($_number0 , $_number1)
{
	$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP);
	$sql = 'SELECT count(*) FROM users WHERE number0="'.$_number0.'" AND number1="'.$_number1.'"';
	$reponse = $bdd->query($sql);
	$donnees = $reponse->fetch();
	return ($donnees[0] == 1);
}
function isNumber0($_number0)
{
	$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP);
	$sql = 'SELECT count(*) FROM users WHERE number0="'.$_number0.'"';
	$reponse = $bdd->query($sql);
	$donnees = $reponse->fetch();
	return ($donnees[0] == 1);
}

function isNumber1($_number1)
{
	$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP);
	$sql = 'SELECT count(*) FROM users WHERE number1="'.$_number1.'"';
	$reponse = $bdd->query($sql);
	$donnees = $reponse->fetch();
	return ($donnees[0] == 1);
}

function number0()
{
	$_number0 = number();
	while(isNumber0($_number0))
		$_number0 = number();
	return $_number0;
}

function number1()
{
	$_number1 = number();
	while(isNumber1($_number1))
		$_number1 = number();
	return $_number1;
}

function number()
{
	$number = "#";
	for($a = 0; $a <= 9; $a++)
		$number .= rand(0,9);
	return $number;
}


	/**
	@param: $mail de l'user
	@return : boolean
	**/
	function sendRetrieveMail($mail){
		$k = re2(10);
		$date = date('Y-m-d');
		$link = 'http://www.4dmotions.com/bin/php/lostlogs.php?k=' . $k . '&d=' . md5($date) . '&m=' . md5($mail);
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET requestMailKey = :key, requestMailDate = :da WHERE mail = :mail');
			$req->execute(array('key' => $k,'mail' => $mail, 'da' => $date));

			//code pour envoyer le mail
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){$passage_ligne = "\r\n";}
			else{
				$passage_ligne = "\n";
			}

			//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = 'Hello, Click the following link to change your password.<br />' .  $passage_ligne . ' If you can not click it, copy and paste it into the address bar of your browser. ' . $passage_ligne . ' '. $link;
			$message_html = '<html><head></head><body>Hello, Click the following link to change your password.<br />' .  $passage_ligne . ' If you can not click it, copy and paste it into the address bar of your browser.<br />' . $passage_ligne . ' <a href="' . $link . '">' . $link . '</a>.</body></html>';
			//==========

			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			//=====Définition du sujet.
			$sujet = "Request new password.";
			//=========
			//=====Création du header de l'e-mail
			$header = "From: \"4D Motions\"<noreply@4dmotions.com>".$passage_ligne;
			$header .= "Reply-to: \"admin\" <admin@4dmotions.com>".$passage_ligne;
			$header .= "MIME-Version: 1.0".$passage_ligne;
			$header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//==========
			//=====Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format texte.
			$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_txt.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========
			 
			//=====Envoi de l'e-mail.
			mail($mail,$sujet,$message,$header);
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}

	/**
	fonction signin
	@param: $mail de l'user
	@return : boolean
	**/
	function sendSignMail($mail, $pass, $pseudo){
		$k = re2(10);
		$date = date('Y-m-d');
		$identifier = re2(10);
		while(!iexistepas($identifier))
		{
			$identifier = re2(10);
		}
		$signDate = date('Y-m-d H:i:s');
		//creation du compte temporaire sur la base de donnée
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=localhost;dbname=z4dmo671_4dmotions', $LOG_ID, $LOG_MDP, $pdo_options);
		$query = $bdd->prepare("INSERT INTO users(mail, pass, pseudo, identifier, signDate, requestMailKey, requestMailDate, md5Mail, isValid) VALUES(:mail, :pass, :pseudo, :identifier, :signDate, :reqMK, :reqMD, :md5M, :v)");
		$query->execute(array(
			'mail'       => $mail,
			'pass'       => md5($pass),
			'pseudo'     => $pseudo,
			'identifier' => $identifier,
			'signDate'   => $date,
			'reqMK'      => $k,
			'reqMD'      => $date,
			'md5M'       => md5($mail),
			'v'          => 0
			));
		
		$link = 'http://www.4dmotions.com/bin/php/signin.php?k=' . $k . '&d=' . md5($date) . '&m=' . md5($mail);
		try
		{
			
			//code pour envoyer le mail
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)){$passage_ligne = "\r\n";}
			else{
				$passage_ligne = "\n";
			}

			//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = 'Hello, ' .  $passage_ligne . 
			'Thank you for your registration 4DMotions. We hope to satisfy your needs to the fullest.' .  $passage_ligne . 
			' To validate your registration, you must click on the link below. ' . $passage_ligne . 
			' '. $link. $passage_ligne . 
			'We hope you enjoy your navigation.';
			
			$message_html = '<html>
								<head>
								</head>
								<body>
								Hello, <br />' .  $passage_ligne . 
								'Thank you for your registration 4DMotions. We hope to satisfy your needs to the fullest.<br />' .  $passage_ligne . 
								'To validate your registration, you must click on the link below. <br />' . $passage_ligne .
								'<a href="' . $link . '">' . $link . '</a>. <br />' . $passage_ligne .
								'We hope you enjoy your navigation.<br />' . $passage_ligne .
								'</body>
							</html>';
			//==========

			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			//=====Définition du sujet.
			$sujet = "Registration to 4DMotions.";
			//=========
			//=====Création du header de l'e-mail
			$header = "From: \"4D Motions\"<noreply@4dmotions.com>".$passage_ligne;
			$header .= "Reply-to: \"admin\" <admin@4dmotions.com>".$passage_ligne;
			$header .= "MIME-Version: 1.0".$passage_ligne;
			$header .= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//==========
			//=====Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format texte.
			$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_txt.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========
			 
			//=====Envoi de l'e-mail.
			mail($mail,$sujet,$message,$header);
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}
?>