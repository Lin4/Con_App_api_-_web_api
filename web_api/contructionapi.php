<?php
   require_once("Rest.inc.php");
   //require_once 'urbanairship.php';
   
   /*
    * Google API Key
    */
   define("GOOGLE_API_KEY", "AIzaSyCvTdVbjbZdlldc45A3WGrP0zFLG72WgLs");
   
   
   class API extends REST{
    public $data="";
    const DB_SERVER = "localhost:3306";
    const DB_USER = "primetec_prime_c";
    const DB_PASSWORD = "4DC[.(DV]]&b";
    const DB = "primetec_privytex_construct";
    
    //User: privytex_pro1
    //Database: privytex_privytextproduct
    
    private $db=NULL;
    public $path_params;
    
    public function __construct(){
        parent::__construct();
        $this->dbConnect();
    }
    
    private function dbConnect(){
        $this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
        if($this->db){
            mysql_select_db(self::DB,$this->db);
        }
    }
    
    public function processApi(){
        $path = $_SERVER['PATH_INFO'];
        
        if ($path != null) {
            
            $this->path_params = spliti ("/", $path);
            //echo $this->path_params[2];;
            $myfunc = $this->path_params[2];
            
            if((int)method_exists($this,$myfunc) > 0){
                //echo $myfunc;
                $query = mysql_query("SET time_zone ='+5:33'", $this->db);
                $this->$myfunc();
            }
            else
                $this->response('',404);       
        }
        
    }
    
   public function user(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
		if ($this->path_params[3] == "login") {
                    if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "check") {
                           if( ($this->path_params[5] != null) and ($this->path_params[6] != null)){
                              //echo $this->path_params[4];
                              $sql = mysql_query("SELECT username, firstname, lastname,user_type,id_no
                                                   FROM users
                                                   WHERE username = '".mysql_real_escape_string($this->path_params[5])."'
                                                   AND password = '".mysql_real_escape_string($this->path_params[6])."' 
                                                    LIMIT 1", $this->db);
                              if(mysql_num_rows($sql) > 0){
                                 $row = mysql_fetch_array($sql);
                                 $result['firstname']=$row['firstname'];
                                 $result['lastname']=$row['lastname'];
                                 $result['user_type']=$row['user_type'];
                                 $result['id_no']=$row['id_no'];
                                 $result['message'] = array('status' => "sucess");
                                    
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result['message'] = array('status' => "failed");
                                    
                                    // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }
                              $this->response('', 204);
                           }
                        }
                     }
               }elseif($this->path_params[3] == "forgot"){
                     if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "check") {
                            if ($this->path_params[5] != null) {
                                //echo $this->path_params[4];
                                $sql = mysql_query("SELECT password,email FROM users WHERE username = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                                if(mysql_num_rows($sql) > 0){
                                    $row = mysql_fetch_assoc($sql);
                                    //send email
                                    $email = $row['email'] ;
                                    $subject = "PrimeConst | Forgot Password | ".mysql_real_escape_string($this->path_params[5]) ;
                                    $message = "Your password is ".$row['password'] ;
                                    mail($email, $subject,$message, "From:sranjana@3sg.com"); 
                                    
                                    $result = array('status' => "sucess");
                                    // If success everythig is good send header as "OK" and user details
                                    $this->response($this->json($result), 200);
                                    
                                }
                                $result = array('status' => "not found");
                                $this->response($this->json($result), 200);
                            }
                        }
                    }
                }elseif($this->path_params[3] == "get"){
                     if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "all") {
                           
                                //echo $this->path_params[4];
                                $sql = mysql_query("SELECT * FROM users ", $this->db);
                                if(mysql_num_rows($sql) > 0){
                                    
                                    while ($user = mysql_fetch_assoc($sql)) {
                                       $result['users'][] = $user;
                                    }
                                    
                                    $result['message'][] = array('status' => 'sucess');
                                    // If success everythig is good send header as "OK" and user details
                                    $this->response($this->json($result), 200);
                                    
                                }
                                $result = array('status' => "not found");
                                $this->response($this->json($result), 200);
                           
                        }
                    }
                }
            }
        }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "signin"){
                  $sql = mysql_query("SELECT * FROM users
                                          WHERE username = '".mysql_real_escape_string($this->path_params[4])."'", $this->db);
                        if(mysql_num_rows($sql) == 0){
                            $date = date("Y-m-d");
                            $user = mysql_fetch_assoc($sql);
                            
                            $query = mysql_query("INSERT INTO users
                                                 (username, password, firstname, lastname, email,user_type,id_no,created)
                                                 VALUES
                                                 ('".mysql_real_escape_string($this->path_params[4])."',
                                                 '".mysql_real_escape_string($this->path_params[5])."',
                                                 '".mysql_real_escape_string($this->path_params[6])."',
                                                 '".mysql_real_escape_string($this->path_params[7])."',
                                                 '".mysql_real_escape_string($this->path_params[8])."',
                                                 '".mysql_real_escape_string($this->path_params[9])."',
                                                 '".mysql_real_escape_string($this->path_params[10])."',
                                                 now())");
                            
                            
                           $result = array('status' => "sucess");
                                
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                            
                        }else{
                           $result = array('status' => "Already exits");
                           $this->response($this->json($result), 200);
                        }
                        
                  
               }elseif($this->path_params[3] == "password"){
                     if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "change") {
                           if($this->path_params[5] != null and $this->path_params[6] != null and $this->path_params[7] != null){
                              $sql = mysql_query("SELECT * FROM users
                                                   WHERE username = '".mysql_real_escape_string($this->path_params[5])."'
                                                   AND password='".mysql_real_escape_string($this->path_params[6])."'", $this->db);
                              if(mysql_num_rows($sql) != 0){
                                 $query = mysql_query("UPDATE users SET password='".mysql_real_escape_string($this->path_params[7])."' WHERE username='".mysql_real_escape_string($this->path_params[5])."'");
                                    //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                                 if($query){
                                    $result = array('status' => "sucess");
                                        
                                        // If success everythig is good send header as "OK" and user details
                                    $this->response($this->json($result), 200);
                                 }else{
                                    $result = array('status' => "Faild");
                                    $this->response($this->json($result), 200);
                                 }
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                           }
                        }
                     }
               }elseif($this->path_params[3] == "update"){
                  $sql = mysql_query("UPDATE `users` SET
                                     `firstname`='".mysql_real_escape_string($this->path_params[5])."',
                                     `lastname`='".mysql_real_escape_string($this->path_params[6])."',
                                     `email`='".mysql_real_escape_string($this->path_params[7])."',
                                     `user_type`='".mysql_real_escape_string($this->path_params[8])."',
                                     `id_no`='".mysql_real_escape_string($this->path_params[9])."'
                                    WHERE `username`='".mysql_real_escape_string($this->path_params[4])."'");
                  
                  if($sql){
                     $result['message'][] = array('status' => 'sucess');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }else{
                     $result['message'][] = array('status' => 'failed');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }
                  
               }elseif($this->path_params[3] == "delete"){
                  $sql = mysql_query("DELETE FROM `users` WHERE `username`='".mysql_real_escape_string($this->path_params[4])."'");
                  
                  if($sql){
                     $result['message'][] = array('status' => 'sucess');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }else{
                     $result['message'][] = array('status' => 'failed');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }
                  
               }
            }
      }
   }
   
   public function dailyinspection(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `dailyInspectionForm`
                                        INNER JOIN assign_project
                                        ON assign_project.`projectid` = dailyInspectionForm.Project_id 
                                           WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['dailyInspectionForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               } elseif ($this->path_params[3] == "dailyInspectionItemByNo") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `dailyInspection_item`
                                         WHERE `No` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['dailyInspectionForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               } elseif($this->path_params[3] == "single"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `dailyInspectionForm` WHERE dailyInspecNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['dailyinspection'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           $this->response($this->json($result), 200);
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
                     }
               }elseif($this->path_params[3] == "pmlist"){
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `dailyInspectionForm`
                                        INNER JOIN projects
                                        ON projects.`projecct_id` = dailyInspectionForm.Project_id 
                                           WHERE projects.`project_manager` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['dailyInspectionForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }
            }
        }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  
                  $sql = mysql_query("SELECT dailyInspecNo FROM dailyInspectionForm 
                                          ORDER BY id DESC LIMIT 1", $this->db);
                  
                  $user = mysql_fetch_assoc($sql);
                  $lastID = $user['dailyInspecNo'];
                  $lastID = str_replace('IN', '', $lastID);
                  $newID = $lastID + 1;
                  $newID = 'IN'.$newID;
                  
                 
                  //if(mysql_num_rows($sql) == 0){
                     //$date = date("Y-m-d");
                     //$user = mysql_fetch_assoc($sql);
					 $iDesc1 = $iNo1 = $iDesc2 = $iNo2 = $iDesc3 = $iNo3 = $iDesc4 = $iNo4 = $iDesc5 = $iNo5 = "";
						$param53 = $this->path_params[53];
						if(!empty($param53)){
						$iparm1 = explode("--,",$this->path_params[53]);
						$iDesc[1] = $iparm1[0];
						$iNo[1] = $iparm1[1];
						$iQty[1] = $this->path_params[54];
						}
						$param55 = $this->path_params[55];
						if(!empty($param55)){
						$iparm2 = explode("--,",$this->path_params[55]);
						$iDesc[2] = $iparm2[0];
						$iNo[2] = $iparm2[1];
						$iQty[2] = $this->path_params[56];
						}
						$param57 = $this->path_params[57];
						if(!empty($param57)){
						$iparm3 = explode("--,",$this->path_params[57]);
						$iDesc[3] = $iparm3[0];
						$iNo[3] = $iparm3[1];
						$iQty[3] = $this->path_params[58];
						}
						$param59 = $this->path_params[59];
						if(!empty($param59)){
						$iparm4 = explode("--,",$this->path_params[59]);
						$iDesc[4] = $iparm4[0];
						$iNo[4] = $iparm4[1];
						$iQty[4] = $this->path_params[60];
						}
						$param61 = $this->path_params[61];
						if(!empty($param61)){
						$iparm5 = explode("--,",$this->path_params[61]);
						$iDesc[5] = $iparm5[0];
						$iNo[5] = $iparm5[1];
						$iQty[5] = $this->path_params[62];
						}
						
						$sql = "INSERT INTO `dailyInspectionForm`(`report_No`,
						`DIFHeader`,
						`Contractor`,
						`con_Name`,
						`weather`,
						`time`,
						`dailyInspecNo`,
						`P_O_Box`,
						`City`,
						`State`,
						`Zip_Code`,
						`Telephone_No`,
						`Date`,
						`CompetentPerson`,
						`Project`,
						`Project_id`,
						`Town_City`,
						`E_Mail`,
						`WorkDoneBy`,
						`OVJName1`,
						`OVJTitle1`,
						`OVJName2`,
						`OVJTitle2`,
						`OVJName3`,
						`OVJTitle3`,
						`OVJName4`,
						`OVJTitle4`,
						`IFName1`,
						`IFTitle1`,
						`IFName2`,
						`IFTitle2`,
						`IFName3`,
						`IFTitle3`,
						`IFName4`,
						`IFTitle4`,
						`WDODepartmentOrCompany1`,
						`WDODescriptionOfWork1`,
						`WDODepartmentOrCompany2`,
						`WDODescriptionOfWork2`,
						`WDODepartmentOrCompany3`,
						`WDODescriptionOfWork3`,
						`WDODepartmentOrCompany4`,
						`WDODescriptionOfWork4`,
						`ContractorsHoursOfWork`,
						`InspectorSign`,
						`printedName`,
						`original_Calendar_Days`,
						`calendar_Days_Used`,
						`I_No1`,
						`I_Desc1`,
						`I_QTY1`,
						`I_No2`,
						`I_Desc2`,
						`I_QTY2`,
						`I_No3`,
						`I_Desc3`,
						`I_QTY3`,
						`I_No4`,
						`I_Desc4`,
						`I_QTY4`,
						`I_No5`,
						`I_Desc5`,
						`I_QTY5`) VALUES
                                          ('".mysql_real_escape_string($this->path_params[47])."',
										  '".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
										  '".mysql_real_escape_string($this->path_params[48])."',
										  '".mysql_real_escape_string($this->path_params[49])."',
										  '".mysql_real_escape_string($this->path_params[50])."',
                                          '".$newID."',
                                          '".mysql_real_escape_string($this->path_params[8])."',
                                          '".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."',
                                          '".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."',
                                          '".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."',
                                          '".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."',
                                          '".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."',
                                          '".mysql_real_escape_string($this->path_params[20])."',
                                          '".mysql_real_escape_string($this->path_params[21])."',
                                          '".mysql_real_escape_string($this->path_params[22])."',
                                          '".mysql_real_escape_string($this->path_params[23])."',
                                          '".mysql_real_escape_string($this->path_params[24])."',
                                          '".mysql_real_escape_string($this->path_params[25])."',
                                          '".mysql_real_escape_string($this->path_params[26])."',
                                          '".mysql_real_escape_string($this->path_params[27])."',
                                          '".mysql_real_escape_string($this->path_params[28])."',
                                          '".mysql_real_escape_string($this->path_params[29])."',
                                          '".mysql_real_escape_string($this->path_params[30])."',
                                          '".mysql_real_escape_string($this->path_params[31])."',
                                          '".mysql_real_escape_string($this->path_params[32])."',
                                          '".mysql_real_escape_string($this->path_params[33])."',
                                          '".mysql_real_escape_string($this->path_params[34])."',
                                          '".mysql_real_escape_string($this->path_params[35])."',
                                          '".mysql_real_escape_string($this->path_params[36])."',
                                          '".mysql_real_escape_string($this->path_params[37])."',
                                          '".mysql_real_escape_string($this->path_params[38])."',
                                          '".mysql_real_escape_string($this->path_params[39])."',
                                          '".mysql_real_escape_string($this->path_params[40])."',
                                          '".mysql_real_escape_string($this->path_params[41])."',
                                          '".mysql_real_escape_string($this->path_params[42])."',
                                          '".mysql_real_escape_string($this->path_params[43])."',
                                          '".mysql_real_escape_string($this->path_params[44])."',
										  '".mysql_real_escape_string($this->path_params[45])."',
										  '".mysql_real_escape_string($this->path_params[46])."',
										  '".mysql_real_escape_string($this->path_params[51])."',
										  '".mysql_real_escape_string($this->path_params[52])."',
										  '".mysql_real_escape_string($iDesc[1])."',
										  '".mysql_real_escape_string($iNo[1])."',
										  '".mysql_real_escape_string($iQty[1])."',
										  '".mysql_real_escape_string($iDesc[2])."',
										  '".mysql_real_escape_string($iNo[2])."',
										  '".mysql_real_escape_string($iQty[2])."',
										  '".mysql_real_escape_string($iDesc[3])."',
										  '".mysql_real_escape_string($iNo[3])."',
										  '".mysql_real_escape_string($iQty[3])."',
										  '".mysql_real_escape_string($iDesc[4])."',
										  '".mysql_real_escape_string($iNo[4])."',
										  '".mysql_real_escape_string($iQty[4])."',
										  '".mysql_real_escape_string($iDesc[5])."',
										  '".mysql_real_escape_string($iNo[5])."',
										   '".mysql_real_escape_string($iQty[5])."')";
                     $query = mysql_query($sql);
					 for($i=1; $i < 5; $i++){
						 if(!empty($iNo[$i])){
							$query = mysql_query("INSERT INTO `dailyInspection_item`(`dailyInspecNo`, `No`, `Description`, `Qty`, `date`)
											  VALUES ('".$newID."','".$iNo[$i]."', '".$iDesc[$i]."', '".$iQty[$i]."', '".mysql_real_escape_string($this->path_params[13])."')");
						}
					}					
                    // echo $sql;
                     $uploaddir = 'dailyinspection/';
                                    
                     for($i=0; $i < 2;$i++) {
                        $file = basename($_FILES['userfile']['name']);
                        $uploadfile = $uploaddir . $file;
                        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                           $im_uploaded =  "OK";
                        }else {
                           $im_uploaded =  "ERROR";
                        }
                        $d[$i] = $_FILES['userfile']['name'];
                     }
                     
                     if($query){
                        $result = array('status' => "sucess" , 'id' => $newID);
                     }else{
                        $result = array('status' => "Faild" , 'err' => $sql);
                            
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  //}else{
                  //   $result = array('status' => "Already exits");
                  //   $this->response($this->json($result), 200);
                  //}
               }elseif($this->path_params[3] == "uploadimages"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT images_uploaded FROM dailyInspectionForm
                                                   WHERE dailyInspecNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['images_uploaded'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE dailyInspectionForm SET images_uploaded='".$currentImage."' WHERE dailyInspecNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                              
                                    
                              if($query){
                                 $uploaddir = 'dailyinspection/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               } elseif($this->path_params[3] == "uploadsketches"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM dailyInspectionForm
                                                   WHERE dailyInspecNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE dailyInspectionForm SET sketch_images='".$currentImage."' WHERE dailyInspecNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'dailyinspection/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               } elseif ($this->path_params[3] == "update"){
				
			   $sql = "UPDATE `dailyInspectionForm` SET
						`weather` = '".mysql_real_escape_string($this->path_params[14])."',
						`time` = '".mysql_real_escape_string($this->path_params[15])."',				
						`P_O_Box` = '".mysql_real_escape_string($this->path_params[6])."',
						`City` = '".mysql_real_escape_string($this->path_params[7])."',
						`State` = '".mysql_real_escape_string($this->path_params[8])."',
						`Zip_Code` = '".mysql_real_escape_string($this->path_params[9])."',
						`Telephone_No` = '".mysql_real_escape_string($this->path_params[10])."',
						`Date` = '".mysql_real_escape_string($this->path_params[11])."',
						`CompetentPerson` = '".mysql_real_escape_string($this->path_params[12])."',
						`Project` = '".mysql_real_escape_string($this->path_params[16])."',
						`Town_City` = '".mysql_real_escape_string($this->path_params[13])."',
						`E_Mail` = '".mysql_real_escape_string($this->path_params[17])."',
						`WorkDoneBy` = '".mysql_real_escape_string($this->path_params[18])."'
						WHERE id = '".mysql_real_escape_string($this->path_params[5])."'";
						echo $sql;exit;
                     $query = mysql_query($sql);
					 if($query){
						 $result = array('status' => "sucess");
						 $this->response($this->json($result), 200);
					 }
			   }
            }
      }
   }
    
   public function noncompliance(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `nonComplianceForm` INNER JOIN assign_project ON assign_project.`projectid` = nonComplianceForm.project_id 
                                           WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['nonComplianceForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif ($this->path_params[3] == "pmlist") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `nonComplianceForm` INNER JOIN projects ON projects.`projecct_id` = nonComplianceForm.project_id 
                                           WHERE projects.`project_manager` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['nonComplianceForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif($this->path_params[3] == "single"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `nonComplianceForm`
						LEFT JOIN `projects` ON `nonComplianceForm`.Project_id = `projects`.projecct_id	
						WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           //$row = mysql_fetch_assoc($sql);
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['nonComplianceForm'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}nonComplianceReportNo
                     }
               }elseif($this->path_params[3] == "get"){
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `nonComplianceForm` INNER JOIN projects ON projects.`projecct_id` = nonComplianceForm.project_id 
                                           WHERE projects.`project_manager` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['nonComplianceForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }
            }
      }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  $sql = mysql_query("SELECT nonComplianceReportNo FROM nonComplianceForm 
                                          ORDER BY id DESC LIMIT 1", $this->db);
                  
                  $user = mysql_fetch_assoc($sql);
                  $lastID = $user['nonComplianceReportNo'];
                  $lastID = str_replace('CN', '', $lastID);
                  $newID = $lastID + 1;
                  $newID = 'CN'.$newID;
                  
                  
                  
                     $user = mysql_fetch_assoc($sql);
                            
                     $query = mysql_query("INSERT INTO `nonComplianceForm`(`Non_ComHeader`, `ContractNo`, `nonComplianceReportNo`, `ProjectDescription`,
                                          `Title`, `Project`, `DateIssued`, `ContractorResponsible`, `To`, `DateCRTCB`, `DateContractorStarted`,
                                          `DateContractorCompleted`, `DateOfDWRReported`, `UserID`, `DescriptionOfNonCompliance`, `Signature`,
                                          `PrintedName`, `Date`, `Project_id`)
                                          VALUES ('".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
                                          '".$newID."',
                                          '".mysql_real_escape_string($this->path_params[8])."',
                                          '".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."',
                                          '".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."',
                                          '".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."',
                                          '".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."',
                                          '".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."',
                                          '".mysql_real_escape_string($this->path_params[20])."',
                                          '".mysql_real_escape_string($this->path_params[21])."',
                                          now(),
                                          '".mysql_real_escape_string($this->path_params[22])."')");
                     
                     $uploaddir = 'noncompliance/';
                                          
                     for($i=0; $i < 2;$i++) {
                        $file = basename($_FILES['userfile']['name']);
                        $uploadfile = $uploaddir . $file;
                        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                           $im_uploaded =  "OK";
                        }else {
                           $im_uploaded =  "ERROR";
                        }
                        $d[$i] = $_FILES['userfile']['name'];
                     }
                     
                     if($query){
                        $result = array('status' => "sucess",'id' => $newID);
                     }else{
                        $result = array('status' => "Faild" , 'err' => $lastID);
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  
               }elseif($this->path_params[3] == "uploadimages"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT images_uploaded FROM nonComplianceForm
                                                   WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['images_uploaded'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE nonComplianceForm SET images_uploaded='".$currentImage."' WHERE nonComplianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'noncompliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }elseif($this->path_params[3] == "uploadsketches"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM nonComplianceForm
                                                   WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE nonComplianceForm SET sketch_images='".$currentImage."' WHERE nonComplianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'noncompliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               } else if($this->path_params[3] == "update"){
			
					$sql = "UPDATE `nonComplianceForm` SET
						`ProjectDescription` = '".mysql_real_escape_string($this->path_params[6])."',
						`Title` = '".mysql_real_escape_string($this->path_params[7])."',				
						`DateIssued` = '".mysql_real_escape_string($this->path_params[8])."',
						`ContractorResponsible` = '".mysql_real_escape_string($this->path_params[9])."',
						`To` = '".mysql_real_escape_string($this->path_params[10])."',
						`DateCRTCB` = '".mysql_real_escape_string($this->path_params[11])."',
						`DateContractorStarted` = '".mysql_real_escape_string($this->path_params[12])."',
						`DateContractorCompleted` = '".mysql_real_escape_string($this->path_params[13])."',
						`DateOfDWRReported` = '".mysql_real_escape_string($this->path_params[14])."',
						`DescriptionOfNonCompliance` = '".mysql_real_escape_string($this->path_params[15])."',
						WHERE id = '".mysql_real_escape_string($this->path_params[5])."'";
						echo $sql;exit;
                     $query = mysql_query($sql);
					 if($query){
						 $result = array('status' => "sucess");
						 $this->response($this->json($result), 200);
					 }
			   }
            }
      }
   }
   
   public function quantity_summary(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
		   $result = array();
                  if ($this->path_params[4] != null) {
				  $sqla = "SELECT `No`, `Description`, `P_O_Box`, `Qty`, dailyInspection_item.`date` FROM `dailyInspection_item`
								LEFT JOIN dailyInspectionForm ON dailyInspectionForm.`dailyInspecNo` = dailyInspection_item.`dailyInspecNo`
                                WHERE dailyInspection_item.`No` = '".mysql_real_escape_string($this->path_params[5])."'
								ORDER BY `date` ASC";
                    // echo $sqla;
					 $sql = mysql_query($sqla);
                     if(mysql_num_rows($sql) > 0){
					 
					 $i=0;
						while ($row = mysql_fetch_assoc($sql)) {
                           $result['quantity_summary'][$i]['No']=$row['No'];
						   $result['quantity_summary'][$i]['Description']=$row['Description'];
						   $result['quantity_summary'][$i]['address']=$row['P_O_Box'];
						   $result['quantity_summary'][$i]['Qty']=$row['Qty'];
						   $result['quantity_summary'][$i]['date']=$row['date'];
						   $accum += $row['Qty'];
						   $result['quantity_summary'][$i]['accum'] = "$accum";
						   $i++;
                        }
                        $result['message'] = array('status' => "sucess");
                        $this->response($this->json($result), 200);
                     } else {
                        $result['message'] = array('status' => "failed");                  
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                  }
			   } elseif($this->path_params[3] == "all"){
			   if ($this->path_params[4] != null) {
			//	$sqla = "SELECT `quantityEstimateForm`.*, `printedName`, `Date` FROM `quantityEstimateForm`
			//						INNER JOIN `dailyInspectionForm` ON quantityEstimateForm.project_id = dailyInspectionForm.`Project_id`
             //                              WHERE quantityEstimateForm.`project_id` = '".mysql_real_escape_string($this->path_params[5])."' AND quantityEstimateForm.`user` = '".mysql_real_escape_string($this->path_params[4])."'
			//							    GROUP BY quantityEstimateForm.`id` ORDER BY quantityEstimateForm.`id` DESC";
				
				$sqla = "SELECT * FROM `quantityEstimateForm` WHERE `Project_id` = '".mysql_real_escape_string($this->path_params[5])."'";							
				$sql = mysql_query($sqla);
				//echo $sqla;	 						   
					if(mysql_num_rows($sql) > 0){
					while ($row1 = mysql_fetch_assoc($sql)) {
								$result['all_quantity_summary'][]=$row1;
						   }
							 $result['message'] = array('status' => "sucess");
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                       // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
			   }
				
				} elseif($this->path_params[3] == "single"){
			   if ($this->path_params[4] != null) {
			
				$sqla = "SELECT * FROM `quantityEstimateForm` WHERE `qunatityReportNo` = '".mysql_real_escape_string($this->path_params[5])."'";							
				$sql = mysql_query($sqla);
				//echo $sqla;	 						   
					if(mysql_num_rows($sql) > 0){
					while ($row1 = mysql_fetch_assoc($sql)) {
								$result['all_quantity_summary'][]=$row1;
						   }
							 $result['message'] = array('status' => "sucess");
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                       // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
			   }
				
				  
                 
				  
               } elseif($this->path_params[3] == "report"){
					  if ($this->path_params[4] != null) {
					  $sqlb = "SELECT `project`, `item_no`, `est_qty`, `unit`, `unit_price`, `Description` FROM `quantityEstimateForm`
									LEFT JOIN `dailyInspection_item` ON dailyInspection_item.`No` = quantityEstimateForm.`item_no`
                                           WHERE quantityEstimateForm.`id` = '".mysql_real_escape_string($this->path_params[5])."' GROUP BY quantity_summary_details.`id`";
                     
					 $sql = mysql_query($sqlb);
					 	
					  if(mysql_num_rows($sql) > 0){
					  
						 while ($row1 = mysql_fetch_assoc($sql)) {
                           $result['quantity_summary']= $row1;
                       
						
					   }
					  
                        $result[] = array('status' => "sucess");
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                       // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
					  }
			   }  elseif($this->path_params[3] == "itemByINID"){
					if ($this->path_params[4] != null) {
					 $sqla = "SELECT * FROM `dailyInspection_item`
                                WHERE `inspectionID` = '".mysql_real_escape_string($this->path_params[5])."'
								ORDER BY `date` ASC";
                     
					 $sql = mysql_query($sqla);
                     if(mysql_num_rows($sql) > 0){
					 while ($row = mysql_fetch_assoc($sql)) {
                           $result['quantity_items'][$i]['No']=$row['No'];
						   $result['quantity_items'][$i]['Description']=$row['Description'];
						   $result['quantity_items'][$i]['Qty']=$row['Qty'];
						   $result['quantity_items'][$i]['date']=$row['date'];
						   $accum += $row['Qty'];
						   $result['quantity_items'][$i]['accum'] = "$accum";
						   $i++;
                        }
                        $result['message'] = array('status' => "sucess");
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");                  
                        $this->response($this->json($result), 200);
                     }
					 }
			   }
			   }
            
      } elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
		
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "save") {
                
					$query = mysql_query("INSERT INTO `quantity_summary_details`(`project_id`, `project`, `item_no`, `est_qty`, `unit`, `unit_price`,`user`)
									VALUES (
										  '".mysql_real_escape_string($this->path_params[4])."',
										  '".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
                                          '".mysql_real_escape_string($this->path_params[7])."',
                                          '".mysql_real_escape_string($this->path_params[8])."',
                                          '".mysql_real_escape_string($this->path_params[9])."',
										  '".mysql_real_escape_string($this->path_params[10])."'
                                          )");
		/* 	$quantity_sum_details_no = mysql_insert_id();		
			if($this->path_params[9]){
			$pram9 = explode("--", $this->path_params[9]);
			for($i=0; $i<count($pram9); $i++){
				if(!empty($pram9[$i])){
					$values = explode("-,-", $pram9[$i]);
				
							$query = mysql_query("INSERT INTO `quantity_summary_items`(`item_no`, `quantity_sum_details_no`, `date`, `location_station`, `daily`, `accum`)
									VALUES (
										  '".$values[0]."',
										  '".$quantity_sum_details_no."',
                                          '".$values[1]."',
                                          '".$values[2]."',
                                          '".$values[3]."',
                                          '".$values[4]."'
                                          )");
					
				}
				
				}
			} */					
				   if($query){
                        $result = array('status' => "sucess");
                     }else{
                        $result = array('status' => "Faild");
                     }
					 $this->response($this->json($result), 200);
				  }
				  
				  }
				  }
	  }
   
   
   
   public function summary1(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `SummarySheet`
                                         WHERE `project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['SummarySheet'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif ($this->path_params[3] == "pmlist") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `SummarySheet`
                                        INNER JOIN projects
                                        ON projects.`projecct_id` = summarySheet1.project_id 
                                           WHERE projects.`project_manager` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['SummarySheet'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif($this->path_params[3] == "single"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `SummarySheet`
                                           
                                WHERE SummarySheet.summarySheetNo = '".mysql_real_escape_string($this->path_params[4])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           //$row = mysql_fetch_assoc($sql);
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['expenseReport'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details summarySheetNo
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
                     }
               }elseif($this->path_params[3] == "pmsingle"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `SummarySheet`
                                           INNER JOIN summarySheet2
                                           ON summarySheet2.`SMSSheetNo` = summarySheet1.summarySheetNo
                                           INNER JOIN summarySheet3
                                           ON summarySheet3.`summarySheetNo` = summarySheet1.summarySheetNo
                                           WHERE summarySheet1.summarySheetNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           //$row = mysql_fetch_assoc($sql);
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['expenseReport'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
                     }
               }
            }
      }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  $sql = mysql_query("SELECT summarySheetNo FROM SummarySheet 
                                          ORDER BY id DESC LIMIT 1", $this->db);
                  
                  $user = mysql_fetch_assoc($sql);
                  $lastID = $user['summarySheetNo'];
                  $lastID = str_replace('SM', '', $lastID);
                  $newID = $lastID + 1;
                  $newID = 'SM'.$newID;
                  
                  
                  
                     $user = mysql_fetch_assoc($sql);
                            
                     $query = mysql_query("INSERT INTO `summarySheet1`(`summarySheetNo`, `Project_id`, `SSHeader`, `Contractor`, `POBox`, `City`, `State`, `zip`,
                                          `TelephoneNo`, `Date`, `ReportNo`, `ConPeWork`, `FederalAidNumber`, `ProjectNo`, `Description`, `ConstructionOrder`,
                                          `LAClass1`, `LANo1`, `LATotalHours1`, `LARate1`, `LAAmount1`, `LAClass2`, `LANo2`, `LATotalHours2`,
                                          `LARate2`, `LAAmount2`, `LAClass3`, `LANo3`, `LATotalHours3`, `LARate3`, `LAAmount3`, `LAClass4`, `LANo4`,
                                          `LATotalHours4`, `LARate4`, `LAAmount4`, `LAClass5`, `LANo5`, `LATotalHours5`, `LARate5`, `LAAmount5`, `TotalLabor`,
                                          `HealWelAndPension`, `InsAndTaxesOnItem1`, `itemDescount20per`, `total`, `printedName`)
                                          VALUES ('".$newID."','".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
                                          '".mysql_real_escape_string($this->path_params[7])."',
                                          '".mysql_real_escape_string($this->path_params[8])."','".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."','".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."','".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."','".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."','".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."','".mysql_real_escape_string($this->path_params[20])."',
                                          '".mysql_real_escape_string($this->path_params[21])."',
                                          '".mysql_real_escape_string($this->path_params[22])."','".mysql_real_escape_string($this->path_params[23])."',
                                          '".mysql_real_escape_string($this->path_params[24])."','".mysql_real_escape_string($this->path_params[25])."',
                                          '".mysql_real_escape_string($this->path_params[26])."','".mysql_real_escape_string($this->path_params[27])."',
                                          '".mysql_real_escape_string($this->path_params[28])."','".mysql_real_escape_string($this->path_params[29])."',
                                          '".mysql_real_escape_string($this->path_params[30])."',
                                          '".mysql_real_escape_string($this->path_params[31])."','".mysql_real_escape_string($this->path_params[32])."',
                                          '".mysql_real_escape_string($this->path_params[33])."','".mysql_real_escape_string($this->path_params[34])."',
                                          '".mysql_real_escape_string($this->path_params[35])."','".mysql_real_escape_string($this->path_params[36])."',
                                          '".mysql_real_escape_string($this->path_params[37])."','".mysql_real_escape_string($this->path_params[38])."',
                                          '".mysql_real_escape_string($this->path_params[39])."',
                                          '".mysql_real_escape_string($this->path_params[40])."','".mysql_real_escape_string($this->path_params[41])."',
                                          '".mysql_real_escape_string($this->path_params[42])."','".mysql_real_escape_string($this->path_params[43])."',
                                          '".mysql_real_escape_string($this->path_params[44])."','".mysql_real_escape_string($this->path_params[45])."',
                                          '".mysql_real_escape_string($this->path_params[46])."','".mysql_real_escape_string($this->path_params[47])."',
                                          '".mysql_real_escape_string($this->path_params[48])."',
										   '".mysql_real_escape_string($this->path_params[49])."',
                                          '".mysql_real_escape_string($this->path_params[50])."')");
                     
                     
                     
                     if($query){
                        $result = array('status' => "sucess",'id' => $newID, 'fr' => "");
                     }else{
                        $result = array('status' => "Faild" , 'err' => "hh");
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  
               }elseif($this->path_params[3] == "uploadimages"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT images_uploaded FROM expenseReport
                                                   WHERE eXReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['images_uploaded'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE expenseReport SET images_uploaded='".$currentImage."' WHERE eXReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'expense/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }elseif($this->path_params[3] == "uploadsketches"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM nonComplianceForm
                                                   WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE nonComplianceForm SET sketch_images='".$currentImage."' WHERE nonComplianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'noncompliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }
            }
      }
   }
   
   public function summary2(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `summarySheet2`
                                        INNER JOIN assign_project
                                        ON assign_project.`projectid` = summarySheet2.project_id 
                                           WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['summarySheet2'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif($this->path_params[3] == "single"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `summarySheet2` WHERE SMSSheetNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           //$row = mysql_fetch_assoc($sql);
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['expenseReport'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
                     }
               }elseif($this->path_params[3] == "get"){
                     if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "all") {
                           
                                //echo $this->path_params[4];
                                $sql = mysql_query("SELECT * FROM users ", $this->db);
                                if(mysql_num_rows($sql) > 0){
                                    
                                    while ($user = mysql_fetch_assoc($sql)) {
                                       $result['users'][] = $user;
                                    }
                                    
                                    $result['message'][] = array('status' => 'sucess');
                                    // If success everythig is good send header as "OK" and user details
                                    $this->response($this->json($result), 200);
                                    
                                }
                                $result = array('status' => "not found");
                                $this->response($this->json($result), 200);
                           
                        }
                     }
               }
            }
      }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  
                     //$user = mysql_fetch_assoc($sql);
                            
                     $query = mysql_query("INSERT INTO `summarySheet2`(`SMSSheetNo`, `Project_id`, `MEDescription1`, `MEQuantity1`, `MEUnitPrice1`, `MEAmount1`,
                                          `MEDescription2`, `MEQuantity2`, `MEUnitPrice2`, `MEAmount2`, `MEDescription3`, `MEQuantity3`, `MEUnitPrice3`,
                                          `MEAmount3`, `MEDescription4`, `MEQuantity4`, `MEUnitPrice4`, `MEAmount4`, `MEDescription5`, `MEQuantity5`,
                                          `MEUnitPrice5`, `MEAmount5`, `Total1`, `LessDiscount`, `Total2`, `AdditionalDiscount`, `Total3`)
                                          VALUES ('".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
                                          '".mysql_real_escape_string($this->path_params[7])."',
                                          '".mysql_real_escape_string($this->path_params[8])."','".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."','".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."','".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."','".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."','".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."','".mysql_real_escape_string($this->path_params[20])."',
                                          '".mysql_real_escape_string($this->path_params[21])."',
                                          '".mysql_real_escape_string($this->path_params[22])."','".mysql_real_escape_string($this->path_params[23])."',
                                          '".mysql_real_escape_string($this->path_params[24])."','".mysql_real_escape_string($this->path_params[25])."',
                                          '".mysql_real_escape_string($this->path_params[26])."','".mysql_real_escape_string($this->path_params[27])."',
                                          '".mysql_real_escape_string($this->path_params[28])."','".mysql_real_escape_string($this->path_params[29])."',
                                          '".mysql_real_escape_string($this->path_params[30])."',
                                          '".mysql_real_escape_string($this->path_params[31])."')");
                     
                     
                     
                     if($query){
                        $result = array('status' => "sucess");
                     }else{
                        $result = array('status' => "Faild" );
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  
               }elseif($this->path_params[3] == "uploadimages"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT images_uploaded FROM expenseReport
                                                   WHERE eXReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['images_uploaded'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE expenseReport SET images_uploaded='".$currentImage."' WHERE eXReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'expense/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }elseif($this->path_params[3] == "uploadsketches"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM nonComplianceForm
                                                   WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE nonComplianceForm SET sketch_images='".$currentImage."' WHERE nonComplianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'noncompliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }
            }
      }
   }
   
   public function summary3(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `summarySheet3`
                                        INNER JOIN assign_project
                                        ON assign_project.`projectid` = summarySheet3.project_id 
                                           WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['summarySheet1'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif($this->path_params[3] == "single"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `summarySheet3` WHERE summarySheetNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           //$row = mysql_fetch_assoc($sql);
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['expenseReport'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
                     }
               }elseif($this->path_params[3] == "get"){
                     if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "all") {
                           
                                //echo $this->path_params[4];
                                $sql = mysql_query("SELECT * FROM users ", $this->db);
                                if(mysql_num_rows($sql) > 0){
                                    
                                    while ($user = mysql_fetch_assoc($sql)) {
                                       $result['users'][] = $user;
                                    }
                                    
                                    $result['message'][] = array('status' => 'sucess');
                                    // If success everythig is good send header as "OK" and user details
                                    $this->response($this->json($result), 200);
                                    
                                }
                                $result = array('status' => "not found");
                                $this->response($this->json($result), 200);
                           
                        }
                     }
               }
            }
      }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                 
                  
                            
                     $query = mysql_query("INSERT INTO `summarySheet3`(`summarySheetNo`, `Project_id`, `EQSizeandClass1`, `EQIdleActive1`, `EQNo1`, `EQTotalHours1`,
                                          `EQRAte1`, `EQAmount1`, `EQSizeandClass2`, `EQIdleActive2`, `EQNo2`, `EQTotalHours2`, `EQRAte2`, `EQAmount2`,
                                          `EQSizeandClass3`, `EQIdleActive3`, `EQNo3`, `EQTotalHours3`, `EQRAte3`, `EQAmount3`, `EQSizeandClass4`,
                                          `EQIdleActive4`, `EQNo4`, `EQTotalHours4`, `EQRAte4`, `EQAmount4`, `EQSizeandClass5`, `EQIdleActive5`, `EQNo5`,
                                          `EQTotalHours5`, `EQRAte5`, `EQAmount5`, `Inspector`, `Signature1`, `Date1`, `ContractorRepresentative`,
                                          `Signature2`, `Date2`, `DailyTotal`,`total_to_date`)
                                          VALUES ('".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
                                          '".mysql_real_escape_string($this->path_params[7])."',
                                          '".mysql_real_escape_string($this->path_params[8])."','".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."','".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."','".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."','".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."','".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."','".mysql_real_escape_string($this->path_params[20])."',
                                          '".mysql_real_escape_string($this->path_params[21])."',
                                          '".mysql_real_escape_string($this->path_params[22])."','".mysql_real_escape_string($this->path_params[23])."',
                                          '".mysql_real_escape_string($this->path_params[24])."','".mysql_real_escape_string($this->path_params[25])."',
                                          '".mysql_real_escape_string($this->path_params[26])."','".mysql_real_escape_string($this->path_params[27])."',
                                          '".mysql_real_escape_string($this->path_params[28])."','".mysql_real_escape_string($this->path_params[29])."',
                                          '".mysql_real_escape_string($this->path_params[30])."',
                                          '".mysql_real_escape_string($this->path_params[31])."','".mysql_real_escape_string($this->path_params[32])."',
                                          '".mysql_real_escape_string($this->path_params[33])."','".mysql_real_escape_string($this->path_params[34])."',
                                          '".mysql_real_escape_string($this->path_params[35])."','".mysql_real_escape_string($this->path_params[36])."',
                                          '".mysql_real_escape_string($this->path_params[37])."','".mysql_real_escape_string($this->path_params[38])."',
                                          '".mysql_real_escape_string($this->path_params[39])."',
                                          '".mysql_real_escape_string($this->path_params[40])."','".mysql_real_escape_string($this->path_params[41])."',
                                          '".mysql_real_escape_string($this->path_params[42])."','".mysql_real_escape_string($this->path_params[43])."',
                                          '".mysql_real_escape_string($this->path_params[44])."')");
                     
                     
                     
                     if($query){
                        $result = array('status' => "sucess");
                     }else{
                        $result = array('status' => "Faild");
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  
               }elseif($this->path_params[3] == "uploadimages"){
                     //if ($this->path_params[4] != null) {
                        $uploaddir = 'summery/';
                                          
                        for($i=0; $i < 2;$i++) {
                           $file = basename($_FILES['userfile']['name']);
                           $uploadfile = $uploaddir . $file;
                           if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                              $im_uploaded =  "OK";
                           }else {
                              $im_uploaded =  "ERROR";
                           }
                           $d[$i] = $_FILES['userfile']['name'];
                        //}
                        $result = array('status' => "sucess");
                                        
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                         
                        
                     }
               }elseif($this->path_params[3] == "uploadsignature"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM nonComplianceForm
                                                   WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE nonComplianceForm SET sketch_images='".$currentImage."' WHERE nonComplianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'noncompliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }
            }
      }
   }
   
   public function expense(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `expenseReportModel`
                                        INNER JOIN assign_project
                                        ON assign_project.`projectid` = expenseReportModel.project_id
                                         WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['expenseReportModel'][]=$row;
                        }
                        
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }if ($this->path_params[3] == "expensedata") {
$total =0;
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `expensedata`
                                        WHERE expensedata.`eXReportNo` = '".mysql_real_escape_string($this->path_params[4])."'", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['expensedata'][]=$row;
								$total += $row['ERTotal1'];
							$result['sum'] = array('total' => $total);
                        }
                   
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif($this->path_params[3] == "single"){
                     if ($this->path_params[4] != null) {
                        //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `expenseReportModel` WHERE eXReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                       
						if(mysql_num_rows($sql) > 0){
                           //$row = mysql_fetch_assoc($sql);
                           while ($user = mysql_fetch_assoc($sql)) {
                              $result['expenseReport'] = $user;
                           }
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
                     }
               }elseif($this->path_params[3] == "get"){
                     if ($this->path_params[4] != null) {
                        if ($this->path_params[4] == "id") {
                           //echo $this->path_params[4];
                           $sql = mysql_query("SELECT eXReportNo FROM expenseReport WHERE Project_id = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                           if(mysql_num_rows($sql) > 0){
                              while ($user = mysql_fetch_assoc($sql)) {
                                 $expence_id = $user['eXReportNo'];
                              }
                                    
                              $result = array('status' => 'sucess', 'id' => $expence_id);
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                                    
                           }
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                           
                        }
                     }
               }
            }
      }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  $sql = mysql_query("SELECT eXReportNo FROM expenseReport WHERE eXReportNo = '".mysql_real_escape_string($this->path_params[4])."'", $this->db);
                  if(mysql_num_rows($sql) >0){
                     $user = mysql_fetch_assoc($sql);
                     $newID = $user['eXReportNo'];
                  }else{
                     $sql = mysql_query("SELECT eXReportNo FROM expenseReport ORDER BY id DESC LIMIT 1", $this->db);
                     $user = mysql_fetch_assoc($sql);
                     $lastID = $user['eXReportNo'];
                     $lastID = str_replace('EX', '', $lastID);
                     $newID = $lastID + 1;
                     $newID = 'EX'.$newID;
                     
                     $query = mysql_query("INSERT INTO `expenseReport`(`eXReportNo`, `Project_id`, `ERFHeader`) 
                                          VALUES ('".$newID."','".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."')");
                  }
                  
                  
                  
                  
                     //$user = mysql_fetch_assoc($sql);
                            
                     $query = mysql_query("INSERT INTO `expensedata`(
                                          `eXReportNo`, `ERDate1`, `ERDescription1`, `ERJobNo1`, `ERType1`, `ERPAMilage1`,
                                          `ERPARate1`, `ERTotal1`) 
                                          VALUES ('".$newID."','".mysql_real_escape_string($this->path_params[7])."',
                                          '".mysql_real_escape_string($this->path_params[8])."',
                                          '".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."','".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."','".mysql_real_escape_string($this->path_params[13])."')");
                     if($query){
                        $used_id = mysql_insert_id();
                        $result = array('status' => "sucess",'exp_no' => $newID, 'record_id' => $used_id);
                     }else{
                        $result = array('status' => "Faild" , 'err' => $lastID);
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  
               }if($this->path_params[3] == "update"){
                     //$user = mysql_fetch_assoc($sql);
                            
                     $query = mysql_query("UPDATE `expenseReport` SET
                                          `ERFHeader`='".mysql_real_escape_string($this->path_params[5])."',
                                          `ERCashAdvance`='".mysql_real_escape_string($this->path_params[6])."',
                                          `ERReimbursement`='".mysql_real_escape_string($this->path_params[7])."',
                                          `EMPName`='".mysql_real_escape_string($this->path_params[8])."',
                                          `WeekEnding`='".mysql_real_escape_string($this->path_params[9])."',
                                          `Signature`='".mysql_real_escape_string($this->path_params[10])."',
                                          `EmployeeNo`='".mysql_real_escape_string($this->path_params[11])."',
                                          `ApprovedBy`='".mysql_real_escape_string($this->path_params[12])."',
                                          `Date`='".mysql_real_escape_string($this->path_params[13])."',
                                          `Attachment`='".mysql_real_escape_string($this->path_params[14])."',
                                          `CheckNo`='".mysql_real_escape_string($this->path_params[15])."'
                                          WHERE eXReportNo = '".mysql_real_escape_string($this->path_params[4])."'");
                     
                     $uploaddir = 'expense/';
                                          
                     for($i=0; $i < 2;$i++) {
                        $file = basename($_FILES['userfile']['name']);
                        $uploadfile = $uploaddir . $file;
                        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                           $im_uploaded =  "OK";
                        }else {
                           $im_uploaded =  "ERROR";
                        }
                        $d[$i] = $_FILES['userfile']['name'];
                     }
                     
                     if($query){
                        $result = array('status' => "sucess");
                     }else{
                        $result = array('status' => "Faild");
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  
               }elseif($this->path_params[3] == "uploadimages"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT * FROM `expensedata` WHERE `id` = ".mysql_real_escape_string($this->path_params[5])."", $this->db);
                              $row = mysql_fetch_array($sql);
                              $currentImage = $row['images_uploaded'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE expensedata SET images_uploaded='".$currentImage."'
                                                   WHERE id=".mysql_real_escape_string($this->path_params[5])."");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'expense/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }elseif($this->path_params[3] == "uploadsketches"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM nonComplianceForm
                                                   WHERE nonComplianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE nonComplianceForm SET sketch_images='".$currentImage."' WHERE nonComplianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'noncompliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }
            }
      }
   }
   
   public function compliance(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "list") {
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `complianceForm` INNER JOIN assign_project ON assign_project.`projectid` = complianceForm.project_id 
                                           WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['complianceForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "SELECT * FROM `complianceForm` INNER JOIN assign_project ON assign_project.`projectid` = complianceForm.project_id 
                                           WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }elseif($this->path_params[3] == "single"){
                  //if ($this->path_params[4] == "check") {
                        $sql = mysql_query("SELECT * FROM `complianceForm`
								LEFT JOIN `projects` ON `complianceForm`.Project_id = `projects`.projecct_id
								WHERE complianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           $row = mysql_fetch_assoc($sql);
                           //while ($user = mysql_fetch_assoc($sql)) {
                              $result['complianceReportNo'] = $row;
                           //}
                           $result[] = array('status' => "sucess");
                           // If success everythig is good send header as "OK" and user details
                           $this->response($this->json($result), 200);
                                    
                        }else{
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                        //}
               }elseif($this->path_params[3] == "pmlist"){
                  if ($this->path_params[4] != null) {
                     $sql = mysql_query("SELECT * FROM `complianceForm` INNER JOIN projects ON projects.`projecct_id` = complianceForm.project_id 
                                           WHERE projects.`project_manager` = '".mysql_real_escape_string($this->path_params[4])."' AND
                                           `Project_id` = '".mysql_real_escape_string($this->path_params[5])."' ", $this->db);
                     if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result['complianceForm'][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "Failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
                     $this->response('', 204);
                        
                        
                  }
               }
            }
        }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  $sql = mysql_query("SELECT complianceReportNo FROM complianceForm 
                                          ORDER BY id DESC LIMIT 1", $this->db);
                  
                  $user = mysql_fetch_assoc($sql);
                  $lastID = $user['complianceReportNo'];
                  $lastID = str_replace('CN', '', $lastID);
                  $newID = $lastID + 1;
                  $newID = 'CN'.$newID;
                  
                  
                     //$date = date("Y-m-d");
                     
                            $sqla = "INSERT INTO `complianceForm`(`comHeader`, `ContractNo`, `complianceReportNo`, `ProjectDescription`, `Title`,
                                          `Project`, `DateIssued`, `ContractorResponsible`, `To`, `DateContractorStarted`, `DateContractorCompleted`,
                                          `DateOfDWRReported`, `UserID`, `CorrectiveActionCompliance`, `Signature`, `PrintedName`, `Date`,`Project_id`)
                                          VALUES ('".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[6])."',
                                          '".$newID."',
                                          '".mysql_real_escape_string($this->path_params[8])."',
                                          '".mysql_real_escape_string($this->path_params[9])."',
                                          '".mysql_real_escape_string($this->path_params[10])."',
                                          '".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."',
                                          '".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."',
                                          '".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."',
                                          '".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."',
                                          '".mysql_real_escape_string($this->path_params[20])."',
                                          now(),
                                          '".mysql_real_escape_string($this->path_params[21])."')";
                     $query = mysql_query($sqla);
					//echo $sqla;
                     $uploaddir = 'compliance/';
                                          
                     for($i=0; $i < 2;$i++) {
                        $file = basename($_FILES['userfile']['name']);
                        $uploadfile = $uploaddir . $file;
                        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                           $im_uploaded =  "OK";
                        }else {
                           $im_uploaded =  "ERROR";
                        }
                        $d[$i] = $_FILES['userfile']['name'];
                     }
                            
                     
                     
                     if($query){
                        $result = array('status' => "sucess",'id' => $newID);
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result = array('status' => "Already exits");
                        $this->response($this->json($result), 200);
                     }
               }elseif($this->path_params[3] == "uploadimages"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT images_uploaded FROM complianceForm
                                                   WHERE complianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['images_uploaded'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE complianceForm SET images_uploaded='".$currentImage."' 
                                                   WHERE complianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'compliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }elseif($this->path_params[3] == "uploadsketches"){
                     if ($this->path_params[4] != null) {
                       
                           if($this->path_params[5] != null){
                              $sql = mysql_query("SELECT sketch_images FROM complianceForm
                                                   WHERE complianceReportNo = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                              $row = mysql_fetch_assoc($sql);
                              $currentImage = $row['sketch_images'].','.mysql_real_escape_string($this->path_params[6]);
                              
                              $query = mysql_query("UPDATE complianceForm SET sketch_images='".$currentImage."' 
                                                   WHERE complianceReportNo='".mysql_real_escape_string($this->path_params[5])."'");
                              //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                                    
                              if($query){
                                 $uploaddir = 'compliance/';
                                          
                                 for($i=0; $i < 2;$i++) {
                                    $file = basename($_FILES['userfile']['name']);
                                    $uploadfile = $uploaddir . $file;
                                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                                       $im_uploaded =  "OK";
                                    }else {
                                       $im_uploaded =  "ERROR";
                                    }
                                    $d[$i] = $_FILES['userfile']['name'];
                                 }
                                 $result = array('status' => "sucess");
                                        
                                 // If success everythig is good send header as "OK" and user details
                                 $this->response($this->json($result), 200);
                              }else{
                                 $result = array('status' => "Faild");
                                 $this->response($this->json($result), 200);
                              }
                              
                           }
                        
                     }
               }
            }
      }
   }
   
   public function project(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	       if ($this->path_params[3] == "get") {
                  if ($this->path_params[4] != null) {
                     if ($this->path_params[4] == "list") {
                        if($this->path_params[5] != null){
                           //echo $this->path_params[4];
                           $sql = mysql_query("SELECT projects.* FROM `projects` INNER JOIN assign_project ON assign_project.`projectid` = projects.projecct_id
                                                 WHERE assign_project.`username` = '".mysql_real_escape_string($this->path_params[5])."' AND projects.status = 0", $this->db);
                           if(mysql_num_rows($sql) > 0){
                              
                              while ($row = mysql_fetch_assoc($sql)) {
                                $result['project'][]=$row;
                              }
                              
                              $result['message'] = array('status' => "sucess");
                                    
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                           }else{
                              $result['message'] = array('status' => "failed");
                                    
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                           }
                           $this->response('', 204);
                        }
                     } elseif ($this->path_params[4] == "edit") {
						if($this->path_params[5] != null){
							$sql = mysql_query("SELECT projects.* FROM `projects`
                                                 WHERE projects.`projecct_id` = '".mysql_real_escape_string($this->path_params[5])."' AND projects.status = 0", $this->db);
						
							if(mysql_num_rows($sql) > 0){
                              
                              while ($row = mysql_fetch_assoc($sql)) {
                                $result['project'][]=$row;
                              }
                              
                              $result['message'] = array('status' => "sucess");
                                    
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                           }else{
                              $result['message'] = array('status' => "failed");
                                    
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                           }
						}
					 
					 } 
                  }
               }elseif($this->path_params[3] == "forgot"){
                  if ($this->path_params[4] != null) {
                     if ($this->path_params[4] == "check") {
                        if ($this->path_params[5] != null) {
                           //echo $this->path_params[4];
                           $sql = mysql_query("SELECT password,email FROM users WHERE username = '".mysql_real_escape_string($this->path_params[5])."'", $this->db);
                           if(mysql_num_rows($sql) > 0){
                              $row = mysql_fetch_assoc($sql);
                              //send email
                              $email = $row['email'] ;
                              $subject = "PrimeConst | Forgot Password | ".mysql_real_escape_string($this->path_params[5]) ;
                              $message = "Your password is ".$row['password'] ;
                              mail($email, $subject,$message, "From:sranjana@3sg.com"); 
                                    
                              $result = array('status' => "sucess");
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                                    
                           }
                           $result = array('status' => "not found");
                           $this->response($this->json($result), 200);
                        }
                     }
                  }
               }elseif($this->path_params[3] == "pmget"){
                  if ($this->path_params[4] != null) {
                     if ($this->path_params[4] == "pmlist") {
                        //echo $this->path_params[4];
                        $sql = mysql_query("SELECT projects.* FROM `projects` 
                                                 WHERE `project_manager` = '".mysql_real_escape_string($this->path_params[5])."' AND projects.status = 0 ", $this->db);
                        if(mysql_num_rows($sql) > 0){
                           while ($row = mysql_fetch_assoc($sql)) {
                                $result['project'][]=$row;
                              }
                              
                              $result['message'] = array('status' => "sucess");
                                    
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                                    
                        }
                        $result = array('status' => "not found");
                        $this->response($this->json($result), 200);
                           
                     }
                  }
               }
            }
        }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){
            if ($this->path_params[3] != null) {
               if($this->path_params[3] == "create"){
                  $sql = mysql_query("SELECT projecct_id FROM projects
                                          WHERE projecct_id = '".mysql_real_escape_string($this->path_params[5])."' AND projects.status = 0", $this->db);
                  if(mysql_num_rows($sql) == 0){
                     $date = date("Y-m-d");
                     $user = mysql_fetch_assoc($sql);
                     
                     $query = mysql_query("INSERT INTO `projects`(`projecct_id`, `contract_no`, `p_name`, `p_description`, `p_title`, `address`, `street`,
                                          `city`, `state`, `zip`, `phone`, `p_date`, `client_name`, `project_manager`, `p_latitude`, `p_longitude`, `created_date`,`inspecter`)
                                          VALUES ('".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[5])."',
                                          '".mysql_real_escape_string($this->path_params[7])."',
                                          '".mysql_real_escape_string($this->path_params[8])."',
                                          '".mysql_real_escape_string($this->path_params[9])."',
                                          'Add',
                                          '".mysql_real_escape_string($this->path_params[10])."',
                                          '".mysql_real_escape_string($this->path_params[11])."',
                                          '".mysql_real_escape_string($this->path_params[12])."',
                                          '".mysql_real_escape_string($this->path_params[13])."',
                                          '".mysql_real_escape_string($this->path_params[14])."',
                                          '".mysql_real_escape_string($this->path_params[15])."',
                                          '".mysql_real_escape_string($this->path_params[16])."',
                                          '".mysql_real_escape_string($this->path_params[17])."',
                                          '".mysql_real_escape_string($this->path_params[18])."',
                                          '".mysql_real_escape_string($this->path_params[19])."',
                                          now(),
                                          'Art,".mysql_real_escape_string($this->path_params[20])."')");
                     
                     $users_list = explode(',', 'Art,'.$this->path_params[20]);
                     $usersCount = count($users_list);
                     
                     for($i=0;$i<$usersCount;$i++){
                        $sql = mysql_query("INSERT INTO `assign_project`(`username`, `projectid`, `assign_date`) VALUES
                                     ('".$users_list[$i]."',
                                     '".mysql_real_escape_string($this->path_params[5])."',
                                     now())");
                     }
                     
                     
                     if($query){
                        $result = array('status' => "sucess");
                     }else{
                        $result = array('status' => "failed");
                     }
                     
                                
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                            
                  }else{
                     $result = array('status' => "Already exits");
                     $this->response($this->json($result), 200);
                  }
               }elseif($this->path_params[3] == "update"){
                     if ($this->path_params[4] != null) {
                        $sql = mysql_query("SELECT projecct_id FROM projects
                                                   WHERE projecct_id = '".mysql_real_escape_string($this->path_params[5])."' AND projects.status = 0", $this->db);
                        if(mysql_num_rows($sql) != 0){
                           $query = mysql_query("UPDATE `projects` SET
                                                   `p_name`='".mysql_real_escape_string($this->path_params[6])."',
                                                   `p_description`='".mysql_real_escape_string($this->path_params[7])."',
                                                   `p_title`='".mysql_real_escape_string($this->path_params[8])."',
                                                   `address`='".mysql_real_escape_string($this->path_params[9])."',
                                                   `street`='".mysql_real_escape_string($this->path_params[10])."',
                                                   `city`='".mysql_real_escape_string($this->path_params[11])."',
                                                   `state`='".mysql_real_escape_string($this->path_params[12])."',
                                                   `zip`='".mysql_real_escape_string($this->path_params[13])."',
                                                   `phone`='".mysql_real_escape_string($this->path_params[14])."',
                                                   `p_date`='".mysql_real_escape_string($this->path_params[15])."',
                                                   `client_name`='".mysql_real_escape_string($this->path_params[16])."',
                                                   `project_manager`='".mysql_real_escape_string($this->path_params[17])."',
                                                   `p_latitude`='".mysql_real_escape_string($this->path_params[18])."',
                                                   `p_longitude`='".mysql_real_escape_string($this->path_params[19])."'
                                                   WHERE projecct_id='".mysql_real_escape_string($this->path_params[5])."'");
                           //$sql = mysql_query("SELECT * FROM loginfo WHERE user_name = 'ranjana'");
                           
                                    
                           if($query){
                              $result = array('status' => "sucess");
                                        
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                           }else{
                              $result = array('status' => "Faild");
                              $this->response($this->json($result), 200);
                           }
                        }else{
                           $result = array('status' => "Faild");
                           $this->response($this->json($result), 200);
                        }
                     }
               }elseif($this->path_params[3] == "assign"){
                  $sql = mysql_query("INSERT INTO `assign_project`(`username`, `projectid`, `assign_date`) VALUES
                                     ('".mysql_real_escape_string($this->path_params[5])."',
                                     '".mysql_real_escape_string($this->path_params[6])."',
                                     '".mysql_real_escape_string($this->path_params[7])."')");
                  
                  if($sql){
                     $result['message'][] = array('status' => 'sucess');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }else{
                     $result['message'][] = array('status' => 'failed');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }
                  
               }elseif($this->path_params[3] == "delete"){
                 // $sql_del = mysql_query("DELETE FROM `projects` WHERE `projecct_id`='".mysql_real_escape_string($this->path_params[5])."'");
				  
                  $sql_del = mysql_query("UPDATE `projects` SET status = 1 WHERE `projecct_id`='".mysql_real_escape_string($this->path_params[4])."'");
				  
              /*     $sql = mysql_query("SELECT * FROM assign_project WHERE `projectid`='".mysql_real_escape_string($this->path_params[5])."' AND 
                                     `username`='".mysql_real_escape_string($this->path_params[4])."' ", $this->db);
                  if(mysql_num_rows($sql) > 0){
                     while ($user = mysql_fetch_array($sql)) {
                        $sql = mysql_query("DELETE FROM `assign_project` WHERE `projecct_id`='".$user['id']."' ");
                     }
                  } */
                  
                  if($sql_del){
                     $result['message'][] = array('status' => 'sucess');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }else{
                     $result['message'][] = array('status' => 'failed');
                     // If success everythig is good send header as "OK" and user details
                     $this->response($this->json($result), 200);
                  }
                  
               }
            }
      }
   }
    
    	 public function reimp(){
         if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
	      
                           //echo $this->path_params[4];
                           $sql = mysql_query("SELECT SUM(ERTotal1) AS total FROM `expensedata` WHERE `eXReportNo` = '".mysql_real_escape_string($this->path_params[3])."' ", $this->db);
                           if(mysql_num_rows($sql) > 0){
                              
                              while ($row = mysql_fetch_assoc($sql)) {
                                $result=$row;
                              }
                              
                              $result['message'] = array('status' => "sucess");
                                    
                              // If success everythig is good send header as "OK" and user details
                              $this->response($this->json($result), 200);
                } else {
                              $result['message'] = array('status' => "failed");
                              $this->response($this->json($result), 200);
                           }
			} else {
					$result['message'] = array('status' => "Invalid");
				$this->response($this->json($result), 401);
			}
		}
	}
  

	public function sync(){
		 if ($_SERVER['REQUEST_METHOD'] == 'GET'){
            if ($this->path_params[3] != null) {
			$sqla = "SELECT * FROM `".mysql_real_escape_string($this->path_params[3])."`";
	   			$sql = mysql_query("SELECT * FROM `".mysql_real_escape_string($this->path_params[3])."`", $this->db);
			       if(mysql_num_rows($sql) > 0){
                        //$row = mysql_fetch_array($sql);
                        while ($row = mysql_fetch_assoc($sql)) {
                           $result[$this->path_params[3]][]=$row;
                        }
                        $result['message'] = array('status' => "sucess");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }else{
                        $result['message'] = array('status' => "failed");
                                    
                        // If success everythig is good send header as "OK" and user details
                        $this->response($this->json($result), 200);
                     }
		   
	} 
}
}	
       public function syncall(){
		 if ($_SERVER['REQUEST_METHOD'] == 'GET'){
                        $table_array = array("assign_project", "complianceForm", "dailyInspectionForm", "dailyInspection_item", "expensedata", "expenseReport", "nonComplianceForm", "projects"
                                               ,"quantity_summary_details", "quantity_summary_items", "summarySheet1", "summarySheet2", "summarySheet3");

                        foreach ($table_array as $table_item) {
                           $sql = mysql_query("SELECT * FROM `$table_item`", $this->db);
                           if($sql){                          
                              while ($row = mysql_fetch_assoc($sql)) {
                                 $result[$table_item][]=$row;
                              }                             
                           }else{
                              error_log("Error executing SQL query", 0);
                              $result['message'] = array('status' => "failed");
                              break;
                           }                          
                        }
                        $result['message'] = array('status' => "sucess");
                        $this->response($this->json($result), 200);
	          }
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
   

    $api = new API;
    $api->processApi();
    
   
?>