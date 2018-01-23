<?
error_reporting(0);
extract($_POST,EXTR_SKIP);
extract($_GET,EXTR_SKIP);
extract($_COOKIE,EXTR_SKIP);
$upfile_name=isset($_FILES["upfile"]["name"]) ? $_FILES["upfile"]["name"] : "";
$upfile=isset($_FILES["upfile"]["tmp_name"]) ? $_FILES["upfile"]["tmp_name"] : "";

define("LOGFILE", 'registroimg.log');		//ログファイル名
define("TREEFILE", 'registro.log');		//ログファイル名
define("IMG_DIR", 'static/imagenes/');		//画像保存ディレクトリ。futaba.phpから見て
define("THUMB_DIR",'static/miniaturas/');		//サムネイル保存ディレクトリ
define("TITLE", 'ANIMBO V1');		//タイトル（<title>とTOP）
define("HOME",  '../');			//「ホーム」へのリンク
define("MAX_KB", '500');			//投稿容量制限 KB（phpの設定により2Mまで
define("MAX_W",  '250');			//投稿サイズ幅（これ以上はwidthを縮小
define("MAX_H",  '250');			//投稿サイズ高さ
define("PAGE_DEF", '5');			//一ページに表示する記事
define("LOG_MAX",  '500');		//ログ最大行数
define("ADMIN_PASS", 'password');	//管理者パス
define("RE_COL", '510ba8');               //Define un color cuando pones > antes de un mensaje.
define("PHP_SELF", 'tablon.php');	//このスクリプト名
define("PHP_SELF2", 'index.html');	//入り口ファイル名
define("PHP_EXT", '.html');		//1ページ以降の拡張子
define("RENZOKU", '10');			//SEGUNDOS PARA EVITAR SPAM
define("RENZOKU2", '10');		//SEGUNDOS PARA EVITAR SPAM
define("MAX_RES", '30');		//Número máximo de veces que una imagen puede ser bumpeada hacia arriba con una nueva respuesta.
define("USE_THUMB", 1);		//サムネイルを作る する:1 しない:0
define("PROXY_CHECK", 1);		//Chequea proxy.
define("DISP_ID", 0);		//Se muestra una ID de mensaje.
define("BR_CHECK", 3);		//Numero máximo de lineas por post..
define("IDSEED", 'idseed');		//idの種
define("RESIMG", 1);		//レスに画像を貼る:1 貼らない:0

$path = realpath("./").'/'.IMG_DIR;
$badstring = array("dummy_string","dummy_string2"); //拒絶する文字列
$badfile = array("dummy","dummy2"); //拒絶するファイルのmd5
$badip = array("addr.dummy.com","addr2.dummy.com"); //拒絶するホスト
$addinfo='';

