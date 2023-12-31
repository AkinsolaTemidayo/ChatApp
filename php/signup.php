<?Php 
    session_start();
    include_once "config.php";
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if(!empty($fname) && !empty($lname) && !empty($email) && !empty($password)){
        //checking if user email is valid or not
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){ //If email is valid
            // checking if email already exist in the database or not
            $sql = mysqli_query($conn, "SELECT email FROM users WHERE email = '{$email}'");
            if(mysqli_num_rows($sql) > 0){ //if email already exist
                echo "$email - This email already exist!";
            }else{
                //checking if user upload file or not
                if(isset($_FILES['image'])){ // if file is uploaded 
                     $img_name = $_FILES['image']['name'];//getting user uploaded img name
                     $tmp_name = $_FILES['image']['tmp_name'];//this temporary file is used to move/save file in our folder

                     //exploding image and get the last extension  last jpg png
                     $img_explode = explode('.', $img_name);
                     $img_ext = end($img_explode);//here we get the extension of user uploaded image file

                     $extensions = ['png', 'jpeg', 'jpg'];//these are some valid img ext and we've store them in array
                     if(in_array($img_ext, $extensions) === true){//if user uploaded img ext is matched with any array extension
                        $time = time();//this will return current time...
                        $new_img_name = $time.$img_name;
                        
                        if(move_uploaded_file($tmp_name, "images/".$new_img_name)){ //if user upload img move to our folder successfully
                            $status = "Active now"; //once the user sign up then his status will be active now
                            $random_id = rand(time(), 10000000); //creating random id for user

                            // inserting all user data inside table
                            $sql2 = mysqli_query($conn, "INSERT INTO users (unique_id, fname, lname, email, password, img, status)
                                                VALUES({$random_id}, '{$fname}', '{$lname}', '{$email}', '{$password}','{$new_img_name}','{$status}')");
                            if($sql2){//if these data inserted
                                $sql3 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                                if(mysqli_num_rows($sql3) > 0){
                                    $row = mysqli_fetch_assoc($sql3);
                                    $_SESSION['unique_id'] = $row['unique_id']; 
                                    echo "success";
                                }
                            }else{
                                echo "Something went wrong!";
                            }
                        }
                     }else{
                        echo "Please select an image file - jpeg, jpg, png!";
                     }
                }else{
                    echo "Please select an image file!";
                }
            }
        }else{
            echo "$email, - This is not a valid email";
        }
    }else{
        echo "All input field are required";
    }
?>