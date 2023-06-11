<?php

class AjaxSession extends Session {
#########################################################################
	public function __construct(){//
			parent::__construct();
		}
#########################################################################
	public function __destruct(){
		parent::__destruct();
	}
#########################################################################
	public function processRequest(){
				
		//include("mysqlconfig.php");
		include("mysqlconfig_local.php");
		$this->conn = mysqli_connect($hostname, $username, $password, $dbname);
		mysqli_set_charset($this->conn,"utf8");
		
		if(array_key_exists('req', $_REQUEST)){
			if($_REQUEST['req'] == 'session'){
					if(array_key_exists('aj_action', $_REQUEST)){


		//$ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
		//$ACTION = isset($_REQUEST['aj_action']) ? $_REQUEST['aj_action'] : "";
		//$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
		//$REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";

						
						if($_REQUEST['aj_action'] == 'getUsers'){
							$this->getUsers();
						}
					//	else if($_REQUEST['aj_action'] == 'getTests'){
					//		$this->getTests();
					//	}
					//	else if($_REQUEST['aj_action'] == 'saveProgress'){
					//		$this->saveProgress();
					//	}
						
						
//						else if($_REQUEST['aj_action'] == 'sendEmail'){
//							$this->sendEmail();
//						}	
//						else if($_REQUEST['aj_action'] == 'login'){
//							$this->login();
//						}						
						else{
							//action is unknown therefore invalid request
							$this->ajaxResponse(1005,"action is unknown","ERR");
						}
					}// array_key_exists
					else{
						//action is not set therefore invalid request
						$this->ajaxResponse(1004,"action is not set","ERR");
					}
//				}// $_SESSION['loginStatus']
//				else{
//					//session id expired
//					$this->ajaxResponse(1003,"session id expired","ERR");
//				}
			}// $_REQUEST['req'] == 'session'
			else{
				//req is not session therefore invalid request
				$this->ajaxResponse(1002,"req is not session","ERR");
			}
		}// array_key_exists
		else{
			//req is not set therefore invalid request
			$this->ajaxResponse(1001,"req is not set","ERR");
		}
	}

	private function getUsers(){ 
	
		$idUser = isset($_REQUEST['idUser']) ? $_REQUEST['idUser'] : 0;
		$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
		$education = isset($_REQUEST['education']) ? $_REQUEST['education'] : '';
		
		$whereUser = '';
		if ($idUser > 0) {
			$whereUser = 'Where idUser = $idUser';
		}
		
		$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : 100;
		$start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
		
        $response = array();	

		$sqlText = " 
			SELECT 
				users.idUser,
				users.userName,
				userscity.city,
				usersinfo.education,
				usersinfo.dob,
				usersinfo.email,
				usersinfo.phone,
				usersinfo.comments
			FROM users
			INNER JOIN userscity on users.idUser = userscity.idUser
			INNER JOIN usersinfo on users.idUser = usersinfo.idUser
			$whereUser
		";

$fp = fopen('C:/WebServers/home/localhost/www/test/controllers/ajax_session_log.txt', 'a');
fwrite($fp,"166--------AjaxSession.php5----------getUsers"."\n");
fwrite($fp, $sqlText."\n");
fclose($fp);
		$result = mysqli_query($this->conn, $sqlText);

		$nodes = array();
		if($result and mysqli_num_rows($result) > 0){
			while ($row = mysqli_fetch_assoc($result)) {
				$nodes[] = array(
					"idUser"=>$row['idUser'],
					"userName"=>$row['userName'], 
					"city"=>$row['city'],
					"education"=>$row['education'],
					"dob"=>$row['dob'],
					"email"=>$row['email'],
					"phone"=>$row['phone'],
					"comments"=>$row['comments']
				);
			}

			$response['data'] = $nodes;
			$this->ajaxResponse(0,"","DATA", $nodes,$response);
		}
		else{
			$this->ajaxResponse(1,"table is empty","DATA", null, $response);
		}	
	}

	private function addUser(){ 
	
		$userName = isset($_REQUEST['userName']) ? $_REQUEST['userName'] : '';
		$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
		$education = isset($_REQUEST['education']) ? $_REQUEST['education'] : '';
		
		$comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : '';
		
		$idLanguage = isset($_REQUEST['idLanguage']) ? $_REQUEST['idLanguage'] : 0;
		$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
		$text = isset($_REQUEST['text']) ? $_REQUEST['text'] : '';
		$tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
		$idGender = isset($_REQUEST['idGender']) ? $_REQUEST['idGender'] : '';
		$idStatus = isset($_REQUEST['idStatus']) ? $_REQUEST['idStatus'] : 0;
		

        $response = array();	
		$response['success'] = false;
		
		$comments = mysql_real_escape_string($comments);
		
		$sqlText = "							
			INSERT INTO users(idUser,education,dob,email,phone,comments)
			VALUES ($idUser,'$education','$dob','$email','$phone','$comments')			
		";
		$sqlText = "							
			INSERT INTO usersinfo(idUser,education,dob,email,phone,comments)
			VALUES ($idUser,'$education','$dob','$email','$phone','$comments')			
		";
	
		//$response['sqlText'] = $sqlText;		
		
//$fp = fopen('/usr/local/apache2/sites/archprofile.com/htdocs/translations/ajax_session_log.txt', 'a');
//fwrite($fp, $sqlText."\n");
//fclose($fp);		

		$result = mysqli_query($this->conn, $sqlText);
		
		if($result){
			
			$response['success'] = true;
			$this->ajaxResponse(0,"","DATA", '',$response);
			
		}
		else{
			$this->ajaxResponse(1,"error","DATA", null, $response);
		}	
	}
	
	private function editUser(){ 
	
		$idUser = isset($_REQUEST['idUser']) ? $_REQUEST['idUser'] : 0;
		$city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
		$education = isset($_REQUEST['education']) ? $_REQUEST['education'] : '';
		
		$comments = isset($_REQUEST['comments']) ? $_REQUEST['comments'] : '';
		
		$idLanguage = isset($_REQUEST['idLanguage']) ? $_REQUEST['idLanguage'] : 0;
		$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
		$text = isset($_REQUEST['text']) ? $_REQUEST['text'] : '';
		$tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
		$idGender = isset($_REQUEST['idGender']) ? $_REQUEST['idGender'] : '';
		$idStatus = isset($_REQUEST['idStatus']) ? $_REQUEST['idStatus'] : 0;
		

        $response = array();	
		$response['success'] = false;
		
		$comments = mysql_real_escape_string($comments);
		

		$sqlText = "							
			INSERT INTO usersinfo(idUser,education,dob,email,phone,comments)
			VALUES ($idUser,'$education','$dob','$email','$phone','$comments')
			ON DUPLICATE KEY UPDATE education = '$education', dob = $dob, email = '$email', phone = $phone, comments = $comments;	
		";
	
		//$response['sqlText'] = $sqlText;		
		
//$fp = fopen('/usr/local/apache2/sites/archprofile.com/htdocs/translations/ajax_session_log.txt', 'a');
//fwrite($fp, $sqlText."\n");
//fclose($fp);		

		$result = mysqli_query($this->conn, $sqlText);
		
		if($result){
			
			$response['success'] = true;
			$this->ajaxResponse(0,"","DATA", '',$response);
			
		}
		else{
			$this->ajaxResponse(1,"error","DATA", null, $response);
		}	
	}
	

#########################################################################

}
?>
