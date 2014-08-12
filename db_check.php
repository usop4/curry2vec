<?php
/**
 * Created by PhpStorm.
 * User: tueha_000
 * Date: 14/08/12
 * Time: 18:07
 */

header("Content-Type: text/html; charset=UTF-8");

$date = new DateTime($time="now",new DateTimeZone('Asia/Tokyo'));
$d = $date->format('Y-m-dTH:i:s');

$pdo = new PDO('sqlite:corpus.db');

if( isset($_GET["id"]) ){
    $id = $_GET["id"];
    $sql = "SELECT * FROM corpus WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}elseif( isset($_GET["url"]) ){
    $url = $_GET["url"];
    $sql = "SELECT * FROM corpus WHERE url=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$url]);
}else{
    $sql = "SELECT * FROM corpus ORDER BY RANDOM() LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $id = htmlspecialchars($result["id"]);
    $url = htmlspecialchars($result["url"]);
    $content = htmlspecialchars($result["content"]);
    echo '<p><a href="?id='.$id.'">'.$id.'</a></p>';
    echo '<p><a href="'.$url.'">'.$url.'</a></p>';
    echo '<form role="form" action="db.php?mode=insert" method="post">';
    echo '<input type="hidden" name="url" value="'.$url.'">';
    echo '<textarea name="content" rows="10" cols="100">'.$content.'</textarea><br>';
    echo '<input type="submit">';
    echo '</form>';
    file_put_contents("temp.txt",$content);
}

echo exec("mecab -d /home/barcelona/src/newDict -Owakati temp.txt");

echo '<p><a href="db_check.php">next</a></p>';
echo '<p><a href="db.php">db.php</a></p>';

?>