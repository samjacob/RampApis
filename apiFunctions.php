<?php    
	/* 
	* API Class for login validation and preference details 
	*/
	
	//require_once("Rest.inc.php");
	
	class APIFunctions {
	
		public $data = "";
		
		const DB_SERVER = "localhost";
		const DB_USER = "root";
		const DB_PASSWORD = "root";
		const DB = "ramp";
		
		private $db = NULL;
	
		public function __construct(){
			//parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}
		
		/*
		 *  Database connection 
		*/
		private function dbConnect(){
			$this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
			if($this->db)
				mysql_select_db(self::DB,$this->db);
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 //
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		/* 
		 *	Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		public function login($email, $pswd){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
						
			$email = $email;		
			$pswd = $pswd;
			
			// Input validations
			if(!empty($email) and !empty($pswd)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysql_query("select USER_ID,FIRST_NAME, LAST_NAME from ramp.RAMP_USERS where EMAIL='$email' and PWD='$pswd' and ACT_IND=1 LIMIT 1", $this->db);
					if(mysql_num_rows($sql) > 0){
						$result = mysql_fetch_array($sql,MYSQL_ASSOC);
						$results = array('status' => "Success", "data" => $result);
						
						// If success everythig is good send header as "OK" and user details
						return "Success";
						}else {
					$res=array('status' => "failed", "msg" => "No data found");
					return "failed";	// If no records "No Content" status
					}
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			//$this->response($this->json($error), 400);
		
		}
		
		
		# Preference API
		public function preferences(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$email = $_POST['username'];		
			//$pswd = $_POST['pswd'];
			
			// Input validations
			if(!empty($email)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$sql = mysql_query("SELECT W.WIDGET_NAME,W.WIDGET_ID, W.WIDGET_TYPE_ID FROM ramp.WIDGET W 
WHERE W.WIDGET_ID IN(SELECT P.WIDGET_ID FROM ramp.PREFERENCES P WHERE P.USER_ID = (SELECT  U.USER_ID FROM  ramp.RAMP_USERS U WHERE U.EMAIL = '$email' and U.ACT_IND=1) and P.ACT_IND=1)", $this->db);
					
					if(mysql_num_rows($sql) > 0){
						//$dbres = array();
						$i = 0;
						while($result = mysql_fetch_array($sql,MYSQL_ASSOC)){
							$dbres[$i]['widgetName']=$result['WIDGET_NAME'];
							$dbres[$i]['widgetId']=$result['WIDGET_ID'];
							//$dbres[$i]['widgettypeId']=$result['WIDGET_TYPE_ID'];
							$typid = $result['WIDGET_TYPE_ID'];
							$sql1 = mysql_query("SELECT `WIDGET_TYPE_NAME` FROM ramp.WIDGET_MASTER WM WHERE WM.WIDGET_TYPE_ID = $typid");
							$value = mysql_fetch_array($sql1);
							$dbres[$i]['widgetTypeName'] = $value['WIDGET_TYPE_NAME'];
							$i++;
						}
						
						//$result = array('status' => "Success", "msg" => "valid Email address and Password");
						$result = $this->json($dbres);
						// If success everythig is good send header as "OK" and user details
						$this->response($result, 200);
					}
					$res=array('status' => "failed", "msg" => "No data found");
					$this->response($this->json($res), 400);	// If no records "No Content" status
				}
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Preference details");
			$this->response($this->json($error), 400);
		}
		
		
		# API for all widgets
		
		private function widgetlist(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
						
				$qry = "Select widget_type_id, widget_type_name from WIDGET_MASTER where act_ind=1";

					$exqry = mysql_query($qry);
					$arr1 =array();
					$i =0;
					while($row = mysql_fetch_array($exqry)){
 					$wtid = $row['widget_type_id'];
 					$subqry = "select widget_id, widget_name from WIDGET where widget_type_id = $wtid and act_ind=1";
 					$exqry1 = mysql_query($subqry);
 					$arr11 = array();
 					$k=0;
 					while($row1 = mysql_fetch_array($exqry1)){
 						$arr11[$k] = array('wid'=>$row1['widget_id'],'widname'=>$row1['widget_name']);
 						$k++;
 					}
 					$arr1[$i]=array('widgetTypeName'=>$row['widget_type_name'],'widgets'=>$arr11);
 					$i++; 	
 				} 
 				$resp = $arr1;
 				$result = $this->json($resp);
				$this->response($result, 200);			
		}
		
		# SetPreference API
		private function setpreference(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$userid = $_POST['userid'];		
			$widgetid = $_POST['widgetid'];
			
			// Input validations
			if(!empty($userid)){
				if(filter_var($userid, FILTER_VALIDATE_INT)){
						$checksql ="SELECT pref_id from PREFERENCES where `user_id` = $userid and `widget_id` = $widgetid and act_ind = 1";
						$checkqry = mysql_query($checksql);
						if(mysql_num_rows($checkqry)>0){
							$res=array('status' => "failed", "msg" => "Widget Already set in Preference");
							$this->response($this->json($res), 400);
						}else{
						$updsql = "INSERT into PREFERENCES (`user_id`,`widget_id`,`act_ind`) values($userid,$widgetid,1)";
						$updqry = mysql_query($updsql, $this->db);
						if($updqry === true){ 
							$result = array('status' => "Success", "msg" => "Widget set in preference");
							// If success everythig is good send header as "OK" and user details
							$this->response($this->json($result), 200);
						}
						else {
							$res=array('status' => "failed", "msg" => "Not able to set as preference, check the data");
							$this->response($this->json($res), 400);
						}
						}
				 }
				 // If invalid inputs "Bad Request" status message and reason
				$error = array('status' => "Failed", "msg" => "Invalid Userid");
				$this->response($this->json($error), 400);
									
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Please provide a userid");
			$this->response($this->json($error), 400);
		}
		
		
		
		# unsetPreference API
		private function unsetpreference(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			
			$userid = $_POST['userid'];		
			$widgetid = $_POST['widgetid'];
			
			// Input validations
			if(!empty($userid)){
				if(filter_var($userid, FILTER_VALIDATE_INT)){
						$checksql ="SELECT pref_id from PREFERENCES where `user_id` = $userid and `widget_id` = $widgetid and act_ind = 1";
						$checkqry = mysql_query($checksql);
						if(mysql_num_rows($checkqry)>0){				
				
						$updsql = "UPDATE PREFERENCES set act_ind = 0 where `user_id` = $userid and `widget_id` = $widgetid";
						$updqry = mysql_query($updsql, $this->db);
						if($updqry === true){ 
							$result = array('status' => "Success", "msg" => "Widget removed from preferences");
							// If success everythig is good send header as "OK" and user details
							$this->response($this->json($result), 200);
						}
						else {
							$res=array('status' => "failed", "msg" => "Not able to unset preference, check the data");
							$this->response($this->json($res), 400);
						}
						} else {
							$res=array('status' => "failed", "msg" => "Widget Not set in Preference");
							$this->response($this->json($res), 400);
						}
				 }
				 // If invalid inputs "Bad Request" status message and reason
				$error = array('status' => "Failed", "msg" => "Invalid Userid");
				$this->response($this->json($error), 400);
									
			}
			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Please provide a userid");
			$this->response($this->json($error), 400);
		}
		
		
		
		
		private function users(){	
			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$sql = mysql_query("SELECT user_id, first_name, last_name, email FROM RAMP_USERS WHERE act_ind = 1", $this->db);
			if(mysql_num_rows($sql) > 0){
				$result = array();
				while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC)){
					$result[] = $rlt;
				}
				// If success everythig is good send header as "OK" and return list of users in JSON format
				$this->response($this->json($result), 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}
	
	// Initiiate Library
	
	$api = new API1;
	//$api->processApi();
?>
