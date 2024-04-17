<?php
/**
* This file contains the AdminHome Class
* 
*/


/**
 * AdminHome is an extended PanelModel Class
 * 
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for an <em><b>ADMINISTRATOR home</b></em> page.  The content generated is intended for 3 panel
 * view layouts. 
 * 
 * 
 * @author gerry.guinane
 * 
 */


class AdminHome extends PanelModel{
    

    
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
        $this->modelType='AdminHome';
        parent::__construct($user,$db,$postArray,$pageTitle,$pageHead,$pageID);
    } 


    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1(){
            $this->panelHead_1='<h3>Web Application Framework</h3>';
    }
    public function setPanelContent_1()
    {
        $this->panelContent_1 = '<p>You are currently logged in as Customer.</p>';
        $menuTable = new MenuTable($this->db);
        $rs = $menuTable->retrieveMenu();
        $this->panelContent_1 .= HelperHTML::generateTABLE($rs);
    }      

    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2()
    {
        $this->panelHead_2 = '<h3>Welcome to your Customer Home Page</h3>';
    }

    /**
     * Set the Panel 2 text content 
     */       
    public function setPanelContent_2()
    {
        $menuTable = new MenuTable($this->db);
        $this->panelContent_2 = '
            <form action="index.php?pageID=home" method="POST">
                <label for="search">Search Dish by ID:</label>
                <input type="text" name="dish_id" id="search" placeholder="Enter Dish ID">
                <input type="submit" value="Search">
            </form>';

        if(isset($_POST["dish_id"]))
        {
            $rs = $menuTable->retrieveMenu($_POST["dish_id"]);
            $this->panelContent_2 .= HelperHTML::generateTABLE($rs);
            $this->panelContent_2 .= '
                <form action="index.php?pageID=home" method="POST">
                    <input type="hidden" name="add_dish_id" value="'.$_POST["dish_id"].'">';

                    if(isset($_POST["chosen_items"]))
                    {
                        foreach($_POST["chosen_items"] as $i)
                        {
                            $this->panelContent_2.='<input type="hidden" name="chosen_items[]" value="'.$i.'">';

                        }
                    }

                    $this->panelContent_2.='<input type="hidden" name="chosen_items[]" value="'.$_POST["dish_id"].'">';
   
                    

            if(isset($_POST["show_all"]))
            {
                $this->panelContent_2 .='<input type="hidden" name="chosen_items[]" value="'.$_POST["dish_id"].'">';
            }

            $this->panelContent_2 .= '<input type="submit" name="add_dish" value="Add">
                </form>';
        }

        if(isset($_POST["add_dish"]))
        {
            // Handle adding the dish here
            var_dump($_POST);

            $this->panelContent_2 .= '<p>Dish with ID '.$_POST["add_dish_id"].' added successfully!</p>';
            // array_push($_POST["chosen_items"], $_POST["add_dish_id"]);
        }

        if(isset($_POST["show_all"]))
        {

            if(isset($_POST["chosen_items"]))
            {
                $added_dish_ids = $_POST["chosen_items"]; // $this->retrieveAddedDishIDs();
                $this->panelContent_2 .= '<h3>Added Dish IDs:</h3>';
                $this->panelContent_2 .= '<ul>';
                foreach($added_dish_ids as $dish_id)
                {
                    $this->panelContent_2 .= '<li>'.$dish_id.'</li>';
                }
                $this->panelContent_2 .= '</ul>';
            }

        }

        $this->panelContent_2 .= '
            <form action="index.php?pageID=home" method="POST">
                <input type="submit" name="show_all" value="Show All">
            </form>';
    }

    /**
     * Retrieve added dish IDs from the database
     */ 
    private function retrieveAddedDishIDs() 
    {
        // Implement logic to retrieve added dish IDs from the database
        // For demonstration purposes, returning a hardcoded array
        return ['1', '2', '3', '4'];
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
            $this->panelContent_3='<p>To set up this application read the following <a href="readme/installation.php" target=”_blank” >SETUP INSTRUCTIONS</a></p>';            
    }         



     

}
        