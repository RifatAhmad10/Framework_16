<?php

/**
 * This file contains the CustomerMyAccount Class
 * 
 */


/**
 * CustomerMyAccount is an extended PanelModel Class
 * 
 * The purpose of this class is to generate HTML view panel headings and template content
 * for an  <em><b>CUSTOMER user account management </b></em>  page.  The content generated is intended for 3 panel
 * view layouts. 
 * 
 * @author gerry.guinane
 * 
 */


class CustomerMyAccount extends PanelModel
{




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
    function __construct($user, $db, $postArray, $pageTitle, $pageHead, $pageID)
    {
        $this->modelType = 'CustomerMyAccount';
        parent::__construct($user, $db, $postArray, $pageTitle, $pageHead, $pageID);
    }

    private function getUserNr($userEmailId)
    {
        // Assuming userEmailId is properly escaped or validated before this point to prevent SQL injection
        $userEmailId = $this->db->real_escape_string($userEmailId);

        $sql = "SELECT userNr FROM users WHERE userEmail = '{$userEmailId}'";
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch_assoc()) {
                return $row['userNr'];
            } else {
                // No user found with this email ID
                return null;
            }
        } else {
            // Error executing query
            error_log("Database error in getUserNr: " . $this->db->error);
            return null;
        }
    }



    /**
     * Set the Panel 1 heading 
     */
    public function setPanelHead_1()
    {
        switch ($this->pageID) {
            case "myOrders":
                $this->panelHead_1 = '<h3>My Orders</h3>';
                break;
            case "editAccount":
                $this->panelHead_1 = '<h3>Edit My Account</h3>';
                break;
            case "changePassword":
                $this->panelHead_1 = '<h3>Change My Password</h3>';
                break;
            default:
                $this->panelHead_1 = '<h3>My Orders</h3>';
                break;
        } //end switch       
    }

    /**
     * Set the Panel 1 text content 
     */
    public function setPanelContent_1()
    {
        switch ($this->pageID) {
            case "printOrderTable":
                $this->panelContent_1 = 'orders';
                break;
            case "editAccount":
                $countyTable = new CountyTable($this->db);
                $userTable = new UserTable($this->db);
                $thisUserRecord = $userTable->getRecordByID($this->user->getUserID());
                $this->panelContent_1 = Form::form_edit_account($countyTable, $thisUserRecord, $this->pageID);
                array_push($this->panelModelObjects, $userTable); #for diagnostic purposes
                break;
            case "changePassword":
                $this->panelContent_1 = Form::form_password_change($this->pageID);
                break;
            default:
                switch ($this->pageID) {
                    case "printOrderTable":
                        // Get the user's email ID, then get their user number
                        $userEmailId = $this->user->getUserID();  // Assuming this returns an email ID
                        $userNr = $this->getUserNr($userEmailId);

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
                           $this->displayOrder($result);

                            // Check if we have results

                        } catch (Exception $e) {
                            // Handle exceptions such as connection errors
                            $this->panelContent_2 = 'Error fetching dishes: ' . $e->getMessage();
                        }
                }
        } //end switch  
    }

    public function displayOrder($result)
    {
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
    }
    public function printOrderTable($userOrdersRs)
    {
        $html = "<table border='1'>";  // Added a border for visibility
        $html .= "<tr><th>Dish Name</th><th>Quantity</th><th>Price Each</th><th>Total Price</th></tr>";  // Headers

        foreach ($userOrdersRs as $dataRow) {
            $html .= "<tr>";
            $html .= "<td>" . htmlspecialchars($dataRow["DishName"]) . "</td>";  // Assuming 'DishName' is the column name
            $html .= "<td>" . $dataRow["OrderQuantity"] . "</td>";
            $html .= "<td>$" . number_format($dataRow["Price"], 2) . "</td>";
            $html .= "<td>$" . number_format($dataRow["Price"] * $dataRow["OrderQuantity"], 2) . "</td>";
            $html .= "</tr>";
        }

        $html .= "</table>";
        $this->panelContent_1 .= $html;  // Append to panelContent_1 instead of panelContent_2
    }

    /**
     * Set the Panel 2 heading 
     */
    public function setPanelHead_2()
    {
        switch ($this->pageID) {
            case "myAccount":
                $this->panelHead_2 = '<h3>Manage My Account</h3>';
                break;
            case "editAccount":

                $this->panelHead_2 = '<h3>Edit Account Result</h3>';
                break;
            case "changePassword":
                $this->panelHead_2 = '<h3>Password Change Result</h3>';
                break;
            default:
                $this->panelHead_2 = '<h3>Manage My Account</h3>';
                break;
        } //end switch
    }

    /**
     * Set the Panel 2 text content 
     */
    public function setPanelContent_2()
    {
        switch ($this->pageID) {

            case "myAccount":
                $this->panelContent_2 = 'myAccount';
                break;
            case "editAccount":
                if (isset($this->postArray['btnUpdateAccount'])) {
                    $userTable = new UserTable($this->db);
                    if ($userTable->updateRecord($this->postArray)) {
                        $this->panelContent_2 = 'Record Updated';
                        $this->setPanelContent_1();  //refresh panel 1 data after change
                    } else {
                        $this->panelContent_2 = 'Unable to update record or no new values entered';
                    }
                    array_push($this->panelModelObjects, $userTable); #for diagnostic purposes
                } else {
                    $this->panelContent_2 = 'Use the form on the left to edit your account details. <br><br> Note that it is <b> not possible to edit the email field as this is used as your unique userID </b>.';
                }
                break;
            case "changePassword":
                if (isset($this->postArray['btnChangePW'])) {
                    //check both new passwords match 
                    if ($this->postArray['pass1'] === $this->postArray['pass2']) {
                        $userTable = new UserTable($this->db);
                        if ($userTable->changePassword($this->postArray, $this->user)) {
                            $this->panelContent_2 = 'Password changed - next time you log in use the new password';
                        } else {
                            $this->panelContent_2 = 'Unable to change password - check you have entered the correct OLD password';
                        }
                        array_push($this->panelModelObjects, $userTable); #for diagnostic purposes
                    } else {
                        $this->panelContent_2 = "OLD Passwords entered DON'T match - please retry.";
                    }
                } else {
                    $this->panelContent_2 = 'To change your password - enter the new password TWICE along with your OLD password for authorisation.';
                }
                break;
            default:
                $this->panelContent_2 = 'myAccount';
                break;
        } //end switch
        $this->panelContent_2 .= "orders";
    }

    /**
     * Set the Panel 3 heading 
     */
    public function setPanelHead_3()
    {

        $this->panelHead_3 = '<h3>Panel 3</h3>';
    }

    /**
     * Set the Panel 3 text content 
     */
    public function setPanelContent_3()
    { //set the panel 2 content
        $this->panelContent_3 = "Panel 3 content for <b>$this->pageHeading</b> menu item is under construction/not in use.";;
    }
}
