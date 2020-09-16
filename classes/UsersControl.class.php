<?php
class UsersControl extends Users {

   public function setNewType($newType){
       $this->setType($newType);
   }

   
   public function addNewItem($type,$desc){
         // filtrera input, notera sanitize int
    $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
    $desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_SPECIAL_CHARS); 
    $this->addItem($type, $desc);
    }

    public function delItem($id){
        $id = filter_input(INPUT_GET, 'delItem', FILTER_SANITIZE_NUMBER_INT);
        $this->deleteItem($id);
    }

    public function delType($id){
        $id = filter_input(INPUT_GET, 'delType', FILTER_SANITIZE_NUMBER_INT);
        $this->deleteType($id);
    }

    public function setNewLoan($return, $item, $user){
        // filtrera input, float för datum fältet tillåter - och .
        $return = filter_input(INPUT_POST, 'returndate', FILTER_SANITIZE_NUMBER_FLOAT);
        $item = filter_input(INPUT_POST, 'item', FILTER_SANITIZE_NUMBER_INT);
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_NUMBER_INT);
        $this->setLoan($return, $item, $user);
    }

    public function showLoan(){
        $this->getLoan();
    }

    public function showUsersLoan(){
        $this->getUsersLoan();
    }

    public function returnItem($id){
        $id = filter_input(INPUT_GET, 'returnItem', FILTER_SANITIZE_NUMBER_INT);
        $this->returnUsersItem($id);
    }

}