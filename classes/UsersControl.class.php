<?php
/* MVC */
/* Controller */

// A class to control the data that will wright in the database with public functions
//that is conected to protected function in the model file (Users.class.php)
// the class is extends from the users class so we can call the protected function inside the model
class UsersControl extends Users
{

    public function setNewType($newType)
    {
        $this->setType($newType);
    }

    public function addNewItem($type, $desc)
    {
        // Sanitize input
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
        $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_SPECIAL_CHARS);
        $this->addItem($type, $desc);
    }

    public function delItem($id)
    {
        // Sanitize input
        $id = filter_input(INPUT_GET, 'delItem', FILTER_SANITIZE_NUMBER_INT);
        $this->deleteItem($id);
    }

    public function delType($id)
    {
        // Sanitize input
        $id = filter_input(INPUT_GET, 'delType', FILTER_SANITIZE_NUMBER_INT);
        $this->deleteType($id);
    }

    public function setNewLoan($return, $item, $user)
    {
        // Sanitize input
        $return = filter_input(INPUT_POST, 'returndate', FILTER_SANITIZE_NUMBER_FLOAT);
        $item = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT);
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_NUMBER_INT);
        $this->setLoan($return, $item, $user);
    }

    public function showLoan()
    {
        $this->getLoan();
    }

    public function showUsersLoan()
    {
        $this->getUsersLoan();
    }

    public function returnItem($id)
    {
        // Sanitize input
        $id = filter_input(INPUT_GET, 'returnItem', FILTER_SANITIZE_NUMBER_INT);
        $this->returnUsersItem($id);
    }

}
