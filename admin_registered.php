<?php
include "connect.php";
include "email_template.php";
$name=$_POST['name'];
$des=$_POST['des'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$insert_master=mysql_query("insert into master set username='$email',password='$name',type='A'") or die("insertion problem");
$uid=mysql_insert_id();
$insert=mysql_query("insert into admin set name='$name',Designation='$des',email='$email',phone='$phone',mid='$uid'") or die("cannot insert");
$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
    
    # show key size use either 16, 24 or 32 byte keys for AES-128, 192
    # and 256 respectively
    $key_size =  strlen($key);
    echo "Key size: " . $key_size . "\n";
    
    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    
    # creates a cipher text compatible with AES (Rijndael block size = 128)
    # to keep the text confidential 
    # only suitable for encoded input that never ends with value 00h
    # (because of default zero padding)
    $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                 $uid, MCRYPT_MODE_CBC, $iv);

    # prepend the IV for it to be available for decryption
    $ciphertext = $iv . $ciphertext;
    
    # encode the resulting cipher text so it can be represented by a string
    $ciphertext_base64 = base64_encode($ciphertext);

    //echo  $ciphertext_base64 . "\n";

    # === WARNING ===

    # Resulting cipher text has no integrity or authenticity added
    # and is not protected against padding oracle attacks.
    

//'authentication.php?$uid='".$ciphertext_base64."'>
echo $ciphertext_base64;
$body="<!DOCTYPE html >
<html>
<head>
<title>Untitled Document</title>
</head>
<body>
<a href="."'localhost/major_sa/authenticate.php?uid=".$ciphertext_base64."'>Click Verify</a>
</body>
</html>";
sendmail($email,'Email Verification',$body);
echo "Registration Successful";
?>