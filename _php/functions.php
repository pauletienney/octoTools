<?php
include('DEFINE_PHP.php');
/*
**************************************************************************
commmon functions


**************************************************************************
*/
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
		return preg_match("#[a-z0-9A-Z]{4,30}#", $pass);
	}

	/**
	@param: $pass chaine représentant le password
	@return: retourne vrai si @param correspond a un password valide de longueur minimum 6char max 255char

	**/
	function isValidPseudo($pseudo){
		return preg_match("#^[a-z0-9A-Z]{2,30}$#", $pseudo);
	}
	/**
	@param: $m, une adresse mail
			$database une base de donnée
			$table une table
	@return: true si le mail n'existe pas dans la base de donnée
			false sinon
	**/
	function exist_mail($m, $d, $t)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT COUNT(*) FROM users WHERE mail= :mail');
			$reponse->execute(array('mail' => $m));
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
	@param: $p, unpseudo
			$database une base de donnée
			$table une table
	@return: true si le pseudo n'existe pas dans la base de donnée
			false sinon
	**/
	function exist_pseudo($p, $d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT COUNT(*) FROM users WHERE pseudo= :pseudo');
			$reponse->execute(array('pseudo' => $p));
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
	@param: $m un mail
			$p un mot de passe
			$database une base de donnée
			$table une table
	@return: true si l'association des deux n'existe pas dans la base de donnée
			false sinon
	**/
	function exist_account($m,$p,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pass = md5($p);
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT count(*) FROM users WHERE mail= :mail AND pass= :pass');
			$reponse->execute(array('mail' => $m, 'pass' => $pass));
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

/*
***************************************************************************
Fonction Mink

***************************************************************************
*/
	/**
	@param: $m un mail
			$database une base de donnée
			$table une table
	@return: name
	**/
	function getUserType($m,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT account_type FROM users WHERE mail= :mail');
			$reponse->execute(array('mail' => $m));
			$res = $reponse->fetch();
			return $res[0];
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
	}
	/**
	@param: $m un mail
			$database une base de donnée
			$table une table
	@return: name
	**/
	function getImageType($m,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT img_ext FROM users WHERE mail= :mail');
			$reponse->execute(array('mail' => $m));
			$res = $reponse->fetch();
			return $res[0];
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
	}
	/**
	@param: $m un mail
			$database une base de donnée
			$table une table
	@return: name
	**/
	function useGravatar($m,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT use_gravatar FROM users WHERE mail= :mail');
			$reponse->execute(array('mail' => $m));
			$res = $reponse->fetch();
			return $res[0];
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
	}
	/**
	@param: $m un mail
			$database une base de donnée
			$table une table
	@return: name
	**/
	function getUserName($m,$d,$t)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT pseudo FROM users WHERE mail= :mail');
			$reponse->execute(array('mail' => $m));
			$res = $reponse->fetch();
			return $res[0];
		}
		catch (Exception $e)
		{
				die('Erreur : ' . $e->getMessage());
		}
		$bdd= NULL;
	}
