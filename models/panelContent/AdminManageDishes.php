<?php
/**
 * This file contains the AdminManageDishes Class
 */

/**
 * AdminManageDishes is an extended PanelModel Class
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for managing dishes. The content generated is intended for 3 panel view layouts. 
 * 
 * This class is intended as a TEMPLATE - to be copied and modified to provide
 * specific panel content.  
 *
 * @author gerry.guinane
 * 
 */

class AdminManageDishes extends PanelModel {
  
    /**
    * Constructor Method
    * 
    * The constructor for the AdminManageDishes class. The AdminManageDishes class provides the 
    * panel content for up to 3 page panels.
    * 
    * @param User $user  The current user
    * @param MySQLi $db The database connection handle
    * @param Array $postArray Copy of the $_POST array
    * @param String $pageTitle The page Title
    * @param String $pageHead The Page Heading
    * @param String $pageID The currently selected Page ID
    * 
    */  
    function __construct($user, $db, $postArray, $pageTitle, $pageHead, $pageID) {  
        $this->modelType = 'AdminManageDishes';
        parent::__construct($user, $db, $postArray, $pageTitle, $pageHead, $pageID);
    } 

    
    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1() {
        $this->panelHead_1 = '<h3>Panel 1</h3>'; 
        switch ($this->pageID) {
            case "manageDishes":
                $this->panelHead_1 = '<h3>Manage Dishes</h3>';
                break;
            case "viewDishes":
                $this->panelHead_1 = '<h3>View Dishes</h3>';
                break;
            case "editDishes": 
                $this->panelHead_1 = '<h3>Edit Dishes</h3>';
                break;
            case "addDishes": 
                $this->panelHead_1 = '<h3>Add Dishes</h3>';
                break;
            default:  //DEFAULT menu item handler
                $this->panelHead_1 = '<h3>Manage Dishes</h3>';
                break;
        }
    }

    
    
    /**
    * Set the Panel 1 text content 
    */ 
    public function setPanelContent_1() {
        switch ($this->pageID) {
            case "manageDishes":  // menu item handler
                $this->panelContent_1 = "Panel 1 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "viewDishes":  // menu item handler
                $this->panelContent_1 = "Panel 1 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                 $menuTable = new MenuTable($this->db);
        $rs = $menuTable->retrieveMenu();
        $this->panelContent_2 .= HelperHTML::generateTABLE($rs);
                break;
            case "editDishes":  // menu item handler
                $this->panelContent_1 = "Panel 1 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "addDishes":  // menu item handler
                $this->panelContent_1 = "Panel 1 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            default:  // DEFAULT menu item handler
                $this->panelContent_1 = "Panel 1 content for \$pageID <b>DEFAULT</b> menu item is under construction.";
                break;
        }
    }


    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2() { 
        $this->panelHead_2 = '<h3>Panel 2</h3>'; 
        switch ($this->pageID) {
            case "manageDishes":  // menu item handler
                $this->panelHead_2 = '<h3>Manage Dishes</h3>';
                break;
            case "viewDishes":  // menu item handler
                $this->panelHead_2 = '<h3>View Dishes</h3>';
                break;
            case "editDishes":  // menu item handler
                $this->panelHead_2 = '<h3>Edit Dishes</h3>';
                break;
            case "addDishes":  // menu item handler
                $this->panelHead_2 = '<h3>Add Dishes</h3>';
                break;
            default:  // DEFAULT menu item handler
                $this->panelHead_2 = '<h3>Manage Dishes</h3>';
                break;
        }
    }

    
    
    /**
     * Set the Panel 2 text content 
     */ 
    public function setPanelContent_2() {
        switch ($this->pageID) {
            case "manageProducts":  // menu item handler
                $this->panelContent_2 = "Panel 2 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "viewProducts":  // menu item handler
                $this->panelContent_2 = "Panel 2 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "editProduct":  // menu item handler
                $this->panelContent_2 = "Panel 2 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "addProduct":  // menu item handler
                $this->panelContent_2 = "Panel 2 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            default:  // DEFAULT menu item handler
                $this->panelContent_2 = "Panel 2 content for \$pageID <b>DEFAULT</b> menu item is under construction.";
                break;
        }
    }

    /**
     * Set the Panel 3 heading 
     */
    public function setPanelHead_3() { 
        $this->panelHead_3 = '<h3>Panel 3</h3>'; 
        switch ($this->pageID) {
            case "manageDishes":  // menu item handler
                $this->panelHead_3 = '<h3>Manage Dishes</h3>';
                break;
            case "viewDishes":  // menu item handler
                $this->panelHead_3 = '<h3>View Dishes</h3>';
                break;
            case "editDishes":  // menu item handler
                $this->panelHead_3 = '<h3>Edit Dishes</h3>';
                break;
            case "addDishes":  // menu item handler
                $this->panelHead_3 = '<h3>Add Dishes</h3>';
                break;
            default:  // DEFAULT menu item handler
                $this->panelHead_3 = '<h3>Manage Dishes</h3>';
                break;
        }
    }

    
    
    /**
     * Set the Panel 3 text content 
     */ 
    public function setPanelContent_3() {
        switch ($this->pageID) {
            case "manageProducts":  // menu item handler
                $this->panelContent_3 = "Panel 3 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "viewProducts":  // menu item handler
                $this->panelContent_3 = "Panel 3 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "editProduct":  // menu item handler
                $this->panelContent_3 = "Panel 3 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            case "addProduct":  // menu item handler
                $this->panelContent_3 = "Panel 3 content for \$pageID <b>$this->pageID</b> menu item is under construction.";
                break;
            default:  // DEFAULT menu item handler
                $this->panelContent_3 = "Panel 3 content for \$pageID <b>DEFAULT</b> menu item is under construction.";
                break;
        }
    }
}
