<?PHP
    header("content-type: application/json; charset=UTF-8");  

function main(){
    /* MAIN ROUTINE 
      @var $dataReceived object collection of data sent
      @var $response object collection of data to send back
    */

    //include PHP scripts for accessing the database and token
    require '../connect/connectUser.php';
    require '../token/token.php';

    //capture the input and decode it
    $dataReceived = json_decode(file_get_contents('php://input'));
        
    //debugging
    //echo("The data received is: \n");
    //print_r($dataReceived);
    //die();

    //run the add user subroutine
    $response = checkUser($conn, $dataReceived);
         
    //send the results back
    echo json_encode($response);

    //close the connection  
    $conn = null;
    }

    function checkUser($conn, $data){
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
        "webToken"        => "",
        "message"     => ""
  );

  //prepare the parameterised SQL for the query
  $sql = "SELECT email, password
              FROM user
              WHERE email = :email";

  

  $query = $conn->prepare($sql);

  //bind data to the parameters  
  $query->bindParam(':email', $data->email);                 
 
 //grab the password to encrypt it
  $password = $data->password;

  try{
    $query->execute();
    
    //determine if there is a match i.e. one record
      if ($query->rowCount() == 1){
          //The username exists so check the password
          //Put user details into a dictionary
          $user = $query->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);

          //check the password
          if (password_verify($password, $user["password"])){
              //password is verified, generate token
              $response["webToken"] = getToken($user);
              $response["successCode"] = 1;
          }
          else {
              $response["successCode"] = 2;
              $response["message"] = "The inputted password was incorrect";
          }
      }


      
  } catch (PDOException $e) {
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