<?php
class UsersView extends Users
{

    public function showUsers()
    {
        $row = $this->getUsers();
        echo '<h3>The users are: </h3>';
        foreach ($row as $user) {
            //echo '<pre>'; print_r($row); echo '</pre>';
            echo 'Username: ' . $user['username'] . "<br>";
            echo 'First name: ' . $user['firstname'] . "<br>" . 'Last name: ' . $user['lastname'] . "<br>";
            echo 'Birthdate: ' . $user['birthdate'] . "<br>";
            echo "<br>";
        }

    }

    public function showTypes()
    {
        $this->getTypes();
    }

    public function showCategories()
    {
        $this->getCategories();
    }

    public function showListOfItems()
    {
        $this->getListOfItems();
    }

    public function showLoanList()
    {
        $this->getLoanList();
    }
}
