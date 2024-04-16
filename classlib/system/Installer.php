<?php
/**
* This file contains the Installer Class
* 
*/

/**
 * The Installer class provides the static installation methods. 
 * 
 * This class is responsible for providing the following functions:
 * 
    * MySQL Database installation
 *
 * @author Gerry Guinane 
 * 
 */


class Installer {
  
    
    
/**
 * 
 * Installs the MySQL database for this app
 * 
 * @param string $DBServer String containing address of the MySQL server
 * @param string $DBUser   String containing MySQL user ID 
 * @param string $DBPass   String containing MySQL user password
 * @param string $SQLscript String containing path to SQL script for database creation
 * @return string String containing description of result of database installation. 
 * 
 */    
public static function installDB($DBServer, $DBUser, $DBPass,$SQLscript){

    
    $msg= '<h3>Database installation</h3>';
    

    //initialise connection error flags
    $errorNo=0000; //initialise to error code zero
    $errorMsg='';  //initialise to empty error message

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); //Sets mysqli error reporting mode, Throw mysqli_sql_exception for all errors
    try {
        $dbConn = new mysqli($DBServer, $DBUser, $DBPass);
    } catch (mysqli_sql_exception $e) { //catch the exception for all connection errors
         $errorNo=$e->getCode();
         $errorMsg=$e->getMessage(); 
    }    

    if($dbConn->connect_errno){
        $msg.= '<br>**installation FAIL** ERROR NR:'.$db->connect_errno.'<br>';
        $dbConn->close();
        return $msg;
        }
        else{
            //install the database
            try{
                $sql = @file_get_contents($SQLscript);
                $dbConn->multi_query($sql);
                $msg.= '<br>**Database installation SUCCESSFUL<br>';
                $dbConn->close();
            } catch (Exception $ex) {
                $msg.= '<br><font color="red"><b>Database Installation FAIL</b></font> - unable to access backup SQL file. Possible fixes: <ul><li>Check backup file name is correct in config.php</li><li>Check backup file is located in /database/folder in this project. <li>You could also try to manually restore the database to the MySQL server</li> </ul>';
            }
            
            


            return $msg;
        }    
    }


}



