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
        $this->panelHead_1 = '<h3>Restaurant Takeaway Menu- Phone No:089 477 2046</h3>';
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
        $this->panelHead_2 = '<h3>Enter your choice:</h3>';
    }
    public function getUserNr($userEmailId)
    {
        $sql = 'SELECT UserNr FROM User WHERE UserId= "' . $userEmailId . '";';
        $rs = $this->db->query($sql);
        return $rs->fetch_assoc()['UserNr'];
    }
    public function printOrderTable($userOrdersRs)
    {
        $html = "<table>";
        foreach ($userOrdersRs as $dataRow) {

            $html .= "<tr>";

            $html .= "<td>";
            $html .= $dataRow["dish"];
            $html .= "</td>";

            $html .= "</tr>";
        }
        $html .= "</table>";
        $this->panelContent_2 .= $html;
    }
    public function processOrder()
    {
        $dishId = $_POST["add_dish_id"];

        $userEmailId = $this->user->getUserID();
        $userNr = $this->getUserNr($userEmailId);
        echo $userNr;
        try {


            $this->saveOrder($userNr, $dishId);
        } catch (Exception $e) {
            try{

            
            $this->updateOrder($userNr, $dishId);
            }
            catch(Exception $e1){}
        }
    

        $userOrdersRs = $this->getUserOrders($userNr);
        $this->printOrderTable($userOrdersRs);

    


        // Prepare the SQL query
        $sql = "SELECT 
        rd.DishID, 
        rd.DishName, 
        rd.DishType, 
        rd.Price,
        o.OrderQuantity
    FROM 
        `order` o
    INNER JOIN 
        `restaurantdishes` rd ON o.Dish = rd.DishID
    WHERE 
        o.customer = $userNr";

try {
// Execute the query
$result = $this->db->query($sql);

// Check if we have results
if ($result->num_rows > 0) {
    // Initialize the panel content
    $this->panelContent_2 = '<h2>Ordered Dishes Details:</h2>';

    // Loop through each result and append details
    while ($row = $result->fetch_assoc()) {
        $this->panelContent_2 .= '<p>Dish with ID ' . $row['DishID'] . ':</p>';
        $this->panelContent_2 .= '<ul>';
        $this->panelContent_2 .= '<li>Name: ' . htmlspecialchars($row['DishName']) . '</li>';
        $this->panelContent_2 .= '<li>Type: ' . htmlspecialchars($row['DishType']) . '</li>';
        $this->panelContent_2 .= '<li>Price: $' . number_format($row['Price'], 2) . '</li>';
        $this->panelContent_2 .= '<li>Quantity Ordered: ' . $row['OrderQuantity'] . '</li>'; // Display the order quantity
        $this->panelContent_2 .= '</ul>';
    }
} else {
    $this->panelContent_2 = '<p>No dishes found for customer.</p>';
}
} catch (Exception $e) {
// Handle exceptions such as connection errors
$this->panelContent_2 = 'Error fetching dishes: ' . $e->getMessage();
}
    }


    public function saveOrder($userNr, $dishId)
    {
        $sql = "INSERT INTO `order` (`customer`, `dish`) VALUES($userNr,$dishId);";
        $this->db->query($sql);
    }
    public function updateOrder($userNr, $dishId,)
    {
        $sql = "UPDATE `order` 
            SET `OrderQuantity` = `OrderQuantity` + 1 
            WHERE `customer` = $userNr AND `dish` = $dishId;";
        $this->db->query($sql);
    }

    public function resetOrder($userNr)
    {
        $sql = "DELETE FROM `order` WHERE customer=" . $userNr . ";";
        $this->db->query($sql);
    }

    public function getUserOrders($userNr)
    {
        $sql = "SELECT * FROM `order`WHERE customer=$userNr;";
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

        if (isset($_POST["reset_dish"])) {
            $userEmailId = $this->user->getUserID();
            $userNr = $this->getUserNr($userEmailId);
            $this->resetOrder($userNr);
        }

        if (isset($_POST["dish_id"])) {
            $rs = $menuTable->retrieveMenu($_POST["dish_id"]);
            $this->panelContent_2 .= HelperHTML::generateTABLE($rs);
            $this->panelContent_2 .= '
                <form action="index.php?pageID=home" method="POST">
                    <input type="hidden" name="add_dish_id" value="' . $_POST["dish_id"] . '">';

            if (isset($_POST["chosen_items"])) {
                foreach ($_POST["chosen_items"] as $i) {
                    $this->panelContent_2 .= '<input type="hidden" name="chosen_items[]" value="' . $i . '">';
                }
            }

            $this->panelContent_2 .= '<input type="hidden" name="chosen_items[]" value="' . $_POST["dish_id"] . '">';



            if (isset($_POST["show_all"])) {
                $this->panelContent_2 .= '<input type="hidden" name="chosen_items[]" value="' . $_POST["dish_id"] . '">';
            }

            $this->panelContent_2 .= '<input type="submit" name="add_dish" value="Add">
                                    

                                        </form>';
        }

        if (isset($_POST["add_dish"])) {
            // Handle adding the dish here
            //var_dump($_POST);
            $this->processOrder();
        }

        if (isset($_POST["show_all"])) {

            if (isset($_POST["chosen_items"])) {
                $added_dish_ids = $_POST["chosen_items"]; // $this->retrieveAddedDishIDs();
                $this->panelContent_2 .= '<h3>Added Dish IDs:</h3>';
                $this->panelContent_2 .= '<ul>';
                foreach ($added_dish_ids as $dish_id) {
                    $this->panelContent_2 .= '<li>' . $dish_id . '</li>';
                }
                $this->panelContent_2 .= '</ul>';
            }
        }
    

        $this->panelContent_2 .= '
            <form action="index.php?pageID=home" method="POST">
                <input type="submit" name="show_all" value="Show All">
                <input type="submit" name="reset_dish" value="Reset">
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
    public function setPanelHead_3()
    {
        $this->panelHead_3 = '<h3>Application Setup</h3>';
    }

    /**
     * Set the Panel 3 text content 
     */
    public function setPanelContent_3()
    {
        
        ///"<p>To set up this application read the following <a href='readme/installation.php' target='_blank'>SETUP INSTRUCTIONS</a>.</p>";   
    }
}

