<?php
    header("content-type: application/json; charset=UTF-8");  

function main(){
    /* MAIN ROUTINE 
      @var $dataReceived object collection of data sent
      @var $response object collection of data to send back
    */

    //include PHP scripts for accessing the database
    require '../connect/connectUser.php';

    //capture the input and decode it
    $dataReceived = json_decode(file_get_contents('php://input'));
        
    //debugging
    //echo("The data received is: \n");
    //print_r($dataReceived);
    //die();

    //run the add user subroutine
    $response = addNewUser($conn, $dataReceived);
         
    //send the results back
    echo json_encode($response);

    //close the connection  
    $conn = null;
    }

    function addNewUser($conn, $data){
  /*
   FUNCTION to run a SELECT query to check credentials
   @params  $conn     object     connection to the database
   @params  $data     JSON       collection of data from client
   @var     $response dictionary JSON to return to client
   @var     $query    object     query object
   @var     $sql      string     holds the SQL script
  */
   //set default response as an associative array (dictionary)  
  $response = array(
        "successCode" => 0,
        "data"        => "",
        "message"     => ""
  );

  //prepare the parameterised SQL for the query
  $sql = $conn->prepare("INSERT INTO user (password, email, firstname, lastname, postcode)
          VALUES(:password, :email, :firstname, :lastname, :postcode)");

  //bind data to the parameters  
  $sql->bindParam(':email', $data->email);                 
  $sql->bindParam(':firstname', $data->firstname);
  $sql->bindParam(':lastname', $data->lastname);
  $sql->bindParam(':postcode', $data->postcode);

  //grab the password to encrypt it
  $password = $data->password;

  //encrypt the password using the hashing algorithm
  $options = ["cost"=>12];
  $encPassword = password_hash($password, PASSWORD_BCRYPT, $options);


  //bind encrypted password to the password attribute      
  $sql->bindParam(':password', $encPassword);  
        
  try {
     //execute the Query     
     $sql->execute();
      
     //amend the response object
     $response["successCode"] = 1;
  }
  catch (PDOException $e) {
     //deal with error in running the query
     $response["successCode"] = 0;
     $response["message"] = $e->getMessage();
  }

   //return the response
  return $response;
}
    
    //run main
    main();



?>