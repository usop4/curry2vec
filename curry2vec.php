<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>curry2vec</title>

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

<?php
$kws = ["札幌 スープカレー 金沢",
        "カレー","カレー","カツカレー","キーマカレー",
        "CoCo壱番屋","ゴーゴーカレー",
        "東銀座","新橋","大阪","神保町","下北沢",
        "南インド","北インド","欧風",
        "茄子","トマト","ヨーグルト","タマネギ","あさり","豆腐",
        "ビーフ","サバ","チキン","シーフード","エビ",
        "専門店",
        "ビール","ワイン","ビール ワイン インド",
        "口コミ","行列","テレビ","コラボ"];
if( isset($_GET["key1"]) ){
    $kw = $_GET["key1"];
}else{
    $kw = $kws[rand(0,count($kws)-1)];
}
?>

<div class="container">

    <div class="page-header">
        <h1>curry2vec</h1>
    </div>


    <div class="row">
        <div class="col-xs-4">
            <form role="form">
                <input name="key1" onkeyup="load_distance()" id="key1" type="text" class="form-control" value="<?php echo $kw;?>">
            </form>
            <button class="btn btn-default btn-xs" onclick="searchUrl('http://search.rakuten.co.jp/search/mall?sitem=')">楽天で探す</button>
            <button class="btn btn-default btn-xs" onclick="searchUrl('http://r.gnavi.co.jp/food/curry/rs/?fw=')">ぐるなびで探す</button>
            <button class="btn btn-default btn-xs" onclick="searchUrl('http://search.yahoo.co.jp/search?p=')">Yahoo!で探す</button>
            <br>
            <button class="btn btn-default btn-xs" onclick="searchUrl('http://barcelona-prototype.com/curry/db.php?q=')">コーパスを確認する（開発者用）</button>
        </div>
        <div class="col-xs-4">
            distance:<div id="target1"></div>
        </div>
        <div class="col-xs-4">
            analogy:<div id="target2"></div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">カレー情報の入力</h3>
                </div>
                <div class="panel-body">
                    <p>本文に入力した情報を元に機械学習します。登録状況はこちら→<a href="db.php">db.php</a></p>
                    <p>このボタン（ブックマークレット）をブックマークすることで登録作業が効率化します。<a href="javascript:(function(){window.location='http://barcelona-prototype.com/curry/curry2vec.php?url='+escape(location.href);})(window,document)" onclick="alert('右クリックしてお気に入りに追加してください'); return false;"><span class="badge">ブックマークレット</span></a>
                    </p>

                    <form role="form" action="db.php?mode=insert" method="post">
                        <div class="form-group">
                            <label for="inputUrl">URL</label>
                            <input name="url" type="text" class="form-control input-sm" placeholder="http://" value="<?php echo htmlspecialchars($_GET["url"]);?>">
                        </div>

                        <?php
                        $s = "";
                        if( isset($_GET["url"]) ){
                            $url = $_GET["url"];
                            $pdo = new PDO('sqlite:corpus.db');
                            $stmt = $pdo->prepare("SELECT * FROM corpus WHERE url LIKE '".$url."%' LIMIT 1");
                            $stmt->execute();
                            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                                $s = $result["content"];
                            }
                        }
                        ?>
                        <div class="form-group">
                            <label for="inputText">本文</label>
                            <input name="content" type="text" class="form-control input-sm" value="<?php echo $s;?>">
                            <!--
                            <textarea name="content" class="form-control input-sm"><?php echo $s;?></textarea>
                            -->
                        </div>
                        <button type="submit" class="btn btn-warning">登録</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script>

    var v = [];
    var i = 0;

    $(function(){
        load_distance();
    });

    function searchUrl(url){
        var s = $("#key1").val().replace(/\s/g,"+");
        location.href = url+s;
    }

    function searchGnavi(){
        var s = $("#key1").val().replace(/\s/g,"+");
        var url = "http://r.gnavi.co.jp/food/curry/rs/?fw="+s;
        location.href = url;
    }

    function searchYahoo(){
        var s = $("#key1").val().replace(/\s/g,"+");
        var url = "http://search.yahoo.co.jp/search?p="+s;
        location.href = url;
    }

    function addWords(i){
        var s = $("#key1").val() + " " + v[i];
        $("#key1").val(s);
        load_distance();
    }

    function getLabelAttr(s){
        if( s.part == "名詞" ){
            if( s.part1 == "店舗" ){
                return "label label-danger";
            }
            if( s.part1 == "食材" ){
                return "label label-warning";
            }
            return "label label-default";
        }else{
            return "label label-default";
        }
    }

    function getFlag(s){
        if(s.part == "接続詞" ){
            return false;
        }
        if(s.part == "助詞" ){
            return false;
        }
        if(s.part1 == "非自立" ){
            return false;
        }
        if(s.part1 == "代名詞" ){
            return false;
        }
        return true;
    }

    function load_distance(){
        var key = $("#key1").val();

        $.ajax({
            url:"json2.cgi?cmd=distance2&key="+key
        }).done(
            function (json){
                $("#target1").text("");
                for(i=0;i<json.length;i++){
                    var name = json[i].name;// + "/" + json[i].part + "/" + json[i].part1;
                    v[i] = name;
                    var flag = getFlag(json[i]);
                    if( flag ){
                        var labelclass = getLabelAttr(json[i]);
                        $("#target1").append('<span onclick="addWords('+i+')" class="'+labelclass+'">'+name+'</span> ');
                    }
                }
            }
        );

        $.ajax(
            {
                url:"json2.cgi?cmd=word-analogy2&key="+key
            }
        ).done(
            function (json){
                $("#target2").text("");
                for(i=0;i<json.length;i++){
                    var name = json[i].name;// + "/" + json[i].part + "/" + json[i].part1;
                    v[i] = name;
                    var flag = getFlag(json[i]);
                    if( flag ){
                        var labelclass = getLabelAttr(json[i]);
                        $("#target2").append('<span onclick="addWords('+i+')" class="'+labelclass+'">'+name+'</span> ');
                    }
                }
            }
        );

    }
</script>


</body>
</html>