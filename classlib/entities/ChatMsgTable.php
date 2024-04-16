<?php
/**
* This file contains the ChatMsgTable Class
* 
*/

/**
 * 
 * ChatMsgTable entity class implements the table entity class for the 'chatmsg' table in the database. 
 * 
 * @author Gerry Guinane
 * 
 */

class ChatMsgTable extends TableEntity {

    /**
     * Constructor for the TableEntity Class
     * 
     * @param MySQLi $databaseConnection  The database connection object. 
     */
    function __construct($databaseConnection){
        parent::__construct($databaseConnection,'chatmsg');  //the name of the table is passed to the parent constructor
    }


    /**
     * Returns a record including message author ID and name
     * 
     * @param string $msgID
     * @return mixed Returns false on failure. For successful SELECT returns a mysqli_result object $rs
     */ 
    public function getRecordByID($msgID){
        $this->SQL="SELECT msgID,msgText,dateTimestamp,msgAuthorID FROM chatmsg WHERE msgID='$msgID'";
        
        //execute the query
        try {
            $rs=$this->db->query($this->SQL);
        } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
            $rs=FALSE;
        }
        
        
        if($rs->num_rows===1){
            return $rs;
        }
        else{
            return false;
        }        
        
    }

    
    
    

     /**
     * Performs a DELETE query for a single record ($msgID).  Verifies the
     * record exists before attempting to delete
     * 
     * @param $msgID  String containing ID of user record to be deleted
     * 
     * @return boolean Returns FALSE on failure. For successful DELETE returns TRUE
     */
    public function deleteRecordbyID($msgID){
        
        if($this->getRecordByID($msgID)){ //confirm the record exists before deletig
            $this->SQL = "DELETE FROM chatmsg WHERE msgID='$msgID'";
            $this->db->query($this->SQL); //delete the record
            
            //execute the query
            try {
                $rs=$this->db->query($this->SQL);
                return TRUE;
            } catch (mysqli_sql_exception $e) { //catch the exception 
                    $this->MySQLiErrorNr=$e->getCode();
                    $this->MySQLiErrorMsg=$e->getMessage();
                return false;
            }
        }
        else{
            return false;
        }       
    }


    /**
     * Performs a SELECT query to returns all records from the table where messages are TO the specified user or ALL users and NOT authored by the specified user 
     *
     * @param string $userID The user's unique ID
     * 
     * @return mixed Returns false on failure. For successful SELECT returns a mysqli_result object $rs
     */
     public function getUserMessages($userID){
        $this->SQL = "SELECT msgID,dateTimeStamp,msgAuthorID,msgTo,msgText FROM chatmsg WHERE (msgTo='$userID' OR msgTo='ALL') AND msgAuthorID<>'$userID'";

        
        //execute the query
        try {
            $rs=$this->db->query($this->SQL);
        } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
            return false;
        }        

        //check the recordset is not empty
        if($rs->num_rows){
            return $rs;
        }
        else{
            return false;
        }        
        
    }   


    

    /**
     * Performs a SELECT query to returns all records from the table where messages are TO the specified user or ALL users. 
     *
     * @param string $userID The user's unique ID
     * @param integer $nrMsgsToGet The required number of messages to retrieve 
     * 
     * @return mixed Returns false on failure. For successful SELECT returns a mysqli_result object $rs
     */
     public function getLatestUserMessages($userID,$nrMsgsToGet){

        //SQL  to select most recent messages ($nrMsgsToGet) to or from the user ($userID) , records are returned in ASCENDING order
        $this->SQL = "SELECT
                    T.SenderID,
                    T.Sent,
                    T.Recipient,
                    T.UserName,
                    T.Message_Content
                FROM 
                 (SELECT 
                    cm.msgID,
                    cm.msgTo AS Recipient,
                    cm.msgAuthorID AS SenderID,
                    CONCAT(u.FirstName,' ',u.LastName) as UserName,
                    cm.dateTimeStamp AS Sent,
                    cm.msgText AS Message_Content
                FROM
                    chatmsg cm,
                    user u
                WHERE
                        cm.msgAuthorID=u.email
                    AND
                    (cm.msgTo = '$userID' OR cm.msgAuthorID='$userID' OR cm.msgTo='ALL')
                ORDER BY msgID DESC
                LIMIT $nrMsgsToGet) AS T
                ORDER BY T.msgID ASC";

        //execute the query
        try {
            $rs=$this->db->query($this->SQL);
        } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
            return false;
        }        

        //check the recordset is not empty
        if($rs->num_rows){
            return $rs;
        }
        else{
            return false;
        }            
        
    }   

    
    
 
    /**
     * Performs a SELECT query to returns all records from the table where messages are TO the specified user or ALL users. 
     *
     * @param string $userID The user's unique ID
     * 
     * @return mixed Returns false on failure. For successful SELECT returns a mysqli_result object $rs
     */
     public function getUserAuthoredMessages($userID){
        $this->SQL = "SELECT msgID,dateTimeStamp,msgAuthorID,msgTo,msgText FROM chatmsg WHERE msgAuthorID='$userID'";
        
        
        //execute the query
        try {
            $rs=$this->db->query($this->SQL);
        } catch (mysqli_sql_exception $e) { //catch the exception 
                $this->MySQLiErrorNr=$e->getCode();
                $this->MySQLiErrorMsg=$e->getMessage();
            return false;
        }        

        //check the recordset is not empty
        if($rs->num_rows){
            return $rs;
        }
        else{
            return false;
        }        
        
    }   

    
    
   
 
    /**
     * Inserts a new record in the table. 
     * 
     * @param array $postArray containing data to be inserted
         * $postArray['firstName'] string FirstName
         * $postArray['lastName'] string LastName
         * $postArray['pass1'] string PassWord
         * $postArray['email'] string email
         * $postArray['mobile'] string mobile
     * 
     * @param String $userID The users unique identifier (email address)
     * @param String $userType The users type - eg ADMIN,CUSTOMER, MANAGER
     * @param String $msgTo The recipients unique identifier (email address) 
     * 
     * @return boolean TRUE if message is added successfully , else FALSE
     * 
     * 
     */   
    public function addRecord($postArray,$userID,$userType,$msgTo){
        
        //get the values entered in the registration form contained in the $postArray argument     
        extract($postArray);
        
        //add escape to special characters
        $message= addslashes($message);
        $msgTo= addslashes($msgTo);
        $msgTo=strtolower($msgTo);
        
        //Note - this function does not validate that the $msgTo user  ID is valid. 
        
        //check if $msgTo is empty if it is - set it to ALL recipients
        if(!$msgTo) {$msgTo='ALL';}
     
        //construct the INSERT SQL
        $this->SQL="INSERT INTO chatmsg (msgText,msgAuthorID,userType,msgTo) VALUES ('$message','$userID','$userType','$msgTo')";  
       
        
        //try to execute the query
        try {
                $rs=$this->db->query($this->SQL);
                if($rs){ //check the insert query worked
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
  
    
   
    
}

