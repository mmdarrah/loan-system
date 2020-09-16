<?php
/* MVC */
/* Veiw */

// A class to veiw the data that will get from the database with public functions
//that is conected to protected function in the model file (Users.class.php)
// the class is extends from the users class so we can call the protected function inside the model
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
