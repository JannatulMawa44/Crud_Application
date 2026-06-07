<?php
include '../function.php';
session_start();


$name = test_user($_POST['name']);
$email = test_user($_POST['email']);
$btn = test_user($_POST['submit']);
$experience = test_user($_POST['experience']);
$description = test_user($_POST['description']);
$project = test_user($_POST['project']);
$profile_image = $_FILES['profile_image'];

if (isset($btn)) {
    

    if (empty($name)) {
        $_SESSION['name_err'] = 'Name is required';
        header("location: ../index.php");
        exit();
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $_SESSION['name_err'] = 'Only letters and white space allowed';
        header("location: ../index.php");
        exit();
    }
    
    if (empty($email)) {
        $_SESSION['email_err'] = 'Email is required';
        header("location: ../index.php");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email_err'] = 'Invalid email format';
        header("location: ../index.php");
        exit();
    }
    //description validation
    if (empty($description)) {
        $_SESSION['description_err'] = 'Description is required';
        header("location:../index.php");
        exit();
    }
    //file handle
    if (isset($_FILES['profile_image'])) {
        if (empty($profile_image['name'])) {
            $_SESSION['image_err'] = 'Profile image is required';
            header("location:../index.php");
            exit();
        }
//extension check
        $image_name = $profile_image['name'];
        $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        print_r($file_extension);
        $allowed = ['jpg', 'png', 'jpeg', 'webp'];
        if (!in_array($file_extension, $allowed)) {
            $_SESSION['image_err'] = 'Invalid Image';
            header("location:../index.php");
            exit();
        }
    }

// image
    $image_location = $profile_image['tmp_name'];
    $new_image_name = uniqid("user_") . '.' . $file_extension;
    $image_url = "http://localhost/Assignment_2/uploads/" . $new_image_name;
    
    //store in DB
    include('../config/db.php');
    $stmt = $conn->prepare("INSERT INTO users(name, email, description, experience, project, image_name, image_url) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param(
        "sssssss",
        $name,
        $email,
        $description,
        $experience,
        $project,
        $new_image_name,
        $image_url
    );
    $insert = $stmt->execute();




    if ($insert) {
        move_uploaded_file($image_location, '../uploads/' . $new_image_name);
        $_SESSION['sucess'] = "Add user sucessfully";
        header("location:../index.php");
    }
}
