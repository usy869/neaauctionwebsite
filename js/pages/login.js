$(document).ready(function() {

    $("#btnSubmit").on("click", function(){
        //METHOD to handle click event of submit button
        //@var:  userDetails  JSON   data for new user
                
        //get the user details from the form
        let pattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        let email = gatherData($("#email"), pattern)

        pattern = /^[A-Za-z*_]+$/;
        let password = gatherData($("#pwd1"), pattern)


        //test if there is something to send
        if (email.valid && password.valid){

            let userDetails = {
                email     : $('#email').val(),
                password  : $('#pwd1').val()
            }

            //send the data to the PHP script
            postLogin("../../php/account/login.php", JSON.stringify(userDetails));
        }  
    })

    function postLogin(phpScript, dataToSend){
        /*FUNCTION to send data and handle the response
        @param dataToSend  JSON    collection to send 
        @param phpScript   string  path to the php file
        @var   jxhr        object  instance of a POST request
        */
     
        //POST the data to the PHP script  
        var jqxhr = $.post(phpScript, dataToSend, 
              function(responseJSON) {
                /*
                CALLBACK function to handleResponse
                @param responseJSON object data returned 
                */
                    
                //check the response code
                switch (responseJSON.successCode) {
                    case 1:
                        //store the token
                        sessionStorage.setItem("token", responseJSON.webToken)
                        
                        //success, so go to page
                        window.location.assign("../pages/index.html");
                        break;
                    default:
                        //show the response message
                        alert(responseJSON.message)
                    }
              } //END of callback function
            ) //END of POST             
    } //END of function 
})