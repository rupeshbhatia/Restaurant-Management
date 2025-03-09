<?php
session_start();
include "connection.php";
$var=1;
$num=0;
$row;
if(isset($_SESSION['new'])) {
    $edit_id = $_SESSION['new'];
    $sql = "SELECT * FROM formdata WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "No record found";
    }
} else {
    echo "No ID provided";
}

if(isset($_POST['update'])) {
    $updatedAdd= $conn->real_escape_string($_POST['address']);
    $updatedCity = $conn->real_escape_string($_POST['city']);
    $updatedState = $conn->real_escape_string($_POST['state']);
    $updatedPhone = $conn->real_escape_string($_POST['phone']);
    $updatedPin = $conn->real_escape_string($_POST['pin']);
    $updateSql = "UPDATE formdata SET   address = ?,city = ?,state= ? ,phone=?,pin=? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssiii", $updatedAdd,$updatedCity, $updatedState, $updatedPhone,$updatedPin, $edit_id);
    $updateStmt->execute();

    if ($updateStmt->affected_rows > 0) {
        echo "Record updated successfully";
        // Redirect or further processing
    } else {
        if($updateStmt->error) {
            echo "Error updating record: " . $updateStmt->error;
        } else {
            echo "No changes were made to the record.";
        }
    }
    // $updateStmt->close();
}
$usernameErr="";
$emailErr="";
if(isset($_POST['add_employee'])) {
    $e_address= $conn->real_escape_string($_POST['e_address']);
    $e_name= $conn->real_escape_string($_POST['e_name']);
    $e_state = $conn->real_escape_string($_POST['e_state']);
    $e_phone = $conn->real_escape_string($_POST['e_phone']);
    $e_username = $conn->real_escape_string($_POST['e_username']);
    $e_password = $conn->real_escape_string($_POST['e_password']);
    $e_pin = $conn->real_escape_string($_POST['e_pin']);
    $e_role = $conn->real_escape_string($_POST['e_role']);
    $e_email = $conn->real_escape_string($_POST['e_email']);


    $sql="SELECT * FROM employees WHERE  employee_username = '$e_username'";
    $result = $conn->query($sql);
    $check = mysqli_fetch_array($result);
    $sql2="SELECT * FROM employees WHERE  employee_email = '$e_email'";
    $result2 = $conn->query($sql2);
    $check2 = mysqli_fetch_array($result2);
    if(isset($check)){
        $usernameErr="username exists";
    }else if(isset($check2)){
       $emailErr="email exists";
    }else{
        $sql3 = "INSERT INTO employees (employee_name,employee_address, employee_state,
        employee_phone,employee_username,employee_password,employee_pincode,role,employee_email) VALUES ( ?,?,?,?,?,?,?,?,?)";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("sssssssis", $e_name,$e_address, $e_state, $e_phone,$e_username,$e_password,$e_pin,$e_role, $e_email);
        if ($stmt3->execute()) {
            include "employee_added.html";
       
        } else {
                $errorMsg =  "Error: " . $insertSql->error;
        }
    }
}
// $conn->close();

 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nova+Square&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<style>
.logo p {
    margin: 0;
    padding: 0;
}

.links {
    display: flex;
    /* padding: 0 1rem; */
    /* gap: 1rem; */
    width: 88%;
}

.links p {
    margin: 0;
    padding: 0;
}

a {
    text-decoration: none;
    text-transform: capitalize;
    color: black;
}

.links>a {
    width: 100%;
    display: flex;
    gap: 1rem;
}

.thenavbanner {
    height: 15vh;
    width: 100%;
    background-image: url(fastfood.jpg);
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
}

#main_logo_a {
    display: flex;
    justify-content: space-between;
}

#nav {
    transition: 0.5s ease all;
}

#main_container {
    transition: 0.5s ease all;
}

th {
    font-size: 0.8rem;
}

label {
    display: block;
    margin-bottom:0.4rem;
}

input ,select,textarea{
    outline: none;
    border: 1px solid white;
    border-bottom: 2px solid #d6d6d6;
    border-right: 2px solid #d6d6d6;
    height: 1.9rem;
    width: 90%;
    border-radius: 4px;
    padding-left: 10px;
}
select{
    width:92%;
    height: 2.4rem;
}
button {
    outline: none;
    border: 1px solid #FF8400;
    background-color: #FF8400;
    border-radius: 7px;
    color: white;
    padding: 0.3rem 1rem;
    box-shadow: 1px 1px 2px grey;
}
</style>

