<?php
#PHP5
class Session {

#########################################################################
	public function __construct(){
/* config edit starts */
//		$confArray = parse_ini_file("/usr/local/software/plumeus_tests/packages/dbConfig.ini", true);
//		$this->databaseHost = $confArray['teprod']['tehost'];
//		$this->databasePort = $confArray['teprod']['teport'];
//		$this->databaseUser = $confArray['teprod']['teusername'];
//		$this->databaseUserPwd = $confArray['teprod']['tepassword'];
/* config edit ends */
//		parent::__construct();
//		print_r($this->dbConn->getConnection());
	}
#########################################################################
	public function __destruct(){

	}
#########################################################################

	public function startSession(){
		
		session_start();
		$this->idSession = session_id();	
	
		if(isset($_REQUEST['req']) and $_REQUEST['req'] == "start"){
			$_SESSION = array();
			session_destroy();
			session_start();
			$this->idSession = session_id();
		}
		if(!isset($_SESSION['idSession'])) {
						
			$_SESSION['loginStatus'] = 0;
			$_SESSION['idSession'] = $this->idSession;

		}
		else {

			if($_SESSION['loginStatus']){
				if(!$this->checkSession()){
					$_SESSION['loginStatus'] = 0;
				}
			}

		}
	}

#########################################################################
	public function ajaxResponse($errCode = 0, $errText = "",$responseType = "HTML", $responseData = "", $custom_response = null){
		//errCode = 0 everything is ok if > 0 then a debugging errorCode
		//responseType = "HTML" the html should be get from this->templates buffer
		//responseType <> "HTML" then responseData should be used instead
		$response = array();
		if($custom_response != null){
			$response = $custom_response;
		}
		//if 'login_status' == 'not' ajax should logoff the user
		$response['loginStatus'] = (($_SESSION['loginStatus']) ? "ok" : "not");
		//status of the ajax response errText is passed to ajax for showing the debuging error; if not in debug mode then session expired!
		$response['errCode'] = $errCode;
		$response['errText'] = $errText;
		$response['responseType'] = $responseType;
		if($responseType == "DATA"){
			$response['ajaxResponse'] =$responseData;
		}
		elseif($responseType == "FORM"){
			if($errCode > 0){
				$response['success'] = false;
			}
			else{
				$response['success'] = true;
			}
		}
		sleep(0);
		
		echo json_encode($response);

		exit();
	}

#########################################################################
	public function checkSession(){
		return 1;
	}

#########################################################################
}
?>
