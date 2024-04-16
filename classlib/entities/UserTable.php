<?php
/**
* This file contains the CountyTable Class
* 
*/

/**
 * 
 * ChatMsgTable entity class implements the table entity class for the 'user' table in the database. 
 * 
 * @author Gerry Guinane
 * 
 */

class UserTable extends TableEntity {

    /**
     * Constructor for the UserTable Class
     * 
     * @param MySQLi $databaseConnection  The database connection object. 
     */
    function __construct($databaseConnection){
        parent::__construct($databaseConnection,'user');  //the name of the table is passed to the parent constructor
    }

   

    /**
     * Performs validation of user login credentials
     * 
     * @param string $userID
     * @param string $password
     * @param boolean $encryptPW True if Password is hashed
     * @return boolean Returns TRUE if validation is successful. FALSE for invalid credentials.
     */
    public function validate_login($userID,$password,$encryptPW){  
        
        if($encryptPW){//encrypt the password
        $password = hash('ripemd160', $password);       
        }     
        
        $this->SQL="SELECT * FROM user WHERE userID='$userID' AND PassWord='$password'";


        //execute the query
        try {
                $rs=$this->db->query($this->SQL);
            } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
                return false;
            }
         
            
            //check the credentials
            if ($rs->num_rows===1 && $rs){
                //valid username and password combination entered. 
                //check if the user login is enabled. 
                
                $row=$rs->fetch_assoc();
              
                if($row['userEnabled']){
                    return TRUE;  //user is validate 
                }
                else {
                    return FALSE;  //user login is not permitted, login is disabled
                }
                   
            }
            else{
                return FALSE; //user credentials are not correct
                
            }
        
    }

    
    

    /**
     * Validates and implements password change for specified user.  
     * 
     * @param array $postArray containing data to be inserted
        * $postArray['pass1'] String New Password copy 1 
        * $postArray['pass2'] String New Password copy 2
        * $postArray['email'] String user ID/email address 
        * $postArray['password'] String user old Password
     * @param User $user The current user.
     * 
     * @return boolean TRUE if password is changes, else FALSE
     * 
     * 
     */   
    public function changePassword($postArray,$user){
        
        //get the values entered in the registration form contained in the $postArray argument      
        extract($postArray);
    
        //add escape to special characters      
        $pass1= addslashes($pass1);
        $pass2= addslashes($pass2);
        $password= addslashes($password);
        $userID=$user->getUserID();

        //check old password is valid before changing
        if($this->validate_login($userID, $password, $user->getPWEncrypted())){
                         
            //encrypt the password if required
            if($user->getPWEncrypted()){
                $pass1 = hash('ripemd160', $pass1);       
            }  
            
            //construct the UPDATE SQL 
            $this->SQL="UPDATE user SET PassWord='$pass1' WHERE userID='$userID'";   
            
            //execute the query
            try {
                $rs=$this->db->query($this->SQL);
            } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
                return false;
            }
 
            //check the insert query worked
            if ($this->db->affected_rows===1 && $rs){return TRUE;}else{return FALSE;}
        }
        else{return FALSE;}  //user did not provide valid old password
    }

    

    /**
     * Returns a resultset record (FirstName and LastName only by ID
     * 
     * @param string $userID
     * @return mixed Returns false on failure. For successful SELECT returns a mysqli_result object $rs
     */ 
    public function getRecordByID($userID){ 
        
        //build the SQL Query
        $this->SQL="SELECT u.userID,u.FirstName,u.LastName,u.email,u.mobile,u.idcounty,ut.userTypeNr,ut.userTypeDescr,u.userEnabled ";
        $this->SQL.=" FROM user u,usertype ut";
        $this->SQL.=" WHERE u.userID = '$userID' AND u.userTypeNr = ut.userTypeNr";
        
        try {
                $rs=$this->db->query($this->SQL);
                if($rs->num_rows===1){ //check that only one record is returned
                    return $rs; //return the requested recordset
                }
                else{
                    return false;  //no records found
                }  
        } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
                return false;  //the query failed for some reason
            }        
             
        
    }

    
    
    

     /**
     * Performs a DELETE query for a single record ($userID).  Verifies the
     * record exists before attempting to delete
     * 
     * @param $userID  String containing ID of user record to be deleted
     * 
     * @return boolean Returns FALSE on failure. For successful DELETE returns TRUE
     */
    public function deleteRecordbyID($userID){
        
        if($this->getRecordByID($userID)){ //confirm the record exists before deletig
            
            $this->SQL = "DELETE FROM user WHERE userID='$userID'"; //construct the SQL
            
            //execute the query
            try {
                $rs=$this->db->query($this->SQL);
                return true;
            } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
                return false;
            }
        }
        else{
            return false;  //ID not valid
        }       
    }

   

    /**
     * Performs a SELECT query to returns all records from the table. 
     * ID,Firstname and Lastname columns only.
     *
     * @param $userType  String containing user type to be selected, Default is wildcard
     * 
     * @return mixed Returns false on failure. For successful SELECT returns a mysqli_result object $rs
     */
     public function getAllRecords($userType=0){
        
         //construct the SQL
        if($userType){ //select data for the specified user type
           $this->SQL = "SELECT userID,FirstName,LastName, ut.userTypeDescr AS UserType, userEnabled AS LoginEnabled FROM user u,usertype ut where u.userTypeNr=ut.userTypeNr and u.userTypeNr=$userType ORDER BY userTypeDescr";
        } 
        else{  //select data for all user types
           $this->SQL = "SELECT userID,FirstName,LastName, ut.userTypeDescr AS UserType, userEnabled AS LoginEnabled FROM user u,usertype ut where u.userTypeNr=ut.userTypeNr ORDER BY userTypeDescr";

        }

        //execute the query
        try {
                $rs=$this->db->query($this->SQL);
                if($rs->num_rows){
                    return $rs; //return the recordset
                }
                else{
                    return false;  //no records found
                }  
        } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
                return false;  //the query failed for some reason
            }        
        
   
        
    }   

    /**
     * 
     * Adds a new record to the database table - user.
     * 
     * @param array $postArray Copy of $_POST array containing data to be inserted
     * @param boolean $encryptPW  TRUE if the password will be hashed in the database record
     * @param string $userType The current user type 
     * @return boolean
     */
    public function addRecord($postArray,$encryptPW){
        
        // use extract() toget the values entered in the registration form contained in the $postArray argument
        extract($postArray);

        //add escape to special characters      
        $firstName= addslashes($firstName);//
        $lastName= addslashes($lastName);//
        $email=addslashes($email);//
        $userID=$email; //  default is email for userID
        $mobile=addslashes($mobile); //
        $idCounty=(integer) $idCounty;  //idcounty is an integer value only
        $userTypeNr=(integer) $userTypeNr;  //usertype is integer value only
        $userEnabled=(integer) $userEnabled;  //userEnabled is (boolean/integer) value only     
        
        //check is password encryption is required
        if($encryptPW){//encrypt the password
        $password = hash('ripemd160', $pass1);       
        } 
        
        //construct the INSERT SQL
        $this->SQL="INSERT INTO user (userID,FirstName,LastName,PassWord,email,mobile,idcounty,userTypeNr,userEnabled) VALUES ('$userID','$firstName','$lastName','$password','$email','$mobile',$idCounty,$userTypeNr,1)";   
        
        //execute the insert query
        try {
            $rs=$this->db->query($this->SQL);
        } catch (mysqli_sql_exception $e) { //catch the exception 
            $this->MySQLiErrorNr=$e->getCode();
            $this->MySQLiErrorMsg=$e->getMessage();
            return false;
        }


        //check the insert query worked
        if ($rs){return TRUE;}else{return FALSE;}
              
        
    }
  
    

    /**
     * Updates an existing record by ID. Does not change password or user type.  
     * 
     * @param array $postArray containing data to be inserted
         * $postArray['userID'] string StudentID
         * $postArray['firstName'] string FirstName
         * $postArray['lastName'] string LastName
         * $postArray['mobile'] string mobile
         * $postArray['county'] integer idcounty
         * $postArray['userTypeNr'] integer userTypeNr
         * $postArray['userEnabled'] integer userEnabled
         * @return boolean
     * 
     * 
     */   
    public function updateRecord($postArray){
        
        //get the values entered in the registration form contained in the $postArray argument      
        extract($postArray);
    
        //add escape to special characters      
        $firstName= addslashes($firstName);//
        $lastName= addslashes($lastName);//
        $email=addslashes($email);//
        $userID=$email; // //email by default
        $mobile=addslashes($mobile); //
        $idCounty=(integer) $idCounty;  //idcounty is an integer value only
        $userTypeNr=(integer) $userTypeNr;  //usertype is integer value only
        $userEnabled=(integer) $userEnabled;  //userEnabled is (boolean/integer) value only        
        
                
        //construct the INSERT SQL                    
        $this->SQL="UPDATE user SET FirstName='$firstName',LastName='$lastName',mobile='$mobile',idcounty=$idCounty, userTypeNr=$userTypeNr,userEnabled=$userEnabled,email='$email' WHERE userID='$userID'";   
               
        //execute the query
            try {
                $rs=$this->db->query($this->SQL);
            } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
                return false;
            }
            
            
        //check the insert query worked
            if ($this->db->affected_rows===1 && $rs){return TRUE;}else{return FALSE;}
    }

   
    
}

