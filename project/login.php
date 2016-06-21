<html>
<head>
    <title>Story Sign In</title>
    <script type="text/javascript" src="js/verify_login.js"></script>
    <style type="text/css">
        table {
            border: 1px solid gray;
            text-align: center;
        }
        .error, #blank_vals, #display_values, #story {
            display: none;
        }

    </style>
</head>
<body>

<h2>Story Sign In</h2>

<p id="blank_vals">Please enter a username AND password</p>
<table>
    <tr>
        <td id="post">
            <fieldset>
                <legend>Information</legend>
                <form id="login_form" action="checkAccount.php" method="post">
                    Username: 
                    <input id="usernameID" type='text' name='username' value=''/><br/>
                    <br/>
                    Password: 
                    <input id="passwordID" type="password" name="password" value=""/><br/>
                    <br/>
                    <input type="submit" name="postSubmit" value="Login"/>
                </form>
            </fieldset>

                <?php
                // If the username is set in POST,
                if (isset($_POST["username"])) {
                    include_once 'storyDB.php';
                    $name = $_POST["username"];
                    $_SESSION["username"] = $name;
                    echo "<h4>Logging in as $name</h4>";
                    echo "setting username";
                    // resend to same page -> attempting to update
                    //header("Location: index.php");
                }
                ?>


        </td>
</table>
