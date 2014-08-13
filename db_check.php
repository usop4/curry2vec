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

$id = "";

if( strstr($_SERVER["HTTP_REFERER"],"curry2vec") ){
    echo '<h3>登録が完了しました</h3>';
}

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
    echo '<p>';
    echo '<a href="?id='.$id.'">'.$id.'</a> ';
    echo '<a href="'.$url.'">'.$url.'</a>';
    echo '</p>';
    echo '<form role="form" action="db.php?mode=insert" method="post">';
    echo '<input type="hidden" name="url" value="'.$url.'">';
    echo '<textarea name="content" rows="10" cols="100">'.$content.'</textarea><br>';
    echo '<input type="submit">※修正したい場合のみ（手作業で広告を除去など）';
    echo '</form>';
    file_put_contents("temp.txt",$content);

    $checked = exec("zsh /home/barcelona/www/curry/db_check.sh");
    echo '<p>Mecab処理プレビュー（開発者用）</p>';
    echo '<textarea name="content" rows="10" cols="100">'.$checked.'</textarea><br>';
    echo '<p>Mecab辞書追加用（開発者用）</p>';
    echo <<<FORM
<form method="post" action="db_check.php?mode=mecab&id={$id}">
<input name="k1" type="text">
<input name="k2" type="text" value="店舗">
<input type="submit">
</form>
FORM;

    if( isset($_GET["mode"]) ){
        if( $_GET["mode"] === "mecab" ){
            $cmd = $_POST["k1"].",*,*,100,名詞,".$_POST["k2"].",*,*,*,*,,,";
            echo $cmd;
            exec("echo ".$cmd." >> /home/barcelona/www/curry/bin/Others.csv");
        }
    }


}

echo '<p><a href="db_check.php">next</a></p>';
echo '<p><a href="db.php">db.php</a></p>';

?>