<?php
/**
* This file contains the CustomerHome Class
* 
*/


/**
 * CustomerHome is an extended PanelModel Class
 * 
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for a <em><b>CUSTOMER user home</b></em>  page.  The content generated is intended for 3 panel
 * view layouts. 
 * 
 * @author gerry.guinane
 * 
 */


class CustomerHome extends PanelModel{


    /**
    * Constructor Method
    * 
    * The constructor for the PanelModel class. The ManageSystems class provides the 
    * panel content for up to 3 page panels.
    * 
    * @param User $user  The current user
    * @param MySQLi $db The database connection handle
    * @param Array $postArray Copy of the $_POST array
    * @param String $pageTitle The page Title
    * @param String $pageHead The Page Heading
    * @param String $pageID The currently selected Page ID
    */  
    function __construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID){  
        $this->modelType='CustomerHome';
        parent::__construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID);
    } 



    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1(){
            $this->panelHead_1='<h3>Web Application Framework</h3>';
    }
    
    /**
    * Set the Panel 1 text content 
    */   
   public function setPanelContent_1(){
        $this->panelContent_1='<p>You are currently logged in as Customer.';
        $menuTable = new menuTable($this->db);
        $rs = $menuTable->retrieveMenu();
        $this->panelContent_1.=HelperHTML::generateTABLE($rs);

    }      

    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2(){
        $this->panelHead_2='<h3>Welcome to your Customer Home Page</h3>';
    }

    
     /**
     * Set the Panel 2 text content 
     */       
    /**
 * Set the Panel 3 text content 
 */       
/**
 * Set the Panel 2 text content
 */
/**
 * Set the Panel 2 text content
 */
public function setPanelContent_2()
{
    $menuTable = new menuTable($this->db);

    // Set the Panel 2 content with a search form
    $this->panelContent_2 = '
        <form action="index.php?pageID=home" method="POST">
            <label for="search">Search Dish by ID:</label>
            <input type="text" name="dish_id" id="search" placeholder="Enter Dish ID">
            <input type="submit" value="Search">
        </form>';

    // Check if dish_id is set in the POST request
    if(isset($_POST["dish_id"]))
    {
        // Retrieve menu based on the dish_id
        $rs = $menuTable->retrieveMenu($_POST["dish_id"]);

        // Generate HTML table for search results
        $this->panelContent_2 .= HelperHTML::generateTABLE($rs);

        // Add "Add" button below search results
        $this->panelContent_2 .= '
            <form action="index.php?pageID=home" method="POST">
                <input type="hidden" name="add_dish_id" value="'.$_POST["dish_id"].'">
                <input type="submit" name="add_dish" value="Add">
            </form>';
    }

    // Check if "Add" button is clicked
    if(isset($_POST["add_dish"]))
    {
        // Handle adding the dish here
        // You can implement the logic to save the dish to the database
        // This could involve creating an instance of a DishModel and calling a method to add the dish
        // For demonstration purposes, I'll just display a message
        $this->panelContent_2 .= '<p>Dish with ID '.$_POST["add_dish_id"].' added successfully!</p>';
    }
}




    /**
     * Set the Panel 3 heading 
     */
    public function setPanelHead_3(){ 
        $this->panelHead_3='<h3>Application Setup</h3>';
    } 

     /**
     * Set the Panel 3 text content 
     */       
    public function setPanelContent_3(){ 
             $this->panelContent_3="<p>To ! set up this application read the following <a href='readme/installation.php' target='_blank' >SETUP INSTRUCTIONS</a></p>";   
    }         


     
        
}
        