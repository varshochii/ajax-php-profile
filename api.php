

<?php 


/////////////////////////////////////////////////////////

    function query($query){
        
         $res = false;

         if(!$conn = mysqli_connect('localhost','root','','ajax_profile')){
             die('unable to connect!');
         }

         $result = mysqli_query($conn,$query);

         if($result && mysqli_num_rows($result) > 0){
              
              while($row = mysqli_fetch_assoc($result)){
                  $res[] = $row;
              }
         }

         return $res;
    }


/////////////////////////////////////////////////////////

if(count($_POST) > 0){

    $info = [];
    $info['data_type'] = $_POST['data_type'];

     if($_POST['data_type'] == 'read'){

         $query = "SELECT * FROM users ORDER BY id DESC";
         $result = query($query);
         $info['data'] = $result;

     }elseif($_POST['data_type'] == 'save'){

        $image = "";
        ///// check for image
        if(!empty($_FILES['image']['name'])){

                $allowed = ['image/jpg','image/jpeg','image/png'];
                if(in_array($_FILES['image']['type'],$allowed)){
                
                    $folder = 'uploads/';
                    if(!file_exists($folder)){
                    mkdir($folder,0777,true);
                    }
                    $path = $folder . time() . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'],$path);
                    $image = $path;
               }
         }

        $name  = addslashes($_POST['name']);
        $age   = addslashes($_POST['age']);
        $city  = addslashes($_POST['city']);
        $email = addslashes($_POST['email']);
        $image = $image;
        $date_created = date("Y-m-d H:i:s");
          
        $query = "INSERT INTO users(name,image,age,city,email,date_created) VALUES('$name','$image','$age','$city','$email','$date_created')";
        $result = query($query);
        $info['data'] = "Record was saved!";

     }elseif($_POST['data_type'] == 'delete'){

        $id = (int)$_POST['id'];
         
        $query = "DELETE FROM users WHERE id = '$id' LIMIT 1";
        $result = query($query);
        $info['data'] = 'Record Deleted!!';

     }elseif($_POST['data_type'] == 'get-row'){

        $id = (int)$_POST['id'];
        $query = "SELECT * FROM users WHERE id = '$id' LIMIT 1";  
        $result = query($query); 
        $info['data'] = false;
        if($result){
            $info['data'] = $result[0];
        }

     }elseif($_POST['data_type'] == 'edit'){ 

        $image = "";
        ///// check for image
        if(!empty($_FILES['image']['name'])){

                $allowed = ['image/jpg','image/jpeg','image/png'];
                if(in_array($_FILES['image']['type'],$allowed)){
                
                    $folder = 'uploads/';
                    if(!file_exists($folder)){
                    mkdir($folder,0777,true);
                    }
                    $path = $folder . time() . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'],$path);
                    $image = $path;
               }
         }

        $id    = (int)$_POST['id'];
        $name  = addslashes($_POST['name']);
        $age   = addslashes($_POST['age']);
        $city  = addslashes($_POST['city']);
        $email = addslashes($_POST['email']);
        $image = $image;
        $date_updated = date("Y-m-d H:i:s");
          
        if(empty($image)){

            $query = "UPDATE users SET name = '$name', age = '$age', city = '$city', email = '$email', date_updated = '$date_updated' WHERE id = '$id'";

        }else{

            $query = "UPDATE users SET name = '$name', image = '$image', age = '$age', city = '$city', email = '$email', date_updated = '$date_updated' WHERE id = '$id'";
        }


        $result = query($query);
        $info['data'] = "Record was edited!";
         
     }

     echo json_encode($info);
}

?>