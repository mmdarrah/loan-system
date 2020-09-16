<?php

include 'dbCon.php';

class Users extends DB {
    protected function getUsers(){  
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

    protected function setType($newType){  
        // create a new instance of DB
        $db = new DB();
        // query för att lägga till name i itemtypes
        $query = "INSERT INTO itemtypes(name)
        VALUES (:name)";
        // filtrera input
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);

        $sth = $db->prepare($query);

            if ($sth->execute(array(':name' => $name))) {
            echo "<h4>Itemtype added</h4>";
            } else {
            // om något går fel skriv ut PDO felmeddelande
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
            }
        }


    protected function getTypes(){
            $db = new DB();
            /* Här hämtas all data från itemtypes tabellen för att enkelt skapa
             * en lista över existerande itemtype kategorier till formuläret.
             * Detta gör även att vi slipper uppdatera vår html när vi lägger
             * till nya kategorier.
             */
            $query = "SELECT * FROM itemtypes ORDER BY name ASC";

            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
                }
            }
    }


    protected function addItem($type, $desc){
        $db = new DB();
        $query = "INSERT INTO items(type, description)
        VALUES (:type, :description)";

        // filtrera input, notera sanitize int
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
        $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_SPECIAL_CHARS);

        // array med värden för prep. statement
        $values = array(':type' => $type,
                        ':description' => $desc);

        $sth = $db->prepare($query);

        if ($sth->execute($values)) {
            echo "<h4>Item added</h4>";
            } else {
                // om något går fel skriv ut PDO felmeddelande
                echo "<h4>Error</h4>";
                echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
            }
    }

    protected function getCategories(){
        $db = new DB();
        // skriv ut en lista över existerande kategorier med delete länk
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

    protected function getListOfItems(){
        $db = new DB();
        // skriv ut en lista över existerande föremål med delete länk

            // Vi använder en JOIN för att visa föremålets typ i listan
            $query = "SELECT items.id, description, name FROM items
                    LEFT JOIN itemtypes ON itemtypes.id = items.type";

            if ($result = $db->query($query)) {
                echo "<ul>";
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    // notera att länken nu går till delItem
                    echo '<li>id: ' . $row['id'] . ' type: ' . $row['name'] .
                        ' desc: ' . $row['description'] .
                        ' <a href="index.php?delItem=' . $row['id'] .
                        '">Delete item</a></li>';
                }
                echo "</ul>";
            }
    }


    protected function deleteItem($id){
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

    protected function deleteType($id){
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


    protected function setLoan($return, $item, $user){
    $db = new DB();
    // Låna ett föremål och lägg till rad för detta i loan
    /* Eftersom det är möjligt att föremål ska lånas innan de finns i 
     * låntabellen så behöver vi kontrollera om active är 0 eller inte satt.
     */
    $query = "SELECT active FROM loan WHERE item = :id AND active = 0 OR active = ''";

    $sth = $db->prepare($query);

    if ($sth->execute(array(':id' => $item))) {
        // lägg till lånet, vi anger inte active då det automatisk sätts till 1
        $query = "INSERT INTO loan(item, user, returndate)
                      VALUES (:item, :user, :returndate)";

        $values = array(':item' => $item,
                        ':user' => $user,
                        ':returndate' => $return);

        $sth = $db->prepare($query);

        if ($sth->execute($values)) {
            echo "Loan for item with id " . $item . " registered.";
        } else {
            // om något går fel skriv ut PDO felmeddelande
            echo "<h4>Error</h4>";
            echo "<pre>" . print_r($sth->errorInfo(), 1) . "</pre>";
        }
    } else {
        echo "A loan for item with id: " . $item . " is already active.";
   
}

/* Vid återlämning av föremål
 * Sätter loan.active till 0 för att visa att det är ett inaktivt lån.
 * Detta för att arkivera alla lån.
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

    protected function getLoan(){
        $db = new DB();
        /* Vi använder en SQL JOIN på items, itemtypes och loan för att 
         * hämta data för select fältet.
         * Vi vill inte visa föremål som redan är utlånade så vi 
         * hämtar active fältet från loan.
         *
         * Detta illustrerar ett problem med att vi sparar all data, även
         * inaktiva lån, då det kan bli en väldigt stor hämtning utan att
         * datan används. Därför kan det vara en god ide att spara arkiverade
         * lån i en separat tabell.
         */ 
        $query = "SELECT items.id, items.description AS name, loan.active
                  FROM items
                  LEFT JOIN loan ON loan.item = items.id
                  LEFT JOIN itemtypes ON itemtypes.id = items.type";

            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    // kontrollera att active inte är 1
                    if ($row['active'] != 1) {
                        echo '<option value="' . $row['id'] . '">' .
                              $row['name'] . '</option>';
                    }
                }
            }
    }

    protected function getUsersLoan(){
        $db = new DB();
        // Query för att hämta användare och skriva ut dem.
            $query = "SELECT id, username FROM user ORDER BY username ASC";

            if ($result = $db->query($query)) {
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    echo '<option value="' . $row['0'] . '">' . $row['1'] . '</option>';
                }
            }
    }


    protected function getLoanList(){
        $db = new DB();
        // Query för att hämta användare och skriva ut dem.
            /* Lista över de lån som finns i databasen, både aktiva och inaktiva
     * Skrivs ut med möjlighet att lämna tillbaka föremålet.
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

    // En tabell för överskådlig utskrift av exempeldata
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
    // Fält för att visa länk för återlämning av föremål
    echo "<td>";
    if($row['active'] == '1') {
    echo '<a href="index.php?returnItem=' . $row['lid'] . '">Return item</a>';    
    } else {
    echo "No active loan";
    }
    echo "</td></tr>";
    }
    echo "</table>";
    }
        }


    protected function returnUsersItem($id){
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