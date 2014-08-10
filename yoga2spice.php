<?php

$date = new DateTime($time="now",new DateTimeZone('Asia/Tokyo'));

$menu = <<<MENU
<p><a href="?mode=drop">reset</a></p>

MENU;

$pdo = new PDO('sqlite:yoga2spice.db');

if( isset($_GET["mode"]) ){

    if( $_GET["mode"] === "create" ){
        $pdo->query("CREATE TABLE yoga(name,effect,point INTEGER)");
        $pdo->query("CREATE TABLE spice(name,effect,point INTEGER)");

        // http://yogajo.jp/pose/efficacy
        $stmt = $pdo->prepare("INSERT INTO yoga VALUES(?,?,?)");
        $stmt->execute(["バラドヴァジャーサナ","腰痛緩和",0]);
        $stmt->execute(["バラドヴァジャーサナ","ストレス解消",0]);
        $stmt->execute(["スカーサナ","ストレス解消",0]);
        $stmt->execute(["スカーサナ","リラックス",0]);
        $stmt->execute(["アグニスタンバーサナ","ストレス解消",0]);
        $stmt->execute(["アグニスタンバーサナ","リラックス",0]);
        //$stmt->execute(["パダングシュターサナ","ストレス解消",0]);
        //$stmt->execute(["パダングシュターサナ","頭痛緩和",0]);
        $stmt->execute(["ドルフィン・ポーズ","ストレス解消",0]);
        $stmt->execute(["ドルフィン・ポーズ","疲労回復",0]);
        $stmt->execute(["ドルフィン・ポーズ","頭痛緩和",0]);
        //$stmt->execute(["ドルフィン・プランク・ポーズ","ストレス解消",0]);
        //$stmt->execute(["ドルフィン・プランク・ポーズ","コアを鍛える",0]);
        //$stmt->execute(["ピンチャ・マユラーサナ","ストレス解消",0]);
        //$stmt->execute(["ピンチャ・マユラーサナ","腕の強化",0]);
        //$stmt->execute(["マラーサナ（花輪）","ウエスト引き締め",0]);
        $stmt->execute(["ヴィラバドラーサナ（戦士）","姿勢の改善",0]);
        $stmt->execute(["ヴィラバドラーサナ（戦士）","脚の強化",0]);
        $stmt->execute(["ヴィラバドラーサナ（戦士）","ウエスト引き締め",0]);
        $stmt->execute(["シャラバーサナ（バッタ）","ウエスト引き締め",0]);
        $stmt->execute(["シャラバーサナ（バッタ）","疲労回復",0]);
        $stmt->execute(["シャラバーサナ（バッタ）","便秘解消",0]);
        $stmt->execute(["アルダ・マツィエンドラーサナ（半分の魚の王）","消化促進",0]);
        $stmt->execute(["アルダ・マツィエンドラーサナ（半分の魚の王）","月経中の不調緩和",0]);
        $stmt->execute(["バッダ・コナーサナ（合せきのポーズ）","リラックス",0]);
        $stmt->execute(["バッダ・コナーサナ（合せきのポーズ）","疲労回復",0]);

        // http://www.mahouspice.com/
        // http://www.pranarom.co.jp/feeling/
        $stmt = $pdo->prepare("INSERT INTO spice VALUES(?,?,?)");
        $stmt->execute(["カルダモン","脂肪燃焼","0"]);
        $stmt->execute(["カルダモン","便秘解消","0"]);
        $stmt->execute(["クミン","食欲増進","0"]);
        $stmt->execute(["クミン","腰痛緩和","0"]);
        $stmt->execute(["コリアンダー","滋養強壮","0"]);
        $stmt->execute(["コリアンダー","消化促進","0"]);
        $stmt->execute(["コリアンダー","ポジティブ","0"]);
        $stmt->execute(["ターメリック","美肌効果","0"]);
        $stmt->execute(["ターメリック","食欲増進","0"]);
        $stmt->execute(["ターメリック","疲労回復","0"]);
        $stmt->execute(["シナモン","食欲増進","0"]);
        $stmt->execute(["シナモン","発汗作用","0"]);
        $stmt->execute(["シナモン","ラブラブ","0"]);
        $stmt->execute(["フェネグリーク","疲労回復","0"]);
        $stmt->execute(["レモングラス","美肌効果","0"]);
        $stmt->execute(["レモングラス","肩こり解消","0"]);
        $stmt->execute(["レモングラス","目覚め","0"]);
        $stmt->execute(["レモングラス","リラックス","0"]);
        $stmt->execute(["レモングラス","リフレッシュ","0"]);

        header("Location: yoga2spice.php");

    }elseif( $_GET["mode"] === "drop" ){
        $pdo->query("DROP TABLE yoga");
        $pdo->query("DROP TABLE spice");
        header("Location: yoga2spice.php?mode=create");

    }

}elseif( isset($_GET["yoga"]) ){

    $sql = "UPDATE yoga SET point=point+10 WHERE name=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET["yoga"]]);

    $sql = "SELECT * FROM yoga WHERE name=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET["yoga"]]);
    while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $sql = "UPDATE spice SET point=point+10 WHERE effect=?";
        $stmt2 = $pdo->prepare($sql);
        $stmt2->execute([$result["effect"]]);
    }

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>yoga2spice</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="bootstrap.css">
    <!--
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">

    <div class="page-header">
        <h1>yoga2spice</h1>
    </div>

    <?php echo $menu;?>

    <div class="row">
        <div class="col-xs-6">

            <table class="table table-condensed">
            <?php
            $sql = "SELECT name FROM yoga GROUP BY name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                echo '<tr><td>';
                echo '<button onclick="location.href=\'?yoga='.$result["name"].'\'">';
                echo $result["name"];
                //echo '</p>';
                //echo '<p>';
                echo '</button></td>';
                echo '<td>';

                $sql = "SELECT effect FROM yoga WHERE name=?";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->execute([$result["name"]]);
                while($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    echo $result2["effect"].' ';
                }
                //echo '</button> ';
                echo '</td></tr>';
            }
            ?>
            </table>
        </div>
        <div class="col-xs-6">

            <table class="table table-condensed">
            <?php
            $sql = "SELECT name,sum(point) as sum FROM spice GROUP BY name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                echo '<tr><td><b>'.$result["name"].'</b><br>';

                $sql = "SELECT effect,point FROM spice WHERE name=?";
                $stmt2 = $pdo->prepare($sql);
                $stmt2->execute([$result["name"]]);
                while($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    echo $result2["effect"].' ';
                }

                echo '</td>';
                echo '<td width="50%">';
                echo <<<GRAPH
             <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: {$result["sum"]}%;">
                    <span class="sr-only">{$result["sum"]}</span>
                </div>
             </div>
GRAPH;
                echo '</td></tr>';
            }
            ?>
            </table>
        </div>
    </div>
</div><!-- container -->
</body>
</html>