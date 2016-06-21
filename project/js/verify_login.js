/**
 * Created by kurmasz on 4/20/15.
 */


window.onload = function () {

    /* 
     Put your submit event handler here.  Remember, the submit listener goes on the <form> element, not the
     submit button (i.e., the <input type="submit"/> 

     You event handler should iterate over all the "fillin" items and replace the text
     with the value of the corresponding input field.
     */
    document.getElementById("login_form").addEventListener("submit",
        function(event) {
            //event.preventDefault();
            
            console.log("login form submitted");
            checkInput(event);
        });

    function displayError(blank_nodes) {
        console.log("Displaying error");
        var error_node = document.getElementById("blank_vals");
        error_node.style.display = "block";
        error_node.style.background = "red";
        for(i = 0; i < blank_nodes.length; i++) {
            var curr_node = blank_nodes[i];
            curr_node.style.background = "red";
            //blank_nodes[i].style.background-color = "red";
        }
    }

    function checkInput(event) {
        console.log("Checking input...");
        // First, reset displays back to normal
        document.getElementById("blank_vals").style.display = "none";

        //document.getElementById("story").style.display = "none";
        // Then, get all of the form box nodes and their values
        var name_node = document.getElementById("usernameID"); 
        var pass_node = document.getElementById("passwordID"); 
        var name_val = name_node.value;
        var pass_val = pass_node.value;
        console.log("name: node: " + name_node + "\n\tvalue: " + name_val);
        console.log("pass: node: " + pass_node + "\n\tvalue: " + pass_val);
        if(!name_val || !pass_val) {
            event.preventDefault();
            document.getElementById("blank_vals").style.display = "block";
            document.getElementById("blank_vals").style.background = "orange";
            return;
        }



    }


};
