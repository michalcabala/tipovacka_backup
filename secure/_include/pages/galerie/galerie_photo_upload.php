<?php
if ($_SERVER["SERVER_ADDR"]=="127.0.0.1" OR $_SERVER["SERVER_ADDR"]=="::1"):
    $host = 'localhost';
    $db   = 'xemilovapalenice_cz_main';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';
else:
    $host = 'localhost';
    $db   = '';
    $user = '';
    $pass = '';
    $charset = 'utf8mb4';
endif;


//pripojeni k databazovemu systemu
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$uploadDir = 'temp/';

/*if (!empty($_FILES)) {
    $tmpFile = $_FILES['file']['tmp_name'];
    $filename = $uploadDir.'/'. $_FILES['file']['name'];
    move_uploaded_file($tmpFile,$filename);
}*/

$qn_user = $_SESSION['qn_user'];

if(isset($_FILES["file"]))
{
    $ret = array();

    $error =$_FILES["file"]["error"];
    //You need to handle  both cases
    //If Any browser does not support serializing of multiple files using FormData()
    if(!is_array($_FILES["file"]["name"])) //single file
    {
        $fileName = $_FILES["file"]["name"];
        move_uploaded_file($_FILES["file"]["tmp_name"],$uploadDir.$fileName);
        $ret[]= $fileName;
        $sql = "INSERT INTO galerie_photo (galerie_id, soubor, user_i, user_u) VALUES (999999, :filename, :qn_user_i, :qn_user_u)";
        $res = $pdo->prepare($sql);
        $res->execute(['filename'=>$fileName, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
    }
    else  //Multiple files, file[]
    {
        $fileCount = count($_FILES["file"]["name"]);
        for($i=0; $i < $fileCount; $i++)
        {
            $fileName = $_FILES["file"]["name"][$i];
            move_uploaded_file($_FILES["file"]["tmp_name"][$i],$uploadDir.$fileName);
            //$ret[]= $fileName;
            $sql = "INSERT INTO galerie_photo (galerie_id, soubor, user_i, user_u) VALUES (999999, :filename, :qn_user_i, :qn_user_u)";
            $res = $pdo->prepare($sql);
            $res->execute(['filename'=>$fileName, 'qn_user_i'=>$qn_user, 'qn_user_u'=>$qn_user]);
        }

    }
    //echo json_encode($ret);
}

