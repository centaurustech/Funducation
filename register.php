<!DOCTYPE html>
<?php
require("header.php");

// check if user is taken in a cerstain table
function checktableuser($conn, $tablename, $username)
{
    $query = sprintf("select * from %s where name='%s'", $tablename, $username);
    return mysqli_num_rows(mysqli_query($conn, $query));
}

// validate user if POST, else if logged in, redirect to index page
if(!empty($_POST))
{
    if(!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm']))
    {
        $roles = array("donors", "teachers", "principals");

        $email = mysqli_escape_string($conn, $_POST['email']);
        $username = mysqli_escape_string($conn, $_POST['username']);
        $password = mysqli_escape_string($conn, $_POST['password']);
        $confirm = mysqli_escape_string($conn, $_POST['confirm']);
        $role = $roles[$_POST['role']];
        
        if(checktableuser($conn, 'donors', $username) == 0 
        && checktableuser($conn, 'principals', $username) == 0
        && checktableuser($conn, 'teachers', $username) == 0)
        {
            if($password == $confirm)
            {
                $query = sprintf("insert into %s (name, email, password) values ('%s', '%s', '%s');", $role, $username, $email, $password);
                $user_insert = mysqli_query($conn, $query) or die(mysqli_error($conn)); 
                echo "Registered successfully!";
            }
            else
            {
                echo 'Passwords do not match!';
            }
        }
        else
        {
           echo 'Username is already taken!';
        }
    }
    else
    {
        echo 'Empty fields!';
    }
}
else
{
    if(!empty($_SESSION['logged_in']) && $_SESSION['logged_in'])
    {
        header("Location: index.php");
        exit();
    }
}
?>

<form action="" method="post">
    <div class="login-register-container">

        <div style="flex:2"></div>

        <div class="login-register-shape">

            <div>
                <p style="font-size:300%; margin:20px">Sign Up</p>
            </div>                   
            <div class="form-container">
                <div>
                    <input class="inputthing" name="username" type="text" placeholder="Username" autofocus>
                </div>
                <div>
                    <input class="inputthing" name="email" type="text" placeholder="E-mail">
                </div>
                <div>
                    <input class="inputthing" name="password" type="password" placeholder="Password">
                </div>
                <div>
                    <input class="inputthing" name="confirm" type="password" placeholder="Confirm Password">
                </div>
            </div>
            <div style="font-size: 123%;">
                <p style="margin: 10px">Registering as:</p>
                <label><input type="radio" name="role" value="0" checked>Donor<br></label>
                <label><input type="radio" name="role" value="2">Principal<br></label>
            </div>

            <div style="margin-top:40px">
                <input style ="font-size:150%; margin-bottom:40px" value="Register" type="submit">
            </div>
        </div>

        <div style="flex:2"></div>
    </div>
</form>
<?php require('footer.php'); ?>