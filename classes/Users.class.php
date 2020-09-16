<?php
/* MVC */
/* Model */

// calling the connection to Database class
include 'dbCon.php';

// Users class
// All the connection to the database are done from the model file with a protected functions
// that can be control from the UsersView and UsersControl files
class Users extends DB
{
    // A protected function to get all the users in the database
    protected function getUsers()
    {
        // create a new instance of DB
        $db = new DB();
        $query = "SELECT username, firstname, lastname, birthdate, address
        FROM user LEFT JOIN userdata ON userdata.userid = user.id ";
        // If we get a result, then loop through this and print each line
        if ($result = $db->query($query)) {
            $row = $result->fetchAll(PDO::FETCH_ASSOC);
            //echo '<pre>'; print_r($result2); echo '</pre>';
            return $row;
        }
    }

    // A protected function to set a type in the database
    protected function setType($newType)
    {
        // create a new instance of DB
        $db = new DB();
        // query to add name to itemtypes
        $query = "INSERT INTO itemtypes(name)
        VALUES (:name)";
        // Sanitize input
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $sth = $db->prepare($query);

        if ($sth->execute(array(':name' => $name))) {
            echo "<h4>Itemtype added</h4>";
        } else {
            // if something goes wrong print PDO error message
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    }

    // A protected function to get type from the database
    protected function getTypes()
    {
        // create a new instance of DB
        $db = new DB();
        /* Here all data is retrieved from the itemtypes table for easy creation
         * a list of existing item type categories for the form.
         * This also means that we do not have to update our html when we add
         * to new categories.
         */
        $query = "SELECT * FROM itemtypes ORDER BY name ASC";

        if ($result = $db->query($query)) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
            }
        }
    }

    // A protected function to add iten in the database
    protected function addItem($type, $desc)
    {
        // create a new instance of DB
        $db = new DB();
        $query = "INSERT INTO items(type, description)
        VALUES (:type, :description)";

        // filter input
        // The FILTER_SANITIZE_NUMBER_INT filter removes all illegal characters from a number.
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
        $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_SPECIAL_CHARS);

        // array with values for prep. statement
        $values = array(':type' => $type,
            ':description' => $desc);

        $sth = $db->prepare($query);

        if ($sth->execute($values)) {
            echo "<h4>Item added</h4>";
        } else {
            // if something goes wrong print PDO error message
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    }

    // A protected function to get categories from the database
    protected function getCategories()
    {
        // create a new instance of DB
        $db = new DB();
        // print a list of existing categories with delete link
        $query = "SELECT * FROM itemtypes ORDER BY name ASC";

        if ($result = $db->query($query)) {
            echo "<ul>";
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<li>id: ' . $row['id'] . ' type: ' . $row['name'] .
                    ' <a href="index.php?delType=' . $row['id'] .
                    '">Delete type</a></li>';
            }
            echo "</ul>";
        }
    }

    // A protected function to get the list of items from the database
    protected function getListOfItems()
    {
        // create a new instance of DB
        $db = new DB();
        // print a list of existing objects with delete link

        // use a JOIN to display the item type in the list
        $query = "SELECT items.id, description, name FROM items
                    LEFT JOIN itemtypes ON itemtypes.id = items.type";

        if ($result = $db->query($query)) {
            echo "<ul>";
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // the link now goes to delItem
                echo '<li>id: ' . $row['id'] . ' type: ' . $row['name'] .
                    ' desc: ' . $row['description'] .
                    ' <a href="index.php?delItem=' . $row['id'] .
                    '">Delete item</a></li>';
            }
            echo "</ul>";
        }
    }

    // A protected function to delete item from the database
    protected function deleteItem($id)
    {
        // create a new instance of DB
        $db = new DB();
        $query = "DELETE FROM items WHERE id=(:id)";
        $sth = $db->prepare($query);
        if ($sth->execute(array(':id' => $id))) {
            echo "<h4>Item with id: " . $id . " deleted";
        } else {
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    }

    // A protected function to delete type from the database
    protected function deleteType($id)
    {
        // create a new instance of DB
        $db = new DB();
        $query = "DELETE FROM itemtypes WHERE id=(:id)";
        $sth = $db->prepare($query);

        if ($sth->execute(array(':id' => $id))) {
            echo "<h4>Item type with id: " . $id . " deleted";
        } else {
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    }

    // A protected function to delete type from the database
    protected function setLoan($return, $item, $user)
    {
        // create a new instance of DB
        $db = new DB();
        // Borrow an item and add a line for this in the loan
        /* Because it is possible to borrow items before they are in
         * the loan table, we need to check if active is 0 or not set.
         */
        $query = "SELECT active FROM loan WHERE item = :id AND active = 0 OR active = ''";

        $sth = $db->prepare($query);

        if ($sth->execute(array(':id' => $item))) {
            // add the loan, we do not specify active as it is automatically set to 1
            $query = "INSERT INTO loan(item, user, returndate)
                      VALUES (:item, :user, :returndate)";

            $values = array(':item' => $item,
                ':user' => $user,
                ':returndate' => $return);

            $sth = $db->prepare($query);

            if ($sth->execute($values)) {
                echo "Loan for item with id " . $item . " registered.";
            } else {
                // if something goes wrong print PDO error message
                echo "<h4>Error</h4>";
                echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
            }
        } else {
            echo "A loan for item with id: " . $item . " is already active.";

        }

        /* When returning items
         * Sets loan.active to 0 to indicate that it is an inactive loan.
         * This is to file all loans.
         */
        if (isset($_GET['returnItem'])) {
            $id = filter_input(INPUT_GET, 'returnItem', FILTER_SANITIZE_NUMBER_INT);

            // query för att uppdatera active fältet
            $query = "UPDATE loan SET active='0' WHERE id=(:id)";

            $sth = $db->prepare($query);

            if ($sth->execute(array(':id' => $id))) {
                echo "<h4>Item type with id: " . $id . " returned.";
            } else {
                echo "<h4>Error</h4>";
                echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
            }
        }
    }

    // A protected function to get all the loans from the database
    protected function getLoan()
    {
        // create a new instance of DB
        $db = new DB();
        /* We use a SQL JOIN on items, itemtypes and loan to
         * retrieve data for the select field.
         * We do not want to show items that are already on loan so we
         * retrieves the active field from loan.
         *
         * This illustrates a problem that we save all data, too
         * inactive loans, as it can be a very large download without
         * the data is used. Therefore, it may be a good idea to save archived
         * loans in a separate table.
         */
        $query = "SELECT items.id, items.description AS name, loan.active
                  FROM items
                  LEFT JOIN loan ON loan.item = items.id
                  LEFT JOIN itemtypes ON itemtypes.id = items.type";

        if ($result = $db->query($query)) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // check that active is not 1
                if ($row['active'] != 1) {
                    echo '<option value="' . $row['id'] . '">' .
                        $row['name'] . '</option>';
                }
            }
        }
    }

    // A protected function to get all the users from the database
    protected function getUsersLoan()
    {
        // create a new instance of DB
        $db = new DB();
        // Query för att hämta användare och skriva ut dem.
        $query = "SELECT id, username FROM user ORDER BY username ASC";

        if ($result = $db->query($query)) {
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
            }
        }
    }

    // A protected function to get loan list from the database
    protected function getLoanList()
    {
        // create a new instance of DB
        $db = new DB();
        // Query to retrieve users and print them.
        /* List of loans available in the database, both active and inactive
         * Printed with the option to return the item.
         */
        $query = "SELECT loan.id AS lid, active, item, loandate, returndate,
    description, name AS itemtype, loan.user AS loaner
    FROM loan
    LEFT JOIN items ON items.id = loan.item
    LEFT JOIN itemtypes ON itemtypes.id = items.type
    LEFT JOIN user AS loaner ON loaner.id = loan.user
    ORDER BY loandate DESC";

        if ($result = $db->query($query)) {

            echo "<h2>Registered loans</h2>";

            // A table for clear printing of sample data
            echo '<table border="1"><tr><th>item id</th><th>item type</th><th>description</th>' .
                '<th>user</th><th>loan date</th><th>return date</th><th>return item</th></tr>';
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['item'] . "</td>";
                echo "<td>" . $row['itemtype'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['loaner'] . "</td>";
                echo "<td>" . $row['loandate'] . "</td>";
                echo "<td>" . $row['returndate'] . "</td>";
                // Field to display link for item return
                echo "<td>";
                if ($row['active'] == '1') {
                    echo '<a href="index.php?returnItem=' . $row['lid'] . '">Return item</a>';
                } else {
                    echo "No active loan";
                }
                echo "</td></tr>";
            }
            echo "</table>";
        }
    }

    // A protected function to return loan in the database
    protected function returnUsersItem($id)
    {
        // create a new instance of DB
        $db = new DB();
        // query för att uppdatera active fältet
        $query = "UPDATE loan SET active='0' WHERE id=(:id)";

        $sth = $db->prepare($query);

        if ($sth->execute(array(':id' => $id))) {
            echo "<h4>Item type with id: " . $id . " returned.";
        } else {
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    }

}