<body>


    <!-- new  -->

    <div class="container">
        <div class="thenavbanner">
        </div>
        <div class="sidebar" id="nav">
            <div class='buttons'>
                <div class="logo links">
                    <a href="#" id="main_logo_a">
                        <p class="nav_link">Brother's Restaurant</p>
                        <span class="nav_main_logo ">
                            <i onclick="slide(this)" class="fa-solid nav_logo fa-angle-left"></i>
                        </span>
                    </a>
                </div>
            </div>

            <div class="buttons" onclick="display('dashbord')">
                <div class="links">
                    <a href="#" class="main_a">
                        <span>
                            <i class="fa-solid fa-igloo nav_logo"></i>
                        </span>
                        <p class="nav_link">Dashbord</p>
                    </a>
                </div>
            </div>

            <div class="buttons" onclick="display('table1')">
                <div class="links">
                    <a href="#" class="main_a">
                        <span>
                            <i class="fa-solid fa-user-pen nav_logo"></i>
                        </span>
                        <p class="nav_link">Manage Profile</p>
                    </a>
                </div>
            </div>

            <div class="buttons">
                <div class="links">
                    <a href="manageorder.php" class="main_a">
                        <span>
                            <i class="fa-solid fa-file-pen nav_logo"></i>
                        </span>
                        <p class="nav_link">Manage Orders</p>
                    </a>
                </div>
            </div>


            <div class="buttons">
                <div class="links">
                    <a href="storeadditems.php" class="main_a">
                        <span>
                            <i class="fa-solid fa-pen-to-square nav_logo"></i>
                        </span>
                        <p class="nav_link">Manage items</p>
                    </a>
                </div>
            </div>

            <div class="buttons">
                <div class="links">
                    <a href="reservation.php" class="main_a">
                        <span>
                            <i class="fa-solid fa-pen-to-square nav_logo"></i>
                        </span>
                        <p class="nav_link">Manage Reservations</p>
                    </a>
                </div>
            </div>

            <div class="buttons">
                <div class="links">
                    <a href="manageuser.php" class="main_a">
                        <span>
                            <i class="fa-solid fa-file-circle-plus nav_logo"></i>
                        </span>
                        <p class="nav_link">Users/Employees</p>
                    </a>
                </div>
            </div>
            <div class="buttons" onclick="display('table2')">
                <div class="links">
                    <a href="#" class="main_a">
                        <span>
                            <i class="fa-solid fa-file-pen nav_logo"></i>
                        </span>
                        <p class="nav_link">Add Employee</p>
                    </a>
                </div>
            </div>

            <div class="buttons" onclick="display('table3')">
                <div class="links">
                    <a href="#" class="main_a">
                        <span>
                            <i class="fa-solid fa-plus nav_logo"></i>
                        </span>
                        <p class="nav_link">Contact us Data</p>
                    </a>
                </div>
            </div>

            <div class="buttons logoutbtn" onclick="logout()">
                <div class="links">
                    <a href="logout.php" class="main_a">
                        <span class="nav_logo">
                            <i class="fa-solid fa-right-from-bracket nav_logo"></i>
                        </span>
                        <p class="nav_link">Log Out</p>
                    </a>
                </div>
            </div>
        </div>
        <div class="maincontainer" id='main_container'>
            <div class="nav">
                <span class="heading">ADMIN'S PAGE</span>
                <span class="profile">

                    <img class="profilepicture" src="admin.jfif" alt="">
                    <p><?php echo $_SESSION["names"];?></p>
                </span>
            </div>

            <div class="totalcontainer" id="dashbord">
                <div class="third">
                    <div class="textbanner">
                        WELCOME &nbsp; <span style="text-transform:uppercase;"><?php echo $_SESSION["names"];?></span>
                    </div>

                </div>
                <div class="boxes">
                    <div class="total totalpersons">
                        <div class="num">
                            <?php
                               $sql3="SELECT * FROM formdata WHERE prole='1'";
                               if($result=mysqli_query($conn,$sql3)){
                                   $rowcount=mysqli_num_rows($result);
                               }
                            ?>
                            <h1><?php echo $rowcount;?></h1>
                            <i class="fa-solid fa-person-circle-check"></i>
                        </div>
                        <span class="flag">TOTAL</span>
                        <p>Total Users</p>
                    </div>
                    <div class="total totalcourses">
                        <div class="num">
                            <?php
                            $sql4="SELECT * FROM orders";
                            if($result=mysqli_query($conn,$sql4)){
                                $rowcount=mysqli_num_rows($result);
                            }
                            ?>
                            <h1><?php echo $rowcount;?></h1>
                            <i class="fa-solid fa-book"></i>
                        </div>
                        <span class="flag">TOTAL</span>
                        <p>Orders </p>
                    </div>
                    <div class="total totaldepartments">
                        <div class="num">
                            <?php
                            $sql5="SELECT * FROM orders WHERE orderstatus='delivered'";
                            $result1=$conn->query($sql5);
                            $total_earnings=0;
                            while($row1=$result1->fetch_assoc()){
                                $total_earnings+=($row1['price']*$row1['quantity']);
                            }
                            ?>
                            <h1>Rs. <?php echo $total_earnings; ?> </h1>
                            <i class="fa-solid fa-coins"></i>
                        </div>
                        <span class="flag">TOTAL</span>
                        <p>Earnings By Online Mode</p>
                    </div>
                </div>

            </div>

            <div id='total_students_table' style="margin-bottom:2rem;">
                <div id='table1'>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="text-align-justify">

                        <div class="form-group ">
                            <label>Name:</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"
                                class="form-control" disabled>
                        </div>

                        <div class="form-group">
                            <label>Address:</label>
                            <input type="text" name="address" value="<?php echo htmlspecialchars($row['address']); ?>"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label>City:</label>
                            <input type="text" name="city" value="<?php echo htmlspecialchars($row['city']); ?>"
                                class="form-control">
                        </div>

                        <div class="form-group">
                            <label>State:</label>
                            <input type="text" name="state" value="<?php echo htmlspecialchars($row['state']); ?>"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Pin:</label>
                            <input type="text" name="pin" value="<?php echo htmlspecialchars($row['pin']); ?>"
                                class="form-control">
                        </div>
                        <br>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                        <!-- <a href="index.php">Back</a> -->
                    </form>
                </div>

            </div>


            <div id='total_courses_table'>
                <div id="table2" style="margin-bottom:3rem;">
                    <form style="display:flex;gap:2rem;" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="text-align-justify">
                    <div style="width:50%">
                    <div class="form-group ">
                            <label>Name:</label>
                            <input type="text" name="e_name" class="form-control" >
                        </div>

                        <div class="form-group">
                            <label>Address:</label>
                            <textarea   name="e_address"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input type="text" name="e_phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>State:</label>
                            <input type="text" name="e_state" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="e_email" class="form-control">
                            <span><?php echo $emailErr; ?></span>
                        </div>
                    </div>
                    <div style="width:50%">
                  
                        <div class="form-group">
                            <label>Area Pincode:</label>
                            <input type="text" name="e_pin" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Username:</label>
                            <input type="text" name="e_username" class="form-control">
                            <span><?php echo $usernameErr; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Password:</label>
                            <input type="password" name="e_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Employee Role:</label>
                            <select name="e_role">
                                <option selected>Select Employee Role</option>
                                <option value="1">Chef</option>
                                <option value="2">Reception</option>
                                <option value="3">Delivery Boy</option>
                            </select>
                        </div>
                        <br>
                        <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
                        <!-- <a href="index.php">Back</a> -->
                    </div>
                      
                        
                    </form>
                </div>
            </div>

            <?php   $sql="SELECT * FROM contactus";
$result=$conn->query($sql);
if($result->num_rows>0){
        echo "<div id='total_departments_table'>  <table id='table3'>
       <tr>
       <th>S.No.</th>
       <th>Name</th>
       <th>Email</th>
       <th>Subject</th>
       <th>Message</th>
       </tr>
        ";
    while($row=$result->fetch_assoc()){
echo "
<tr>
<td>".$var++  . "</td>
<td>" .$row['sname']."</td>
<td>" .$row['email']."</td>
<td>" .$row['subject']++. "</td>
<td>"  .$row['msg']. "</td>
<tr>
";
}echo "</table></div>";
}
?>







        </div>
    </div>




    <script src="admin.js"></script>

</body>

</html>