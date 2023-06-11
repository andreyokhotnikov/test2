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
/*$fp = fopen('/usr/local/apache2/sites/archprofile.com/htdocs/class/andrey_log_AjaxSession.txt', 'a');
 fwrite($fp,"----------Session.class.php5----------startSession"."\n");
 fwrite($fp, print_r($_SESSION, TRUE));
fclose($fp);*/			

		if(isset($_REQUEST['req']) and $_REQUEST['req'] == "start"){
			$_SESSION = array();
			session_destroy();
			session_start();
			$this->idSession = session_id();
		}
		if(!isset($_SESSION['idSession'])) {
/*$fp = fopen('/usr/local/apache2/sites/archprofile.com/htdocs/class/andrey_log_AjaxSession.txt', 'a');
 fwrite($fp,"----------Session.class.php5-----68-----startSession"."\n");
 fwrite($fp, print_r($_SESSION, TRUE));
fclose($fp);*/			
			//new request or cookie session expired
			$this->debug_info .= "idSession not set<br>";
			$_SESSION['loginStatus'] = 0;
			$_SESSION['idSession'] = $this->idSession;
			$_SESSION['userType'] = $this->userType;
			$this->detectConfig();
			//detect basic configuration
			//set templates paths according to theme and custon_client config
		}
		else{
/*$fp = fopen('/usr/local/apache2/sites/archprofile.com/htdocs/class/andrey_log_AjaxSession.txt', 'a');
 fwrite($fp,"----------Session.class.php5-----81-----startSession"."\n");
 fwrite($fp, print_r($_SESSION, TRUE));
fclose($fp);*/			
			$this->debug_info .= "idSession set<br>";

			if($_SESSION['loginStatus']){
				if(!$this->checkSession()){
					$_SESSION['loginStatus'] = 0;
				}
			}
			//check if session is not expired
//			$this->detectConfig();
			//if session expired check request status
			//if request is 'start'
				//then reset session
				//else exit
		}
	}
#########################################################################
//	public function logoutSession(){
//		$_SESSION['loginStatus'] = 0;
//		mysql_query("UPDATE users_session SET idSession = 'logout', dateLastAccess=NOW() WHERE idUser=$this->idUser and idSession=$this->idSession");
//	}
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

#########################################################################

#########################################################################

#########################################################################
/*	public function checkSession(){
		$sqlText = sprintf("SELECT idUser FROM users_session WHERE idUser = %d and idSession='%s'",$_SESSION['idUser'], $_SESSION['idSession']);
		#print "<span style='color:red'>sqltext: $sqlText</span><br>";

		 $dbCnn = $this->dbConn->getConnection() ;
##DK		$result = mysql_query($sqlText, $this->dbConn->getConnection());
		$result = mysql_query($sqlText, $dbCnn );

		if($result and mysql_num_rows($result) > 0){
			mysql_query("Call update_usr_session(".$this->idUser.",'".$this->idSession."','".$_SERVER['REMOTE_ADDR']."');", $this->dbConn->getConnection());
			return 1;
		}
		else{

			$NumRows = -99999 ;
			if( $result ) {
				$numRows = mysql_num_rows($result) ;
			 }else{
				$result = -99000 ;
			}

			//$idSessPerCookiePHPSESSID = $_COOKIE['PHPSESSID'] ;
			$idSessPerCookiePHPSESSID = isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : "";
			$URI = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : "";
			$REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "";
			$ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
			$AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";
			
			$sessName = session_name() ;

			return 0;
		}
	}
*/

#########################################################################
}
?>
