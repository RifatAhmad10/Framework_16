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
