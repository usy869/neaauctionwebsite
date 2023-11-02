$(document).ready(function () {

    $("#btnSubmit").on("click", function () {
        //METHOD to handle click event of submit button
        //@var:  userDetails  JSON   data for new user

        //get the user details from the form
        let pattern = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
        let email = gatherData($("#email"), pattern)

        pattern = /^[A-Z][a-z]+$/;
        let firstName = gatherData($("#firstname"), pattern)

        pattern = /^[A-Z][a-z]+$/;
        let lastName = gatherData($("#lastname"), pattern)

        pattern = /^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([AZa-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9]?[A-Za-z])))) [0-9][A-Za-z]{2})$/;
        let postcode = gatherData($("#postcode"), pattern)

        pattern = /^[A-Za-z*_]+$/;
        let password = gatherData($("#pwd1"), pattern)

        pattern = /^[A-Za-z*_]+$/;
        let password2 = gatherData($("#pwd2"), pattern)

        //determine if the passwords are correct and that they match
        let passwordCheck = true
        if (!password.valid || !password2.valid) {
            passwordCheck = false
        }
        else {
            if (password.value != password2.value) {
                passwordCheck.false
            }
        }

        //test if there is something to send
        if (email.valid && firstName.valid && lastName.valid && postcode.valid && passwordCheck) {

            let userDetails = {
                email: $('#email').val(),
                firstname: $('#firstname').val(),
                lastname: $('#lastname').val(),
                postcode: $('#postcode').val(),
                password: $('#pwd1').val()
            }

            //send the data to the PHP script
            postSignup("../../php/account/signup.php", JSON.stringify(userDetails));
        }

    })

    function postSignup(phpScript, dataToSend) {
        /*FUNCTION to send data and handle the response
        @param dataToSend  JSON    collection to send 
        @param phpScript   string  path to the php file
        @var   jxhr        object  instance of a POST request
        */

        //POST the data to the PHP script  
        var jqxhr = $.post(phpScript, dataToSend,
            function (responseJSON) {
                /*
                CALLBACK function to handleResponse
                @param responseJSON object data returned 
                */

                //check the response code
                switch (responseJSON.successCode) {
                    case 1:
                        //success, so go to flashcards
                        alert("signup was successful - now login please")
                        window.location.assign("../index.html");

                        break;
                    default:
                        //show the response message
                        alert(responseJSON.message)
                }
            } //END of callback function
        ) //END of POST             
    } //END of function 
})