/**
	@param: $m un mail
			$database une base de donnée
			$table une table
	@return: void
	**/
	function connect($m,$d,$t)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{

			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET connected = :c WHERE mail= :mail');
			$req->execute(array('mail' => $m, 'c' => 1));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}
	/**
	@param: $m un mail
			$database une base de donnée
	@return: boolean
	**/
	function firstConnexion($m, $d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		$fc = false;
		try
		{

			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT firstConnexion FROM users WHERE mail= :mail');
			$reponse->execute(array('mail' => $m));
			$res = $reponse->fetch();
			if($res[0] == 1){
				$fc = true;
			}
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
		return $fc;
	}
	/**
	@param: $m un mail
			$database une base de donnée
	@return: void
	**/
	function validFirstConnexion($m, $type, $gravatar, $ext, $d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		$at = 0;
		if($type == "bar"){
			$at = 0;
		}
		else{
			$at = 1;
		}
		if($gravatar){
			$gr = 1;
		}
		else{
			$gr = 0;
		}
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('UPDATE users SET firstConnexion = :c, account_type = :at, use_gravatar = :gr, img_ext = :ext WHERE mail= :mail');
			$reponse->execute(array('c' => 0, 'at' => $at, 'gr' => $gr, 'ext' => $ext, 'mail' => $m));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}
	/**
	@param: $m un mail
			$database une base de donnée
			$table une table
	@return: name
	**/
	function deconnect($m,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET connected = :c WHERE mail= :mail');
			$req->execute(array('mail' => $m, 'c' => 0));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}
	/**
	@param: $id, id de l'user
	@return: true si l'user est connecté d'après la base de donnée
			false sinon
	**/
	function isConnected($m, $d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);

			$req = $bdd->prepare('SELECT connected FROM users WHERE mail= :mail');
			$req->execute(array(
				'mail' => $m
				));
			$res = $req->fetch();
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
/*
***********************************************************************************
***********************************************************************************
*/




/*
***********************************************************************************
FONCTIONS TALKBOX

***********************************************************************************
*/
	/**
	function exist_pseudo_account
	@param: $pseudo, $pass, $database

	**/
	function exist_pseudo_account($pseudo, $pass, $d){
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pass = md5($pass);
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$reponse = $bdd->prepare('SELECT count(*) FROM users WHERE pseudo= :pseu AND password= :pass');
			$reponse->execute(array('pseu' => $pseudo, 'pass' => $pass));
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
	fonction signin
	@param: $mail de l'user
	@return : boolean
	**/
	function createPseudo($pseudo, $pass, $d){
		global $LOG_ID;
		global $LOG_MDP;
		
		try
		{
			//creation du compte temporaire sur la base de donnée
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$query = $bdd->prepare("INSERT INTO users(pseudo, password) VALUES(:pseudo, :passw)");
			$query->execute(array(
			'pseudo'    => $pseudo,
			'passw' =>md5($pass)
			));
			
			$bdd = NULL;
			return true;
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
			return false;
		}
		
	}
	/**
	@param: $p un pseudo
			$database une base de donnée

	@return: void
	**/
	function connectPseudo($p,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{

			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET connected = :c WHERE pseudo= :pseudo');
			$req->execute(array('pseudo' => $p, 'c' => 1));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}
	/**
	@param: $p un pseudo
			$database une base de donnée

	@return: void
	**/
	function deconnectPseudo($p,$d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{

			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET connected = :c WHERE pseudo= :pseudo');
			$req->execute(array('pseudo' => $p, 'c' => 0));
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
	}
	
	
	/**
	@param: $p pseudo, $d database
	@return: true si l'user est connecté d'après la base de donnée
			false sinon
	**/
	function isConnectedPseudo($p, $d)
	{
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);

			$req = $bdd->prepare('SELECT connected FROM users WHERE pseudo= :pseudo');
			$req->execute(array(
				'pseudo' => $p
				));
			$res = $req->fetch();
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
	addfriend
	@param: $pseudo
			$name
			$database
	**/
	function addFriend($p, $n, $d){
		
	}
/*
*******************************************************************************
*******************************************************************************
*/

	/**
		@param: $k clé générée, $d date en md5
		return: possible ou non de reset le password
	**/
	function validAccount($k, $d, $m, $da, $t){
		$now = md5(date('Y-m-d'));
		$b = false;
		$p = "";
		global $LOG_ID;
		global $LOG_MDP;
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$da, $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('SELECT requestMailKey FROM users WHERE md5Mail= :mail');
			$req->execute(array(
				'mail' => $m
				));
			$rep = $req->fetch();
			if($rep['requestMailKey'] == $k && $d == $now){
				$req = $bdd->prepare('UPDATE users SET isValid = :v WHERE md5Mail= :mail');
				$req->execute(array('v'=> 1,'mail' => $m));
				//
				// creation des dossiers perso ici
				//
				$path = "../mink/users/".$m;
				mkdir($path, 0777);
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
	fonction signin
	@param: $mail de l'user
	@return : boolean
	**/
	function sendSignMail($mail, $pass, $pseudo, $d, $t){
		global $LOG_ID;
		global $LOG_MDP;
		$maill = strtolower( trim($mail) );
		$k = re2(10);
		$date = date('Y-m-d');
		$signDate = date('Y-m-d H:i:s');
		//creation du compte temporaire sur la base de donnée
		$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
		$query = $bdd->prepare("INSERT INTO users(mail, pass, connected, pseudo, signDate, requestMailKey, requestMailDate, md5Mail, isValid, account_type ,firstConnexion, use_gravatar, img_ext) VALUES(:mail, :pass, :connected, :pseudo, :signDate, :reqMK, :reqMD, :md5M, :v,:ac, :fc, :ug, :ie)");
		$query->execute(array(
			'mail'      => $maill,
			'pass'      => md5($pass),
			'connected' => 0,
			'pseudo'    => $pseudo,
			'signDate'  => $date,
			'reqMK'     => $k,
			'reqMD'     => $date,
			'md5M'      => md5($maill),
			'v'         => 0,
			'ac'        => 0,
			'fc'        => 1,
			'ug'        => 1,
			'ie'        => ""
			));
		
		$link = 'http://www.4dmotions.com/mink/signin.php?k=' . $k . '&d=' . md5($date) . '&m=' . md5($maill);
		try
		{
			
			//code pour envoyer le mail
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $maill)){$passage_ligne = "\r\n";}
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
			$sujet = "Inscription à Mink.";
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
			mail($maill,$sujet,$message,$header);
			$bdd = NULL;
			return true;
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
			return false;
		}
		
	}
	/**
	@param: $mail de l'user
	@return : boolean
	**/
	function sendRetrieveMail($mail, $d, $t){
		$retour = false;
		global $LOG_ID;
		global $LOG_MDP;
		$maill = strtolower( trim($mail) );
		$newPass = re2(10);
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
			$bdd = new PDO('mysql:host=localhost;dbname='.$d, $LOG_ID, $LOG_MDP, $pdo_options);
			$req = $bdd->prepare('UPDATE users SET pass = :newPass WHERE mail = :mail');
			$req->execute(array('newPass' => md5($newPass),'mail' => $maill));

			//code pour envoyer le mail
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $maill)){$passage_ligne = "\r\n";}
			else{
				$passage_ligne = "\n";
			}

			//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = 'Bonjour,<br />' .  $passage_ligne . 
							'Voici votre nouveau mot de passe, notez le ! ' . $passage_ligne . 
							'Mot de passe : '. $newPass;
			$message_html = '<html>
								<head></head>
								<body>
									Bonjour,<br />' .  $passage_ligne . 
									'Voici votre nouveau mot de passe, notez le !<br />' . $passage_ligne . 
									'Mot de passe : ' . $newPass .'
								</body>
							</html>';
			//==========

			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			//=====Définition du sujet.
			$sujet = "Demande de mot de passe";
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
			mail($maill,$sujet,$message,$header);
			$retour = true;
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		$bdd = NULL;
		return $retour;
	}
	
	
?>