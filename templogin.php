<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
require_once "dbconf.php";
 
$Username = "";
$Password = "";
$CoPassword = "";
$UsernameErr = "";
$PasswordErr = "";
$LoginErr = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty(trim($_POST["username"]))){
        $UsernameErr = "enter something";
    } else{
        $Username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $PasswordErr  = "enter something";
    } else{
        $Password = trim($_POST["password"]);
    }
    
    if(empty($UsernameErr) && empty($PasswordErr)){
        $Sql = "SELECT id, username, password FROM users WHERE username = ?";
        if($PrepStatement = mysqli_prepare($SqlConn, $Sql)){
            mysqli_stmt_bind_param($PrepStatement, "s", $PUsername);      
            $PUsername = $Username;
            if(mysqli_stmt_execute($PrepStatement)){
                mysqli_stmt_store_result($PrepStatement);
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    mysqli_stmt_bind_result($PrepStatement, $Id, $Username, $HPassword);
                    if(mysqli_stmt_fetch($PrepStatement)){
                        if(password_verify($Password, $HPassword)){
                            session_start();               
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            header("location: index.php");
                        } else{
                            $LoginErr = "wrong pass.";
                        }
                    }
                } else{
                    $LoginErr = "you dont exist dumby.";
                }
            mysqli_stmt_close($PrepStatement);
        }
		}}
    
	
    mysqli_close($SqlConn);
}
?>
 
 
<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body> 
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">   
                <input type="text" name="username">
                <input type="text" name="password">
                <input type="submit" value="Submit">
        </form>
    </div>    
</body>
</html>