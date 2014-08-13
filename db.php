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
<a href="curry2vec.php">curry2vec</a>
<a href="?mode=raw">raw</a>
<br>
MENU;

$pdo = new PDO('sqlite:corpus.db');

if( isset($_GET["mode"]) ){

    if( $_GET["mode"] === "create" ){
        $pdo->query("CREATE TABLE corpus(id,url,content)");
        header("Location: db.php");

    }elseif( $_GET["mode"] === "drop" ){
        $pdo->query("DROP TABLE corpus");
        header("Location: db.php");

    }elseif( $_GET["mode"] === "insert" ){

        $url = $_POST["url"];
        $stmt = $pdo->prepare("DELETE FROM corpus WHERE url=?");
        $stmt->execute([$url]);

        $stmt = $pdo->prepare("INSERT INTO corpus VALUES(?,?,?)");
        $stmt->execute([$d,$url,$_POST["content"]]);

        /*
        echo '<a href="'.$url.'">'.$url.'</a><br>';
        echo '<a href="db.php">db.php</a><br>';
        echo '<a href="db_check.php?url='.$url.'">db_check.php</a><br>';
        exit();
        */

        if( strlen($url) > 2 ){
            header("Location: db_check.php?url=".$url);
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
        $q = explode(" ",$_GET["q"]);
        $sql = "SELECT * FROM corpus WHERE (url LIKE ? OR content LIKE ?) AND content LIKE ? AND content LIKE ? ORDER BY id DESC LIMIT 100";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%".$q[0]."%","%".$q[0]."%","%".$q[1]."%","%".$q[2]."%"]);
    }else{
        $q = "";
        $sql = "SELECT * FROM corpus ORDER BY id DESC LIMIT 100";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        //var_dump($result);

        echo '<a href="?mode=del&id='.$result["id"].'">[x]</a> ';
        echo '<a href="db_check.php?id='.$result["id"].'">'.$result["id"].'</a> ';
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