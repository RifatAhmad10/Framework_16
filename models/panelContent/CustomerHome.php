<?php

/**
 * CustomerHome Class
 * 
 * This class generates HTML view panel headings and template content
 * for a CUSTOMER user home page with 3 panel view layouts.
 * 
 * @author gerry.guinane
 */

class CustomerHome extends PanelModel
{
    /**
     * Constructor Method
     * 
     * Initializes the CustomerHome object.
     * 
     * @param User $user         The current user
     * @param MySQLi $db         The database connection handle
     * @param Array $postArray   Copy of the $_POST array
     * @param String $pageTitle  The page Title
     * @param String $pageHead   The Page Heading
     * @param String $pageID     The currently selected Page ID
     */
    public function __construct($user, $db, $postArray, $pageTitle, $pageHead, $pageID)
    {
        $this->modelType = 'CustomerHome';
        parent::__construct($user, $db, $postArray, $pageTitle, $pageHead, $pageID);
    }

    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1()
    {
        $this->panelHead_1 = '<h3>Web Application Framework</h3>';
    }
    
    /**
     * Set the Panel 1 text content 
     */   
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

    public function getUserNr($userEmailId){
        $sql= 'SELECT UserNr FROM User WHERE UserId= "'.$userEmailId.'";';
        $rs=$this->db->query($sql);
        return $rs->fetch_assoc()['UserNr'];
    }

    public function printOrderTable($userOrdersRs){
        $html = "<table>";
        foreach ($userOrdersRs as $dataRow) {
            $html .= "<tr><td>".$dataRow["dish"]."</td></tr>";    
        }
        $html .= "</table>";
        $this->panelContent_2 .= $html;
    }

    public function processOrder() {
        $dishId = $_POST["add_dish_id"];
        $userEmailId = $this->user->getUserID();
        $userNr = $this->getUserNr($userEmailId);
        echo $userNr;

        try {
            $this->saveOrder($userNr, $dishId);
        } catch(Exception $e) {
            // Handle exception if necessary
        }
        
        $userOrdersRs = $this->getUserOrders($userNr);
        $this->printOrderTable($userOrdersRs);
        $this->panelContent_2 .= '<p>Dish with ID '.$dishId.' added successfully!</p>';
    }

    public function saveOrder($userNr, $dishId)
    {
        $sql = "INSERT INTO `order` (`customer`, `dish`) VALUES ($userNr, $dishId);";
        $this->db->query($sql);
    }

    public function getUserOrders($userNr){
        $sql = "SELECT * FROM `order` WHERE customer=$userNr;";
        $rs = $this->db->query($sql);
        return $rs;
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

                    $this->panelContent_2.='<input type="hidden" name="chosen_items[]" value="'.$_POST["dish_id"].'">
                    <input type="submit" name="add_dish" value="Add">
                </form>';
        }

        if(isset($_POST["add_dish"]))
        {
            $this->processOrder();
        }

        if(isset($_POST["show_all"]))
        {
            if(isset($_POST["chosen_items"]))
            {
                $added_dish_ids = $_POST["chosen_items"];
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
            <form action="index.php?pageID=home" method="POST" style="display:inline;">
                <input type="submit" name="show_all" value="Show All">
            </form>
            <form action="index.php?pageID=home" method="POST" style="display:inline;">
                <input type="submit" name="reset_orders" value="Reset">
            </form>';
    }

    /**
     * Set the Panel 3 heading 
     */
    public function setPanelHead_3()
    { 
        $this->panelHead_3 = '<h3>Application Setup</h3>';
    } 

    /**
     * Set the Panel 3 text content 
     */       
    public function setPanelContent_3()
    { 
        $menuTable = new MenuTable($this->db);
        $rs = $menuTable->retrieveMenu();
        $this->panelContent_3 .= HelperHTML::generateTABLE($rs);
    }         
}

// HelperHTML and other classes should be defined elsewhere in your project.
?>
