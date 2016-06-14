
<!-- This file demonstrates
    1.) Displaying the current story id data -> $_GET for story_id
    2.) Display the current event data -> $_POST for event_id
        so users can't see other options
    3.) Accept votes for what option to choose
-->
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Story List</title>
    <style type="text/css">
        #post {
            vertical-align: top;
            }
        #LISTTABLE table td, #LISTTABLE table th, #LISTTABLE table,
        #LinkTable table, #LinkTable table td{
            border: 1px solid gray;
            text-align: center;
        }


    </style>
</head>
<body>

<h1>Available Stories </h1>



<p id="demo"></p>

<script src="sortProfs.js"></script>
<script>
    var table_html = document.getElementById("LISTTABLE");
    console.log(table_html);

    // Note: I know there is a better way to add same action to multiple
    // objects where they don't all change/mess with the params of the
    // other listeners, yet this is sufficient for now.
    // TODO: use a loop to add the listeners (in case more cols are added).
    var headers = table_html.getElementsByTagName("th");
    console.log(headers);
    var h0 = headers[0].innerHTML;
    headers[0].addEventListener("click", function(){
            testing("LISTTABLE", h0, 0);
    });
    var h1 = headers[1].innerHTML;
    headers[1].addEventListener("click", function(){
            testing("LISTTABLE", h1, 1);
    });
    var h2 = headers[2].innerHTML;
    headers[2].addEventListener("click", function(){
            testing("LISTTABLE", h2, 2);
    });
    var h3 = headers[3].innerHTML;
    headers[3].addEventListener("click", function(){
            testing("LISTTABLE", h3, 3);
    });

    /*for ( i = 0; i < headers.length; i++) {
        console.log("headers[" + i + "]: " + headers[i]);
        var hh = headers[i].innerHTML;
        console.log("headers[" + i + "] html: " + headers[i].innerHTML);
        headers[i].addEventListener("click", function(){
            testing("LISTTABLE", hh, i);
        });
    }
    */
</script>

<hr>
<p>Home</p>
<p>Create Account / Sign In / Logout</p>
<!--<h4><a href='index.php'>Home<a></h4>
<a href='logout.php'>Logout</a>-->


</body>
</html>
