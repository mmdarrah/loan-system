<?php
//include 'includes/autoLoader.inc.php';
include 'classes/Users.class.php';
include 'classes/UsersView.class.php';
include 'classes/UsersControl.class.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $getUsers = new UsersView();
    $getUsers->showUsers();
    ?>

    <h4>Add itemtype</h4>
    <!--Formulär för kategorier -->
    <form action="index.php" method="post">
        <input type="text" name="name" placeholder="name">
        <input type="submit" name="addType" value="Submit">
    </form>
    <?php
    // Lägg till itemtype
if (isset($_POST['addType'])) {
    $newType = $_POST['name'];
    //echo $newType;
    $setType = new UsersControl();
    $setType->setNewType($newType);
}
    ?>

        <h4>Add item</h4>
        <!--Formulär för föremål -->
        <form action="index.php" method="post">
            <select name="type">
            <?php
            $addType = new UsersView();
            $addType->showTypes();
            
            if (isset($_POST['addItem'])) {
            $addItem = new UsersControl();
            $addItem->addNewItem($type, $desc); 
            }
            ?>
            </select>
            <input type="text" name="desc" placeholder="description">
            <input type="submit" name="addItem" value="Submit">
        </form>
        <?php

        // ta bort itemtypes
        if (isset($_GET['delType'])) {
            $id = $_GET['delType'];
            $delTyp = new UsersControl();
            $delTyp->delType($id);
        }

        ?>
        <?php
        // ta bort itemtypes
        if (isset($_GET['delItem'])) {
            $id = $_GET['delItem'];
            $delItem = new UsersControl();
            $delItem->delItem($id);
        }
        ?>

        <h4>List categories</h4>
            <?php
            $items = new UsersView();
            $items->showCategories();
            ?>

        <h4>List items</h4>
            <?php
            $items = new UsersView();
            $items->showListOfItems();
        ?>

<h4>Register loan</h4>
    <form action="index.php" method="post">
        <label>Item: </label><select name="item">

        <?php
        $showLoan = new UsersControl();
        $showLoan->showLoan();
        ?>

        </select>
        <label>User: </label><select name="user">

        <?php
        $showUsersLoan = new UsersControl();
        $showUsersLoan->showUsersLoan();
        ?>

        <?php
        if (isset($_POST['addLoan'])) {
           
        echo $_POST['returndate'];
        echo $_POST['item'];
        echo $_POST['user'];
        $return =  $_POST['returndate'];
        $item = $_POST['item'];
        $user = $_POST['user'];
        echo $user;
        $setNewLoan = new UsersControl();
        $setNewLoan->setNewLoan($return, $item, $user);
        }
        ?>
    

        </select>
        <label>Return before: </label><input type="date" name="returndate" placeholder="yyyy-mm-dd">
        <input type="submit" name="addLoan" value="Submit">
    </form>
        <?php
        $loanList = new UsersView();
        $loanList->showLoanList();
        ?>


        <?php
                /* Vid återlämning av föremål
        * Sätter loan.active till 0 för att visa att det är ett inaktivt lån.
        * Detta för att arkivera alla lån.
        */
        if (isset($_GET['returnItem'])) {
           $id = $_GET['returnItem'];
           $returnItem = new UsersControl();
           $returnItem->returnItem($id);
        }
        ?>
</body>
</html>