function head(&$dat){
  $dat.='<!DOCTYPE html>
<html lang="es"><head>

<!-- COOKIE -->
<script language="JavaScript"><!--
function l(e){var P=getCookie("pwdc"),N=getCookie("namec"),i;with(document){for(i=0;i<forms.length;i++){if(forms[i].pwd)with(forms[i]){pwd.value=P;}if(forms[i].name)with(forms[i]){name.value=N;}}}};onload=l;function getCookie(key, tmp1, tmp2, xx1, xx2, xx3) {tmp1 = " " + document.cookie + ";";xx1 = xx2 = 0;len = tmp1.length;	while (xx1 < len) {xx2 = tmp1.indexOf(";", xx1);tmp2 = tmp1.substring(xx1 + 1, xx2);xx3 = tmp2.indexOf("=");if (tmp2.substring(0, xx3) == key) {return(unescape(tmp2.substring(xx3 + 1, xx2 - xx1 - 1)));}xx1 = xx2 + 1;}return("");}
//--></script>

<meta HTTP-EQUIV="pragma" CONTENT="no-cache">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<meta name="language" content="es">
<title>'.TITLE.' - Animbo v1</title>
<meta name="description" content="DESCRIPCION DE TU TABLON">
<meta name="keywords" content="PALABRAS CLAVE DE TU TABLON">
<meta name="robots" content="POLITICAS DE FOLLOW DE TU TABLON" />


<!-- Estilos -->
<link rel=StyleSheet href="css/tablon.css" TYPE="text/css" media=screen>
<link href="https://fonts.googleapis.com/css?family=Nova+Square" rel="stylesheet">
<script defer src="https://use.fontawesome.com/releases/v5.0.2/js/all.js"></script>

 <!-- ANALYTICS -->
 <!-- 
 TU CODIGO DE GOOGLE ANALYTICS VA ACA 
 -->

</head>
<body>
<p align=center>
<br>
<p align=center>
<?php include("genesis/tablon/banner.php"); ?>
<br>
';
}
/* 投稿フォーム */
function form(&$dat,$resno,$admin=""){
  global $addinfo; $msg=""; $hidden="";
  $maxbyte = MAX_KB * 1024;
  $no=$resno;
  if($resno){
    $msg .= "<a href=\"".PHP_SELF2."\"><i class='fa fa-undo' aria-hidden='true'></i><br><b>Volver al Tablon<b></a>\n";
    $msg .= "<table><tr><th>\n";
    $msg .= "<img id='respuestapng' src=genesis/imagenes/respuesta.png>\n";
    $msg .= "</th></tr></table>\n";
  }
  if($admin){
    $hidden = "<input type=hidden name=admin value=\"".ADMIN_PASS."\">";
    $msg = "<h4>Puedes usar etiquitas</h4>";
  }
  $dat.=$msg.'<center>
<form action="'.PHP_SELF.'" method="POST" enctype="multipart/form-data">
<input type=hidden name=mode value="regist">
'.$hidden.'
<input type=hidden name="MAX_FILE_SIZE" value="'.$maxbyte.'">
';
if($no){$dat.='<input type=hidden name=resto value="'.$no.'">
';}
$dat.='<table id="formulario" cellpadding=1 cellspacing=1>
  <tr><td><b class="posteo">Nombre</b></td><td><input type=text name=name size="28"></td></tr>
  <tr><td><b>Comentario</b></td><td><textarea rows="5" cols="30" name=com></textarea></td></tr>
  ';
if(RESIMG || !$resno){
$dat.='<tr><td><b></b></td>
<td><i class="fa fa-upload" aria-hidden="true"></i> <input type=file name=upfile size="35"> 
<br><label><input type=checkbox name=textonly value=on>Sin imagen</label></td></tr><br>
';}
$dat.='<tr><td><b> Contraseña de borrado</b></td><td><input type=password name=pwd size=8 maxlength=8 value=""><small> (Max. 8 caracteres alfanumericos)</small></td></tr>
<br>
<tr><td><input id="envio" type=submit value="Enviar"></b></td></tr>
<tr><td colspan=2>
<small>
*Formatos sportados：GIF, JPG, PNG.
<br>
*El tamano maximo soportado es de '.MAX_KB.' KB.
<br>
*Las medidas maximas sin redimencionar la imagen son de '.MAX_W.' X '.MAX_H.' 
<br>
Si se excede, sera redimencionada.
'.$addinfo.'
<br>
*Si colocas ">" (sin comillas) antes de escribir, el texto se escribirá <br>
en color <b style="color:#510ba8;">violeta.</b>
</small></td></tr></table></form></center><br>';
}
/* 記事部分 */
function updatelog($resno=0){
  global $path;$p=0;

  $tree = file(TREEFILE);
  $find = false;
  if($resno){
    $counttree=count($tree);
    for($i = 0;$i<$counttree;$i++){
      list($artno,)=explode(",",rtrim($tree[$i]));
      if($artno==$resno){$st=$i;$find=true;break;} //レス先検索
    }
    if(!$find) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Post no encontrado!</center></div>");
  }
  $line = file(LOGFILE);
  $countline=count($line);
  for($i = 0; $i < $countline; $i++){
    list($no,) = explode(",", $line[$i]);
    $lineindex[$no]=$i + 1; //逆変換テーブル作成
  }

  $counttree = count($tree);
  for($page=0;$page<$counttree;$page+=PAGE_DEF){
    $dat='';
    head($dat);
    form($dat,$resno);
    if(!$resno){
      $st = $page;
    }
    $dat.='<form action="'.PHP_SELF.'" method=POST>';

  for($i = $st; $i < $st+PAGE_DEF; $i++){
    if(empty($tree[$i])){continue;}
    $treeline = explode(",", rtrim($tree[$i]));
    $disptree = $treeline[0];
    $j=$lineindex[$disptree] - 1; //該当記事を探して$jにセット
    if(empty($line[$j])){continue;}   //$jが範囲外なら次の行
    list($no,$now,$name,$email,$sub,$com,$url,
         $host,$pwd,$ext,$w,$h,$time,$chk) = explode(",", $line[$j]);
    // URLとメールにリンク
    if($email) $name = "<a href=\"mailto:$email\">$name</a>";
    $com = auto_link($com);
    $com = eregi_replace("(^|>)(&gt;[^<]*)", "\\1<font color=".RE_COL.">\\2</font>", $com);
    // 画像ファイル名
    $img = $path.$time.$ext;
    $src = IMG_DIR.$time.$ext;
    // <imgタグ作成
    $imgsrc = "";
    if($ext && is_file($img)){
      $size = filesize($img);//altにサイズ表示
      if($w && $h){//サイズがある時
        if(@is_file(THUMB_DIR.$time.'s.jpg')){
          $imgsrc = "<a href=\"".$src."\" target=_blank><img src=".THUMB_DIR.$time.'s.jpg'.
      " border=0 align=left width=$w height=$h hspace=20 alt=\"".$size." B\"></a>";
        }
      }else{//それ以外
        $imgsrc = "<img src=".$src." alt=\"".$size." B\"></a>";
      }
      $dat.="$imgsrc<a href=\"$src\" target=_blank></a>";
    }
      
    // Posteos
      $dat.="<table id='posteos'>
        </td><td nowrap>\n";
    $dat.="<input type=checkbox name=\"$no\" value=delete>\n";
    $dat.="Post #$no - Fecha: $now - <b class='nombrepost'>$name</b>&nbsp;dijo:&nbsp; \n";
    $dat.="\n <div><blockquote>$com</blockquote></div>";
      if(!$resno) $dat.="<button id='responder'><a href=".PHP_SELF."?res=$no>Responder</a></button>";
       $dat.="</td></tr></table></div>\n <br>";

     // そろそろ消える。
     if($lineindex[$no]-1 >= LOG_MAX*0.95){
      $dat.="<font color=\"#f00000\"><b>Este hilo es antiguo! Pronto sera borrado!</b></font><br>\n";
     }

    //レス作成
    if(!$resno){
     $s=count($treeline) - 10;
     if($s<1){$s=1;}
     elseif($s>1){
      $dat.="<font color=\"#707070\">レス".
             ($s - 1)."No hay comentarios. Responde para leer todos.</font><br>\n";
     }
    }else{$s=1;}
    for($k = $s; $k < count($treeline); $k++){
      $disptree = $treeline[$k];
      $j=$lineindex[$disptree] - 1;
      if($line[$j]=="") continue;
      list($no,$now,$name,$email,$sub,$com,$url,
           $host,$pwd,$ext,$w,$h,$time,$chk) = explode(",", $line[$j]);
      // URLとメールにリンク
      if($email) $name = "<a href=\"mailto:$email\">$name</a>";
      $com = auto_link($com);
      $com = eregi_replace("(^|>)(&gt;[^<]*)", "\\1<font color=".RE_COL.">\\2</font>", $com);

    // 画像ファイル名
    $img = $path.$time.$ext;
    $src = IMG_DIR.$time.$ext;
    // <imgタグ作成
    $imgsrc = "";
    if($ext && is_file($img)){
      $size = filesize($img);//altにサイズ表示
      if($w && $h){//サイズがある時
        if(@is_file(THUMB_DIR.$time.'s.jpg')){
          $imgsrc = "<small>Visualizacion de miniaturas</small><br><a href=\"".$src."\" target=_blank><img src=".THUMB_DIR.$time.'s.jpg'.
      " border=0 align=left width=$w height=$h hspace=20 alt=\"".$size." B\"></a>";
        }else{
          $imgsrc = "<a href=\"".$src."\" target=_blank><img src=".$src.
      " border=0 align=left width=$w height=$h hspace=20 alt=\"".$size." B\"></a>";
        }
      }else{//それ以外
        $imgsrc = "<a href=\"".$src."\" target=_blank><img src=".$src.
      " border=0 align=left hspace=20 alt=\"".$size." B\"></a>";
      }
      $imgsrc="<br> &nbsp; &nbsp; <a href=\"$src\" target=_blank>$time$ext</a>-($size B) $imgsrc";
    }

      // Respuestas
      $dat.="<table id='posteos'>
        </td><td>\n";
    $dat.="<input type=checkbox name=\"$no\" value=delete>\n";
    $dat.="</div>Post #$no - Fecha: $now - <b id='nombrepost'>$name</b>&nbsp;ha respondido:&nbsp; \n";
    $dat.="\n <div><blockquote>$com</blockquote></div>";
       $dat.="</td></tr></table>\n <br>";
    }
    $dat.="<br clear=left><br>\n";
    clearstatcache();//ファイルのstatをクリア
    $p++;
    if($resno){break;} //res時はtree1行だけ
  }
$dat.='<table id="eliminarpost" align="right"><tr><td align="center">
<input type=hidden name=mode value=usrdel><i class="fas fa-trash-alt"></i>Eliminar Post
<br>
Contraseña: <input type=password name=pwd size=8 maxlength=8 value="">
<input type=submit value="Eliminar">
<br>
<input type=checkbox name=onlyimgdel value=on>Solo eliminar imagenes
</form></td></tr></table>';

    if(!$resno){ //res時は表示しない
      $prev = $st - PAGE_DEF;
      $next = $st + PAGE_DEF;
    // 改ページ処理
      if($prev >= 0){
        if($prev==0){
          $dat.="<form action=\"".PHP_SELF2."\" method=get>";
        }else{
          $dat.="<form action=\"".$prev/PAGE_DEF.PHP_EXT."\" method=get>";
        }
        $dat.="<input type=submit value=\"Pagina anterior\">";
        $dat.="</form>";
      }else{$dat.="<i class='fas fa-chevron-circle-left'></i> <font face='Nova Square' , cursive;> Primer pagina </font>";}
        
        for($i = 0; $i < count($tree) ; $i+=PAGE_DEF){
        if($st==$i){$dat.="[<b>".($i/PAGE_DEF)."</b>] ";}
        else{
          if($i==0){$dat.="[<a href=\"".PHP_SELF2."\">0</a>] ";}
          else{$dat.="[<a href=\"".($i/PAGE_DEF).PHP_EXT."\">".($i/PAGE_DEF)."</a>] ";}
        }
      }

      if($p >= PAGE_DEF && count($tree) > $next){
        $dat.="<form action=\"".$next/PAGE_DEF.PHP_EXT."\" method=get>";
        $dat.="<font face='Nova Square' , cursive;> Siguiente pagina </font>";
        $dat.="</form>";
      }else{$dat.="<font face='Nova Square' , cursive;>  ultima Pagina </font> <i class='fas fa-chevron-circle-right'></i>";}
        $dat.="<br clear=all>\n";
    }
    foot($dat);
    if($resno){echo $dat;break;}
    if($page==0){$logfilename=PHP_SELF2;}
        else{$logfilename=$page/PAGE_DEF.PHP_EXT;}
    $fp = fopen($logfilename, "w");
    set_file_buffer($fp, 0);
    rewind($fp);
    fputs($fp, $dat);
    fclose($fp);
    chmod($logfilename,0666);
  }
  if(!$resno&&is_file(($page/PAGE_DEF+1).PHP_EXT)){unlink(($page/PAGE_DEF+1).PHP_EXT);}
}
/* フッタ */
function foot(&$dat){
  $dat.='
<?php include("genesis/tablon/footer.php"); ?>
</body></html>';
}
/* オートリンク */
function auto_link($proto){
  $proto = ereg_replace("(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)","<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>",$proto);
  return $proto;
}
/* エラー画面 */
function error($mes,$dest=''){
  global $upfile_name,$path;
  if(is_file($dest)) unlink($dest);
  head($dat);
  echo $dat;
  echo "<br><br><br><br>
        <center><b>$mes<br><br><b id='ok'><a href=".PHP_SELF2.">OK!</a></b></b></center>
        <br><br>";
  die("</body></html>");
}

function  proxy_connect($port) {
  $a="";$b="";
  $fp = @fsockopen ($_SERVER["REMOTE_ADDR"], $port,$a,$b,2);
  if(!$fp){return 0;}else{return 1;}
}
/* 記事書き込み */
function regist($name,$email,$sub,$com,$url,$pwd,$upfile,$upfile_name,$resto){
  global $path,$badstring,$badfile,$badip,$pwdc,$textonly;
  $dest="";$mes="";

  // 時間
  $time = time();
  $tim = $time.substr(microtime(),2,3);

  // アップロード処理
  if($upfile&&file_exists($upfile)){
    $dest = $path.$tim.'.tmp';
    move_uploaded_file($upfile, $dest);
    //↑でエラーなら↓に変更
    //copy($upfile, $dest);
    $upfile_name = CleanStr($upfile_name);
    if(!is_file($dest)) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Carga Fallida<br>No es compatible con el servidor!!!</center></div>",$dest);
    $size = getimagesize($dest);
    if(!is_array($size)) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Carga fallida!<br>No aceptamos eso!</center></div>",$dest);
    $chk = md5_of_file($dest);
    foreach($badfile as $value){if(ereg("^$value",$chk)){
      error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Imagen duplicada!</center></div>",$dest); //拒絶画像
    }}
    chmod($dest,0666);
    $W = $size[0];
    $H = $size[1];

    switch ($size[2]) {
      case 1 : $ext=".gif";break;
      case 2 : $ext=".jpg";break;
      case 3 : $ext=".png";break;
      case 4 : $ext=".swf";break;
      case 5 : $ext=".psd";break;
      case 6 : $ext=".bmp";break;
      case 13 : $ext=".swf";break;
      default : $ext=".xxx";error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Formato no soportado!</center></div>",$dest);
    }

    // 画像表示縮小
    if($W > MAX_W || $H > MAX_H){
      $W2 = MAX_W / $W;
      $H2 = MAX_H / $H;
      ($W2 < $H2) ? $key = $W2 : $key = $H2;
      $W = ceil($W * $key);
      $H = ceil($H * $key);
    }
    $mes = "<center><br><br><font face='Nova Square', cursive;>$upfile_name Fue subida exitrosamente!</font></center>";
  }

  foreach($badstring as $value){if(ereg($value,$com)||ereg($value,$sub)||ereg($value,$name)||ereg($value,$email)){
  error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>ERROR EL (str)</center></div>",$dest);};}
  if($_SERVER["REQUEST_METHOD"] != "POST") error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>ERROR!</center>(post)</div>",$dest);
  // フォーム内容をチェック
  if(!$name||ereg("^[ |　|]*$",$name)) $name="";
  if(!$com||ereg("^[ |　|\t]*$",$com)) $com="";
  if(!$sub||ereg("^[ |　|]*$",$sub))   $sub=""; 

  if(!$resto&&!$textonly&&!is_file($dest)) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>No hay imagen o El archivo es incorrecto.</div>",$dest);
  if(!$com&&!is_file($dest)) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Escribe algo!</center></div>",$dest);

  $name=ereg_replace("Gestion","\"Gestion\"",$name);
  $name=ereg_replace("Eliminar","\"Eliminar\"",$name);

  if(strlen($com) > 120) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>El texto es demasiado largo!</center></div>",$dest);
  if(strlen($name) > 10) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>El nombre es muy largo!</center></div>",$dest);
  if(strlen($email) > 0) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>No aceptamos e-mail</center></div>",$dest);
  if(strlen($sub) > 0) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>No aceptamos asunto.</center></div>",$dest);
  if(strlen($resto) > 10) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>ERROR DESCONOCIDO</center></div>",$dest);
  if(strlen($url) > 10) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>La URL es extraña...</center></div>",$dest);

  //ホスト取得
  $host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);

  foreach($badip as $value){ //拒絶host
   if(eregi("$value$",$host)){
    error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>TU IP ESTÁ BANEADA.</center></div>",$dest);
  }}
  if(eregi("^mail",$host)
    || eregi("^ns",$host)
    || eregi("^dns",$host)
    || eregi("^ftp",$host)
    || eregi("^prox",$host)
    || eregi("^pc",$host)
    || eregi("^[^\.]\.[^\.]$",$host)){
    $pxck = "on";
  }
  if(eregi("ne\\.jp$",$host)||
    eregi("ad\\.jp$",$host)||
    eregi("bbtec\\.net$",$host)||
    eregi("aol\\.com$",$host)||
    eregi("uu\\.net$",$host)||
    eregi("asahi-net\\.or\\.jp$",$host)||
    eregi("rim\\.or\\.jp$",$host)
    ){$pxck = "off";}
  else{$pxck = "on";}

  if($pxck=="on" && PROXY_CHECK){
    if(proxy_connect('80') == 1){
      error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>E R R O R! R E G U L A C I O N E E P R O X Y!(80)</center></div>",$dest);
    } elseif(proxy_connect('8080') == 1){
      error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>E R R O R! R E G U L A C I O N E E P R O X Y!(8080)</center></div>",$dest);
    }
  }

  // No.とパスと時間とURLフォーマット
  srand((double)microtime()*1000000);
  if($pwd==""){
    if($pwdc==""){
      $pwd=rand();$pwd=substr($pwd,0,8);
    }else{
      $pwd=$pwdc;
    }
  }

  $c_pass = $pwd;
  $pass = ($pwd) ? substr(md5($pwd),2,8) : "*";
  $yd =" a las ";
    $now = gmdate("y/m/d",$time+9*60*60).($yd).gmdate("H:i",$time+9*60*60);
  if(DISP_ID){
    if($email&&DISP_ID==1){
      $now .= " ID:???";
    }else{
      $now.=" ID:".substr(crypt(md5($_SERVER["REMOTE_ADDR"].IDSEED.gmdate("dmy", $time+9*60*60)),'id'),-8);
    }
  }
  //テキスト整形
  $email= CleanStr($email);  $email=ereg_replace("[\r\n]","",$email);
  $sub  = CleanStr($sub);    $sub  =ereg_replace("[\r\n]","",$sub);
  $url  = CleanStr($url);    $url  =ereg_replace("[\r\n]","",$url);
  $resto= CleanStr($resto);  $resto=ereg_replace("[\r\n]","",$resto);
  $com  = CleanStr($com);
  // 改行文字の統一。 
  $com = str_replace( "\r\n",  "\n", $com); 
  $com = str_replace( "\r",  "\n", $com);
  // 連続する空行を一行
  $com = ereg_replace("\n((　| )*\n){3,}","\n",$com);
  if(!BR_CHECK || substr_count($com,"\n")<BR_CHECK){
    $com = nl2br($com);		//改行文字の前に<br>を代入する
  }
  $com = str_replace("\n",  "", $com);	//\nを文字列から消す。

  $name=ereg_replace("◆","◇",$name);
  $name=ereg_replace("[\r\n]","",$name);
  $names=$name;
  $name = CleanStr($name);
  if(ereg("(#|＃)(.*)",$names,$regs)){
    $cap = $regs[2];
    $cap=strtr($cap,"&amp;", "&");
    $cap=strtr($cap,"&#44;", ",");
    $name=ereg_replace("(#|＃)(.*)","",$name);
    $salt=substr($cap."H.",1,2);
    $salt=ereg_replace("[^\.-z]",".",$salt);
    $salt=strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef"); 
    $name.="</b>◆".substr(crypt($cap,$salt),-10)."<b>";
  }

  if(!$name) $name="Anonimo";
  if(!$com) $com="Sin texto";
  if(!$sub) $sub="Sin titulo"; 

  //ログ読み込み
  $fp=fopen(LOGFILE,"r+");
  flock($fp, 2);
  rewind($fp);
  $buf=fread($fp,1000000);
  if($buf==''){error("error load log",$dest);}
  $line = explode("\n",$buf);
  $countline=count($line);
  for($i = 0; $i < $countline; $i++){
    if($line[$i]!=""){
      list($artno,)=explode(",", rtrim($line[$i]));  //逆変換テーブル作成
      $lineindex[$artno]=$i+1;
      $line[$i].="\n";
  }}

  // 二重投稿チェック
  $imax=count($line)>20 ? 20 : count($line)-1;
  for($i=0;$i<$imax;$i++){
   list($lastno,,$lname,,,$lcom,,$lhost,$lpwd,,,,$ltime,) = explode(",", $line[$i]);
   if(strlen($ltime)>10){$ltime=substr($ltime,0,-3);}
   if($host==$lhost||substr(md5($pwd),2,8)==$lpwd||substr(md5($pwdc),2,8)==$lpwd){$pchk=1;}else{$pchk=0;}
   if(RENZOKU && $pchk && $time - $ltime < RENZOKU)
    error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Espera un poco entre cada post!</center></div>",$dest);
   if(RENZOKU && $pchk && $time - $ltime < RENZOKU2 && $upfile_name)
    error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Espera un poco entre cada post!</center></div>",$dest);
   if(RENZOKU && $pchk && $com == $lcom && !$upfile_name)
    error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Espera un poco entre cada post!</center></div>",$dest);
  }

  // ログ行数オーバー
  if(count($line) >= LOG_MAX){
    for($d = count($line)-1; $d >= LOG_MAX-1; $d--){
      list($dno,,,,,,,,,$dext,,,$dtime,) = explode(",", $line[$d]);
      if(is_file($path.$dtime.$dext)) unlink($path.$dtime.$dext);
      if(is_file(THUMB_DIR.$dtime.'s.jpg')) unlink(THUMB_DIR.$dtime.'s.jpg');
      $line[$d] = "";
      treedel($dno);
    }
  }
  // アップロード処理
  if($dest&&file_exists($dest)){
    $imax=count($line)>200 ? 200 : count($line)-1;
    for($i=0;$i<$imax;$i++){ //画像重複チェック
     list(,,,,,,,,,$extp,,,$timep,$chkp,) = explode(",", $line[$i]);
     if($chkp==$chk&&file_exists($path.$timep.$extp)){
      error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Imagen duplicada!</center></div>",$dest);
    }}
  }
  list($lastno,) = explode(",", $line[0]);
  $no = $lastno + 1;
  isset($ext)?0:$ext="";
  isset($W)?0:$W="";
  isset($H)?0:$H="";
  isset($chk)?0:$chk="";
  $newline = "$no,$now,$name,$email,$sub,$com,$url,$host,$pass,$ext,$W,$H,$tim,$chk,\n";
  $newline.= implode('', $line);
  ftruncate($fp,0);
  set_file_buffer($fp, 0);
  rewind($fp);
  fputs($fp, $newline);

    //ツリー更新
  $find = false;
  $newline = '';
  $tp=fopen(TREEFILE,"r+");
  set_file_buffer($tp, 0);
  rewind($tp);
  $buf=fread($tp,1000000);
  if($buf==''){error("error tree update",$dest);}
  $line = explode("\n",$buf);
  $countline=count($line);
  for($i = 0; $i < $countline; $i++){
    if($line[$i]!=""){
      $line[$i].="\n";
      $j=explode(",", rtrim($line[$i]));
      if($lineindex[$j[0]]==0){
        $line[$i]='';
  } } }
  if($resto){
    for($i = 0; $i < $countline; $i++){
      $rtno = explode(",", rtrim($line[$i]));
      if($rtno[0]==$resto){
        $find = TRUE;
        $line[$i]=rtrim($line[$i]).','.$no."\n";
        $j=explode(",", rtrim($line[$i]));
        if(count($j)>MAX_RES){$email='sage';}
        if(!stristr($email,'sage')){
          $newline=$line[$i];
          $line[$i]='';
        }
        break;
  } } }
  if(!$find){if(!$resto){$newline="$no\n";}else{error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>No hay posts...</center></div>",$dest);}}
  $newline.=implode('', $line);
  ftruncate($tp,0);
  set_file_buffer($tp, 0);
  rewind($tp);
  fputs($tp, $newline);
  fclose($tp);
  fclose($fp);

    //クッキー保存
  setcookie ("pwdc", $c_pass,time()+7*24*3600);  /* 1週間で期限切れ */
  if(function_exists("mb_internal_encoding")&&function_exists("mb_convert_encoding")
      &&function_exists("mb_substr")){
    if(ereg("MSIE|Opera",$_SERVER["HTTP_USER_AGENT"])){
      $i=0;$c_name='';
      mb_internal_encoding("SJIS");
      while($j=mb_substr($names,$i,1)){
        $j = mb_convert_encoding($j, "UTF-16", "SJIS");
        $c_name.="%u".bin2hex($j);
        $i++;
      }
      header("Set-Cookie: namec=$c_name; expires=".gmdate("D, d-M-Y H:i:s",time()+7*24*3600)." GMT",false);
    }else{
      $c_name=$names;
      setcookie ("namec", $c_name,time()+7*24*3600);  /* 1週間で期限切れ */
    }
  }

  if($dest&&file_exists($dest)){
    rename($dest,$path.$tim.$ext);
    if(USE_THUMB){thumb($path,$tim,$ext);}
  }
  updatelog();

  echo "<html><head><META HTTP-EQUIV=\"refresh\" content=\"5;URL=".PHP_SELF2."\"><title>Cargando...</title></head>";
  echo "<body bgcolor='#FFFFEE'>$mes<br><br><br><br><center><img src='genesis/imagenes/cargando.gif'><br><br><b id='cargando'>Cargando...</b></center></body></html>";
}

//サムネイル作成
function thumb($path,$tim,$ext){
  if(!function_exists("ImageCreate")||!function_exists("ImageCreateFromJPEG"))return;
  $fname=$path.$tim.$ext;
  $thumb_dir = THUMB_DIR;     //サムネイル保存ディレクトリ
  $width     = MAX_W;            //出力画像幅
  $height    = MAX_H;            //出力画像高さ
  // 画像の幅と高さとタイプを取得
  $size = GetImageSize($fname);
  switch ($size[2]) {
    case 1 :
      if(function_exists("ImageCreateFromGIF")){
        $im_in = @ImageCreateFromGIF($fname);
        if($im_in){break;}
      }
      if(!is_executable(realpath("./gif2png"))||!function_exists("ImageCreateFromPNG"))return;
      @exec(realpath("./gif2png")." $fname",$a);
      if(!file_exists($path.$tim.'.png'))return;
      $im_in = @ImageCreateFromPNG($path.$tim.'.png');
      unlink($path.$tim.'.png');
      if(!$im_in)return;
      break;
    case 2 : $im_in = @ImageCreateFromJPEG($fname);
      if(!$im_in){return;}
       break;
    case 3 :
      if(!function_exists("ImageCreateFromPNG"))return;
      $im_in = @ImageCreateFromPNG($fname);
      if(!$im_in){return;}
      break;
    default : return;
  }
  // リサイズ
  if ($size[0] > $width || $size[1] >$height) {
    $key_w = $width / $size[0];
    $key_h = $height / $size[1];
    ($key_w < $key_h) ? $keys = $key_w : $keys = $key_h;
    $out_w = ceil($size[0] * $keys) +1;
    $out_h = ceil($size[1] * $keys) +1;
  } else {
    $out_w = $size[0];
    $out_h = $size[1];
  }
  // 出力画像（サムネイル）のイメージを作成
  if(function_exists("ImageCreateTrueColor")&&get_gd_ver()=="2"){
    $im_out = ImageCreateTrueColor($out_w, $out_h);
  }else{$im_out = ImageCreate($out_w, $out_h);}
  // 元画像を縦横とも コピーします。
#  ImageCopyResampled($im_out, $im_in, 0, 0, 0, 0, $out_w, $out_h, $size[0], $size[1]);
  ImageCopyResized($im_out, $im_in, 0, 0, 0, 0, $out_w, $out_h, $size[0], $size[1]);
  // サムネイル画像を保存
  ImageJPEG($im_out, $thumb_dir.$tim.'s.jpg',60);
  chmod($thumb_dir.$tim.'s.jpg',0666);
  // 作成したイメージを破棄
  ImageDestroy($im_in);
  ImageDestroy($im_out);
}
//gdのバージョンを調べる
function get_gd_ver(){
  if(function_exists("gd_info")){
    $gdver=gd_info();
    $phpinfo=$gdver["GD Version"];
  }else{ //php4.3.0未満用
    ob_start();
    phpinfo(8);
    $phpinfo=ob_get_contents();
    ob_end_clean();
    $phpinfo=strip_tags($phpinfo);
    $phpinfo=stristr($phpinfo,"gd version");
    $phpinfo=stristr($phpinfo,"version");
  }
  $end=strpos($phpinfo,".");
  $phpinfo=substr($phpinfo,0,$end);
  $length = strlen($phpinfo)-1;
  $phpinfo=substr($phpinfo,$length);
  return $phpinfo;
}
//ファイルmd5計算 php4.2.0未満用
function md5_of_file($inFile) {
 if (file_exists($inFile)){
  if(function_exists('md5_file')){
    return md5_file($inFile);
  }else{
    $fd = fopen($inFile, 'r');
    $fileContents = fread($fd, filesize($inFile));
    fclose ($fd);
    return md5($fileContents);
  }
 }else{
  return false;
}}
//ツリー削除
function treedel($delno){
  $fp=fopen(TREEFILE,"r+");
  set_file_buffer($fp, 0);
  flock($fp, 2);
  rewind($fp);
  $buf=fread($fp,1000000);
  if($buf==''){error("error tree del");}
  $line = explode("\n",$buf);
  $countline=count($line);
  if($countline>2){
    for($i = 0; $i < $countline; $i++){if($line[$i]!=""){$line[$i].="\n";};}
    for($i = 0; $i < $countline; $i++){
      $treeline = explode(",", rtrim($line[$i]));
      $counttreeline=count($treeline);
      for($j = 0; $j < $counttreeline; $j++){
        if($treeline[$j] == $delno){
          $treeline[$j]='';
          if($j==0){$line[$i]='';}
          else{$line[$i]=implode(',', $treeline);
            $line[$i]=ereg_replace(",,",",",$line[$i]);
            $line[$i]=ereg_replace(",$","",$line[$i]);
            $line[$i].="\n";
          }
          break 2;
    } } }
    ftruncate($fp,0);
    set_file_buffer($fp, 0);
    rewind($fp);
    fputs($fp, implode('', $line));
  }
  fclose($fp);
}
/* テキスト整形 */
function CleanStr($str){
  global $admin;
  $str = trim($str);//先頭と末尾の空白除去
  if (get_magic_quotes_gpc()) {//￥を削除
    $str = stripslashes($str);
  }
  if($admin!=ADMIN_PASS){//管理者はタグ可能
    $str = htmlspecialchars($str);//タグっ禁止
    $str = str_replace("&amp;", "&", $str);//特殊文字
  }
  return str_replace(",", "&#44;", $str);//カンマを変換
}
/* ユーザー削除 */
function usrdel($no,$pwd){
  global $path,$pwdc,$onlyimgdel;
  $host = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
  $delno = array("dummy");
  $delflag = FALSE;
  reset($_POST);
    while ($item = each($_POST)){
     if($item[1]=='delete'){array_push($delno,$item[0]);$delflag=TRUE;}
    }
  if($pwd==""&&$pwdc!="") $pwd=$pwdc;
  $fp=fopen(LOGFILE,"r+");
  set_file_buffer($fp, 0);
  flock($fp, 2);
  rewind($fp);
  $buf=fread($fp,1000000);
  fclose($fp);
  if($buf==''){error("error user del");}
  $line = explode("\n",$buf);
  $countline=count($line);
  for($i = 0; $i < $countline; $i++){if($line[$i]!=""){$line[$i].="\n";};}
  $flag = FALSE;
  $countline=count($line)-1;
  for($i = 0; $i<$countline; $i++){
    list($dno,,,,,,,$dhost,$pass,$dext,,,$dtim,) = explode(",", $line[$i]);
    if(array_search($dno,$delno) && (substr(md5($pwd),2,8) == $pass || $dhost == $host||ADMIN_PASS==$pwd)){
      $flag = TRUE;
      $line[$i] = "";			//パスワードがマッチした行は空に
      $delfile = $path.$dtim.$dext;	//削除ファイル
      if(!$onlyimgdel){
        treedel($dno);
      }
      if(is_file($delfile)) unlink($delfile);//削除
      if(is_file(THUMB_DIR.$dtim.'s.jpg')) unlink(THUMB_DIR.$dtim.'s.jpg');//削除
    }
  }
  if(!$flag) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>El post no se encuentra, o la contraseña es incorrecta.</center></div>");
}
/* パス認証 */
function valid($pass){
  if($pass && $pass != ADMIN_PASS) error("<div id='error'><center><img src='genesis/imagenes/no.png'><br><br>Contraseña incorrecta!</center></div>");

  head($dat);
  echo $dat;
  echo "[<a href=\"".PHP_SELF2."\">De vuelta al tablero de anuncios</a>]\n";
  echo "[<a href=\"".PHP_SELF."\">Actualizar registro</a>]\n";
  echo "<table width='100%'><tr><th bgcolor=#E08000>\n";
  echo "<font color=#FFFFFF>Modo de gestion</font>\n";
  echo "</th></tr></table>\n";
  echo "<p><form action=\"".PHP_SELF."\" method=POST>\n";
  // ログインフォーム
  if(!$pass){
    echo "<center><input type=radio name=admin value=del checked>Eliminar articulo ";
    echo "<input type=radio name=admin value=post>Publicado por admin<p>";
    echo "<input type=hidden name=mode value=admin>\n";
    echo "<input type=password name=pass size=8>";
    echo "<input type=submit value=\"Autenticacion\"></form></center>\n";
    die("</body></html>");
  }
}
/* 管理者削除 */
function admindel($pass){
  global $path,$onlyimgdel;
  $all=0;$msg="";
  $delno = array("dummy");
  $delflag = FALSE;
  reset($_POST);
  while ($item = each($_POST)){
   if($item[1]=='delete'){array_push($delno,$item[0]);$delflag=TRUE;}
  }
  if($delflag){
  $fp=fopen(LOGFILE,"r+");
  set_file_buffer($fp, 0);
  flock($fp, 2);
  rewind($fp);
  $buf=fread($fp,1000000);
  if($buf==''){error("error admin del");}
  $line = explode("\n",$buf);
  $countline=count($line)-1;
  for($i = 0; $i < $countline; $i++){if($line[$i]!=""){$line[$i].="\n";};}
    $find = FALSE;
    for($i = 0; $i < $countline; $i++){
      list($no,$now,$name,$email,$sub,$com,$url,$host,$pw,$ext,$w,$h,$tim,$chk) = explode(",",$line[$i]);
      if($onlyimgdel=="on"){
        if(array_search($no,$delno)){//画像だけ削除
          $delfile = $path.$tim.$ext;	//削除ファイル
          if(is_file($delfile)) unlink($delfile);//削除
          if(is_file(THUMB_DIR.$tim.'s.jpg')) unlink(THUMB_DIR.$tim.'s.jpg');//削除
        }
      }else{
        if(array_search($no,$delno)){//削除の時は空に
          $find = TRUE;
          $line[$i] = "";
          $delfile = $path.$tim.$ext;	//削除ファイル
          if(is_file($delfile)) unlink($delfile);//削除
          if(is_file(THUMB_DIR.$tim.'s.jpg')) unlink(THUMB_DIR.$tim.'s.jpg');//削除
          treedel($no);
        }
      }
    }
    if($find){//ログ更新
      ftruncate($fp,0);
      set_file_buffer($fp, 0);
      rewind($fp);
      fputs($fp, implode('', $line));
    }
    fclose($fp);
  }
  // 削除画面を表示
  echo "<input type=hidden name=mode value=admin>\n";
  echo "<input type=hidden name=admin value=del>\n";
  echo "<input type=hidden name=pass value=\"$pass\">\n";
  echo "<center><P>Selecciona la casilla del mensaje que deseas eliminar, y preciona el boton Eliminar.\n";
  echo "<p><input type=submit value=\"Eliminar\">";
  echo "<input type=reset value=\"Restablecer\">";
  echo "[<input type=checkbox name=onlyimgdel value=on>Solo eliminar imagenes]";
  echo "<P><table border=1 cellspacing=0>\n";
  echo "<tr bgcolor=6080f6><th>Eliminar</th><th>Post No.</th><th>Fecha</th><th>Titulo</th>";
  echo "<th>Post</th><th>Comentario</th><th>Hostname</th><th>Adjunto<br>(Bytes)</th><th>md5</th>";
  echo "</tr>\n";
  $line = file(LOGFILE);

  for($j = 0; $j < count($line); $j++){
    $img_flag = FALSE;
    list($no,$now,$name,$email,$sub,$com,$url,
         $host,$pw,$ext,$w,$h,$time,$chk) = explode(",",$line[$j]);
    // フォーマット
    $now=ereg_replace('.{2}/(.*)$','\1',$now);
    $now=ereg_replace('\(.*\)',' ',$now);
    if(strlen($name) > 10) $name = substr($name,0,9).".";
    if(strlen($sub) > 10) $sub = substr($sub,0,9).".";
    if($email) $name="<a href=\"mailto:$email\">$name</a>";
    $com = str_replace("<br />"," ",$com);
    $com = htmlspecialchars($com);
    if(strlen($com) > 20) $com = substr($com,0,18) . ".";
    // 画像があるときはリンク
    if($ext && is_file($path.$time.$ext)){
      $img_flag = TRUE;
      $clip = "<a href=\"".IMG_DIR.$time.$ext."\" target=_blank>".$time.$ext."</a><br>";
      $size = filesize($path.$time.$ext);
      $all += $size;			//合計計算
      $chk= substr($chk,0,10);
    }else{
      $clip = "";
      $size = 0;
      $chk= "";
    }
    $bg = ($j % 2) ? "d6d6f6" : "f6f6f6";//背景色

    echo "<tr bgcolor=$bg><th><input type=checkbox name=\"$no\" value=delete></th>";
    echo "<th>$no</th><td><small>$now</small></td><td>$sub</td>";
    echo "<td><b>$name</b></td><td><small>$com</small></td>";
    echo "<td>$host</td><td align=center>$clip($size)</td><td>$chk</td>\n";
    echo "</tr>\n";
  }

  echo "</table><p><input type=submit value=\"Para eliminar$msg\">";
  echo "<input type=reset value=\"リセット\"></form>";

  $all = (int)($all / 1024);
  echo "【Total de data de imagen: <b>$all</b> KB 】";
  die("</center></body></html>");
}
function init(){
  $err="";
  $chkfile=array(LOGFILE,TREEFILE);
  if(!is_writable(realpath("./")))error("No se puede escribir en el directorio actual.<br>");
  foreach($chkfile as $value){
    if(!file_exists(realpath($value))){
      $fp = fopen($value, "w");
      set_file_buffer($fp, 0);
      if($value==LOGFILE)fputs($fp,"1,2018/01/01 00:00,Animbo-Sama A.K.A El administrador,,Sin titulo,Bienvenido a Animbo v1 (Ramen),,,,,,,,\n");
      if($value==TREEFILE)fputs($fp,"1\n");
      fclose($fp);
      if(file_exists(realpath($value)))@chmod($value,0666);
    }
    if(!is_writable(realpath($value)))$err.=$value."Ruta inescribible.<br>";
    if(!is_readable(realpath($value)))$err.=$value."Ruta ilegible.<br>";
  }
  @mkdir(IMG_DIR,0777);@chmod(IMG_DIR,0777);
  if(!is_dir(realpath(IMG_DIR)))$err.=IMG_DIR."Ruta inexistente.<br>";
  if(!is_writable(realpath(IMG_DIR)))$err.=IMG_DIR."Ruta no escribible.<br>";
  if(!is_readable(realpath(IMG_DIR)))$err.=IMG_DIR."Ruta no legible.<br>";
  if(USE_THUMB){
    @mkdir(THUMB_DIR,0777);@chmod(THUMB_DIR,0777);
    if(!is_dir(realpath(IMG_DIR)))$err.=THUMB_DIR."No existe la ruta.<br>";
    if(!is_writable(realpath(THUMB_DIR)))$err.=THUMB_DIR."Ruta no escribible.<br>";
    if(!is_readable(realpath(THUMB_DIR)))$err.=THUMB_DIR."Ruta no legible.<br>";
  }
  if($err)error($err);
}
/*-----------Main-------------*/
init();		//←■■初期設定後は不要なので削除可■■
$iniv=array('mode','name','email','sub','com','pwd','upfile','upfile_name','resto','pass','res','post','no');
foreach($iniv as $iniva){
  if(!isset($$iniva)){$$iniva="";}
}
switch($mode){
  case 'regist':
    regist($name,$email,$sub,$com,'',$pwd,$upfile,$upfile_name,$resto);
    break;
  case 'admin':
    valid($pass);
    if($admin=="del") admindel($pass);
    if($admin=="post"){
      echo "</form>";
      form($post,$res,1);
      echo $post;
      die("</body></html>");
    }
    break;
  case 'usrdel':
    usrdel($no,$pwd);
  default:
    if($res){
      updatelog($res);
    }else{
      updatelog();
      echo "<META HTTP-EQUIV=\"refresh\" content=\"0;URL=".PHP_SELF2."\">";
    }
}
?>
