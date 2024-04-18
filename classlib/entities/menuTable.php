<?php
/**
* This file contains the ProductsTable Class Template
* 
*/

/**
 * 
 * ProductsTable entity class implements the table entity class for the 'ProductsTable' table in the database. 
 * 
 * 
 * @author Gerry Guinane
 * 
 */

class menuTable extends TableEntity {

    /**
     * Constructor for the ProductsTable Class
     * 
     * @param MySQLi $databaseConnection  The database connection object. 
     */
    function __construct($databaseConnection){
        parent::__construct($databaseConnection,'products');  //the name of the table is passed to the parent constructor
    }

    function addDish($id,$Name,$Type,$Price){
        $this->SQL ="INSERT INTO `restaurantdishes`
        (`DishID`,
        `DishName`,
        `DishType`,
        `Price`)
        VALUES(
        '".$id."',
        '".$Name."',
        '".$Type."',
        '".$Price."');
        ";
    
        try {
        
            $rs=$this->db->query($this->SQL);
            return $rs;
            
            
        } catch (mysqli_sql_exception $e) { //catch the exception 
           
            $this->MySQLiErrorNr=$e->getCode();           
            $this->MySQLiErrorMsg=$e->getMessage();  
            return false;
            
     
        }
    }

    function updateDish($id, $name, $type, $price) {
        // Prepare an update statement
        $this->SQL = "UPDATE `restaurantdishes` SET
            `DishName` = '".mysqli_real_escape_string($this->db, $name)."',
            `DishType` = '".mysqli_real_escape_string($this->db, $type)."',
            `Price` = '".mysqli_real_escape_string($this->db, $price)."'
            WHERE `DishID` = '".mysqli_real_escape_string($this->db, $id)."'";
    
        try {
            $rs = $this->db->query($this->SQL);
            if ($this->db->affected_rows === 0) {
                throw new Exception("No dish found with ID $id, or no data was changed.");
            }
            return true;
        } catch (Exception $e) {
            // Catch any type of exception, including cases where no rows are updated
            $this->MySQLiErrorNr = $e->getCode();
            $this->MySQLiErrorMsg = $e->getMessage();
            return false;
        }
    }
    
    
    
    function retrieveMenu($id=0)
    {
        
        if($id)
        {
            $this->SQL = "SELECT * FROM restaurantdishes where DishID=".$id.";";
        }
        else
        {
            $this->SQL = "SELECT * FROM restaurantdishes ;";

        }
        
                    
        
        try {
        
            $rs=$this->db->query($this->SQL);
            return $rs;
            
            
        } catch (mysqli_sql_exception $e) { //catch the exception 
           
            $this->MySQLiErrorNr=$e->getCode();           
            $this->MySQLiErrorMsg=$e->getMessage();  
            return false;
            
     
        }

        
    }
    //END METHOD: Construct
    
}
