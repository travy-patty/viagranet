<?php
require_once "dbconfig.php";

$Username = "";
$Password = "";
$CoPassword = "";
$UsernameErr = "";
$PasswordErr = "";
$CoPasswordErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
   
    if(empty(trim($_POST["username"]))){
        $UsernameErr = "enter something";
    } else{
        $Sql = "SELECT id FROM users WHERE username = ?";
        if($PrepStatement = mysqli_prepare($SqlConn, $Sql)){
            mysqli_stmt_bind_param($Sql, "s", $PUsername);
            $PUsername = trim($_POST["username"]);          
            if(mysqli_stmt_execute($PrepStatement)){
                mysqli_stmt_store_result($PrepStatement);
                if(mysqli_stmt_num_rows($PrepStatement) == 1){
                    $UsernameErr = "fucked.";
                } else{
                    $Username = trim($_POST["username"]);
                }
            } else{
                echo "broken";
            }
            mysqli_stmt_close($PrepStatement);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $PasswordErr = "enter something.";     
    } else{
        $Password = trim($_POST["password"]);
    }
    
    if(empty(trim($_POST["confirm_password"]))){
        $CoPasswordErr = "Please confirm password.";     
    } else{
        $CoPassword = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $CoPasswordErr = "dumby";
        }
    }
    
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $Sql = "INSERT INTO users (username, password, nickname, description, pfp) VALUES (?, ?, ?, ?, ?)";
        if($PrepStatement = mysqli_prepare($SqlConn, $Sql)){
            mysqli_stmt_bind_param($SqlConn, "ss", $PUsername, $PPassword, $PNick, $PDesc, $PPfp);
            $PUsername = $Username;
            $PPassword = password_hash($Password, PASSWORD_DEFAULT);
			$PNick = $Username;
			$PDesc = "Hi Wiener Head";
		    $PfpImages = glob('assets/pfps/*');
            $PPfp = $PfpImages[rand(0, count($PfpImages) - 1)];
            if(mysqli_stmt_execute($PrepStatement)){
             echo "Cool!";
            }
            mysqli_stmt_close($PrepStatement);
        }
    }
    
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
                            <input type="text" name="confirm_password">
                <input type="submit" value="Submit">
        </form>
    </div>    
</body>
</html>