 <?php  
 $connect = mysqli_connect("localhost", "root", "root", "kartofliska_01");  
 session_start();  
 if(isset($_SESSION["username"]))  
 {  
      header("location:entry.php");  
 }  
 if(isset($_POST["register"]))  
 {  
      if(empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["password2"]))  
      {  
           echo '<script>alert("Uzupełnij dane!")</script>';  
      }

      else
     {
     if ($_POST["password"]!=$_POST["password2"]){
               echo '<script>alert("Hasła muszą być takie same")</script>';
          }
     elseif (strlen($_POST["password"]) < 8) {
          echo '<script>alert("Hasło zbyt krótkie.")</script>';
      }
  
     elseif (!preg_match("#[0-9]+#", $_POST["password"])) {
          echo '<script>alert("Hasło musi zawierać przynajmniej jedną cyfrę.")</script>';
      }
  
     elseif (!preg_match("#[a-zA-Z]+#", $_POST["password"])) {
          echo '<script>alert("Hasło musi zawierać przynajmniej jedną literę.")</script>';
      }     
     else
      {  
           $username = mysqli_real_escape_string($connect, $_POST["username"]);  
           $password = mysqli_real_escape_string($connect, $_POST["password"]);  
           $password = md5($password);  
           $query = "INSERT INTO user (username, password) VALUES('$username', '$password')";  
           if(mysqli_query($connect, $query))  
           {  
                echo '<script>alert("Rejestracja pomyślna!")</script>';  
           }
           else  {
               echo '<script>alert("Nie udało się połączyć z baża danych.")</script>';
           }
      }  
     }
 }  
 if(isset($_POST["login"]))  
 {  
      if(empty($_POST["username"]) && empty($_POST["password"]))  
      {  
           echo '<script>alert("Uzupełnij dane!")</script>';  
      }  
      else  
      {  
           $username = mysqli_real_escape_string($connect, $_POST["username"]);  
           $password = mysqli_real_escape_string($connect, $_POST["password"]);  
           $password = md5($password);  
           $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";  
           $result = mysqli_query($connect, $query);  
           if(mysqli_num_rows($result) > 0)  
           {  
                $_SESSION['username'] = $username;  
                header("location:entry.php");  
           }  
           else  
           {  
                echo '<script>alert("Złe dane!")</script>';  
           }  
      }  
 }  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Kartofliska</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      </head>  
      <body>  
           <br /><br />  
           <div class="container" style="width:500px;">  
                <h3 align="center">Kartofliska</h3>  
                <br />  
                <?php  
                if(isset($_GET["action"]) == "login")  
                {  
                ?>  
                <h3 align="center">Login</h3>  
                <br />  
                <form method="post">  
                     <label>Podaj nazwę użytkownika</label>  
                     <input type="text" name="username" class="form-control" />  
                     <br />  
                     <label>Podaj hasło</label>  
                     <input type="password" name="password" class="form-control" />  
                     <br />  
                     <input type="submit" name="login" value="Zaloguj" class="btn btn-info" />  
                     <br />  
                     <p align="center"><a href="register.php">Rejestracja</a></p>  
                </form>  
                <?php       
                }  
                else  
                {  
                ?>  
                <h3 align="center">Wprowadź dane</h3>  
                <br />  
                <form method="post">  
                     <label>Podaj nazwę użytkownika</label>  
                     <input type="text" name="username" class="form-control" />  
                     <br />  
                     <label>Podaj hasło</label>  
                     <input type="password" name="password" class="form-control" />  
                     <label>Powtórz hasło</label>  
                     <input type="password" name="password2" class="form-control" /> 
                     <br />  
                     <input type="submit" name="register" value="Zarejestruj" class="btn btn-info" />  
                     <br />  
                     <p align="center"><a href="register.php?action=login">Logowanie</a></p>  
                </form>  
                <?php  
                }  
                ?>  
           </div>  
      </body>  
 </html>  