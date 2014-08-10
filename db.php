<?php
/**
 * Created by PhpStorm.
 * User: tueha_000
 * Date: 14/07/26
 * Time: 11:25
 */

$date = new DateTime($time="now",new DateTimeZone('Asia/Tokyo'));
$d = $date->format('Y-m-dTH:i:s');

$menu = <<<MENU
<form>
<input type="text" name="q" value="{$_GET["q"]}">
<input type="submit" value="検索">
</form>
<a href="curry2vec.php">index</a>
<a href="?mode=raw">raw</a>
<br>
MENU;

$pdo = new PDO('sqlite:copus.db');

if( isset($_GET["mode"]) ){

    if( $_GET["mode"] === "create" ){
        $pdo->query("CREATE TABLE corpus(id,url,content)");
        header("Location: db.php");

    }elseif( $_GET["mode"] === "drop" ){
        $pdo->query("DROP TABLE corpus");
        header("Location: db.php");

    }elseif( $_GET["mode"] === "insert" ){

        $stmt = $pdo->prepare("DELETE FROM corpus WHERE url=?");
        $stmt->execute([$_POST["url"]]);

        $stmt = $pdo->prepare("INSERT INTO corpus VALUES(?,?,?)");
        $stmt->execute([$d,$_POST["url"],$_POST["content"]]);
        if( strlen($_POST["url"]) > 2 ){
            header("Location: ".$_POST["url"]);
        }else{
            header("Location: curry2vec.php");
        }

    }elseif( $_GET["mode"] === "del" ){
        $stmt = $pdo->prepare("DELETE FROM corpus WHERE id=?");
        $stmt->execute([$_GET["id"]]);
        header("Location: ".$_SERVER["HTTP_REFERER"]);

    }elseif( $_GET["mode"] === "raw" ){
        $stmt = $pdo->prepare("SELECT * FROM corpus ORDER BY id DESC");
        $stmt->execute();
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo $result["content"]."\n";
        }

    }else{
        echo $menu;
        var_dump($_GET);
    }
}else{

    //var_dump($_SERVER);
    echo $menu;

    if( isset($_GET["q"]) ){
        $q = $_GET["q"];
        $sql = "SELECT * FROM corpus WHERE content LIKE '%".$q."%' ORDER BY id DESC LIMIT 100";
    }else{
        $q = "";
        $sql = "SELECT * FROM corpus ORDER BY id DESC LIMIT 100";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        //var_dump($result);

        echo '<a href="?mode=del&id='.$result["id"].'">[x]</a> ';
        echo $result["id"]." ";
        echo '<a href="'.$result["url"].'">'.urldecode($result["url"]).'</a> ';
        if( @strpos($result["content"],$q) ){
            @$start = strpos($result["content"],$q);
        }else{
            $start = 0;
        }

        echo mb_substr($result["content"],$start,30,$encoding='utf-8');
        echo "[".mb_strlen($result["content"])."]";
        echo "<br>\n";
    }
}

?>