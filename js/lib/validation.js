function gatherData(inputBox, pattern) {
    /*
    SUBROUTINE to gather data from the formDetails
    @param : inputBox  object  holds the inputBox to be tested
    @param : pattern    RegEx Pattern  holds the regex test pattern 

    @var   : data      JSON           holds the dictionary to return

    @return: data JSON dictionary
    */

    //initialise dictionary
    let data = {
        value: null,
        valid: false,
        message: inputBox.val() + " is not valid"
    }

    //use pattern to test the contents of the input box
    if (pattern.test(inputBox.val())) {
        //convert firstNumber to integer and save as data
        data.value = inputBox.val();

        //update the valid switch
        data.valid = true

        //update the message
        data.message = inputBox.val() + " is valid"
    }
    //return the dictionary
    return data
}

