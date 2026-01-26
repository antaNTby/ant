<?php
/** Adminer Editor - Compact database editor
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2009 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 5.4.1
*/namespace
Adminer;const
VERSION="5.4.1";error_reporting(24575);set_error_handler(function($Xb,$Yb){return!!preg_match('~^Undefined (array key|offset|index)~',$Yb);},E_WARNING|E_NOTICE);$oc=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($oc||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Wg=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Wg)$$X=$Wg;}}if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection($h=null){return($h?:Db::$instance);}function
adminer(){return
Adminer::$instance;}function
driver(){return
Driver::$instance;}function
connect(){$nb=adminer()->credentials();$H=Driver::connect($nb[0],$nb[1],$nb[2]);return(is_object($H)?$H:null);}function
idf_unescape($t){if(!preg_match('~^[`\'"[]~',$t))return$t;$Hd=substr($t,-1);return
str_replace($Hd.$Hd,$Hd,substr($t,1,-1));}function
q($Q){return
connection()->quote($Q);}function
escape_string($X){return
substr(q($X),1,-1);}function
idx($na,$w,$j=null){return($na&&array_key_exists($w,$na)?$na[$w]:$j);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
number_type(){return'((?<!o)int(?!er)|numeric|real|float|double|decimal|money)';}function
remove_slashes(array$of,$oc=false){if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){while(list($w,$X)=each($of)){foreach($X
as$_d=>$W){unset($of[$w][$_d]);if(is_array($W)){$of[$w][stripslashes($_d)]=$W;$of[]=&$of[$w][stripslashes($_d)];}else$of[$w][stripslashes($_d)]=($oc?$W:stripslashes($W));}}}}function
bracket_escape($t,$wa=false){static$Gg=array(':'=>':1',']'=>':2','['=>':3','"'=>':4');return
strtr($t,($wa?array_flip($Gg):$Gg));}function
min_version($lh,$Sd="",$h=null){$h=connection($h);$Sf=$h->server_info;if($Sd&&preg_match('~([\d.]+)-MariaDB~',$Sf,$z)){$Sf=$z[1];$lh=$Sd;}return$lh&&version_compare($Sf,$lh)>=0;}function
charset(Db$g){return(min_version("5.5.3",0,$g)?"utf8mb4":"utf8");}function
ini_bool($md){$X=ini_get($md);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
ini_bytes($md){$X=ini_get($md);switch(strtolower(substr($X,-1))){case'g':$X=(int)$X*1024;case'm':$X=(int)$X*1024;case'k':$X=(int)$X*1024;}return$X;}function
sid(){static$H;if($H===null)$H=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$H;}function
set_password($kh,$M,$V,$D){$_SESSION["pwds"][$kh][$M][$V]=($_COOKIE["adminer_key"]&&is_string($D)?array(encrypt_string($D,$_COOKIE["adminer_key"])):$D);}function
get_password(){$H=get_session("pwds");if(is_array($H))$H=($_COOKIE["adminer_key"]?decrypt_string($H[0],$_COOKIE["adminer_key"]):false);return$H;}function
get_val($F,$l=0,$db=null){$db=connection($db);$G=$db->query($F);if(!is_object($G))return
false;$I=$G->fetch_row();return($I?$I[$l]:false);}function
get_vals($F,$d=0){$H=array();$G=connection()->query($F);if(is_object($G)){while($I=$G->fetch_row())$H[]=$I[$d];}return$H;}function
get_key_vals($F,$h=null,$Vf=true){$h=connection($h);$H=array();$G=$h->query($F);if(is_object($G)){while($I=$G->fetch_row()){if($Vf)$H[$I[0]]=$I[1];else$H[]=$I[0];}}return$H;}function
get_rows($F,$h=null,$k="<p class='error'>"){$db=connection($h);$H=array();$G=$db->query($F);if(is_object($G)){while($I=$G->fetch_assoc())$H[]=$I;}elseif(!$G&&!$h&&$k&&(defined('Adminer\PAGE_HEADER')||$k=="-- "))echo$k.error()."\n";return$H;}function
unique_array($I,array$v){foreach($v
as$u){if(preg_match("~PRIMARY|UNIQUE~",$u["type"])){$H=array();foreach($u["columns"]as$w){if(!isset($I[$w]))continue
2;$H[$w]=$I[$w];}return$H;}}}function
escape_key($w){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$w,$z))return$z[1].idf_escape(idf_unescape($z[2])).$z[3];return
idf_escape($w);}function
where(array$Z,array$m=array()){$H=array();foreach((array)$Z["where"]as$w=>$X){$w=bracket_escape($w,true);$d=escape_key($w);$l=idx($m,$w,array());$lc=$l["type"];$H[]=$d.(JUSH=="sql"&&$lc=="json"?" = CAST(".q($X)." AS JSON)":(JUSH=="pgsql"&&preg_match('~^json~',$lc)?"::jsonb = ".q($X)."::jsonb":(JUSH=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):(JUSH=="mssql"&&strpos($lc,"datetime")===false?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($l,q($X))))));if(JUSH=="sql"&&preg_match('~char|text~',$lc)&&preg_match("~[^ -@]~",$X))$H[]="$d = ".q($X)." COLLATE ".charset(connection())."_bin";}foreach((array)$Z["null"]as$w)$H[]=escape_key($w)." IS NULL";return
implode(" AND ",$H);}function
where_check($X,array$m=array()){parse_str($X,$Ma);remove_slashes(array(&$Ma));return
where($Ma,$m);}function
where_link($r,$d,$Y,$De="="){return"&where%5B$r%5D%5Bcol%5D=".urlencode($d)."&where%5B$r%5D%5Bop%5D=".urlencode(($Y!==null?$De:"IS NULL"))."&where%5B$r%5D%5Bval%5D=".urlencode($Y);}function
convert_fields(array$e,array$m,array$K=array()){$H="";foreach($e
as$w=>$X){if($K&&!in_array(idf_escape($w),$K))continue;$oa=convert_field($m[$w]);if($oa)$H
.=", $oa AS ".idf_escape($w);}return$H;}function
cookie($_,$Y,$Md=2592000){header("Set-Cookie: $_=".urlencode($Y).($Md?"; expires=".gmdate("D, d M Y H:i:s",time()+$Md)." GMT":"")."; path=".preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]).(HTTPS?"; secure":"")."; HttpOnly; SameSite=lax",false);}function
get_settings($kb){parse_str($_COOKIE[$kb],$Wf);return$Wf;}function
get_setting($w,$kb="adminer_settings",$j=null){return
idx(get_settings($kb),$w,$j);}function
save_settings(array$Wf,$kb="adminer_settings"){$Y=http_build_query($Wf+get_settings($kb));cookie($kb,$Y);$_COOKIE[$kb]=$Y;}function
restart_session(){if(!ini_bool("session.use_cookies")&&(!function_exists('session_status')||session_status()==1))session_start();}function
stop_session($vc=false){$fh=ini_bool("session.use_cookies");if(!$fh||$vc){session_write_close();if($fh&&@ini_set("session.use_cookies",'0')===false)session_start();}}function&get_session($w){return$_SESSION[$w][DRIVER][SERVER][$_GET["username"]];}function
set_session($w,$X){$_SESSION[$w][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($kh,$M,$V,$i=null){$ch=remove_from_uri(implode("|",array_keys(SqlDriver::$drivers))."|username|ext|".($i!==null?"db|":"").($kh=='mssql'||$kh=='pgsql'?"":"ns|").session_name());preg_match('~([^?]*)\??(.*)~',$ch,$z);return"$z[1]?".(sid()?SID."&":"").($kh!="server"||$M!=""?urlencode($kh)."=".urlencode($M)."&":"").($_GET["ext"]?"ext=".urlencode($_GET["ext"])."&":"")."username=".urlencode($V).($i!=""?"&db=".urlencode($i):"").($z[2]?"&$z[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($Od,$ee=null){if($ee!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($Od!==null?$Od:$_SERVER["REQUEST_URI"]))][]=$ee;}if($Od!==null){if($Od=="")$Od=".";header("Location: $Od");exit;}}function
query_redirect($F,$Od,$ee,$wf=true,$cc=true,$hc=false,$xg=""){if($cc){$gg=microtime(true);$hc=!connection()->query($F);$xg=format_time($gg);}$dg=($F?adminer()->messageQuery($F,$xg,$hc):"");if($hc){adminer()->error
.=error().$dg.script("messagesPrint();")."<br>";return
false;}if($wf)redirect($Od,$ee.$dg);return
true;}class
Queries{static$queries=array();static$start=0;}function
queries($F){if(!Queries::$start)Queries::$start=microtime(true);Queries::$queries[]=(preg_match('~;$~',$F)?"DELIMITER ;;\n$F;\nDELIMITER ":$F).";";return
connection()->query($F);}function
apply_queries($F,array$T,$Zb='Adminer\table'){foreach($T
as$R){if(!queries("$F ".$Zb($R)))return
false;}return
true;}function
queries_redirect($Od,$ee,$wf){$rf=implode("\n",Queries::$queries);$xg=format_time(Queries::$start);return
query_redirect($rf,$Od,$ee,$wf,false,!$wf,$xg);}function
format_time($gg){return
lang(0,max(0,microtime(true)-$gg));}function
relative_uri(){return
str_replace(":","%3a",preg_replace('~^[^?]*/([^?]*)~','\1',$_SERVER["REQUEST_URI"]));}function
remove_from_uri($Se=""){return
substr(preg_replace("~(?<=[?&])($Se".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
get_file($w,$vb=false,$yb=""){$mc=$_FILES[$w];if(!$mc)return
null;foreach($mc
as$w=>$X)$mc[$w]=(array)$X;$H='';foreach($mc["error"]as$w=>$k){if($k)return$k;$_=$mc["name"][$w];$Dg=$mc["tmp_name"][$w];$ib=file_get_contents($vb&&preg_match('~\.gz$~',$_)?"compress.zlib://$Dg":$Dg);if($vb){$gg=substr($ib,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$gg))$ib=iconv("utf-16","utf-8",$ib);elseif($gg=="\xEF\xBB\xBF")$ib=substr($ib,3);}$H
.=$ib;if($yb)$H
.=(preg_match("($yb\\s*\$)",$ib)?"":$yb)."\n\n";}return$H;}function
upload_error($k){$ae=($k==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($k?lang(1).($ae?" ".lang(2,$ae):""):lang(3));}function
repeat_pattern($af,$Kd){return
str_repeat("$af{0,65535}",$Kd/65535)."$af{0,".($Kd%65535)."}";}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
format_number($X){return
strtr(number_format($X,0,".",lang(4)),preg_split('~~u',lang(5),-1,PREG_SPLIT_NO_EMPTY));}function
friendly_url($X){return
preg_replace('~\W~i','-',$X);}function
table_status1($R,$ic=false){$H=table_status($R,$ic);return($H?reset($H):array("Name"=>$R));}function
column_foreign_keys($R){$H=array();foreach(adminer()->foreignKeys($R)as$o){foreach($o["source"]as$X)$H[$X][]=$o;}return$H;}function
fields_from_edit(){$H=array();foreach((array)$_POST["field_keys"]as$w=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$w];$_POST["fields"][$X]=$_POST["field_vals"][$w];}}foreach((array)$_POST["fields"]as$w=>$X){$_=bracket_escape($w,true);$H[$_]=array("field"=>$_,"privileges"=>array("insert"=>1,"update"=>1,"where"=>1,"order"=>1),"null"=>1,"auto_increment"=>($w==driver()->primary),);}return$H;}function
dump_headers($ad,$le=false){$H=adminer()->dumpHeaders($ad,$le);$Oe=$_POST["output"];if($Oe!="text")header("Content-Disposition: attachment; filename=".adminer()->dumpFilename($ad).".$H".($Oe!="file"&&preg_match('~^[0-9a-z]+$~',$Oe)?".$Oe":""));session_write_close();if(!ob_get_level())ob_start(null,4096);ob_flush();flush();return$H;}function
dump_csv(array$I){foreach($I
as$w=>$X){if(preg_match('~["\n,;\t]|^0.|\.\d*0$~',$X)||$X==="")$I[$w]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($_POST["format"]=="tsv"?"\t":";")),$I)."\r\n";}function
apply_sql_function($q,$d){return($q?($q=="unixepoch"?"DATETIME($d, '$q')":($q=="count distinct"?"COUNT(DISTINCT ":strtoupper("$q("))."$d)"):$d);}function
get_temp_dir(){$H=ini_get("upload_tmp_dir");if(!$H){if(function_exists('sys_get_temp_dir'))$H=sys_get_temp_dir();else{$n=@tempnam("","");if(!$n)return'';$H=dirname($n);unlink($n);}}return$H;}function
file_open_lock($n){if(is_link($n))return;$p=@fopen($n,"c+");if(!$p)return;@chmod($n,0660);if(!flock($p,LOCK_EX)){fclose($p);return;}return$p;}function
file_write_unlock($p,$sb){rewind($p);fwrite($p,$sb);ftruncate($p,strlen($sb));file_unlock($p);}function
file_unlock($p){flock($p,LOCK_UN);fclose($p);}function
first(array$na){return
reset($na);}function
password_file($lb){$n=get_temp_dir()."/adminer.key";if(!$lb&&!file_exists($n))return'';$p=file_open_lock($n);if(!$p)return'';$H=stream_get_contents($p);if(!$H){$H=rand_string();file_write_unlock($p,$H);}else
file_unlock($p);return$H;}function
rand_string(){return
md5(uniqid(strval(mt_rand()),true));}function
select_value($X,$y,array$l,$vg){if(is_array($X)){$H="";foreach($X
as$_d=>$W)$H
.="<tr>".($X!=array_values($X)?"<th>".h($_d):"")."<td>".select_value($W,$y,$l,$vg);return"<table>$H</table>";}if(!$y)$y=adminer()->selectLink($X,$l);if($y===null){if(is_mail($X))$y="mailto:$X";if(is_url($X))$y=$X;}$H=adminer()->editVal($X,$l);if($H!==null){if(!is_utf8($H))$H="\0";elseif($vg!=""&&is_shortable($l))$H=shorten_utf8($H,max(0,+$vg));else$H=h($H);}return
adminer()->selectVal($H,$y,$l,$X);}function
is_blob(array$l){return
preg_match('~blob|bytea|raw|file~',$l["type"])&&!in_array($l["type"],idx(driver()->structuredTypes(),lang(6),array()));}function
is_mail($Ob){$pa='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$Eb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$af="$pa+(\\.$pa+)*@($Eb?\\.)+$Eb";return
is_string($Ob)&&preg_match("(^$af(,\\s*$af)*\$)i",$Ob);}function
is_url($Q){$Eb='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^(https?)://($Eb?\\.)+$Eb(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$Q);}function
is_shortable(array$l){return
preg_match('~char|text|json|lob|geometry|point|linestring|polygon|string|bytea|hstore~',$l["type"]);}function
host_port($M){return(preg_match('~^(\[(.+)]|([^:]+)):([^:]+)$~',$M,$z)?array($z[2].$z[3],$z[4]):array($M,''));}function
count_rows($R,array$Z,$vd,array$Gc){$F=" FROM ".table($R).($Z?" WHERE ".implode(" AND ",$Z):"");return($vd&&(JUSH=="sql"||count($Gc)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$Gc).")$F":"SELECT COUNT(*)".($vd?" FROM (SELECT 1$F GROUP BY ".implode(", ",$Gc).") x":$F));}function
slow_query($F){$i=adminer()->database();$yg=adminer()->queryTimeout();$Yf=driver()->slowQuery($F,$yg);$h=null;if(!$Yf&&support("kill")){$h=connect();if($h&&($i==""||$h->select_db($i))){$Cd=get_val(connection_id(),0,$h);echo
script("const timeout = setTimeout(() => { ajax('".js_escape(ME)."script=kill', function () {}, 'kill=$Cd&token=".get_token()."'); }, 1000 * $yg);");}}ob_flush();flush();$H=@get_key_vals(($Yf?:$F),$h,false);if($h){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$H;}function
get_token(){$uf=rand(1,1e6);return($uf^$_SESSION["token"]).":$uf";}function
verify_token(){list($Eg,$uf)=explode(":",$_POST["token"]);return($uf^$_SESSION["token"])==$Eg;}function
lzw_decompress($Ca){$Bb=256;$Da=8;$Ua=array();$Bf=0;$Cf=0;for($r=0;$r<strlen($Ca);$r++){$Bf=($Bf<<8)+ord($Ca[$r]);$Cf+=8;if($Cf>=$Da){$Cf-=$Da;$Ua[]=$Bf>>$Cf;$Bf&=(1<<$Cf)-1;$Bb++;if($Bb>>$Da)$Da++;}}$Ab=range("\0","\xFF");$H="";$sh="";foreach($Ua
as$r=>$Ta){$Nb=$Ab[$Ta];if(!isset($Nb))$Nb=$sh.$sh[0];$H
.=$Nb;if($r)$Ab[]=$sh.$Nb[0];$sh=$Nb;}return$H;}function
script($ag,$Fg="\n"){return"<script".nonce().">$ag</script>$Fg";}function
script_src($dh,$wb=false){return"<script src='".h($dh)."'".nonce().($wb?" defer":"")."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
input_hidden($_,$Y=""){return"<input type='hidden' name='".h($_)."' value='".h($Y)."'>\n";}function
input_token(){return
input_hidden("token",get_token());}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($Q){return
str_replace("\0","&#0;",htmlspecialchars($Q,ENT_QUOTES,'utf-8'));}function
nl_br($Q){return
str_replace("\n","<br>",$Q);}function
checkbox($_,$Y,$Oa,$Dd="",$Be="",$Ra="",$Fd=""){$H="<input type='checkbox' name='$_' value='".h($Y)."'".($Oa?" checked":"").($Fd?" aria-labelledby='$Fd'":"").">".($Be?script("qsl('input').onclick = function () { $Be };",""):"");return($Dd!=""||$Ra?"<label".($Ra?" class='$Ra'":"").">$H".h($Dd)."</label>":$H);}function
optionlist($B,$Mf=null,$gh=false){$H="";foreach($B
as$_d=>$W){$Ge=array($_d=>$W);if(is_array($W)){$H
.='<optgroup label="'.h($_d).'">';$Ge=$W;}foreach($Ge
as$w=>$X)$H
.='<option'.($gh||is_string($w)?' value="'.h($w).'"':'').($Mf!==null&&($gh||is_string($w)?(string)$w:$X)===$Mf?' selected':'').'>'.h($X);if(is_array($W))$H
.='</optgroup>';}return$H;}function
html_select($_,array$B,$Y="",$Ae="",$Fd=""){static$Dd=0;$Ed="";if(!$Fd&&substr($B[""],0,1)=="("){$Dd++;$Fd="label-$Dd";$Ed="<option value='' id='$Fd'>".h($B[""]);unset($B[""]);}return"<select name='".h($_)."'".($Fd?" aria-labelledby='$Fd'":"").">".$Ed.optionlist($B,$Y)."</select>".($Ae?script("qsl('select').onchange = function () { $Ae };",""):"");}function
html_radios($_,array$B,$Y="",$L=""){$H="";foreach($B
as$w=>$X)$H
.="<label><input type='radio' name='".h($_)."' value='".h($w)."'".($w==$Y?" checked":"").">".h($X)."</label>$L";return$H;}function
confirm($ee="",$Nf="qsl('input')"){return
script("$Nf.onclick = () => confirm('".($ee?js_escape($ee):lang(7))."');","");}function
print_fieldset($s,$Jd,$oh=false){echo"<fieldset><legend>","<a href='#fieldset-$s'>$Jd</a>",script("qsl('a').onclick = partial(toggle, 'fieldset-$s');",""),"</legend>","<div id='fieldset-$s'".($oh?"":" class='hidden'").">\n";}function
bold($Ea,$Ra=""){return($Ea?" class='active $Ra'":($Ra?" class='$Ra'":""));}function
js_escape($Q){return
addcslashes($Q,"\r\n'\\/");}function
pagination($C,$qb){return" ".($C==$qb?$C+1:'<a href="'.h(remove_from_uri("page").($C?"&page=$C".($_GET["next"]?"&next=".urlencode($_GET["next"]):""):"")).'">'.($C+1)."</a>");}function
hidden_fields(array$of,array$dd=array(),$jf=''){$H=false;foreach($of
as$w=>$X){if(!in_array($w,$dd)){if(is_array($X))hidden_fields($X,array(),$w);else{$H=true;echo
input_hidden(($jf?$jf."[$w]":$w),$X);}}}return$H;}function
hidden_fields_get(){echo(sid()?input_hidden(session_name(),session_id()):''),(SERVER!==null?input_hidden(DRIVER,SERVER):""),input_hidden("username",$_GET["username"]);}function
file_input($od){$Xd="max_file_uploads";$Yd=ini_get($Xd);$ah="upload_max_filesize";$bh=ini_get($ah);return(ini_bool("file_uploads")?$od.script("qsl('input[type=\"file\"]').onchange = partialArg(fileChange, "."$Yd, '".lang(8,"$Xd = $Yd")."', ".ini_bytes("upload_max_filesize").", '".lang(8,"$ah = $bh")."')"):lang(9));}function
enum_input($U,$ra,array$l,$Y,$Rb=""){preg_match_all("~'((?:[^']|'')*)'~",$l["length"],$Vd);$jf=($l["type"]=="enum"?"val-":"");$Oa=(is_array($Y)?in_array("null",$Y):$Y===null);$H=($l["null"]&&$jf?"<label><input type='$U'$ra value='null'".($Oa?" checked":"")."><i>$Rb</i></label>":"");foreach($Vd[1]as$X){$X=stripcslashes(str_replace("''","'",$X));$Oa=(is_array($Y)?in_array($jf.$X,$Y):$Y===$X);$H
.=" <label><input type='$U'$ra value='".h($jf.$X)."'".($Oa?' checked':'').'>'.h(adminer()->editVal($X,$l)).'</label>';}return$H;}function
input(array$l,$Y,$q,$va=false){$_=h(bracket_escape($l["field"]));echo"<td class='function'>";if(is_array($Y)&&!$q){$Y=json_encode($Y,128|64|256);$q="json";}$Af=(JUSH=="mssql"&&$l["auto_increment"]);if($Af&&!$_POST["save"])$q=null;$Dc=(isset($_GET["select"])||$Af?array("orig"=>lang(10)):array())+adminer()->editFunctions($l);$Vb=driver()->enumLength($l);if($Vb){$l["type"]="enum";$l["length"]=$Vb;}$Cb=stripos($l["default"],"GENERATED ALWAYS AS ")===0?" disabled=''":"";$ra=" name='fields[$_]".($l["type"]=="enum"||$l["type"]=="set"?"[]":"")."'$Cb".($va?" autofocus":"");echo
driver()->unconvertFunction($l)." ";$R=$_GET["edit"]?:$_GET["select"];if($l["type"]=="enum")echo
h($Dc[""])."<td>".adminer()->editInput($R,$l,$ra,$Y);else{$Nc=(in_array($q,$Dc)||isset($Dc[$q]));echo(count($Dc)>1?"<select name='function[$_]'$Cb>".optionlist($Dc,$q===null||$Nc?$q:"")."</select>".on_help("event.target.value.replace(/^SQL\$/, '')",1).script("qsl('select').onchange = functionChange;",""):h(reset($Dc))).'<td>';$od=adminer()->editInput($R,$l,$ra,$Y);if($od!="")echo$od;elseif(preg_match('~bool~',$l["type"]))echo"<input type='hidden'$ra value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked='checked'":"")."$ra value='1'>";elseif($l["type"]=="set")echo
enum_input("checkbox",$ra,$l,(is_string($Y)?explode(",",$Y):$Y));elseif(is_blob($l)&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$_'>";elseif($q=="json"||preg_match('~^jsonb?$~',$l["type"]))echo"<textarea$ra cols='50' rows='12' class='jush-js'>".h($Y).'</textarea>';elseif(($tg=preg_match('~text|lob|memo~i',$l["type"]))||preg_match("~\n~",$Y)){if($tg&&JUSH!="sqlite")$ra
.=" cols='50' rows='12'";else{$J=min(12,substr_count($Y,"\n")+1);$ra
.=" cols='30' rows='$J'";}echo"<textarea$ra>".h($Y).'</textarea>';}else{$Qg=driver()->types();$ce=(!preg_match('~int~',$l["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$l["length"],$z)?((preg_match("~binary~",$l["type"])?2:1)*$z[1]+($z[3]?1:0)+($z[2]&&!$l["unsigned"]?1:0)):($Qg[$l["type"]]?$Qg[$l["type"]]+($l["unsigned"]?0:1):0));if(JUSH=='sql'&&min_version(5.6)&&preg_match('~time~',$l["type"]))$ce+=7;echo"<input".((!$Nc||$q==="")&&preg_match('~(?<!o)int(?!er)~',$l["type"])&&!preg_match('~\[\]~',$l["full_type"])?" type='number'":"")." value='".h($Y)."'".($ce?" data-maxlength='$ce'":"").(preg_match('~char|binary~',$l["type"])&&$ce>20?" size='".($ce>99?60:40)."'":"")."$ra>";}echo
adminer()->editHint($R,$l,$Y);$pc=0;foreach($Dc
as$w=>$X){if($w===""||!$X)break;$pc++;}if($pc&&count($Dc)>1)echo
script("qsl('td').oninput = partial(skipOriginal, $pc);");}}function
process_input(array$l){if(stripos($l["default"],"GENERATED ALWAYS AS ")===0)return;$t=bracket_escape($l["field"]);$q=idx($_POST["function"],$t);$Y=idx($_POST["fields"],$t);if($l["type"]=="enum"||driver()->enumLength($l)){$Y=$Y[0];if($Y=="orig")return
false;if($Y=="null")return"NULL";$Y=substr($Y,4);}if($l["auto_increment"]&&$Y=="")return
null;if($q=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?idf_escape($l["field"]):false);if($q=="NULL")return"NULL";if($l["type"]=="set")$Y=implode(",",(array)$Y);if($q=="json"){$q="";$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}if(is_blob($l)&&ini_bool("file_uploads")){$mc=get_file("fields-$t");if(!is_string($mc))return
false;return
driver()->quoteBinary($mc);}return
adminer()->processInput($l,$Y,$q);}function
search_tables(){$_GET["where"][0]["val"]=$_POST["query"];$Of="<ul>\n";foreach(table_status('',true)as$R=>$S){$_=adminer()->tableName($S);if(isset($S["Engine"])&&$_!=""&&(!$_POST["tables"]||in_array($R,$_POST["tables"]))){$G=connection()->query("SELECT".limit("1 FROM ".table($R)," WHERE ".implode(" AND ",adminer()->selectSearchProcess(fields($R),array())),1));if(!$G||$G->fetch_row()){$mf="<a href='".h(ME."select=".urlencode($R)."&where[0][op]=".urlencode($_GET["where"][0]["op"])."&where[0][val]=".urlencode($_GET["where"][0]["val"]))."'>$_</a>";echo"$Of<li>".($G?$mf:"<p class='error'>$mf: ".error())."\n";$Of="";}}}echo($Of?"<p class='message'>".lang(11):"</ul>")."\n";}function
on_help($Za,$Xf=0){return
script("mixin(qsl('select, input'), {onmouseover: function (event) { helpMouseover.call(this, event, $Za, $Xf) }, onmouseout: helpMouseout});","");}function
edit_form($R,array$m,$I,$Zg,$k=''){$pg=adminer()->tableName(table_status1($R,true));page_header(($Zg?lang(12):lang(13)),$k,array("select"=>array($R,$pg)),$pg);adminer()->editRowPrint($R,$m,$I,$Zg);if($I===false){echo"<p class='error'>".lang(14)."\n";return;}echo"<form action='' method='post' enctype='multipart/form-data' id='form'>\n";if(!$m)echo"<p class='error'>".lang(15)."\n";else{echo"<table class='layout'>".script("qsl('table').onkeydown = editingKeydown;");$va=!$_POST;foreach($m
as$_=>$l){echo"<tr><th>".adminer()->fieldName($l);$j=idx($_GET["set"],bracket_escape($_));if($j===null){$j=$l["default"];if($l["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$j,$yf))$j=$yf[1];if(JUSH=="sql"&&preg_match('~binary~',$l["type"]))$j=bin2hex($j);}$Y=($I!==null?($I[$_]!=""&&JUSH=="sql"&&preg_match("~enum|set~",$l["type"])&&is_array($I[$_])?implode(",",$I[$_]):(is_bool($I[$_])?+$I[$_]:$I[$_])):(!$Zg&&$l["auto_increment"]?"":(isset($_GET["select"])?false:$j)));if(!$_POST["save"]&&is_string($Y))$Y=adminer()->editVal($Y,$l);$q=($_POST["save"]?idx($_POST["function"],$_,""):($Zg&&preg_match('~^CURRENT_TIMESTAMP~i',$l["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(!$_POST&&!$Zg&&$Y==$l["default"]&&preg_match('~^[\w.]+\(~',$Y))$q="SQL";if(preg_match("~time~",$l["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$q="now";}if($l["type"]=="uuid"&&$Y=="uuid()"){$Y="";$q="uuid";}if($va!==false)$va=($l["auto_increment"]||$q=="now"||$q=="uuid"?null:true);input($l,$Y,$q,$va);if($va)$va=false;echo"\n";}if(!support("table")&&!fields($R))echo"<tr>"."<th><input name='field_keys[]'>".script("qsl('input').oninput = fieldChange;")."<td class='function'>".html_select("field_funs[]",adminer()->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>"."\n";echo"</table>\n";}echo"<p>\n";if($m){echo"<input type='submit' value='".lang(16)."'>\n";if(!isset($_GET["select"]))echo"<input type='submit' name='insert' value='".($Zg?lang(17):lang(18))."' title='Ctrl+Shift+Enter'>\n",($Zg?script("qsl('input').onclick = function () { return !ajaxForm(this.form, '".lang(19)."â€¦', this); };"):"");}echo($Zg?"<input type='submit' name='delete' value='".lang(20)."'>".confirm()."\n":"");if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo
input_hidden("referer",(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"])),input_hidden("save",1),input_token(),"</form>\n";}function
shorten_utf8($Q,$Kd=80,$kg=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$Kd).")($)?)u",$Q,$z))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$Kd).")($)?)",$Q,$z);return
h($z[1]).$kg.(isset($z[2])?"":"<i>â€¦</i>");}function
icon($Zc,$_,$Yc,$_g){return"<button type='submit' name='$_' title='".h($_g)."' class='icon icon-$Zc'><span>$Yc</span></button>";}if(isset($_GET["file"])){if(substr(VERSION,-4)!='-dev'){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");}@ini_set("zlib.output_compression",'1');if($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("h:M‡±h´ÄgÌĞ±ÜÍŒ\"PÑiÒm„™cQCa¤é	2Ã³éˆŞd<Ìfóa¼ä:;NBˆqœR;1Lf³9ÈŞu7&)¤l;3ÍÑñÈÀJ/‹†CQXÊr2MÆaäi0›„ƒ)°ìe:LuÃhæ-9ÕÍ23lÈÎi7†³màZw4™†Ñš<-•ÒÌ´¹!†U,—ŒFÃ©”vt2‘S,¬äa´Ò‡FêVXúa˜Nqã)“-—ÖÎÇœhê:n5û9ÈY¨;jµ”-Ş÷_‘9krùœÙ“;.ĞtTqËo¦0‹³­Öò®{íóyùı\rçHnìGS™ Zh²œ;¼i^ÀuxøWÎ’C@Äö¤©k€Ò=¡Ğb©Ëâì¼/AØà0¤+Â(ÚÁ°lÂÉÂ\\ê Ãxè:\rèÀb8\0æ–0!\0FÆ\nB”Íã(Ò3 \r\\ºÛêÈ„a¼„œ'Iâ|ê(iš\n‹\r©¸ú4Oüg@4ÁC’î¼†º@@†!ÄQB°İ	Â°¸c¤ÊÂ¯Äq,\r1EhèÈ&2PZ‡¦ğiGûH9G’\"v§ê’¢££¤œ4r”ÆñÍDĞR¤\n†pJë-A“|/.¯cê“Du·£¤ö:,˜Ê=°¢RÅ]U5¥mVÁkÍLLQ@-\\ª¦ËŒ@9Áã%ÚSrÁÎñMPDãÂIa\rƒ(YY\\ã@XõpÃê:£p÷lLC —Åñè¸ƒÍÊO,\rÆ2]7œ?m06ä»pÜTÑÍaÒ¥Cœ;_Ë—ÑyÈ´d‘>¨²bnğ…«n¼Ü£3÷X¾€ö8\rí[Ë€-)Ûi>V[Yãy&L3¯#ÌX|Õ	†X \\Ã¹`ËC§ç˜å#ÑÙHÉÌ2Ê2.# ö‹Zƒ`Â<¾ãs®·¹ªÃ’£º\0uœhÖ¾—¥M²Í_\niZeO/CÓ’_†`3İòğ1>‹=Ğk3£…‰R/;ä/dÛÜ\0ú‹ŒãŞÚµmùúò¾¤7/«ÖAÎXƒÂÿ„°“Ãq.½sáL£ı— :\$ÉF¢—¸ª¾£‚w‰8óß¾~«HÔj…­\"¨¼œ•¹Ô³7gSõä±âFLéÎ¯çQò_¤’O'WØö]c=ı5¾1X~7;˜™iş´\rí*\n’¨JS1Z¦™ø£ØÆßÍcå‚tœüAÔVí86fĞdÃy;Y]©õzIÀp¡Ñû§ğc‰3®YË]}Â˜@¡\$.+”1¶'>ZÃcpdàéÒGLæá„#kô8PzœYÒAuÏvİ]s9‰ÑØ_AqÎÁ„:†ÆÅ\nK€hB¼;­ÖŠXbAHq,âCIÉ`†‚çj¹S[ËŒ¶1ÆVÓrŠñÔ;¶pŞBÃÛ)#é‰;4ÌHñÒ/*Õ<Â3L Á;lfª\n¶s\$K`Ğ}ÆôÕ”£¾7ƒjx`d–%j] ¸4œ—Y¤–HbY ØJ`¤GG ’.ÅÜK‚òfÊI©)2ÂŠMfÖ¸İX‰RC‰¸Ì±V,©ÛÑ~g\0è‚àg6İ:õ[jí1H½:AlIq©u3\"™êæq¤æ|8<9s'ãQ]JÊ|Ğ\0Â`p ³îƒ«‰jf„OÆbĞÉú¬¨q¬¢\$é©²Ã1J¹>RœH(Ç”q\n#rŠ’à@e(yóVJµ0¡QÒˆ£òˆ6†Pæ[C:·Gä¼‘ İ4©‘Ò^ÓğÃPZŠµ\\´‘è(\nÖ)š~¦´°9R%×Sj·{‰7ä0Ş_šÇs	z|8ÅHê	\"@Ü#9DVLÅ\$H5ÔWJ@—…z®a¿J Ä^	‘)®2\nQvÀÔ]ëÇ†ÄÁ˜‰j (A¸Ó°BB05´6†bË°][ŒèkªA•wvkgôÆ´öºÕ+k[jm„zc¶}èMyDZií\$5e˜«Ê·°º	”A˜ CY%.W€b*ë®¼‚.­Ùóq/%}BÌXˆ­çZV337‡Ê»a™„€ºòŞwW[áLQÊŞ²ü_È2`Ç1IÑi,÷æ›£’Mf&(s-˜ä˜ëÂAÄ°Ø*””DwØÄTNÀÉ»ÅjX\$éxª+;ĞğËFÚ93µJkÂ™S;·§ÁqR{>l;B1AÈIâb) (6±­r÷\rİ\rÚ‡’Ú‚ìZ‘R^SOy/“ŞM#ÆÏ9{k„àê¸v\"úKCâJƒ¨rEo\0øÌ\\,Ñ|faÍš†³hI“©/oÌ4Äk^pî1HÈ^“ÍphÇ¡VÁvox@ø`ígŸ&(ùˆ­ü;›ƒ~ÇzÌ6×8¯*°ÆÜ5®Ü‰±E ÁÂp†éâîÓ˜˜¤´3“öÅ†gŸ™rDÑLó)4g{»ˆä½å³©—Lš&ú>è„»¢ØÚZì7¡\0ú°ÌŠ@×ĞÓÛœffÅRVhÖ²çIŠÛˆ½âğrÓw)‹ ‚„=x^˜,k’Ÿ2ôÒİ“jàbël0uë\"¬fp¨¸1ñRI¿ƒz[]¤wpN6dIªzëõån.7X{;ÁÈ3ØË-I	‹âûü7pjÃ¢R#ª,ù_-ĞüÂ[ó>3À\\æêÛWqŞq”JÖ˜uh£‡ĞFbLÁKÔåçyVÄ¾©¦ÃŞÑ•®µªüVœîÃf{K}S ÊŞ…‰Mş‡·Í€¼¦.M¶\\ªix¸bÁ¡1‡+£Î±?<Å3ê~HıÓ\$÷\\Ğ2Û\$î eØ6tÔOÌˆã\$s¼¼©xÄşx•ó§CánSkVÄÉ=z6½‰¡Ê'Ã¦äNaŸ¢Ö¸hŒÜü¸º±ı¯R¤å™£8g‰¢äÊw:_³î­íÿêÒ’IRKÃ¨.½nkVU+dwj™§%³`#,{é†³ËğÊƒY‡ı×õ(oÕ¾Éğ.¨c‚0gâDXOk†7®èKäÎlÒÍhx;ÏØ İƒLû´\$09*–9 ÜhNrüMÕ.>\0ØrP9ï\$Èg	\0\$\\Fó*²d'ÎõLå:‹bú—ğ42Àô¢ğ9Àğ@ÂHnbì-¤óE #ÄœÉÃ¨\0ÀpY‚ê¨ tÍ Ø\nğ5.©àÊâî\$op l€X\n@`\r€	àˆ\r€Ğ Î ¦ ” ‚ àêğÛ`”\r ´\r £`‚` „0åpä	‘Ş@“\0’ÀĞ	 V\0ò`fÀÏÀª\0¤ Îf€\0j\n f`â	 ®\n`´@˜\$n=`†\0ÈÀƒànIĞ\$ÿP(Âd'Ëğô„Äà·gÉ\n¬4±\n0·¤ˆ.0ÃpËğÒ\r\0‡`–1`“àÎ\n\0_ óqñ1qµ`ß\0¡À”‚ äà˜†\0¢\n@â€ fÍPæ€æ RÇ ŞÇì‚€@ÙrÇFˆ˜¯h\r€@J¶Ñ^LNË!Àé\"\nÒÄeÊ]r:ÊZ7Ò9#\$0¬µ\"gÚ­t”RB×|‘/¼#í”×¸D’1\"®Ff‡\"nºòæ(Yp`W…”YÆ‘Ò]\$ÀFğF¨ğ¯ÜRn\ràw!MrìæK²*s%S\$² Ä¨.s*G*R©(=+‹Ş‹	\n)Òdûò£*mp‘‚\$rĞìä×\$”ÜÀë-â?.2©+r:~²Ğ‚I69+œ4H¼h ú\nz\"Ğ(,2 +Döjuåt@q. ğ³²½RÃ&i,kJ–r`„cÀÕ\"¢CIÑ	êâz8ÚŒ¥¾Û\r´š¯8êÒøİfƒ¢¿ëÃ.\"úÖËä›ê®Ó*h(åé\0ôO‰ªªÍ€Õ r| Ş…M\nĞå¾­o|LJªê²v1N´Ü3E(„R\".fh+FW/ÒÎIšÎ“~ğ/)ÀÚ¦\rÄ‰ï<ÀÛ=h1‰b]¢Ô&Åiœò-òmRôç?ä0Íîú“¦ĞäÔï êïl¦“‰„“ ×®×@ÎÚœo~ò³DÒì—T7t	>k')@\$E/ÓG''\$1+î*’ã) ÙE²ùBÔa0\rqD.î±'³Äûä»?Sù=Ow*“ÉF’—>o\r>´,`AIB]?£÷*“ğr1âÀß‚¾ëSØàRø]sNlL©B/ï;î²×åŠû¯¾ì­)„!>“í<f›H’y<4ã,RpÃ4Or;J2›Jtó.IPrÎñŠ¶5¢êŠŸÖ*rQ ú ");}elseif($_GET["file"]=="dark.css"){header("Content-Type: text/css; charset=utf-8");echo
lzw_decompress("h:M‡±h´ÄgÆÈh0ÁLĞàd91¢S!¤Û	Fƒ!°æ\"-6N‘€ÄbdGgÓ°Â:;Nr£)öc7›\rç(HØb81˜†s9¼¤Ük\rçc)Êm8O•VA¡Âc1”c34Of*’ª- P¨‚1©”r41Ùî6˜Ìd2ŒÖ•®Ûo½ÜÌ#3—‰–BÇf#	ŒÖg9Î¦êØŒfc\rÇI™ĞÂb6E‡C&¬Ğ,buÄêm7aVã•ÂÁs²#m!ôèhµårùœŞv\\3\rL:SA”Âdk5İnÇ·×ìšıÊaF†¸3é˜Òe6fS¦ëy¾óør!ÇLú -ÎK,Ì3Lâ@º“J¶ƒË²¢*J äìµ£¤‚»	¸ğ—¹Ášb©cèà9­ˆê9¹¤æ@ÏÔè¿ÃHÜ8£ \\·Ãê6>«`ğÅ¸Ş;‡Aˆà<T™'¨p&q´qEˆê4Å\rl­…ÃhÂ<5#pÏÈR Ñ#I„İ%„êfBIØŞÜ²”¨>…Ê«29<«åCîj2¯î»¦¶7j¬“8jÒìc(nÔÄç?(a\0Å@”5*3:Î´æ6Œ£˜æ0Œã-àAÀlL›•PÆ4@ÊÉ°ê\$¡H¥4 n31¶æ1Ítò0®áÍ™9ŒƒéWO!¨r¼ÚÔØÜÛÕèHÈ†£Ã9ŒQ°Â96èF±¬«<ø7°\rœ-xC\n Üã®@Òø…ÜÔƒ:\$iÜØ¶m«ªË4íKid¬²{\n6\r–…xhË‹â#^'4Vø@aÍÇ<´#h0¦Sæ-…c¸Ö9‰+pŠ«Ša2Ôcy†h®BO\$Áç9öw‡iX›É”ùVY9*r÷Htm	@bÖÑ|@ü/€l’\$z¦­ +Ô%p2l‹˜É.õØúÕÛìÄ7ï;Ç&{ÀËm„€X¨C<l9ğí6x9ïmìò¤ƒ¯À­7RüÀ0\\ê4Î÷PÈ)AÈoÀx„ÄÚqÍO#¸¥Èf[;»ª6~PÛ\rŒa¸ÊTGT0„èìu¸ŞŸ¾³Ş\n3ğ\\ \\ÊƒJ©udªCGÀ§©PZ÷>“³Áûd8ÖÒ¨èéñ½ïåôC?V…·dLğÅL.(tiƒ’­>«,ôƒÖLÀ");}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress("':œÌ¢™Ğäi1ã³1Ôİ	4›ÍÀ£‰ÌQ6a&ó°Ç:OAIìäe:NFáD|İ!‘Ÿ†CyŒêm2ËÅ\"ã‰ÔÊr<”Ì±˜ÙÊ/C#‚‘Ùö:DbqSe‰JË¦CÜº\n\n¡œÇ±S\rZ“H\$RAÜS+XKvtdÜg:£í6Ÿ‰EvXÅ³j‘ÉmÒ©ej×2šM§©äúB«Ç&Ê®‹L§C°3„åQ0ÕLÆé-xè\nÓìD‘ÈÂyNaäPn:ç›¼äèsœÍƒ( cLÅÜ/õ£(Æ5{ŞôQy4œøg-–‚ı¢êi4ÚƒfĞÎ(ÕëbUıÏk·îo7Ü&ãºÃ¤ô*ACb’¾¢Ø`.‡­ŠÛ\rÎĞÜü»ÏÄú¼Í\n ©ChÒ<\r)`èØ¥`æ7¥CÊ’ŒÈâZùµãXÊ<QÅ1X÷¼‰@·0dp9EQüf¾°ÓFØ\r‰ä!ƒæ‹(hô£)‰Ã\np'#ÄŒ¤£HÌ(i*†r¸æ&<#¢æ7KÈÈ~Œ# È‡A:N6ã°Ê‹©lÕ,§\r”ôJPÎ3£!@Ò2>Cr¾¡¬h°N„á]¦(a0M3Í2”×6…ÔUæ„ãE2'!<·Â#3R<ğÛãXÒæÔCHÎ7ƒ#nä+±€a\$!èÜ2àPˆ0¤.°wd¡r:Yö¨éE²æ…!]„<¹šjâ¥ó@ß\\×pl§_\rÁZ¸€Ò“¬TÍ©ZÉsò3\"²~9À©³jã‰PØ)Q“Ybİ•DëYc¿`ˆzácµÑ¨ÌÛ'ë#t“BOh¢*2ÿ…<Å’Oêfg-Z£œˆÕ# è8aĞ^ú+r2b‰ø\\á~0©áş“¥ùàW©¸ÁŞnœÙp!#•`åëZö¸6¶12×Ã@é²kyÈÆ9\rìäB3çƒpŞ…î6°è<£!pïG¯9àn‘o›6s¿ğ#FØ3íÙàbA¨Ê6ñ9¦ıÀZ£#ÂŞ6ûÊ%?‡s¨È\"ÏÉ|Ø‚§)şbœJc\r»Œ½NŞsÉÛih8Ï‡¹æİŸè:Š;èúHåŞŒõu‹I5û@è1îªAèPaH^\$H×vãÖ@Ã›L~—¨ùb9'§ø¿±S?PĞ-¯˜ò˜0Cğ\nRòmÌ4‡ŞÓÈ“:ÀõÜÔ¸ï2òÌ4œµh(k\njIŠÈ6\"˜EYˆ#¹W’rª\r‘G8£@tĞáXÔ“âÌBS\nc0Ék‚C I\rÊ°<u`A!ó)ĞÔ2”ÖC¢\0=‡¾ æáäPˆ1‘Ó¢K!¹!†åŸpÄIsÑ,6âdÃéÉi1+°ÈâÔk‰€ê<•¸^	á\nÉ20´FÔ‰_\$ë)f\0 ¤C8E^¬Ä/3W!×)Œu™*äÔè&\$ê”2Y\n©]’„EkñDV¨\$ïJ²’‡xTse!RY» R™ƒ`=Lò¸ãàŞ«\nl_.!²V!Â\r\nHĞk²\$×`{1	|± °i<jRrPTG|‚w©4b´\r‰¡Ç4d¤,§E¡È6©äÏ<Ãh[N†q@Oi×>'Ñ©\rŠ¥ó—;¦]#“æ}Ğ0»ASIšJdÑA/QÁ´â¸µÂ@t\r¥UG‚Ä_G<éÍ<y-IÉzò„¤Ğ\" PÂàB\0ıíÀÈÁœq`‘ïvAƒˆaÌ¡Jå RäÊ®)Œ…JB.¦TÜñL¡îy¢÷ Cpp\0(7†cYY•a¨M€é1•em4Óc¢¸r£«S)oñÍà‚pæC!I†¼¾SÂœb0mìñ(d“EHœøš¸ß³„X‹ª£/¬•™P©èøyÆXé85ÈÒ\$+—Ö–»²gdè€öÎÎyİÜÏ³J×Øë ¢lE“¢urÌ,dCX}e¬ìÅ¥õ«mƒ]ˆĞ2 Ì½È(-z¦‚Zåú;Iöî¼\\Š) ,\n¤>ò)·¤æ\rVS\njx*w`â´·SFiÌÓd¯¼,»áĞZÂJFM}ĞŠ À†\\Z¾Pìİ`¹zØZûE]íd¤”ÉŸOëcmÔ]À ¬Á™•‚ƒ%ş\"w4Œ¥\n\$øÉzV¢SQDÛ:İ6«äG‹wMÔîS0B‰-sÆê)ã¾Zí¤c|Ë^RšïEè8kMïÑÌsŒd¹ka™)h%\"Pà0nn÷†/Áš#;Ög\rdÈ¸8†ŞF<3\$©,åP);<4`Î¢<2\n”Êõé@w-®áÍ—AÏ0¹ºª“¹LrîYhìXCàa˜>ºæt‹ºLõì2‚yto;2‡İQª±tîÊfrmè:§”Aíù‰¡÷ANºİ\\\"kº5oVëÉƒ=îÀt…7r1İpäAv\\+9ª„â€{°ç^(iœ‰f¬=·rŠÒºŠuÚÊûtØ]yÓŞ…ĞùCö¶ºÁ³ÒõİÜgi¥vfİù+¥Ã˜|Êì;œ€¸Âà]~ÓÊ|\re÷¥ì¿“šİ‚Ú'ƒíû²‰”¦ä¯²°	½\0+W‡coµw6wd Su¼j¨3@–Œò0!ã÷\n .w€m[8x<²ËcM¬\n9ı²ı'aùŞˆ1>È£’[¶ïµúdïŞux¯à<\"Yc¸ŞB!i¹¥ê•wÀ}’ô5U¹kººÜØ]­¶¸ÔÒÀ{óI×šR…‰–¥=f W~æ]É(bea®'ubïm‘>ƒ)\$°†P÷á-šƒ6şR*IGu#Æ•UKµAXŒtÑ(Ó`_Âà\" ¾£p¸ &UËËÙIíÉ]ıÁYG6P]Ar!b¡ *Ğ™JŠo•µÓ¯åÿ™óïÁòvı½*À Ø!éš~_ªÀÙ4B³_~RB˜iKùŒ’ş`ç‰&JÛ\0­ô®N\0Ğ\$àÌşåCÂK œSĞòâjZ¤Ğ Ìû0pvMJ bN`Lÿæ­eº/`RO.0Pä82`ê	åüÆ¸d Â˜GxÇbP-(@É¸Ó@æ4¨H%<&–ÀÌZà™Àèp„¬°Š%\0®p€ĞĞ„øêã	…¯	àÈ/\"ö¢J³¢\ns†–_ÀÌ\rŒàg`‹œ!käpX	èĞ:Ävíç6p\$ú'ğÇ¥RUeZÿ¨d\$ì\nLáBºâ†ó.ŞdŒn€î¤Òtm€>v…jä•í€)‘	Mº\r\0Â.àÊŠH’Ñ\"…5‚*!eºZJº‰è’ëãf(dc±¼(xÜÑjg\0\\õ€ÂõÀ¶ Z@ºàê|`^›r)<‹(’ˆ„ˆ†È)ÌëªóÊĞì@YkÂmÌíl3QyÑ@É‘ŒÑfÎìPn„ç¼¨ĞT ò¯N·mRÕq³íâVmvúNÖ‚|úĞ¨Z²„È†Ú(Ypø‰\"„4Ç¨æàò&€î%lÒP`Ä€£Xx bbdĞr0Fr5°<»Cæ²z¨¯6ähe!¤ˆ\rdzàØK;Ät³²\nÙÍ …HÆ‹Qš\$QŸEnn¢n\rÀš©#šT\$°²Ëˆ(ÈŸÑ©|c¤,¼-ú#èÚ\r Üá‰Jµ{dÑE\n\$²ÆBrœiTÔò‘+Å2PED•Be‹}&%Rf²¥\nüƒ^ôˆCàÈZàZ RV“ÅA,Ñ;‘«ç<ÂÄì\0O1éÔêc^\r%‚\r ìë`Òn\0y1èÔ.Âğ\r´Ä‚K1æM3H®\r\"û0\0NkXPr¸¯{3 ì}	\nSÈd†ˆÚ—Šx.ZñRTñ„’wS;53 .¢s4sO3FºÙ2S~YFpZs¡'Î@Ù‘OqR4\n­6q6@DhÙ6ÍÕ7vE¢l\"Å^;-å(Â&Ïb*²*‹ò.! ä\r’!#çx'G\"€Í†w‰Á\"úÕ È2!\"R(vÀXŒæ|\"DÌvÀ¦)@á,¸zmòAÍwT@ÀÔ  Ğ\n‚ÖÓğºĞ«hĞ´IDÔP\$m>æ\r&`‡>´4ÈÒA#*ë#’<”w\$T{\$´4@›ˆdÓ´Rem6¯-#Dd¾%E¥DT\\ \$)@Ü´WC¬(t®\"MàÜ#@úTFŸ\r,g¦\rP8Ã~‘´Ö£Jü°c öŒàÄ¹Æ‚ê Ê\"™LªZÔä\r+P4ı=¥¤™Sâ™TõA)0\"¦CDhÇM\n%FÔpÖÓü|fLNlFtDmH¯ªş°5å=HÍ\n›Ä¼4ü³õ\$à¾Kñ6\rbZà¨\r\"pEQ%¤wJ´ÿV0Ô’M%ål\"hPFïA¬áAãŒ®ò/G’6 h6]5¥\$€f‹S÷CLiRT?R¨şC–ñõ£HU§Z¤æYbFş/æ.êZÜ\"\"^Îy´6R”G ²‹ÌnâúÜŒ\$ªÑå\\&OÖ(v^ ÏKUºÑ®ÎÒam³(\r€Šïº¯¾ü\$_ªæ%ñ+KTtØö.Ù–36\nëcµ”:´@6 újPÃAQõF’/S®k\"<4A„gAĞaU…\$'ëˆÓáfàûQO\"×k~²S;ÅÀ½ó.ïË: ˆk‘¼9­ü²Šóe]`nú¼Ò-7¨˜;îß+VËâ8WÀ©2H¢U‹®YlBívŞöâ¯ÖÔ†´°¶ö	§ıâîp®ÖÉl¾m\0ñ4Bò)¥XÁ\0ÊÂQßqFSq—4–ÿnFx+pÔò¦EÆSovúGW7o×w×KRW×\r4`|cqîe7,×19·u Ïu÷cqä’\"LC tÀhâ)§\r€àJÀ\\øW@à	ç|D#S\rŸ%Œ5læ!%+“+å^‡k^Ê™`/7¸‰(z*ñ˜‹€ğ“´E€İ{¦S(Wà×-“XÄ—0V£‘0Ë¥—îÈ=îÍa	~ëfBëË•2Q­êÂru mCÂìë„£tr(\0Q!K;xNıWÀúÿ§øÈ?b< @Å`ÖX,º‡`0eºÆ‚N'²Â‘…šœ¤&~‘øt”Óu‡\"| ¬i… ñBå  7¾Rø” ¸›lSu†°8Aû‰dF%(Ôú äúïó?3@A-oQŠÅº@|~©K†ÀÊ^@xóbšœ~œD¦@Ø³‰˜¸›…TNÅZ€C	WˆÒÂix<\0P|Äæ\n\0\n`¨¥ ¹\"&?st|Ã¯ˆwî%…ˆàèmdêuÀN£^8À[t©9ƒªB\$àğ§©ğ¦'\">UŒ~ÿ98‡ é“òÃ”FÄf °¹€u€È°/)9‡À™ˆ\0á˜ëAùz\"FWAx¤\$'©jG´(\"Ù ±s%T’HŠîßÀe,	Mœ7ï‹b¼ Ç…Øa„ Ë“”Æƒ·&wYÔÏ†3˜°Øø /’\rÏ–ù¯ŸÙ{›\"ùİœp{%4b„óŒ`íŒ¤Ôõ~n€åE3	•Î ›°9å3XÖd›äÕZÅ9ï'š™@‡¨‡‘l»f¯õØQbP¤*G…oŠåÅ`8•¨‘¯ùA›æB|Àz	@¦	àb¡Zn_Íhº'Ñ¢F\$f¬§`öóº†HdDdŒH%4\rsÎAjLRÈ'ŞùfÚ9g IÏØ,R\\·ø”Ê>\n†šH[´\"°Àî©ª\rÓ…ŒÂ•LÌ,%ëFLl8gzLç<0ko\$Çk­á`ÒÃKPÔvå@dÏ'V:V”ØMü%±èÕ@ø6Ç<\ràùT«‹®LE´‰NÔ€S#ö.¶[„x4¾açÌ­´LL‚® ª\n@’£\0Û«tÙ²å\n^F­—º¥ºŠ5`Í R“7ÈlL uµ(™d’º¡¹ Ô\räBf/uCf×4ÿcÒ Bïì€_´nLÔ\0© \$»îaYÆ¦¶¸€~ÀUkïv¥eôË¥¦Ë²\0™Z’aZ—“šœXØ£¦|CŠq“¨/<}Ø³¡–ÅÃº²”º¶ Zº*­w\nOã‡Åz`¼5“®18¶cø™€û®¯­®æÚIÀQ2YsÇK‹˜€æ\n£\\›\"›­ Ã°‡c†ò*õB¶€îÌ.éR1<3+õÅµ*ØSé[õ4Ómì­›:Rh‹‘ITdevÎIµHäèÒ-Zw\\Æ%nè56Œ\nÌWÓi\$ÕÅow¬˜+© ºùËrÉ¶&Jq+û}ÒDàø¼Ój«dÅÎ?æU%BBeÇ/M‚¶Nm=Ï„óU·Âb\$HRfªwb|•²x dû2æNiSàóØgÉ@îq@œß>ÎSv „§—•ƒ|ïkrŒx½Œ\0{ÔRƒ=FÿÏÎÎâ®Ï#r½‚8	ğˆZàvÈ8*Ê³£{2Sİ+;S¦œ‚Ó¨Æ+yL\$\"_Ûë©Bç8¬İ\"E¸%ºàºŒ\nø‘ĞÂp¾p''«p‚ówUÒª\"8Ğ±I\\ @… Ê¾ ‡Lnğæ Rß#MäDµşqLNÆî\n\\’Ì\$`~@`\0uç‰~^@àÕlˆ-{5ñ,@bruÁo[Á²¾¨Õ}é/ñy.×é {é6q‚°R™pàĞ\$¸+13ÛúÚú+ƒ¨O!D)…® à\nu”<¯,«áñß=‚JdÆ+}µd#©0ÉcÓ3U3»EY¹û¢\rû¦tj5Ò¥7»e©˜w×„Ç¡úµ¢^‚qß‚¿9Æ<\$}kíÍòŒRI-ø°¸+'_Ne?SÛRíhd*X˜4é®üc}¬è\"@Šˆvi>;5>Dn‰ ˜\räë)bNéuP@YäG<ñ¨6iõ#PB2A½-í0d0+ğ…ügKûø¿í?¨néãüdœdøOÀ‚Œ¯åácüi<‹ú‘‹0\0œ\\ù—ëÑgî¦ùæê¡––…NTi'  ·ô;iômjáÜˆÅ÷»¸uÎJ+ªV~À²ù 'ol`ù³¿ó\",ü†Ì£×ÓFÀå–	ıâ{C©¸¤şT aÏNEÛƒQÆp´ p€+?ø\nÆ>„'l½¤* tÉKÎ¬p°(YC\n-qÌ”0å\"*É•Á,#üâ÷7º\"%¨+qÄ¸êB±°=åi.@x7:Å%GcYIĞˆ0*™îÃkÀÛˆ„\\‡·¯ğQ_{¤ ÅÇ#Áı\rç{H³[p¨ >7ÓchënÎÂÔ.œµ£¦S|&JòMÇ¾8´Àm€OhşÄí	ÕÑqJ&a€İ¢¨'‰.bçOpØì\$ö–­Ü€D@°C‚HB–	ƒÈ&âİ¡|\$Ô¬-6°²+Ì+ÂŒ †•Âàœpº…à¬¡AC\r’É“…ì/Î0´ñÂî¢M†ÃiZŠnEœÍ¢j*>™û!Ò¢u%¤©gØ0£à€@ä¿5}r…É+3œ%Â”-m‹¢G‚<”ã¥T;0°¯¨’†DV£dÀgÛ9'lM¶ıHˆ£ F@äP˜‹unütFB%´MÄt'äGÔ2ÅÀ@2¢<«e™”;¢`ˆõ=LXÄ2àÏäX»}oc.LŠ+âxÓ†&D¨a’€¡€É«ÁF2\ngLEƒ°.\\xSLıx­;lwÑD=0_QV,a 5Š+Léó+Û|\$Åi­jZ\nê—DÖEÎ,B¾t\\Ï'H0ÁŒ±R~(\\\"¢Ö:”Ğn*ûšÕ(¡×o®1wãÕQí×röÒÃEteÓF•…\$èSÑ’]Ğ\rLäyF„‰‘\\BŒiÀh”hdáÿ&áš‡h;fo›¾B-y`ÅÔğ0ˆ„JlPéxao·\$ŠXq¼,(Ö¡†C*	Ç\"‚ƒ”¤\"A‘ÀóˆŸE\nÿÓ¾G ¯-zl ’ãÄ”°€•°ÏÑÈãÎ”Å^!Áùâ^sUÂJüƒD ôuÃ\n1¢`Ÿ²æ„ÁôWËúD*u% ò½^ëè<‘; Ø8iÿ„|`_İ×É¢B\rë`\nF<08FäLrdˆüÅ1OHocÁÚoÄXŠewCA{L‰\$LùØ\n¾š@21WÀİĞæ9ÑÏ‰D'ĞA”%#É»Ñ(ñ\n]Ş“ôî5BHÄcmQW¯SøHÍ‚¢¬r\\…ß İâÔ@âk[)»„¬MÂ©?-q#9è? ¥y1ø;yápbõH/m1ÿ#T‚WÍ#Ğ»»|ĞQÄ1âæiTŠ¹g©„’ˆ©äşÊ9Ù\"M”‹³UÁ\"ù°TÙğJ\0ù\\- ‰,H-aÒ0F›¶ø÷È‹,£Bg]&g	0.Gak”ƒb¦qĞIãzXBÖlÏ¢€");}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
lzw_decompress('');}elseif($_GET["file"]=="logo.png"){header("Content-Type: image/png");echo"‰PNG\r\n\n\0\0\0\rIHDR\0\0\09\0\0\09\0\0\0~6¶\0\0\0000PLTE\0\0\0ƒ—­+NvYt“s‰£®¾´¾ÌÈÒÚü‘üsuüIJ÷ÓÔü/.üü¯±úüúC¥×\0\0\0tRNS\0@æØf\0\0\0	pHYs\0\0\0\0\0šœ\0\0´IDAT8Õ”ÍNÂ@ÇûEáìlÏ¶õ¤p6ˆG.\$=£¥Ç>á	w5r}‚z7²>€‘På#\$Œ³K¡j«7üİ¶¿ÌÎÌ?4m•„ˆÑ÷t&î~À3!0“0Šš^„½Af0Ş\"å½í,Êğ* ç4¼Œâo¥Eè³è×X(*YÓó¼¸	6	ïPcOW¢ÉÎÜŠm’¬rƒ0Ã~/ áL¨\rXj#ÖmÊÁújÀC€]G¦mæ\0¶}ŞË¬ß‘u¼A9ÀX£\nÔØ8¼V±YÄ+ÇD#¨iqŞnKQ8Jà1Q6²æY0§`•ŸP³bQ\\h”~>ó:pSÉ€£¦¼¢ØóGEõQ=îIÏ{’*Ÿ3ë2£7÷\neÊLèBŠ~Ğ/R(\$°)Êç‹ —ÁHQn€i•6J¶	<×-.–wÇÉªjêVm«êüm¿?SŞH ›vÃÌûñÆ©§İ\0àÖ^Õq«¶)ª—Û]÷‹U¹92Ñ,;ÿÇî'pøµ£!XËƒäÚÜÿLñD.»tÃ¦—ı/wÃÓäìR÷	w­dÓÖr2ïÆ¤ª4[=½E5÷S+ñ—c\0\0\0\0IEND®B`‚";}exit;}if($_GET["script"]=="version"){$n=get_temp_dir()."/adminer.version";@unlink($n);$p=file_open_lock($n);if($p)file_write_unlock($p,serialize(array("signature"=>$_POST["signature"],"version"=>$_POST["version"])));exit;}if(!$_SERVER["REQUEST_URI"])$_SERVER["REQUEST_URI"]=$_SERVER["ORIG_PATH_INFO"];if(!strpos($_SERVER["REQUEST_URI"],'?')&&$_SERVER["QUERY_STRING"]!="")$_SERVER["REQUEST_URI"].="?$_SERVER[QUERY_STRING]";if($_SERVER["HTTP_X_FORWARDED_PREFIX"])$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];define('Adminer\HTTPS',($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure"));@ini_set("session.use_trans_sid",'0');if(!defined("SID")){session_cache_limiter("");session_name("adminer_sid");session_set_cookie_params(0,preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),"",HTTPS,true);session_start();}remove_slashes(array(&$_GET,&$_POST,&$_COOKIE),$oc);if(function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())set_magic_quotes_runtime(false);@set_time_limit(0);@ini_set("precision",'15');function
lang($t,$A=null){if(is_string($t)){$gf=array_search($t,get_translations("en"));if($gf!==false)$t=$gf;}$ma=func_get_args();$ma[0]=Lang::$translations[$t]?:$t;return
call_user_func_array('Adminer\lang_format',$ma);}function
lang_format($Hg,$A=null){if(is_array($Hg)){$gf=($A==1?0:(LANG=='cs'||LANG=='sk'?($A&&$A<5?1:2):(LANG=='fr'?(!$A?0:1):(LANG=='pl'?($A%10>1&&$A%10<5&&$A/10%10!=1?1:2):(LANG=='sl'?($A%100==1?0:($A%100==2?1:($A%100==3||$A%100==4?2:3))):(LANG=='lt'?($A%10==1&&$A%100!=11?0:($A%10>1&&$A/10%10!=1?1:2)):(LANG=='lv'?($A%10==1&&$A%100!=11?0:($A?1:2)):(in_array(LANG,array('bs','ru','sr','uk'))?($A%10==1&&$A%100!=11?0:($A%10>1&&$A%10<5&&$A/10%10!=1?1:2)):1))))))));$Hg=$Hg[$gf];}$Hg=str_replace("'",'â€™',$Hg);$ma=func_get_args();array_shift($ma);$_c=str_replace("%d","%s",$Hg);if($_c!=$Hg)$ma[0]=format_number($A);return
vsprintf($_c,$ma);}function
langs(){return
array('en'=>'English','ar'=>'Ø§Ù„Ø¹Ø±Ø¨ÙŠØ©','bg'=>'Ğ‘ÑŠĞ»Ğ³Ğ°Ñ€ÑĞºĞ¸','bn'=>'à¦¬à¦¾à¦‚à¦²à¦¾','bs'=>'Bosanski','ca'=>'CatalÃ ','cs'=>'ÄŒeÅ¡tina','da'=>'Dansk','de'=>'Deutsch','el'=>'Î•Î»Î»Î·Î½Î¹ÎºÎ¬','es'=>'EspaÃ±ol','et'=>'Eesti','fa'=>'ÙØ§Ø±Ø³ÛŒ','fi'=>'Suomi','fr'=>'FranÃ§ais','gl'=>'Galego','he'=>'×¢×‘×¨×™×ª','hi'=>'à¤¹à¤¿à¤¨à¥à¤¦à¥€','hu'=>'Magyar','id'=>'Bahasa Indonesia','it'=>'Italiano','ja'=>'æ—¥æœ¬èª','ka'=>'áƒ¥áƒáƒ áƒ—áƒ£áƒšáƒ˜','ko'=>'í•œêµ­ì–´','lt'=>'LietuviÅ³','lv'=>'LatvieÅ¡u','ms'=>'Bahasa Melayu','nl'=>'Nederlands','no'=>'Norsk','pl'=>'Polski','pt'=>'PortuguÃªs','pt-br'=>'PortuguÃªs (Brazil)','ro'=>'Limba RomÃ¢nÄƒ','ru'=>'Ğ ÑƒÑÑĞºĞ¸Ğ¹','sk'=>'SlovenÄina','sl'=>'Slovenski','sr'=>'Ğ¡Ñ€Ğ¿ÑĞºĞ¸','sv'=>'Svenska','ta'=>'à®¤â€Œà®®à®¿à®´à¯','th'=>'à¸ à¸²à¸©à¸²à¹„à¸—à¸¢','tr'=>'TÃ¼rkÃ§e','uk'=>'Ğ£ĞºÑ€Ğ°Ñ—Ğ½ÑÑŒĞºĞ°','uz'=>'OÊ»zbekcha','vi'=>'Tiáº¿ng Viá»‡t','zh'=>'ç®€ä½“ä¸­æ–‡','zh-tw'=>'ç¹é«”ä¸­æ–‡',);}function
switch_lang(){echo"<form action='' method='post'>\n<div id='lang'>","<label>".lang(21).": ".html_select("lang",langs(),LANG,"this.form.submit();")."</label>"," <input type='submit' value='".lang(22)."' class='hidden'>\n",input_token(),"</div>\n</form>\n";}if(isset($_POST["lang"])&&verify_token()){cookie("adminer_lang",$_POST["lang"]);$_SESSION["lang"]=$_POST["lang"];redirect(remove_from_uri());}$aa="en";if(idx(langs(),$_COOKIE["adminer_lang"])){cookie("adminer_lang",$_COOKIE["adminer_lang"]);$aa=$_COOKIE["adminer_lang"];}elseif(idx(langs(),$_SESSION["lang"]))$aa=$_SESSION["lang"];else{$da=array();preg_match_all('~([-a-z]+)(;q=([0-9.]+))?~',str_replace("_","-",strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"])),$Vd,PREG_SET_ORDER);foreach($Vd
as$z)$da[$z[1]]=(isset($z[3])?$z[3]:1);arsort($da);foreach($da
as$w=>$qf){if(idx(langs(),$w)){$aa=$w;break;}$w=preg_replace('~-.*~','',$w);if(!isset($da[$w])&&idx(langs(),$w)){$aa=$w;break;}}}define('Adminer\LANG',$aa);class
Lang{static$translations;}Lang::$translations=(array)$_SESSION["translations"];if($_SESSION["translations_version"]!=LANG.
4038881932){Lang::$translations=array();$_SESSION["translations_version"]=LANG.
4038881932;}if(!Lang::$translations){Lang::$translations=get_translations(LANG);$_SESSION["translations"]=Lang::$translations;}function
get_translations($Gd){switch($Gd){case"en":$f="%ÌÂ˜(ªn0˜†QĞŞ :œ\r†ó	@a0±p(ša<M§Sl\\Ù;™bÑ¨\\Òz†Nb)Ì…#Fá†Cy–fn7Y	Ìé†Ìh5\rÇ˜1ÌÊr†NàQå<›Î°C­|~\n\$›Œuó\rZhsœN¢(¡’fa¯ˆ“(L,É7œ&sL Ø\n'CÎ—Ùôt‹{:Z\rÕc–G 9Î÷\0QfÄ 4NĞÊ\0€á‚;NŒóèl>\"d0!‡CDÊŒ”ôFPVëG7EŒfóqÓ\nu†J9ô0ÃÁar”#u™¢Â™ÁDC,/d\n&sÌçS®«¼ƒšèsuå™9GH›M¶w=ŸĞl‡†„8-£˜îÀ¯àP …¾È‚Üâ!ƒzÚ9#ÃdÉ.\"fš®)jˆ™Œàœ’Jº\nù¶N,ã\r­à¦:0CpÎ‚ˆL*ğ<±(éCX#Œ£|SFŒ{–À¼ã’ÀĞp2<{7@Ê¸ÚçŒ®(@‰Éñ¨îè¶Ch¢ÃòÙ3\0A16H(ò®(\0PH…¡ g8†­èÊ¼c@2£€é9ãÈî4*ñSüğ\$NZº1¸Î\$ì…Ñ€SÖ	@t&‰¡Ğ¦)BØóO\"èZ6¡hÉ&\nnèËF/O¤¾™¤j0@)¨|¯‹+Úç¢ïxÂ<‡xÂ#µ¥l!¸ˆÓÆ¯Ø6Şè½°Ã¶¾!Hbj0*jÙöÍ®ëJ£œ*É£¢d8‚‘c:Ï¯hÌ3\rŒ(ÊÜªíå&Œë\"*\rè|¦7(ƒ&:Œc|9ŒÃ¨Ù+ÉÎX†XÊ0°¶‚h‹\r®;d7åı@àC	ƒxF†Iã \"ã>(öâ³627³MË'_K&ƒá#b:*4#pÖ™ŒÖÈëqEÁ\0‚2fj¾áÂƒ\"¯Y£B¾3¡Ğ:ƒ€æáxï±…È®šø°#8_*…éâFÁxDmÍ‹³fØ“¶Kq¨cÀà4«ã&‰Nët±Šâl*:¨Úœ’É{îÿÀ…Ú£A«ë:Ş»¯ì;ï²é—–Ğ9m[eÁ\n8›•l\$Ï¨\\Râï|~4\rñ>>5¦/ü¥*8·¦öâ?oë*V*Cm1.€çQ›àîá~:¶9h£”R‹#3d¬dYŞKå¯Ú!´\"0Å!Ö•J®0Â6CÈOqQÌK«lAÿ6Dş?ÉP@@Po¨Mú0@\n	¨)W§8;1@Øn\0RÈ\rÇUæ´’iAªÏ	òPŸy(%Dt´ÀÕâpœí\r/¼Î<²òmˆÀs@‹! ©¦IH(0Ò^!ƒkiœÙ¾`ÆZ:ó2P˜4‘`†ÂF5®à79URt!³Õ\$®&*‘â@H‰  fµ•„I¡+\r't»7ÖûûÿA¸Â¾´´‚I,%Œ½‘b€PŠ´X6¤Ì¯‡¬ƒ1™\$­-³¾Tªúˆ™n6†Ù\0¶~¿S9ƒá<)…EZbÜ¨R%L\"“³è¶\nÄu-’Ió-v ²‹+vxì¯¼ÓFGLñcˆç´ˆ‘2*EäÈgL(„Â2\\IÁRò,H â\\#2(‚/R¯	é -±Gµ#½ËDÙ4\$ÍÈó^lg\0IK«^qÍÆ`dÊ¹²@©˜å§Éµ9,{!™UÏ&9=é34&şr«µß?g\0CE°†5âÃZØW`‚ƒZñ¨iÌå¡’ñb#\n¡P#Ğp¶{EÄu3”·;h)ÑO¯ô¿¦R-K¨»ÔÊNc(¹VR\nµªG¨ÍMH9°c‚v@T¢íÀ‚œ£\"KuVJæˆÁpÛRX“&tV%ÂëFÉ°1i4ŠÕ\0 yMä(À(+»BZWÙéKôÜÍSc0fˆ*©¨dª‘3ª‚+~Pî²'dğ\$ùbµ…P¨c*€¯`‚CvQ[5Á<%Ïò-gL¬´ÑkH€";break;case"ar":$f="%ÌÂ˜)²Šl*›–ÂÁ°±CÛ(X²…l¡\"qd+aN.6­…d^\"§ŒÅå(<e°£l ›VÊ&,‡l¢S™\nA”Æ#RÆÂêNd”¥|€X\nFC1 Ôl7`ÈjRæ[¬á-…sa_ƒN‘‚±ÌvfÂ|I7ÎFS	ÌË;9ÏÖ18­Á+[è´x„]°´Å¡'ò„\$¾g)EA²ªxŠª”¬³Dt\nú\"3?…C,è¨Ì…JÙ·dí…j=Ïèv=ššI ,›Î¢A„í7Ä‘¤ìi6LæS˜€éÊ:œ†¥üèh4õN†F~­Â.5Ò/LZuJÙÍ-xkª­¥Åè¿bÄ”*ûxÌB›Œ4Ã:°¤I(—FÁSRÇ2€Pª7\rnHî7(ä9\rã’@\";\"ú¿Œƒ{¨9#¢ì,d8/£˜ïŒŒš‚‡©iÓ,‹¢PB¿©ÌšÀR:ÆÒ6r‚ŞGÌ:†¤‘\nÌŸ”h\\Œ³AÉrÙ°hA\\ş0ÉÊb„¤%š\\ÙÍÄ\"BU·mê	±æÁPl®p™²œ-Í\"Å<AèêFX¡ğ¤İ\$M0b²¬ä*AÇåJ¾Ç0rÉb¡KJÖ>l¢èWMS’ÇO>ï›IR4ÕSS-[vZ©	\$–„àPJ2\"%B€„@PòêNH¡xHÙAŠC_\$eZŠLŠ-¦ƒ¥¤R’¹¥59 3ÈAP¤uËèRÖÔCfÚ±+-ÂÄÓo¡DØB@	¢ht)Š`PÔ5ãhÚ‹c0‹©\nDĞö„[¬M‚hê9ÆAàÂËØè:Q\0Ã‘Dcòã|cXà†7Ã0Ò3ä.Q•0ÀDïÈèç1,B7gëş)¡bƒ(ğîÃ˜Ó˜0(1ø…\$9ƒ`à¸c\nö\rã0Ì6A,«\"Ê5:¥ÔËL¤€P¨7¹CnF<„®š:Œc¨9ŒÃ¨Ø\rƒxÏac¬9oÎ0çyİwŠÁ«ºaKP“Tûb3·\"RÉY^·mfV¤£‹:£6…‹Á8@ ŒƒnÊ9qC 4Œ‘A8‹ØÌ„C@è:˜t…ã¿œ=¯nD£8^2Á|[¢š²ŞÛ¹Øæù[\\İ³Ä;Q\$¬­lŠI‹\"…²RA­’ñ^ÎÂ¼õBK•Z¥\$4¾ĞärÑÊ;Jñ¥\0Ò^Ñë¿/2¼7ŠñŞKËy¯=è»`Üˆ rzÏaë´·°Ó™€/Lp\$†ĞàtCkØ•œÃ¾Õá×b¡„5—ğÒÑvƒá¸:ñrfá¸\"‘¶›±pPcì%ÅˆÙŠ§Røaou˜\0îtb/áÁ:àåUàaĞ·Öşà\\…pè‚/ó­ a† €½W{Ha\rF(\$óşÌY1¤aÑÇÎGHRRJB¨å†€àA<*\\šHiˆê–‘% •HÔ²iI eáºÅ·buQÌ9Ç@éGã–©áF½H'\nãñ?aå\0·ET\0Ui(Ò(ˆ«EDHåKr‹\r~]A0æó:D~ààå @sGHò/\0Æ gxÀ‚1GrşÃlÄ„Á‘ÑdS\nAeÉš}DœH•!.…ùôN%\$,¦“ùmäÔ’e\"S¦)BÉõ’ãThO¡0&DĞ›“*zš­ÄZE²l}K~Lğ»0òp\0dw’íàİOÕ/aÄ:Dd‘ mvoJÀy¸ˆ…/ÑŞ]#ƒ§EHìG#R‰\0 Â˜T\"ÅÎa¨‰DhÈõ+&.q_æ¦Fˆ@µ\"\$Œ’ÅV“Q˜Ä¼œ‘f¦£ğ²(éFˆÖ³Y(YÉ[’Á¤\nöe*ğH\nœO@v\\+ÑL130@ÂˆLÉ ø&‚ P³tıBälÎª=I ˆˆã¢e8ç	°#gÒ#–åh,\\ÚŒ5*Ş„ª+zLÈy '”1F.ƒHº‰\"¸öş#Iq“­È\"\n5ıêN	Ò»•AĞ9ÀWTCHc\rm‘\0í ª,°b°à4†fñÉ\0F–ŒU‹£)ÚÎÂ¨TÀ´47uNMÁ\$ïõwİöäßè·±ÔU`%âvÍšî±¸`—5J‚mô¾ÖŞb‹\\RoMqUoŸe@¢©*æVè+ánõ¸‚c+pÍ[ŸOä5?©v§aĞ‘ 	¾ÍÔJ‹H\nÉoEØ’hE&‚a2©uRRÔü[—y ¡ës!%t¯±bÂJl)†S£ãñ[¢¹o«CV¢R¥©Eg¤¡Vı,[ìHØ}[u[–³HØÍx·©u(!\0";break;case"bg":$f="%ÌÂ˜) h-Z(6Š ¿„´Q\rëA| ‡´P\rÃAtĞX4Pí”‚)	ŒEVŠL¹h.ÅĞdä™u\r4’eÜ/“-è¨šÖO!AH#8´Æ:œÊ¥4©l¾cZˆ§2Í ¤«.Ú(¦Š\n§Y†ØÚ(˜ŠË\$…É\$1`(`1ÆƒQ°Üp9ƒ\$§+Jl–³‹YhmŒrßF® ÊÊÎ@Š®#eº’µ‚‰&ãÈÊa9™kG:ò~ÈdrU„I‹Ñå’í¬Âz¾¹ağëy2ÆµŠÑ¢«êòû^Ğ¦GeS2u¢¨Jíû\\nE¢†W”ü&ÖoI\\qöØÕ=räBz½~Ì²7FÂp0Õî·bv¤%Ê6Ú°ÈÃˆ•©¬k–¸;\r£l©»JK¸§=/\0X+ÄºLˆ=\$\n\r\r6°âŒ3L[Ê;ìqÃlq*oÔYÅÏƒÖh‚„A9ğs’ƒ±Òr] ÑËˆÆ¹Ä\0*ÃXÜ7ãp@2CŞ9&b Â:#PÕƒxÊ9„xè‚£€á+ÌãƒP9ò¸È¯&®ÄG…‘\$„ˆNÒ\\±éKè¸;œ³=J&ô¾é;ÒG»mZ¼°7ªtÃ\$IBÒ²-ˆS<¸o‚¼SÆ©é`ƒ‰\"%‰C éËˆı>F…R’¸JFÌ¦ŒYl Äšà'm»^ë Ïğ›-òJO±1åÆ½,û¨hZ	\n7>Ôæ\$hªii±\r­ŠÅA©4X¿JÂœ£X¨¢Ê£\"0Æ·«(zŞé{¶ƒ<¨4~!hHàÁªQ-DpåV¥ıŸ`I1d´»	¹h0…«&ÄáÏCÕuÙXãZÕ*5O¤¨î=l)­Œıu>X{¡`!N	©92…-æêfŒ.…ªy¤°—`àfN\$}\0ªY3ã^+a\0Ú:s8x0«aóL:£”¤0ìò¨Â<‡xÂ&zşÂ!ãpÌ4Œû(Ë¶ííX@4ÊJÜ²4rèØÕk2¼¦7pÜDÉÅk#(ğ:£pæ4î­f?-Ë#ÀÉ\rƒ äöeÌíÄ¥Õ1 ˜ªñC†®\$•GWÅg±y’{ˆ7)Ü¦7.K.‡\"\n%Œ8Ñ)K…Âà“Ê›å_Ìú0ˆ<Ìíß+W² é2ğ\\ÛÊx§¹hÇ|¦ø?†ÄxÌÏÄZµd…KöŞ‡¥ä±Ğ—VéŞÁ{Níî/Ç¾¼ˆaè+É\n?sl³JÁ,!ˆ‘3–LÂ hm­2g×\0g2×\0•Ècs!¤2%@@×Á\0h4Á˜‚ Ğ p`è‚ğïÁpa„°,%pÎÜ¨/L.0:9€ÜÁ>‰in·ÆàÇÔò*Uf£²GÕ0;}E5;½\"K\"ò†-è-\\å„ê‰)*Uo` -Â0¦	Â,èØÈ²bT¡xa†0ÎÃxsáì?ñ!†ä¨¢4H‰îIÊ9h´HäDû˜ø¬ßŠ·\\OI‘ƒÊKz@LYş øZMÁô\"±ıg®&8Fr¿6*¬©0òpGQ*Ò>†4ÚJgšZµ“¥£Á€Ğjƒc‰­ÔpÒ` F¨8%H<ƒhe^Á„39@äšcLaÌ3Y²xgpT4‡@Ğ'±ªp³„YÄåbha\rhƒŸCş}Š	1Ê4Ì«Rl\nlj,Æ!èKwd4ùñ~¥d(€¡B\rªŠ¡o¡\0PVÁIg‚%\n)˜.› n‘ÓJÏ€Ş€r\r!Úk†PÏ8©ÂlL2†ôÏ@§€wœGğ§ èúa•1KNV™pGÌÓV\$æfg%CUQ\$sMÓ©¿¥)óYC€uM©½8‡%ìÃ@isŞxo6Uga…®Z˜Qªqƒ}±E’Øê|YŒDG)\0ÓÈË'¯	ë?h»*Ğ¢-¹\$ÕBÎ;ÈlœæE˜Cˆéÿ)Ædë¯{.^¼d\"(J•±%ËhQÊ±éÔ˜tò\\L5-*Áa {Ş\nšSñq=œrŞGLáÛHUeŸBJl^¹‹ğFÂ‡˜€ÁCP7Hâ\"S<ÈÌrw5˜÷NE¶‹ïŞF7~E¦3W<\$`„.kèéÎ^5ª¢ygUSÕÑT7ï\\7¬ÖKò@–õt’Ø…æ2Œ‹÷=QZc€¯ìÕù»\$nˆ,ÍÓµL(„ÃvXUmÚ*Q÷í)ñBz'r½Ò;3h”C(^‹¡„€ö×ˆ½Ux\nÜ§¤v±Ä^H0\$ä¸*€p	>8\$S)d‡ÑsÚÉŒ:îÙKÖ6Gã”\n^xÈü«DòaE»ìØ¦f—Ç³n9Y/1®uHqFÎª«57DYk=D}©˜qdéÃ«'ÒÀíÊG›)íl«X”’©2ÂU&Ø˜š^c™Ğ@B F âÅKl4­\r‰3_ˆ™~¼k™ÙÏdyİ{\"gšq”†~ÇUKáª5_ß{Å¡O¦ŠúBJ%øîŞÎ\rrÛ=§`•1Xºşeº-‚ùr[‹PTrŸ2Zøó\"•‘Sİ²jOT”}ØŠù×÷”Ñy,°&¤¿Zu«êµ´MÖÍ(î\n£Uÿ\reî½i3D*Î%y€ŒtŠ;‹%`îÑq,HIyë5-Ş½äìâi’/H0š)ÇÖrõjI¼I+ÍÚkv ˜[\rfÜ#³J££î–Õâôm…œZZ…–Iädì¿lïî\0‚«-c€";break;case"bn":$f="%ÌÂ˜)À¦UÁ×Ğt<d ƒ¡ ê¨sN‹ƒ¨b\nd¬a\n® êè²6­ƒ«#k˜:jKMÅñµD)À¥RA”Ò%4}O&S+&Êe<JÆĞ°yª#‹FÊj4I„©¡jhjšVë©Á\0”æB›Î`õ›ULŸªÏcqØ½2•`—©”—ÜşS4™C- ¡dOTSÑTôÕLZ(§„©èJyBH§WÎ²t|¦,­G©8úšrƒÑg°uê\$¦Š)¦ºk¦ƒ­¯2íÅè~\n\$›Œg#)„æe²œÅÓ«f\n½×ìVUÎßN·¼İ(]>uL¼Úêë]	†q:ÇöôjtZut©*#w=v…½¯ì¨pß=áLË¨\rŠ¶ª?JÒtH;:º”Äâ˜÷º’BÒ6ÊcöÁ äzù°*\nâüº(Ì:O¼-*¶X#psô¿Å{ÈØBÍPB/’¥Åj{ş«B±Zºş-IÚ¼NÒìÅJ‹GED!´Q¤Y\$IMV§.ĞË<SPœw@H<Ù—ÈÛx­ºmë¼^&HÛ¼­ÉÏôˆÒÈÅ4‘Äš6Ø´¯º| /¤½\"Aj”U<#²¼›'Ë’Œë*Io>–¯)‹ê2ñÕ,™­pë§,6IÒQIó4¼»Ï»A§QÄU8\$äXŸGKƒpş”Å\n‡[+lšº\\OjxIÁH<ÙOî«ü’JêK,Æ’½9¡uÃáhCVxÛ3ªxú+­›u	,ÈÛ7bÃÅ5VIuk\\9ªâ´³\\;MÃSÕB/vBâK[’Ü»eİäŸ>SzATñ•Ã\n4ÉÒš®ÖÖ\$[cB‘a*AÑdŞ¨7¸	F@š‡cîqƒÕíëvF*Ù\\ôXH=ˆS×™’€P§SI.çN2Yg´9â-A-ÚåPÆ+ì›s..Æ•t¤Qf¤£&©‰ äø®ëëjß„ò!r%™†@â×øHÁ iº†)ÕûGDæô¡Í9=Šú¶°dñ¬ÙÊ/F+yŒVEádIS=xs»@Ñ‹Odİ*Ûy¢Û7íB\$	Ğš&‡B˜¦pÚcWd5¸gäN¶}-óÖ…Ÿr,o-·[r¸ƒ\nÌKVÔz¥øÑ<Û(´ùí›ßJ+í¦ĞWÁà^0ù?“åÊÍš²ÓéÑ=Û&ûß“n9,…ºîÊòîa?]¡i¼Êüßóïy«y°¡ÕÄ´	âulï¿£ô®\nh.*%SÀès*~…Ü„æÌÅÑzøé°»±L¨ÕcM\"\$—–õZ…™šÑhjÀµGŒç)hM`«5)Z±@W€°²µæHiÍ›ÕX+I¿æh)‰t@/+m++D,®	áYM0 Ï8HT)|-DŠM¥CğyÂd\r¡¤7På\rŒâ[]hÍßšDSá5E68¹ÔÅH \r3ĞD tÌğ^ä€.1ª6Fà\\Ãgá”7ğÈÃpa¦PğDó\n›ŠR(öƒãÂÒ*[4êùJ-H»á\"\\l^'™rº½‘¦#o®‹rVÂW¹2ˆ®?#8vÓÔC2I«åf·VDKšÌêd0\$X®¡Š(*Æ` 9†‡f2“‰²3b®óU±§.‹NkŠia2;LZ&•í<€Ã ¤\$†‘*FHé ä””¡ÊKÉ™7'C(x’p9ÊI:Ÿ#OY‡¤·y}­€I|®´…u%Ôdâ•%',şbª±Œ#Ep·PÛ•fEvp11L¸Ü¹ßp€ Jƒ;'¢+Äœ¡«ètAÚrá•q=>=1L¶VœuE‹2x¥æRŒ&Ø<RÅuEÔ€„3¥Çùp#Æœ\r¹b­Ä\0*ô&iwÕzt¾«j—`€`S6¦ãW¬ŒW®(áâ4êŒÓ©òÆ²‘‚s‹A¸2¤%å\\)¨èË•*ŒUºöWU”Â¾™ÛÖs” Óã­lE°›¯JoÕjpÙÍ¡xLu`%í;bô¬‹fq-¤’f9zªÖ¼ŸVw†‡…8ˆ5 €!…0¤âq^¾•—9Wé¯‰ÈÎ×Š¾QÅ£œv˜¾„>^HÓÍ¹¤ñÎ^³U<¬êü¦µ‘2jşiEríZpÌ®Æzû|ReP~ó¨Ç§2ò}ÊL\0\"Ç¶T¢Ëÿ-×Ê!ƒk¢£ÓèYwvyÖ¥§„À»l×b¢‡xĞ’<»N¬CÜ9H=îFUu-ÇÌg>ğŒ#oê€•%µuB€O\naPäò¶ÏIÆUWtÉWôiì}«B.?Ò@üÔKæ./¦›•¡a1ˆ+¢ÚaÃP0ı1.ªãD@‚¥x¿Dnjššk/«R‰È‹§'\0¦Beöl>f\$#~rEÒ¹7RV£îÁ×Õ¼(qâ³×&À¿#ev¯˜ò¥fÔŒıƒVñ‰«ƒîıZ¹÷­KBd•7}¡Ši¬¶Ààæ#®¶º/:òuA%©„¦\r¡Œ›wltE¶MÂP\$vÔüòëAŠiVXËƒv«dÕ-d%½v“!ëÔšŞÔm9’ÌéºVÑzÖŸ¶]bŸ‰´!ÁpØ\nëû)x!z—p5PU\nƒŠÔlÖº¹.*\\“A¬«.;~Qˆ%ë/ÁChmVçQ´#¬)Š’ÜÛS|\nFÇÉ4\\%²\\¥}mã?Ë(Ó*³QOrìMïu›<1ŞšQúBkÖçõVG\n<‘³‹\$–Üãš*)àvHÛXtÖeæª±îÇpë\nÔ'XõÉ£UMï\0kPÀ®ÜÕª+Ïh@xÒq2vc‚èUQÛ=M<ø”SÒ\"†1R¥ Í1V”Yñ×F(”6Îf×E™	Ê/«û›¢g@\$[\"Ä«xy[‹Ms’s£¹Íù3Ôï-©¸›ß>Œå|­OŸ×_Éæ®„Â|jÎ…«â™:°=.Ë¶î#Éohé¾ä–‹òĞûŒzœ¨Ï°²‹Œ-à";break;case"bs":$f="%ÌÂ˜(¦l0›FQÂt7¦¸a¸ÓNg)°Ş.Œ&£±•ˆ‡0ÃMç£±¼Ù7Jd¦ÃKi˜Ãañœ20%9¤IÜH×)7Cóœ@ÔiCˆÈf4†ãÈ(–o9Nqi±¬Ò :igcH*ˆ šA\"PCIœêr‹ÁD“qŒäe0œá”	>’m7İ¤æSq¼A9Â!PÈtBŒaX.³ƒ	°B2­w1{=bøËiTÚe:E³úüÈo;iË&ó¨€Ğa’ˆ1¢…†Øl2™Ì§;F8êpÊÃ†‹ŒÅÈÌ3c®äÕí£{²1cMàYîòd2àîw¼±T/cgÈêÌ’d9—À¶\rÃ;P1,&)B¶‰MÒ5«ÌÒšÖÃ[;Á\0Ê9K†‡´Œ(7¹n\"‚9êXä:8Œæ;¬\"@&¥ÃHÚ\rprÒ¹¡ht*7Œ:8\n0‹rŠ‰¦Oãˆ¦)Êƒ?»ê è:Æk8ì°¡mx*\"jk>Î/x&¯æ)|0B8Ê7µã¤±4\n«ùˆH-Êm¬+jFÆ¯,´&%iÂ\n{s-Irâ¢ò\nBÜ%/@íA„±èÄ‘€M*RãTMRÔÅ !ÀR’¨\\uXbP­…3Í(Â=1 PÄ4£Á\0Î2=â‚Àö\$sM‹s@‚5ŒtkPÒBëhæ0Œ€P\$Bhš\nbI\r£h\\-7(ò.¤Hõh,2[*ŠJ\0@6£Q@x0¨òÿ)¯Còã|^÷È†7Ã2êõà˜4ˆæ'š†±4F&,²bë°nf;y£Â\$7cN9¤bÄ‰CdÜ7mèÂ¿ãƒ0Ìš).Ì.’@PŸ`QÂ ŞÇ\r¨HòŒå¨Æ1¹c˜Ì:‹ƒœabĞ9ic\riŠâ´ÛT7¨P9…)\0ªŒ°2s\rR\n@*7ğ‹˜3c¨Òp3„É±ÂúĞÇ•/Øä^íòş3¡Ğ:ƒ€æáxïË…Ö§\09Ë\0Î±{n˜­8`^İ½â88+05\"åÑ¬z—¢hFµFn+bæÁIlj[Çğ¼R9Åql,<Klq	HÑÅñ¼#Éò¼¸ïÌïéÄ/Î\\ÿC’äùOL_\"HÚ89\rxÜ:u¸ÜÚ4Gmç!vñB89i*OŞ‹Ë!‰°Ä‡RNKJîH5›ÕÖA¡na †ÆéP¨wWTÒœD.ŞàeG¡„3'ÚsPy\rMª‘àÎÅ`ÑÓ-P‚^ô\"1…¤Î²Ãfh¹•HI '¥şıJXdiœ‹€@@P%Q	!‚‚€\nN«%E4ç’@ÂÃqI‚ííû(ÃraKDìPæÔPgHğw„D€'Sr™â%„¸2Á4.íÎb‘YÛ¡S§!Cƒexï\$9#ĞîuN5AÇ©Ã¥\rI¤†;;|8C\naH##Dxô)L&ˆæ¦nt ^ŠÀ¿´”c³÷\rl¥¤†Â,§*d¼ÖDâ–KÌÀ FÑÓø¦\né aäŞ×\nˆ‘A‚\rÈôéRşC©ËE™£6üæáœ¸dyàMÓ™ ÎT´Äu–p Â˜T]Èp5½`¢K™ ô§—8“?)q-s=OÄ®~ŒiM”Ì5‡¨•šÃ£Î\"e¤à¤VkùÏ#ËQ)Âê˜Q	€‡1¢ØJB0TŠD%£\$LŠ E,œ°@94²™)#¤= F0LiùÀHuÔš³'R5@HkÈÈH9oTÚ’©pU3wœBLÂ .U(†B…ıWK\nÇµŠ­r1'ªòÇ#à(!¦àØ\nÃY4\rhøPôsDQD¨‡ÔÆR@•á^”J±Pª0-yÁ†FöHÙ¶—3:<à]X²‘7¶rOÔ‹A”…›\$¶v¹ÇUçIÑå]f–Zb:M@m&ø7È2 1ıhA’ˆ–ÛS‹i_O0\$’‡¨Jn907¦â‡%ˆE`pU&Ğ! £0ÔĞ4ûH\níz\rªŠÇV²ªˆ7èšÍ©¡j‰\0:/zm­9¿*uJä[©_;ë¨–‡ p*„_j%3z¡¶Ä!¤¥µ B\0PRµ\n ÖŒZP";break;case"ca":$f="%ÌÂ˜(’m8Îg3IˆØeL†£©¸èa9¦Á˜Òt<NBàQ0Â 6šL’sk\r@x4›dç	´Ês“™#qØü†2ÃTœÄ¡\0”æB’c‘éˆ@n7Æ¦3¡”Òx’CˆÈf4†ãÈ(¨i8hTC`ÔuŒADZ¤º„s2™Î§!üÜc9L7Š)ÎI&ZMQ)ÌB™>¡MÎ’êÜÂc:NØÉ!¼äi3šMÆ`(Q4D‰9ŒÂpEÎ¦Ã\r\$É0ß¯¾›QªÖ5Û†©’Mç]Y„í¨bsçcL<Ï7ØÔN§	]Wc©¡EáÆY!,\nóN«Åê¬xmâñoF[×ë7nıñ¨çµ†^¯ ¦ç4C8)»lúlŞ‰-¸Ş™B«26#ãÓr*ÃZ ;ÈĞä93Ï(Âñ0h€È7»\n€è‚;hHåæ;³Ã\"H)µKSÚ¡`@:ÂpªNŠ±¡È\n«ò4ïË\n¤©iêœÅªCJ¨÷¤8Ú10((‰¤î<Üˆh”›BD¸BÂ0<7\"8ËŒ££>Üˆ¯“<ß\rªÜ9®ø!É³´J7-È’7\r#Ò“¶ˆ\"n†eœI4«šŒ#t:7Îb62¦Ï J2òª5=SUÍÌĞ¨A j„pĞi\\Cª>\$ãŠà6/°Ş\rƒ`Ş1Bxä4i)†V¥5\0‚1ÑÔ­.‡Û’eªŒ7\"@	¢ht)Š`PÈ2ãhÚ‹c\rì0‹¶øËpÉ©ÌÌ‚¨ÉBÕ‡ƒ\nŒ0#¢÷Ó¨Œ>0!à^0‡É&ƒĞƒr<½08–(Â¿{Ñ+/@Ï#MM€åË\$À(dšÃ\$°¤®‚“°å9ˆÂ 7ŒÃ4)OFé©O¨æ1É´ó0ÜŠƒz¢™Qûò:Û”˜ÌÙÈÃ{BüÇÓ°òˆŒãEÕÉHİ ¡@æ¼©¾˜7SÉ…5¦C÷Ú.’\nd2¡ÙBÕ„Éµ\$Qöæ†'HÎ\nÕ°#0z\r è8aĞ^üè\\‰q	<3…ğ_´lÌš„A÷QÀ¸ö*’¤ëÂL–nj¤8\r#”G°³Í\"üQ´xäÓÚ³j	¬ªŒÆ(\\ii÷}èËxÑÈò|¯/Ìó|èïÏğíDt}(İÒæ_V`è8à‡Ô£§e&Ã Ğ7Õ¦¦CY#êl95E>I)XÄ@É…	h¨(EµGlÌË|\r@Ëº¤:Î©D@š'^¹Ï=5’ÖÖc^lğıò N	±Â0+PÌšFh™Mƒ/IJQKsVp\ri¯2©ìD&b¸ŠP	A\"â.NŠ((À¤¡¥b£i­éÂ?ã¤f©y…1X¡%bÑY¤YÁİëÀˆv€Tt>?Ø—!H±‰(NÕ†8‡_Ô‡-(Á=5PÍHc\r Ğ9URD\$IÂ%¨ò`ß\0PèC\naH#G¸úJp j!ÉátuÁü#¦x6›2âÁ9ÁFK.“² –Ì	(%ÄÁ>”C\\B0 QfR)òNQ‰ I aäåÅÕPŠ”n§€Uê O®ĞÃd:Öq„Q¹sv	\\X@'…0¨Aª&D,“†w}A\0RzáÔı¤r†¿ÁQ4S1ú©rõŒÁšH­Í—³.î<“Htí¬â\$ğÃ L(„ÂxCÁ°*EE:ª	ŒV=h¬3N%\$y#@òåÚ ’c\0Ëj£~²â]…‘]A~œDnÈ½Iõ	OÍ\nšKài#Æ¹ªê¨Ep	\ntò}Uš¶ˆÊ*¨«áÑšc°EA\0CNÁ°Î…ğë›YÏ@§ÚÌ£HÀ-YÄ#F–8!!\n¡P#ĞpÊZa·\rÁ¢&gªi›¥å+'cÉX«!ë²è¾´*ã‹gRìÑ²îİ“…À£—éR&ÈÚ<ÊÌWsr@0ğP#ilÈ\nzæá¤è…’5S\rË40Œ1NŸ®İw0””Wó\\FCßEué’PßGëôÁ;¦ä&]û\$‚´Z¤­¼»é=[áBK¦Î¨°QiÎ8eU·ŞÎ_’\nÕ˜zê\rg-PŠ\"²Š´d]^‡#]Aç’‰‡†M¤rÍ…MÍ…ÂÉäÜ×övC ";break;case"cs":$f="%ÌÂ˜(œe8Ì†*dÒl7Á¢q„Ğra¨NCyÔÄo9DÓ	àÒmŒ›\rÌ5h‚v7›²µ€ìe6Mfóœlçœ¢TLJs!HŠt	PÊe“ON´Y€0Œ†cA¨Øn8‚Š¬Uñ¤ìa:Nf¶¤@t<œ ¢yÀäa;£ğQhìybÆ¨Ç9:-P£2¹lş= b«éØÉq¾a27äGŒÀÉŒ1W±œı¶Şa1M†³Ìˆ«v¼N€¢´BÉ²ĞèÔ:[t7I¬Â™e!¾í;˜‰¼¡”É²ËZ-çS¤Dé¨ÕÎºíµ—fUîì„©®îÄFôcga;da1šl^ßíôBÍ˜eˆÖ64ˆÊ\$\nchÂ=-\0P•#[h<ŒK»fƒI£†cD 0¤B\"ĞÔ##Ò&7!R¡(¡\0Ğ2h‚D(IâX6²£«n5-*#œ7(cĞ@Ã,2a¥)ƒ“Ú¨Š˜Ê‘„bYŒ T=&Æ#Ğ0 ¦)02Xô1Œ P„4¦ƒ“@)¥†)Jã(Ş6ÌÓ2ˆ®{ª9,MºÎ´€P‘\"\0P †#D\0Poç2Šƒ’Ï\0-ÌâE#,\nfş‰Ì´„»£\\ªŒ\0Ä0Ç\0MISU…URÔñÃ\",ÈA l¥xÁôS¸åôŞCXÉ\"&+42¡£ô	‰;p s\$8pJÒbô	¢ht)Š`PÈ\r¡p¶9^»mÛ¨ÚëJ8@6£šXƒ\nË¼ÈÔ(xŒ!òE€àl4€3\r#8ë-8^#i¶8‘C\rD²G7ÔH²¬¼sOáZ†ÓinL2å=Ÿ\nŒ#jæ6“à”¢cxÌ3'1\"”2ÒZBè2ScqC“)brÀßlÈĞˆ¡.ĞæÈ¸©u¦ÓL`XZÔrêìŒÈí¼•XÛ4:Ø¨jCN¨6êÚÀß­kšÆ¿Eì)fÇ²ß»:#\n²ÛdRíñâànmñ[©¦»Àß«¼·îû¯RüC˜ğ{2'´q^É¶ñ›€è‘	úg=˜¥ÑˆÄåÑH‹Ø„·ÍÛµ¥gO¶ã¥ş Œœn:ìxT9£0z\r énAx^;ûu¥ã\$)\0ÎŒc˜^2\rè¨è4ü¡xDRÃHæ89qÆ†È£ìa—©€Õ£toÊM‹ˆrSA°0¶7Ú¢’â9QîÅ\nÔhéNÚ†€ĞÌ“ÔUI,s­*@í\0Ğã\0<„]æ¼÷¢ôÃ›Õzïeí¦†óà|E@´†çüŸS	!´8\$è’kòcOÕ:RôÇ¨\$º	ò\"OIJOYïõôèNXn%e¹~/âZŸÃ2ÄÅMŠ’D¶\"¦«.u–EL¨ bé÷œ³Ñ¤uæR´\0ÔJÅëosŠ&¢Ñ˜iBè…† ÒH¤‹`–2æ`h9”|…H8I2É\$2>!!‘\0†A\0P	BJöZ(Sh™v\0 £‚”^¢HjSÂ„Ê\$RìYˆxeò™üG’@Š4D†P—ÆC2paéh’qè†\n¿ãœŠHÀ7†%(i‘,„N0ĞèØç:cÈ …Ç„T¡štDEŞhA9\0[ÛÙ3)… Œ­Z„™#ÌHã€-¥Ä’ „CcœHˆÄôO¤I*_ó4Ì?ƒ+Ec\0´\0¿ˆ] j4fÁA½Æ²ˆĞy3mà_-\"ğ‘\nq	J„å²ˆ÷ )§H©5´ÓÓ§ÌÀ´Ëv¢äÛ±5&æ­\0Â¡ùCô’©¤r½Ï#'påüÀ£–®gÃzMGï•Š1cÎ#dĞiy­ó—ö>m	Ál €)…˜GzJœ\"¾q£’*EÈÌb\r¡¾DĞŒ%aä\$èœê§ôTáÈši%	“ŞY¦¢9 `ôbĞâ†³Ö€ò+Fxë u\$ä=K²+gUc¶ºcºLíM¢&Xœœ˜²âÛ\"!\"«´Ü(—q1Ê9‘-UÚurÍmÍ¢Fè\\c˜Dn­Êg&D‘6zYÿ'WJÃE@KA;luÑ¤d2ÁÈ˜z‹0Ù\$‹¾×êÿœ§­Œ1fùT¼_áT*`Z(uNcÛk‘u™ÅÌ¹ØNa\\®¢¥ÃaÈ\\\"#…••1wƒ^+4Q!Ä@‰AåêiÍI«¥öı†ãp£äåÒ6èë¡ M€v7.üãÓ–[Ëkh¬æd¸<Êü(dTHû\$|”êÈ0a=	M{[6]V—Á†ÌbµáUé‹T@*#J+@PC'xÅÔZs’\"ùT®åe†µ^4!¬Ğú%Y„öqEbØ\n	dAµ Š, \n‡zd+•„½óAöÇdŒ¡Á«J©xß«9ºbk";break;case"da":$f="%ÌÂ˜(–u7Œ¢I¬×:œ\r†ó	’f4›À¢i„Ös4›N¦ÑÒ2lŠ\"ñ“™Ñ†¸9Œ¦Ãœ,Êr	Nd(Ù2e7±óL¶o7†CŒ±±\0(`1ÆƒQ°Üp9GS<Üèy8MÁDYÁë†ÃÎCğQ\$Üc™f³“œö2 ˆÄâ´)ÁÌØÃR™N‚1ÈÃ7œ&sI¸Âl¶›«´¡„Å36Mãe#)“b·l51Ó#“´”£”l‰g6˜rY™ÄÈé&3š3´‰1°@aÁé\rÆI‚-	…åræÌÉº6G2›A]	!¼Ï„Ä4z]Nw?ŠÉtú\"´3‡ÛÁ›´”o´Ûb)ætÅ3Ë­Y­™ÁESq¬Ü7ê\nnû5 Pˆà2Ë’2\rã(æ?ìæˆÈ@8.C˜îÄŒˆÚ´®²€61ij„ Œ(0æ¢É¢n….‹³šÂ1¨²ê	ÉŞ9@Î\0Şò:é0È\nc¢dÉªËÒG‚s;Iƒ[Ä7½ò0Ò3Œòºğ?Pü¶ÇÃr7¢Ğà&<±¨(&íŠ70h›1	ÃËè¡@PJ2ì23Üû? £PÂ!CPÁxH c\"%ïĞé*C@ìÄr£tš>nÊÄ&\$#KL\$6C\$HğŠÍ Øı\rÃ¨\$	Ğš&‡B˜¦zH-°	JÀ UZSUî4=AéL)Ğ|Û£”Q&#òã|Ú†7ÈÀÏjŒ¶å¼º<ÑŒ¢²ÌÍØÄÄ÷Š<‰Åğ2	PÜ\\‘½H£GƒdŒ#zÎ0¸£xÌ3Uj\\ˆ›ÄQäĞë²¨7Çópò¼_ã¨Æ1ÁÃ˜Ì:·Ó“9…‰(åŒ#8ÂÆİ´ÚÆ©PP9…(Ş.ß q˜<©C¢6*\r\r’>ì'ª@ÉÉ›¡C–Z1à#J,œZ\0Ğ™ÁèD46ã€æáxïµ…È®¨Í1#8^ ğc“\\xDnÎ3İVş&9àzø¼UI{.Á…¡\rÑ ÏF¨c&£¸ Z5Ìƒ,8ÁpøÊş\\qapA®ëã.Ã±ì»>Óµûn¦Æî–åº_—÷½ZBHÚ‰:*T9p18A*óºeØ3^\0@3¥î*,Á¬#”İá#bJ4Üˆ\$)c[ÉˆZüÀt\rîİ7ãğ é<ú…ú¾g¬Îè½‘ä¹>Ru™£ñ ’¾ÀæOx .í8“Ëq¨c!Ù­2jiÊ@8Ä@á“rZKÍ¹»@\$\0[àª.'\0 —o^€¦II‰.3û›ù%GçÔ4ÁC2WSâGh† ô\"oÎb\0003¤l*†pòÑNG™>ò\$C˜noæh4‚MùC‘vÀ2cCƒ9Bèe\r‡\$øÃ@ip››“eà)a…§1(›ÒÑƒaL)`Z¥ÈyAq§B-r€Ÿ±ï9ÄpDI\r‘•7Á˜™‡DÀŒ{|\$Éé‚¥Q\n#D¦°’@ÃË\n8Íiô%#à)E;¡Ä: ãaR£QvmV<”ÆDËœ—H`®¹‰+ÂxS\n‹(Ê¸yCƒ\\¤0N™,²\"Ng8ğh2 ‚„s\nD¨ƒäÀ™\"â‚K£›\\‹™j·åÈP\rqg|E¼‘B,ñ‰jô\naD&ÔVˆ`p ÁP(\$ïÕÒƒ&0F(Qä˜E\r¤);—ÓäJš1c£n€¤EiÊğæİ\"!Kì¾¼äjFÔx~†\n\"Ñ”\n|¦Dà8S@ÓM©HCHÁ°†´HĞ¹€xÉ@SFéàıùÁ†GÏ€O‘›á|u0âÏ\0ª08á”3ŸÔÈßé‚|5B˜¥8rC©E8PU¬ÖVê!Ê=!	fÈ¤÷È²\né%2Æ` \0¡†!2A˜Çœr,–	œöj *Róğˆ,ª[ª®¤Uœpª¤!(Â®é\\pËø¦Y±~“¨sCjIª<\npTDJ•™3!dÉ	°c€`“Õj5d>œš›†X‚±æ˜%‘4ÄHıp-µİá©Apuíi°G,ÚEÛd‘,VÄœ’À@";break;case"de":$f="%ÌÂ˜(o1š\r†!”Ü ;áäC	ĞÊiŒ°£9”ç	…ÇMÂàQ4Âx4›L&Á”å:˜¢Â¤XÒg90ÖÌ4ù”@i9S™\nI5‹ËeLº„n4ÂN’A\0(`1ÆƒQ°Üp9¡ÇS¡ê]\r3jéñPòp‡Šv£ ‚ç>9ÔMáø(’n1œŒ¦œú‰\$\$›Nó‘Ò›‚‡ÄbqX¼8@a1Gcæ‰\\Z¦\n'œ¦ö©X(—7[sSa²\$±NF(¼XÜ\n\"ÚŒÌ5ä‹M¨ŸR\rÇ6’Êe’]ÄÍ¤<×ÀŒµ#(°@d¦áDM^¶|z:åÍgC¬®×Ü®©vÜ§ë„ûDSuÔïµ—6›-¡§lõ\"ïˆä‡¾‹¨Üâ¡*,Ô7mêâ÷À+ÛÜ\rÃ¢5Áã€ä0¾ P‚:c»Š….\"¨ÜÚ\rcİ\n¿\"26×J:šš2‘<T5¾q`ä ·‹*„ç­A\0 ÂD,c>!?É›€‡¡£”h›‹{,¢?K‘JB02©lrœ‚!(’H-1#nš£lrº‹M¨6½ƒs:?òDR@Pœ2¬¯¸ä5B8Ê7³ãD2·ÀèÆ,2²Í<ÈcœŠÆB`Ş3­@U#I¨C›á	6óØêü¸Cš*=A*ê!(°Ub,‚CÔÓ! l¥x®C«âÛµÉpÚ\rŠhÄ›´ÈÔ¶ëp‡<pÏ<cJ%,Æ¡Ì›²Ì5OƒĞèƒ	Ğš&‡B˜¦*ch\\-W¨Ô.Û³/DE\$ÍTa\0Û`!àÂ¢Í@@ PØóo©Éx1hëšã|’`Ø@–‰-#>\"2ãÒ‡€¯öN£°vÈìô!îT¨7É¯—¥Ì³”ì'Â(äû¿Ésè‘dáhü°ôª€ÿƒ¤òå//®Œ3ÖKRä¥ÃµŒ ŒT(Ø†¶âÓÃœ5Éhä3\rã`Î6\rîÕ-L\rÁh¬—vöV2J}\r\rö^s˜Æhò~‡ÖjÔ²'Ãr4%«RT6ÀNÄˆ)»*]´m[fİK­[–é»9Ìé½[î`»p8ƒÂğ÷×„ñ£w’kšğÉ¡ìÜÅ¦½(ñ»ªëÈB| Œ™KS'\$K%ÌåµiAƒ9lÌ„CDè8aĞ^şè\\†xÉp\\Æázó\"H­´7á}>¬°î4µ£]	BÁK®OõªÃ˜d*'‚:LĞn:Ë-Ñ¶cRY»²5Ş°L‚ƒ-Saİ#ò‚eÌÉ¬€p%¼=\0Ğô£Öaí=Ç¼øSâ|˜2‚ô¸m‹É}¬ Ä‡\\şñoë£4¨SàhhçY4’Ö\\‘*Zğ0\$8^ÒÂ&\"Èì:ÇşFMâN\n (Ğ¦G¡ÉÃ`L›–pcH!¨FO\rl‘ @üÖp \rÑ]Ü¶—v€˜\\kîá””&ÀaCaÍ.Ø2À%õ\0Ã™A°œÏšxbó@‹mË¢‚JÖIN7ò]šŸH¶â>2Ñ2É“ğ¤áÁâ„DC‘œ~)`\"#6ælË£¸Š¨j7?@Ê¦a\n	ÕAò T›10&D‘sxÓW‹‚a”—öÑ_Q=!€©‚dìA\0L¤¹o¬2~šÊÖ5aÁ\"AÃu g€€*£¸®^Pk'H¬‹@†ÂF;a¼ç E­5Ìpgo	\"Ï°ŒcYËtö†X fÙQ¡±Î+‡4`2xa¸=&RXÍQs_?-,ÃhÈI¢to:Š4¨KƒA:‹+ç\0O™áÚ!¸3!²;‹Ô·Ïğ¼‚<CˆiB9„8ë¹BOÒ3©\$ú3´HxS\n€´áBèÄ8\räñ¼'n;;Faœ“²zÑ™«ZDàV\0 ¨±Á*\$jÇêa7b3…,ª-‘Z’õÊ°`¢+a”#@`¨[‹!v%¼VD!vk4ÌÅ\$f`ïaÂ§ìD†×XNŒÙŸT¶è;ÛÂ|GN+ñ1¶à‡*bÊ•.*Î'Æ¢IÀÎgÕƒ1Ç<–FœÚ¡ém86°–²X£MFÄÙŸ„˜à¬Z³¤‰j5©)ø0-\0û3ÈàaKQ\$V²Û»¥;ÌY1åMW—UJqN>±\$§Ä>‡Ñ\r.:çäî,†ÊFˆá\$„ß1	r½ &Äˆ2\0£¤BÌtâ6çîö´¢£«\rH2Á0µ#ƒÁzÌ5_U€ß<I\"D'IæZÕ—˜CEe\"À(×J¥Gƒm‹lX¨\\npzÁ¹,ááÌ‡‘_¤+)Ş|dnÆfúÚà¦ş°O‹ƒ;gt×|:k\rrù¹¹›(˜ÖpâPt";break;case"el":$f="%ÌÂ˜)œ‘g-èVrõœ±g/Êøx‚\"ÎZ³ĞözŒg cLôK=Î[³ĞQeŒ…‡ŒDÙËøXº¤™Å¢JÖrÍœ¹“F§1†z#@ÑøºÖCÏf+‰œªY.˜S¢“D,ZµOˆ.DS™\nlÎœ/êò*ÌÊÕ	˜¯Dº+9YX˜®fÓa€Äd3\rFÃqÀænÏFİWóûšBÎWPckx2V'’Œ\\äñIõs4AÁD“qŒäe0œÌ¶3/¼ÕèÔètf“ÅOåê¥j,·Q#rØô‚D„’I¥½…jI\r›QeÒ^Dƒ…ÅA”üšJ¾­uŠC¢ª\"\nÎ•ºÓ—ÔM¼s7ÊÑäñŸ>|®íw2ò¾U:€¤•©RÎJ.(´¬¨Eª,Z7O\" ï(¹b‹<K›¤¦Š42™·LŠNŒ£pR8ì:°´8¹<,ä‹rªÑZˆì\$ì¢²’39q’ÂÍ!j|¼¢ªRbõ¶Ê’Z÷¥¤\rCMäròGnS1‹Ë”ú>Ì‚¼éŠj® ÄšdÚ¨Qüo(ÆÒé Ğ!r‡§¬{ˆÈL¦qvg‘Êæ%ì|<”B¨Ü5Ãxî7(ä9\rã’l\"# Â15-XÈ7Œ£˜ADˆ ê8B85#˜ïHŒ‹9@)/é=‘Ìk”¤óš%\r±s›œAÁ.ç	£ŠY(	\\Œ§Jï¨éZÈ³­.bÚ­®nÂŒ»­‹ÌZÇÎj¥v‰ ÄºšÏ¢åºŠXs>NÈ14˜¢ˆ“h™Î2\n!Nvi8¦Vk¯|Ó23ŞÊBdX²¯I\$1éô–¡22q0¡ÌZ%b;jL<¡FÄÓŸ~EhúTƒlBŸ#3œN)ˆuuå\$nV¢eÄéœXŠr|úÖ(ìğŒ\0Ä<ƒ(¢hÚE:ƒ<Iı\0&È¡xHëAŒŸÌ†A+ Ò¢Ğ\n2 ^¨I¡›Î\\ç¶L(•qBb÷Ë!ĞÚÿ>sĞ£NeÜ8ûĞDûX ÅIœT‡Fq7Ér0!(ƒ]E¸^‹—x¶˜©İ»†ğ“Öw‡Ø+‚c];Ä,\0Ú:ux0¬óN:£•0÷ôxÂ<‡xÂ&İ¿r!ãpÌ4ŒıèËâøí`@4Ñ–˜\"\r#.65}#FŞÿÃO|}ˆÊ<i#pæ4ù­j\rÉ»ÖC“ƒ äƒLê›Ÿ±™&,	“Å€Íˆs,JÈ	€,4dëJb©<Åê›#‚.­­\"8eÔDŠŠhYPpŒ¸BFv¹ß)h` ­´¤àÌeP%œ‚TZ<ŒÍgº“×aš3.ÅÓBRF#p	ƒM¨§¸<çqå%‘4º”®sOÙs…eU`Bãzn¡Š@‚ğ)ÃxrbáÙ‡¨„‹CòŸOëÖˆ§HŠÅ¾†Š	†…96 çR½’ü&ŠäÁv`¨\0kSÁ™ñ»G°Á\0Aµì(èpßˆiŠ8;p@\r8f ˆ4@è˜:à¼;ÊĞ\\dœ•RJD3‚ğÊz›|¡ÑøK€D¥Ò•‘ïQä.T‰‰\n;©Dô‘ ÚvK\nÒc³xø½!òt!ÑØƒ‡Lº’A-ìˆÄ””æh³\"B,‘ ):dü¡”r–SÊ™W+C¼¯–!¹Géi-¥Ãë}¯½æ‚ğDî\\T=ªñ™k12W*%PÅºÈäO–\"­´†‘cÈ+\"¤l\",Äİ+µ\\cĞ	³K(Uz\"¢Ä\\OaR¡‰ÎÅ®p¤x‚MèõÓ…2ZàO‡@HsVC¼y €;†Ø1«\n:F ÚZ(aÍ\$9* ÆÔèsÁÖ¨ÀŞŞÅL\r!Ğ4\nØjŞõWš¬KyxCcòËÌˆ%ØEœ§`G¹¼WÂ/_Ö)Y}mEÆÇ9†p¶)‹Ú*,¨K\0ÖqıZ €(€ Ad0äÚäÎ){\$\"Q\0«•ÒM`nŸ•&GÖàŞ€r\r!Ú§PÏV-Â¦SÏuO†õC^+0w«œ ˆefEÉå,NKù ’èFÌ24(ãWq'€sUë¨ÊßyC€uTê¥U‡&ŠÃ@iu¶JyKTkuİ®a…ÚRÏs–iØCxª­ªHX‚S\nAfRˆI’{Œ€¹œSÆãlÚ.ÅœR—Vü3…•„¢NŒ‚¹ J:)VN˜bu`n—U±jd™t\"/phÎUäµXÍ4øUÈaëcñ±íz:ê’\"–Ò\"Z‰,SmŠgxÉ¡#e”HÙj’RR~) u]IäpÊıÔäö)DbNpÄQ]²«Ì[†pÑÕ[tH¨P	áL*c‘96	,AI§\$•| [­0X‚» 0öÅ ¨(( ¯Ô¼K™¶1‚'/\"³ğRNu\"¥Œ\$á´\"hq×‚u9ábYB}H1oÇ‘al0¢\r­\$€€#K>–)zÊIh“Ò|ˆ -XÈ˜·Ú¨Eà‹,â^Ó3ôìĞ´apÍ\0»4%Ô•6ÕN­çm•ïMMµC*›|Œncµ·!ˆ‡øåaºîaF%:ƒnÀØ‚šfïÜdsrÄ˜—\r·äk7k¸¹p}ÀÓº<Ù¼İk¨ØßĞlaŒ6_ ×šVÒ¤_lÑ„‹°²ª(ÅJğÜ§5²ªÔ(zÍk““¤€vÁ™‘b—¬ÙÖƒJªQ\"ÜàP¨h8&À‘¢Ù£ş]­6›ŞÉH[Ä–™Ó©AVØ‹{ÃcçQr´´µ¨Ú”‚x³:=²îÃérŒxS¢0ğ²<t.¼Ş2Rîy?ŒæRkëZËªbÄRì?%%»³:g£ÖR‚ÓXå+¼Qá’OˆI„[“,NPgàªQåÇ÷Èò•‘É8¦dÚ£@£ØvEµ‚z9åË:ºË Ùñ¹ï:œ‘ˆèšÔ»ñ1ÍeÁ† ˜æ‰§Šbî˜µl=”UHÜÍ†¬mØtÕ¡ÓÅcKúÉkœ1kÛ8|!p+Í=ã~‚‡:2Ñ¸ú¡BtÚ\\ıÉã#5ÂdLšV´™ÛÛ¶ÌnÉ,\n…KNVHbâàVDše¸gfN-@î¯Ğîêp9\n";break;case"es":$f="%ÌÂ˜(œoNb¼æi1¢„äg‹BM‚±ŒĞi;ÅÀ¢,lèa6˜XkAµ†¡<M°ƒ\$N;ÂabS™\nFE9ÍQé İ2ÌNgC,Œ@\nFC1 Ôl7AECL653MÆ“\$:o9F„S™Ö,i7‚˜KúÒ_2™Î§#xüI7ÎFS\rA<‘’M°Ó”œÆia¬ÍÂ	¬r‡8³MNfƒDÒl4ÉÌ† Òg±MjE*™““Äp²2i¼Èi°ÅN@¢	ŸİÁá:ã.O~i¥ßr2­,ÊdQÄCO&p9H3÷›„,á0ÇgKv€õ…IúyÓfG·´{¿„[æ <Å\ræî¡»â„¶é8Ü²½ï‹î‰¬JŒ¡ëÓªş P¦0ËÎ’4kRİ‰-¸Ş”Nj,’KâÒÍoØŞÇ¬©˜ğŒL*&Ê´c¢Ìéc{õ;ã˜æ;­\"F(-\0Í\n-bÖÃ£sÂÊ½¨Z’×¡ïÚÃ¹i#›¨¯ÃÂ¤¹\nbF'eĞÚ2¹@PŒ2£Ïò4-!Œ)¬œPÚà\nN{Ş2Ãã(è9elä\"À+DB¶­ëˆ†ıĞq’W9	ƒ{P7@¯²3¿ƒ•\$XÎBpê2§3ëPä®!+ 1*`Ub˜‚(P£A j„xÎoÛnÇ²4ÂÆ7/UÂË¢jä'¾N,F&&M\" Œv#\$6®LF0Îğ\$	Ğš&‡B˜¦ƒ ^6¡x¶0Şƒº\nÎCÊ4¼ëM8×¬•Jê:Á\0ãQ„àÂÌl9IËLšË7ã`Şã|‘áXe7,+Õ<9cÔ°„²l@\"!CÆ#)à@„eÎ í˜¡hFe)¸²¨Ü…EÓ\n\r”¦:“Ó/²m~÷\nsD*²·bÑ‘¯}‹n¨òÌ±5ñ²¾›[(RÀl(›ŠÃºÓı±\$z´ÉHÍKÍ:¼4à@a•)Œl‘ŠŒàÜ5¡c4Š¶BÁ\0‚2mÓD•N!éªÓ…C3¡Ğ:ƒ€æáxïÑ…Ó¯9Ë@Î¸¡}–0ºØ^İ{ú3ä¸Ü\$…2¢*2k»Ä”ÆHÒBÒ…0HƒÂ¶¢hí¢oõH‘BƒÈ!Rô¯à4ÓÑnğòÃ/1Ísœ÷AÑt2Å4u(¿X7uƒÄ¨¸~Å”Î„Ë'LèØ²ÃÀåYrhbÄ14–~„	,M­¥–“8Œù¡o-(µ—ğĞ\0R/faÁ42çğ^ñ	\rI°:š\$Bƒ‚£l`0æF^“7å0ı4Ó\nöA)!fçœSj¡Â3‡åææ¦rI°¼AØšÏ’¸P	@‚#¢ÑˆX('€¤aBeA\r,dD¦¬~LR±<0Å46\"vP©5	Õ„rdá rCGPÑ¥“š|Køä 1¼ç xƒf††´3Æ{‚@a?,NBbdÃ)G¯`ù\$¨Ï)TfUEVòKaî\n¼5¶°†ÂF’aØ—šÄ•lfäq”!R2ÅŠû°32…#€ÚOiÙz<Inhåq'ğ²'W´KÉ‰)2„LŠ˜²Í d9`\"”HKL°‡dM•0ÒtHDt2§c“ÛM<‡Aâ8·NÊ±Jæ=«¶¢—Ã\n—=Á@'…0¨e„/ˆA¦>}6É¨.	õà¢*Z‰9)%d!šYç6M¼–fÑQN2÷	–*X9‡:‚J¹ÈEk2aL(„Ê<§–cÚ9ôq\0Œ\"âœÈ,9@³5>ÈÚ\nje ‘ªdÂ÷e\nÕIÈµ>ÅPö±¸\r.ıêUåPÌLu„XJËÙ fM†ˆ‘*ó¡]«Är¯R\\²¥† Ã`+‘µ®.†#Z™»-'~Ô²OUf™K„å‚3öÂ%j)6ææO0ª0-ĞèzÎL‘İs¯À‚×(6¿TëâY8I\\¯\$^’Ùğ›Ú·+Hf+±äSnqJee=¦>8€¢ghëZ#\n§R€¶¢6qnœ©kj@@sj—â›%`‡\"àß7‰FªR÷›uõI}I•¢“\"âŸKkE£¨¤Å‘\$l•…µ/–ÏçwpZBsbÌD’‹b‡-Â#E°İQˆìÚî3C\n‡±9VëlABh¬@ªÅ\\À";break;case"et":$f="%ÌÂ˜(Œa4›\r\"ğØe9›&!¤Úi7D|<@va­bÆQ¬\\\n&˜Mg9’2 3B!G3©Ôäu9ˆ§2	…™apóIĞêd“‹CˆÈf4†ãÈ(–aš&£	Ò\r1L•jÀ‚:e2\rqƒ!”Ø?MÆ3‘–±Ï¦V(Ô6‰ÅbòóyºÔe·WhòsyÈÒgŒDÍ€¢¡„Ån¡ZhB\n%‘(š™ ¢™¤ç­i4ÚsY„İm´œ4'S´RNY7Dƒ	Ú4n7ÆñÇhI”Ï8'S†ˆé:4Üœ´>NS’ozÁ³µZW<,5!ÓZ 6ÍN‘~Ş“³¨0ø~3?‰«Èr3àÌ¾î!¸Î«'\n3R%±š®¬´b¨Ü5¸»2C““ˆŠë,»„ Şä8¢æ#ƒ”<8+˜îÆ³â‚H:°Íl†ƒD<‰\r#+_\0µ	Ğæ½!/ê1>#*VÓ9ğ€Ò1\$âpê6ŒLrf´@cÈ2Ï“6\"CŒÊHNÚÄLˆÂ9B²B9\ra\0PŒ‘<ĞB8Ê7¬@èÇ4b’Ğ•9S\$:«JàÜ‹\r TU	# P˜7²#Vı‰O#ÀÁ­3ªÜ¶ÑM3âµCSö­2ŠÔD:P£Ê 8\0PHÁ iX† P:%€R½Ğ­¨Ğ2Äk;HÆÈ¬u4#“F*8Ò¢q\0006µb¾^,b@	¢ht)Š`R6…ÂØóq\"ìÊ‰¼\rpÉ:ËéÛÑ\0„àÂ‡ËªŒ90ÍpA1ÙCÈxŒ!òOy^‚\n31é°Ëà1Ğ@ŒGP¥41#×sFÎªğœâ÷pÊ<9ãrXÂÇtÌ¼±“¸Ü7S47ŒÃ2Œºğƒ}2Qtj@R\rŠã(7¸#k\\<„bh1Œh€æ3£c9F#˜XäZ Â3¡6!R°t\0ÊaJO3gŠàÙ¨?Haj7#.Úp“ŠäœŒØºpŒá\0ƒu£†¦1ä‹àåx¾#Bê3¡d:˜t…ã¿\$\"Ğä1£8_ğĞÜ®,(^Ü÷@ÿa˜I¥t,*Í|¸Ñb©ÄƒdY§²>Š:µZX9´é:RíÂÜK73CÀád­wİğüOÆñü'Êï¼¿3ÍÜŞAd~èEz	#hà‡Î£péÔáÓ«¢7­.SĞ•#J*7hHÏÕšømlK±µ-%¡Ä~bŒûq#A„1ª‡\\ÈKN2ïÜ97PäKHaÇ=Á´v’ğcNlæ@ÃA¢rUù)ÔBĞ•A\$d¡¡”#<gB6ˆÕ‡&ntÈá!¨Èã³”° @ \n (C„jŞ!ÛMDí±\0¦Û,\ro\$à‡†qN9±X„äé¡Õö–xwy (\"¤\0ÖcK¨kW¡}šø0Üùuaääø‡4HÒXy†:/Ô7\$FñÑ@ çH1†ƒĞcÑ’¦:¤1†pN¸o,-£\0†ÂFvˆ¬ÃÄ¼VĞv1¡µİ»×„J‰cB%Äi³Ò)áò0!åhÏ<äTíŒ14aF½«±²NHy7(d±ÌiÍ|(:eÔ8»È¤”¬onZ#hZÙÍ<Ò'(ã’p Â˜T«©÷¦…\$]q\n\r\$‘L'/¥!<Šşb;©ØfgÑ\"CóÑ;¥Ü@p¦[‡Co2ä©†9œŠ/µLcÁ\0S\n!0˜R?<Á\0F\n‘5u0÷Ì±z›	Ğ‚3d IÂZP­¾¶¨°Û™(Uw“´’nQ™(KY*y‹ øvkYIœSRô”ÕLáºœSÅˆ£>Ù½@Ç¨ö%Ù<Ã`+\rd•ßâ	õ=1J	±\0ª0-`¹¿ñ1é8r\"ª¢«h›–-Ä,Ú¦ÒR>mµ\0”W`Ì@Q­nIRz:°ŞiËé(æ¬\nDE%×z€«¤¬µŠghOY¦fe¬“¨º*Li9Æ×Sü¢ƒ*¿\reøÿœBFFL¡äF¤”£œ–škZPTµüÑ…31R€Klæ6´BEKy»\nÜ–¥Ëcµˆ\"nü:KdÂ‘b¦‚Í–¢BKX";break;case"fa":$f="%ÌÂ˜)²‚l)Û\nöÂÄ@ØT6PğõD&Ú†,\"ËÚ0@Ù@Âc­”\$}\rl,Û\n©B¼\\\n	Nd(z¶	m*[\n¸l=NÙCMáK(”~B§‘¡%ò	2ID6šŠ¾MB†Âåâ\0Sm`Û,›k6ÚÑ¶µm­›kvÚá¶¹BhH²äA9Ä!°d+anÙ¾©¨ô<¦W-l'ÁD“qŒäe0œÌ³œ¾õ\nXÆ¬Ävº”C©‹›”–-*Ue¡KY\$vâ¬…Õ5±ë¥N«W†f+PdF†šØZ\\a‹•ÆT·†ç¶·Jµ±Ä—\\V˜Lù°®Ã£#u\rõ#´´ŸHĞĞı‚¿e¦â)÷¹nZ4®ĞÄ®>ÿŒN©ÖÜà(µNì£‚Íºïª ‡¸j’(l4…{\\)Œ#°Ò7ëŠlX\$‰dË¨ŠÔ)ˆSÌCB¨Ü5Ãxî7(ä9\rã’^\"# Â12,˜È7Œ£˜Aˆ ê8lz82#˜ïŒŠY¿C¡:œ³È±d—ÉKd Ãîª²õ.J	Të¢ÅBLê!E2ZÊ)j:’®[nÓ¥mT¬ëŒ¾Î»Etl(‹ê~C..!h•¿l)Ná9á×8‚rù\"NrYÒ\rë´ô¿ÌÑbƒ()ù\n»§MÒ*€UB?Hœæˆª4ÚèX¹âæ@ˆ³B®%PU0\"–A(É»ÉXaX•ÚâˆÕKA b„¨µì4¾Û..Â~B5È’.6;¨âKóğèC.¢ló*¶Tı\\ÒUCNt¿o\"ÕCJàP\$Bhš\nb˜-8hò.…£hÚŒƒ%ó/LTÅ:>I@6£œz)|Çƒ¨å9dV0!à^0‡É~I“cxÜ3\r#>T2æY£(Aó@\"\r#œf62xô[Ú^›iøğÊ<ƒ(Ü99Ó*ƒ^¶ÕÅCC ØéLˆyD\$ŠkÀJ(ª³;N‹\rXU¶íf¨••–ğØĞÎU:ıaŠ?Î´¦l]æÂ=d6\$FA(µùM!õä—¹Ìsò:ö5}À¶œÛÂ(?ïñIªoÇØ|'ÊÁ\\»‡Ìí¼âçÏQ{«-¹M—SÉ;ER~8…bAö­Ø¨4e£\\t3iù3„È6ÁñPXzğÒ2EAH\rxÌ„C@è:˜t…ã¿ì>ß»E£8^Öz7j!Ñ®†à^ôF/] ³U8£MBT7éı'/b˜¯)´Ieü£ BèÜ•™ö*&è«nÕ™}qf¬Ù¬D˜ic•CiÄ‡·óÀÇ’Ûæ/¡õ>ÇÜü“ô~Áİü? ÜŠsığ«µ–·`;&\"ø”!ç*JŠª€¬¡÷2–›Yã‡%a[¥°ŸiJª–¦äP‰C*ê™rPjQ!4‹|» Rt^y“!3 @ÃHl\r€€10àŠ r\r¡•a†ÌÖC’>a‡0ÌdXl\rá¢È€è§2m*I¾ùt!±¯Å4¬áRÁWSÉÍt—²\$“Ïx?*})â˜Q%Â…) €(€¡£a(äœ(Ø¼\n	)x-ñZ;¶âEšX%ìä7DyõåHoÀ9”Ó<”i	4”vÑì³”AŞJ'#I/LÁR…V8\"ä_gùz\$¾@˜öˆ¡ØsH’q¢\"‰UEC€uHi#‡%†Ã@ir¡îwİ#a…†YÆÂF!­­K£©BUÖ5\0º@¬Q*&yÆ—P‹Á<!Ä­9›’QŠK€™gQr§Ã¬ ávšÔñ¶ÁÃ«LÆ\$(]bBI!¼:‚\0Èøçº=2A¹aÊyìcÃˆuG(ô3\"ÀÚöb3å•H 1Ê#%*i\n:H“ÄËb§1*t^/1bĞ¤øF)Ô4¨ö=ZÅb&^	ù…¬È}3ŸxªğHEN!Õ-;x¦“ÉÑÄ\")b8Ìshåû¾m	ù6%yãƒu(Í¸’…0¢u)Ó°ÌRürJs‡ÁRg™r.C+%ĞOqÉ½ÆföOK¤\$s¥6£+§Š¯j:/.^e	1tqgÊMKØtaÅîW–@¬¼ª¶°]úW§Q;“²{VÖ>Wx¾àZ•}İú[9J[	:Ê\rTU¨9ï€: Ø\nì-\"\rj‚à›²˜¬0mÃ;Å>;\\ ª0-¹º· qï#‹¸CĞ+h±ÑĞÂ„!ìDUR©‹ñSÎI£¹Ù!fğşø2\ršL)0û(l¨¥íºªÆÌ “¹˜+*SÓ\nf³€xJqã?V¤í§xQÜ\$š¢Ÿ™«²©±siÍ{ºµ—ÔÙ7©dÚ>]c3vb¥°ŠÓä6`T„Cª’®ÉC}Æøÿ˜c‘¢rRûL7ßKŠª’hs¨ÓDY¾ŞvºÍŠ€*Kh©a–8ª¾»^‘lì1q±ûcËøW7—2¢.@";break;case"fi":$f="%ÌÂ˜(¨i2™\rç3¡¼Â 2šDcy¤É6bçHyÀÂl;M†“lˆØeŠgS©ÈÒn‚GägC¡Ô@t„B¡†ó\\ğŞ 7Ì§2¦	…ÃañR,#!˜Ğj6 ¢[\rHyšWUéĞòy8N€¢|œé=‰˜NFÓüI7ÎFS	ÌÊ ¡Ñ§4“y¨Ë0ŒÇ&Ù~AH¤’kı!2Í2˜ù¬êp2œ«ØÓp(‹M‡SQ†RM:Ï\rf(˜i9×«Úh”ºCcRJJrıTf!7Éè“Y”ë4èÎÖ£¦éI7¯uzú^º\r2Ã›¶¥O»Ä Öú6øy·bkÙâ÷Îù²Oæ‡úd{•%zçM ìñ¼£s2Ú4¢‚*†6¢Z‘«ŠòÀİŠƒ¨Ü:«¢˜:cĞ” ÈBÙÂê[P:¯ãš>¾Œ/¼®«îÓĞ7£‰{\09…¨ÈÂÃ<Ã¨Ö9¾nò!ª`äÃ+ìT”‰ÃªJ9F€P éBC›Ô.cj&´/€Ò5(2ˆ„—ºÉtÒŠÒêzã(Ş’‰sj\"Œ‹PŞ—B1‹´à\rÉ Ò•EqSÄ®\r#“Z:Œƒ¨\$#ã¬ZÇ#˜òÿ\rt-šÀÌ;j*²oE\0PJ2&ãì:5KS¶£Xòû7hˆ¡pHÖÁ0Œ RÌ48\0SfæR¨ûè&Gn¢E^ŠQÛÆŠ¤£rÒ2W¢²>µ/ÀPÜ°Qb@t&‰¡Ğ¦)C€à>p¶;]ƒ°ºZÉšü#mª†=È`@*|½¦£’aG®Pòã|”ß—ğ† ŒÃHÎ–Œ¸F„³)Ahà@5ÂN©l†?×¼j cÙôa–ÙcÚGƒu;ŒdøØ™&‰¢6N+bÜ Ã2G©Â29Ér’>èt%0Ñ	³T…¶ÃHÓGª<2\nòƒ>¡³ŸHâÉJéî|œÜ)rÍ’f>\".RYjA”}TÆfN|lÏ5i¥-¾®˜k1*!®”šûç±:#pë\rÛE/µÒ[u\"ínIëš4îÔ@Ë¼¨U&ø–'	‡±ğ`Pª6!|¶ šïŒw°)L;|ì-ä.È@ Œª\\¯nH¢Õ}¡ãBö3¡Ğš˜t…ã¿´#^0];Œá{¡Op …á}\r#üŒØªk±ÎÖá‹ak¾Ã§ğÜ‡Ğ»sªY¨(@İÌ¡‡5åõ ZŠš:-DÅ4¿˜s¬DÖ‚J5Ï0<àÊô“ÔzÏaíw¸ñLhr{áÉğ¾0ÊÀnH /à¨qÜ§\0ŞOùe§4Æä~Dƒ‚‘!Ø¯DrR_Ù\r~g!#³ÅLtOè¤Vƒ-^‘C™±mÈG—â`paÍ0îù#ÆÖÀM\\€¨ºFSEO±eE=”,Ì¢<y€î‰Ò“NÓ1S§Ğ&–§(MÙÀuH €(€ YáGÍ•‚†\nN<xÄÌ£¦Ò\nñ)/L2‡•¨5\"§4çÊY\$°pNìä4Ë‚`Db`¤–	 ˜Ÿc¨kğVP¨|“ƒ3›K´>f:\"ÎóÊ)§I2Ä0ÛÏäÅE‡‰¤ô€È™^\$a±I‹d.îW„Ë³53‚\0†ÂF¨\r\$ô¼\0L+È};èsdÔ>ÓÌ7;£MA0‚Ìš‘úCi£tf,š#®«ËT¥3`€%—ÔÇ‹‘!ìl%–~(¥,…€ì\nJ8¤Êe b\\ƒ”a\r†šÓt€©ÉK†Œ¤°—I@Â -^´p¯Qê\0(‰á?ËÔ‡²sfé9¥\$9ŒĞÜÄXšP‹å¨Á“¹ÊJB³ò‘hõøÂÅ	€S\n!1¢ÖÔ60T\n\0éÕ;6<ze‘ˆ.Iœ“ ÓœV0Feö;)Zµ\rD6P¿¸ÀÒtæù8%öLš†Uî~ÎÍ¢³A,7¾×D•Q8³†8KYkÁ€T–ÊÒÛH‚q\r€¬ñ=WgXƒ•*_ Å?DdÒ!–;E€³Í²B¤úôØ“¦’ªò'F–eÂ^>Oô²µE¨ÙBò¨ª&imÔR>aÃ,ü'¨:ÊÃ2q²6‰å	||\r¢9%éxÿæÆm.q¨;'ú±åb¤C“‹+Òº›U‘W\r)ÃTà(é™3HW	ôvó5R©/	»^%¨ç•Ç`PpÊ¡éı\nµ¤ş£ı\\T¦ªI Ã¿‘\0PR¼æíâ<iCiLyÖ›§mÄZb‰ƒ .”I@Á ÂçOXs'äp9DFù ô zaÑê½w²öŞì)…p´7ò<Òºw¥©ÔvŸÀ.";break;case"fr":$f="%ÌÂ˜(’m8Îg3IˆØeæ˜A¼ät2œ„ñ˜Òc4c\"àQ0Â :M&Èá´ÂxŠc†C)Î;ÆfÓS¤F %9¤„È„zA\"O“qĞäo:Œ0ã,X\nFC1 Ôl7AECÉÂj :%f“™†0u9˜h¨ÁÌZv¨MŒq™M0PeˆğcqŒäe0œçç:N+·MæéôŞRŒŒ5M´Çj;g* ¨±¤ÏL™ˆ'SÔİ”Õ\$„ÓyÓŒáyÌ=ÇW­Ê³‡3©²Rt’¯\"pœÂv2„LnÎd§šN“hMÀ@m2)Ñ€@j¨F¯~-…N\$\"›°úsŸãñ9³3ÓNÔ7¬Ã8Û-L˜ü?O\n–77eKzé©éT7@ïÊú<o›ŒÃ0Â½®)0Ü3À Pªµ\r‹cr\"L;¼¦?£t\0Ñ¤Á\0ÄÄ¢	¢\"ÉÍl× ƒ¨à„¢î›ª‡¦ÃhŞí²®¢ÑŠÃ(ê•¡î’Òµ±HĞØµÑÊÎ2ÄÉA€¡¨©ÂÌ¦«F'\rãhÄÃ ¢\"ÜˆACD¤ÃBĞ0˜es^‚ˆM@Ó:B“†Ä PÎN,âœ‘.DL	=ÄÑBÙ;®\0ShÇRsÕ-3ÒšÛ£ìŒÕ•2Ô£UL¢Ì9%Ízš2S‚¢2863Ğè£ @7³ïbØöK¶ÒJ`Pİ¨^v¸cDŒ©3o.#°ÒšZLtˆŒXær±JBJŠ;UÀ‚ÏÌ€P§_Û°,dÜ¥ÊdÄ0Ãb@	¢ht)Š`PÔ5ãhÚ‹c\$0‹­T:œ3DwGÖ\$’:¾¡\0x0¨ó:.¦\01hÂ<‡xÂ\$™J!²hôHÃæ9œÌ›)™úH\"8pû@Ì(8ë–^‚5¤¢ÙüÙ1!Œ›wÍÈ+Œ¸¹Y|U.¡\0Ğ‹&ÑHÎ×¤‚š2èSº!Sµé„*)ãƒ+¸L\"lÎ\$\n„|3(^^è×Ï5§]Œ:XÉ€&Ö`Ú¦-*¨º\rû´u¼nS.ù±»ÿ¶<#Êæ*‡ˆñ“5ÇÀªHÕ„:ñC„\nÇ?ÃZl3j9˜3„ÉÖ#<b†&ˆÖG±0ã0z\rà9‡Ax^;ûpÃá©‰,3…ã(ÜŒŒb„A÷Ê721{š^É®ªÊL…Ñpd©Â/÷«_¯MnÃº`êšúKI¦\$Å2ÄØı©?O,4<×ôC£Óz¯]ì½·ˆ~^ûá|iª&GĞÉH8pX¥aŞFÉ8mRM4ÂGŒ¢:#†7‚ ¬Õ¬9)Iı&ŒZqÖ&Æ(…·¢HÏ\nèˆæL‚’N˜ái4L°8!QÁ8Ãl7ƒ>gaĞq(ïÆ#ÀMŸZ='\\ˆ9„È|â~h,§:SÜ¼b]'Äî<¦6¬€H\n	!‘`è\0((æ™¢ØBCq\r=ä”“Çh¨âCÁÓ‘Çqv®(ViÎÌå)'2ÂÒiPi£UŠ¹T5b¯›{œ4D\$«Ğós:…íÔ&R[•O6‚\0â”åpD¨¾&¾xá„eq&(’@Şæ°C\naH# ²¤ŠI¡úJ)L–8Hx^\nZ(¤X6œRTaàBÓNQ—	ê[º!í™±R¨Œäêˆ“¶#PÏZ{W\na¤?Ô~sÒ•¯ŠxFƒÖº<ÏŒK š¦MpÉ²Ò2x•‡á„3š0ˆ\$€P	áL*†ê<Ó‡¤Ôı(¡R);„\r„œ¾ÎÙÆã‘g*º‡À”Iç:{2­%ÅÌi:ÎM1p–èêD…’rÎí-¨7½³10j	\n2Ää0¢1ÁRE ¨ô”×B[\"‹œÌRZNu¢¨rm‡ä’ÆS\nÖRQ â\$5Û •Wë\0Ù!S#ÅL	6Äz„Ù«g\nl6— Ø\"Ie›…–t“Ù;B’­i¡+-c[%„Mõ«áT8Sƒ`+h¦g¸CˆF¤Y;Çu-Cjª	%,åºG\0ß,í#ŒˆşĞbve¨TÀ´[ŒÙ’µ³ñ¢ÁÈéwoÙ¦Pk¦Ü+C}Hº>—„V&•C*ƒHzôGŒf¦Gõ=uÕ‚úTÃ]÷†Â’¼V\$â‚â‰ÒÅJ\"¦k|:R<Ñ#XœÌS**L“ÜOOáÊš‘¨T×‘Ôqc©ù–ÓU„‚…4W&LŒ1PŸ*1Ä8Å¸Ìªsr³/€e¿D‚¯{¾S%QÌ§ĞØdÑXx°…ÆÈãs†Wà˜­”³›XğpL¿DÔÕ=Ã^F’•°‰²œàÆò16”ÙGsN#œKu))PS4à ¤zIê=g°ŞÖv{ØÒõDÈ°d„áÌcxàDÃs¼VŠÅ÷‚à";break;case"gl":$f="%ÌÂ˜(œo7j‘ÀŞs4˜†Q¤Û9'!¼@f4˜ÍSIÈŞ.Ä£i…†±XjÄZ<dŠH\$RI44Êr6šN†“\$z œ§2¢U:‘ÉcÆè@€Ë59²\0(`1ÆƒQ°Üp9\r0ã Ë 7Q!ğÓy²<u9cf“x(‹Y™Á¦s¬˜~\n\$›Œg#)„ç¥Ê	1s|dÂc4°ÖpšMBysœÍ‘¤ÙB0™2Œ©¦jn0› ÆSvİ£•ÌÌFı]øÉ¨9b\rØó”—gµa®¡8ËÉ²5E”Aá5«iÃŠvÓUïXÙ„A—:^´ç¨İZ³Ş:n·’‚<oUÁöœ½ø,KVßÆÔPQôù<¨ Òá§ï»û\rÊÓú÷/Úú!2£’6	0B¨ç	Š£pÖªèJ~“I@ˆ0£CŠ(£*Úªˆ ê8#ˆàÇc»*2%ƒ*Œ#´~¼\"ƒ«nï5Kj®­8lË6¶²)J>ª)èğï;JÊ¶ƒ9#j~‡Q:4«C+mKPö¾ŠC*p(ß/ƒ‘†VÃ+òÊÃ+ÌŞhDòÇMÍ•\n7£4àŠ	ú[Fiø@1¡k¾ ò¬‰ËĞJúŒ\0Ä<«@MISUpˆJ Ü¨^u¸c6/'\"j„),|®”¾M¤&&Oj ŒlÃ5YŠc*'h¯ƒ‚0Œó8\$	Ğš&‡B˜¦ƒ ^6¡x¶0ŞºZ¶»0„<­Pé0)a\0Ú¼F!àÂ¥‡ÌcÂ9!0k¾9BCÈxŒ!òQ`”Z@Â1˜~\"¥-°9\r0Ú\$È-±ı•‘ãˆê9~Ém¢ „iD*ó ®s‚é%qu/L¦¶šQjNÂ‹HQ­8Ç¢œÂ2¥¨ƒR¹Àreö¶©4Å³ab+¶ˆÒnŠ*”óh¥TªÜ:ßap\"i”FŸë•¨ŠjÎÕ§­j:èÃ¯„\n=±Õ{6Ğ‡Çº(ß\";ì’ÍçhJší+KG-£4†ˆ6¡\0‚2lÉşµ«\"	Â9L¬`Ì„C@è:˜t…ã¿d5<ìJÊŒá{h¨­‚†„á}ŞÅ¶5‰PHëL¼N‘'\rÚÒ1 HHã/JµKvú7ÈJwCó\rŠâ±K_Å__ ÑÓuWY×vïÚs~wĞÜîƒÂLeïü0C&zxäuµöQYJ7BIJ—b|Pk]Ka¶¼õ¦ÜÃcEÊ=2šCLjI(\n„Œï¨å²¹¦\rŠ˜Š~åŠr;á˜­4w–¥C˜sÁÖ—`ÎÇ¡ˆt\r®!´¼¥Ìa8A!¤Ø³URaV€,¹èd,‘ÂI‹P	èĞ \rèÔÔ˜à@\n\nX)<Ç¤P”†BC‰y\"d¡E©B†æWÛã\r'`‰†xtXê/\rèÄØ—`îœ£äQ6†`0²Ş^ù|*À½Ã½ô\$øCdr@aØ™šr‹¡Y?@E´ï4n¥ZÜL@A¸86‚Ñò†¡*/àÒİT4–J\\0¤HTÃ[caL)f·)¦\$à€\"†éH+Qc`{„xĞ\"ƒ@G¡Âÿ\r‰¼”Pğö\n!Ş*iu/ÀÒhMÒ¾2‡UI\"äÌA>#™ £ùŠ¨JÓ^“iMh¨NÒQç'Å\ri8È¤RHó›vÅY­2|H’Å\nÌ×›ÔÄ\\Â€O\naP·4Ø©:?h¹ö'ÒyëT›Ì•æÏø4âMùŒ…ÁÙ\$7BÅÌ)—z	dºFJdì©\0¦BcÚ¥,(–â`&¹ŞÁR4 #(e µ\"„xâ4#+9M;fpæ>-f˜×I8\n	¡¼ß“ª×XÔıoTFI'¡Zâü	TIEUÖê\0GƒfSM=µX[Ôáİ‹”ññ<†ÀVÍ8cAˆÆ@Êx]ˆäKäØ×•û9!!Ä:‘HFyTÅu\$-=Æ‚\0ª0-¡U’	ÆEjùÏ5j©RÛèÜE£d)Šä(†ÈôEzö['”ô¤1\\í’¾8&…JÒ¶¸“Œã3'#€ H¡Ş4áœ0‡ª,Ro5\r!6‰W@ÒN\nZ«•¼å{á22Y}&ÓĞiü\nq7\0£’s‰µàEÑM³².D×Ø\n·¦˜0ÜD§8È*õQ·ÉBe´_ƒÄl80Ê¨õxîÑH¦tPÜŠvc‹MÖATÒD#`";break;case"he":$f="%ÌÂ˜)®”k¨šéÆºA®ªAÚêvºU®‘k©b*ºm®©…ÁàÉ(«]'ˆ§¢mu]2×•C!É˜Œ2\n™AÇB)Ì…„E\"ÑˆÔ6\\×%b1I|½:\n†Ìh5\rÇ˜4¶-\$šL#ğû‚@£'b0åT#LIRãğQ\$Üc9L'3,ğæ.´N(Ñ	\\aMGµX£kŒ1U•Pšê‰tf×OÄn1‰Ì[	ÉÉSVˆôqC–£ælql¦{Q/ÕCQD#) ‡g¶–+n^UºÂ¤ñíVnB”ˆ¥¢°iÿ'Ì±k\"1hDªA³àèbÚ;9QÓ‰ušı‰´v‘GÓêìJŒƒ]/è)\$Q)·¬\n*›fãyÜÜ£ä7L\0ˆ0ƒÄ½¯£ Ş2aø: ƒ¨à8@ à½c¼20¼DŠ\$Cè²:ËzŒ÷’iJ\$¶¢Ékä§/È3\$¢)j:Î±FMvŞÇÏÀ!D£Ú÷µ¦»”×DIz8‡2„ÊÛ¡éÜ¬KŒ¢ù¡McÆè1/ì«¯BÅ#EïtE\$³èBÚ÷£úBÃ¥I|a*šï”¶L\"1dÈŒ‘RÛ¸ˆ2dˆZtXjƒ=ï‘S)¨å:Œ#Ää\\É)ëšÌ-	#8ƒ>RêC&#B!Lx\$Bhš\nb˜-5Èò.…£hÚŒƒ%/5NHäî,éè@6£œ()è|¼ƒ¨äÿŒ6Ä0!à^0‡Ì¡icxÜ3\r#=¬2Û×ü\r/ú{\r#œ6/¶T	\0\r×½óßvPÊ<ƒ(Ü972ÿJ,ÎŒª9ƒbÑ'HI	!M¤¨±>è2“jÉc>\n;<ÉÅÉzN°´ÙÎà4³4L#ÉJ…‹[Ï‹¼élMSE´\"“²Xôİ5N)+„0èb^÷ÍºOD«ÆT­áhÂ2\r·Œ›IèŠ@ÉSV€@4/#0z\r è8aĞ^ûˆ]«ë#t@ƒ8_ƒğuú:a#p^É\nÛ\$“·h|ƒ-¬K”•5M¬òĞ&Ùª®Í3†Hõ3*^–¥Z:Ä‡%²Ò\"ù\$FÅ²»6Ñµm›vá¹nšÔ¼ï|‚àüiM(£U;¥¥ˆ§uŒš¢O“·›0TLÇSÉ3+5(ksÉ¡I}5(èôLŒ%©6*\rèÂ1ğ0@;#`ØKèá\0ŒĞ Ú2Ğ#ÌÁC’a0Ì_˜l\ráx¾ğÒ@ ‚õ{?°@^ãp„60²Œl‹ÄâŒèc… \n (\0POAIÉz¬mr†æìûˆg‚A¼\0äC³ñ¡şCÔ2„Wª\rèR@ îÿ\nzåÄ°´74›ğ×\$„YO”÷Ğ€KìI\$\rÀEààœgÕ\r!Ä<”w\r¤1Á²ÛKô/±¤1†šQ1%ˆ‡ÔaY\\N¹fd{ŒÑ%>B†,”5Bh!%Hõ9‚@IÎÕ4\$`L‚äTF#Ú!“½òØKKÌg±íHóŞa%Acz`´ µ‡lnØ¡Ø%äx‹=òKbÛ›=ìÌÌC\$Ğ^°¢\"2°’½ù ÉÈÙc!‚]ª…\0Â¤¦\"DP¸š©^`ÍÜ×!&ì‹3‰VõŒsĞ7sR,pZOH4‹-¦ÅÊ4draŒCUÁR’R%aâyå‘¦NÇKDR%éğó’£Ş¥jA¢Œğó1º2×y\"Ç(Õ†HZ`\ngÎM=ĞDXÕ)Ë¥f‡@äüÁ[Ì=ä¼Ã˜BG“‹Ì!I\$ìT6*ôˆ3Éœd•=²^p%Ë&\$¨¶6`Z)R=SÖ§ÈJdªJ;7g›M™MªIÚKä±ëI×&vËD©Kjv£WNÔÑQL«fî£L7´F	¤d0’<ˆ•F;…Ä…©yÑ£+­ˆÎFbâE¬mi9ÂLÔ\$‚KG”É»Næ,úVHòL#•‚¹<|„á§„6ÙHE äÚ£Õ\$óIVJ-òhµ¨ÏÓ·8";break;case"hi":$f="%ÌÂ‘pàR¡à©X*\n\n¡AUpUô¤YAˆX*ª\n²„\"áñ¨b‘aTBñ¥tªA ²Ù4!RÂ‘ÜO_ÂàªIœÂQ@Ìèq¨š’*¤‹Æ`¨j:\n°	Nd(ÒæˆO)´úŒ²Œ‚§!å\"š5)RW”¨	|å`RÎÅ‘*š?RÊTª˜DyKR«!\nØDµJ¯\"c°U|†\n—ªÉÔ³u%Ãg\$êI-=a<†fò‘HÕQHªAÔ´•%Â‚¤[M ©ª.í_†ÁD“qŒäe0œÌµ“˜ºÅ‹GèÚøşYH¡éês‹z.ıK`RC¯3Ìu±e¨ë\"#Iùr“·÷®ôêU­»Üì’®öIáBè#ĞR¤E#ÔÉ¿Ò† >+Šù¼IÚ§5)\\§—Ò/ ¯b‚½ê“Hºhó®ö“ïòjÚ¥Oòæé°M¢hÏğŠå\n+®Æû;ÈºÕ¼)ãî¹HP4J*íŠ\r «ój-OÓ4@#M-H”‰!¢ä—& ©‹1³è|H\"±ì,·óL¼D'ŠñHö?Dz1Ó¸§20c+2ñs50§‰Œ†ÎĞ!H(RjÙÄ-ìÈ“âÅH‘ó ¥«ÿK;\n©}'‰£4Î'2š/GŒÌ«SmIC5ŠÒ5?Dô³ïL(+sXK4Û'!5úUh+\$üĞğI/E@©H±º/‚”R­\$†¶-Îòñ&OÄ·K2³ìÿ[¶õŒr‚É‰5Xš2{94OâØÌXIãŞ²VrºxÁ\"óåMK³„ªyGµoÒhÛÇ4ŸQÊ(]j:Ó:KWÖöŒa‰'‘UHRay®M–œÎÛ•µ-X©ô<°ê”¹sù˜ÖwnD×a)˜ŒˆÖn“àN‰£@š:©äùOá b„°§—\\0]å\$¶ıM„ÑZ.¥#¹À÷í[c4±OlM¹“æšÛ;LİEøZŸ<7jBºÌ± \$	Ğš&‡B˜¦cÏ\$<‹¡hÚ6…£ ÉEgLYe|¡NaàÂ­È-€J£D…O\rEí¼ù•à^0‡ÖÆG‘7ÔôÛÏÏMÔt–›Î)¥ãù3–·pæYS˜°ôv	@e#nÙ\"ñ.ééb5_«ŸzWHÎXvËqÁı—¯ót;…½Òö-6Ù-ëoŸOã`è‰á®6H‘\"\nò“CVIİ'¿uNgK,!èRå¦'ØŞ6ŠMÂŠB~ö–|>å52Ó.ÎÍ|4oğƒÂ²ÑJT dhµùõÊupp¥KB´¨˜úÙËlê™Ôa‹c*an±œAH,ˆS“ï‰ş)µ€öX›#KM-U­\n2v‚ò5Ş;oÉF:`@8f ˆ4@è˜:à¼;ÇĞ\\C m\r!¸2‡ \\Ãgá”7ğÈÃpa¦HğDêOÙúv®İÜÆ\nW²¥?ôÓ=øºâÆ%Ş#·ÁI\"»c‰Ø¦›r°›ã„ğÈTJcè¦ÛÂúS2…Œ.–RßJé˜0àÎAÄ½¢¡Ğ/(z\\,Å`³Lwn\rØ!’5åméXˆİ,r‘Ú<G¨ù¤‚’DH©\"ÃÀt‘ÎJHĞDñ\n‚ß3†…œ7wÔ'Şt~Š.S³\".±åê]0È%•CEÒƒ…\"ƒÑEY»Äïß2›]¢å6çöç…\"Ó6Lİ`¯Ôˆ€Ólª†Kµ6>Ùª—a\$ß—QN:G`ÑËj2¥‰T§¬\0]ÉéYÔµ.¬F/DÒl&¥ ¹•ÉÁüRa@\$\n—FM1}iL SĞddTq¹«4vÚKºÚ ÆQC\$Ø’UD<¢ñ~e™7fãçŠyºJXLQ¬1GD¹×|º<d™Ì™.ÌuP¶ˆ¹p0ÆÒ‹YIV»Ñm.®k5Bœª²CŠF”ÿŠAGUa%3ÌŠ¸©²c2ãH¤à€!…0¤YÖ\$¦\ng…¦Ê¨Ü†RÜ™,Ò\nÚëÃö]!Á6óû¬j²ë&Ã8>mí‘İ]%±u™ÇÈ”&!ršs£)eUès›BŠ E€…Tl»¹\nnªF.©šA5´RExbÌ%ÍvÂ·5tM(.êQ-ÕDa’î›K’__‡ä\\ ÜÚ{0!U…\0Â£ØsªâgS‚‰-É¡i)ğÎ#Rñ›ãÉyn¾™QG-›á~H‚ÚùßUİ/×D«øŠú£0¦BaLÈĞ(Œ„`©W©ñf?Fó^'¸ŠYÂ	™,~Ø û0rôÁ'Ş%f»@g:ÔUEfX™ëH€-\"ñ'¥<F’•'NÓE;[\\“Ÿn`SŒ%h&E¡-¥ÏyÁ>\nçªöJ|dÑøÕs=HatF2®f@”öÅ‘´ŒÚw=W¶„òÑOÇÕQVÃr¢ õZwR,ÄYÂßølv	4^:¶±Ñú,¡T*`Z*]¹ª‰ª/ûEšf{ƒ¥’‘:¼\r=(e±h^İ)*„F“=VĞÍ+O¢­Ã+ä,L[’LãSK7M¨¡ªe\"=ç>m†¼œo€ÕvN±øÖø]+g	Ál²	G|\$¶òØ)ø¥èĞ&ûğô?¬e[¤ŠGß[k¿Ç\"a×»Õk^5Èyb\rZ¡k…¯Ã®‘<^·3L6D›3‚ZâdŞ¤è„´)_d¤\"YÅ,(/ÉøG\r\nÓDšPŠ·hW4=ÚÅ÷@İ›ƒbu=Ê~“p¤w«´Û6Û˜ICS¹mózé>ë¿ë8¦Ü\r´óÔ÷Jöáõ8÷ş–ƒw…3@¤Ù0ØUT™·‚à";break;case"hu":$f="%ÌÂk\rBs7™S‘ŒN2›DC©ß3M‡FÚ6e7Dšj‹D!„ği‚¨M†“œ–Nl‚ªNFS €K5!J¥’e @nˆˆ\rŒ5IĞÊz4šåB\0PÀb2£a¸àr\n#F™èİ¥¬äQÅiÀês'£¡¾jbRà¢I¸Ç;²gÇ:ÚŠl’Æ£‘èâ¦jlÁŒ&è™¦7’•C¦Iá¦i¿McŠ°Ã*)¡†³-”éqÖ˜k£–C2™ÍQ©\rZt4O©hÉ97eE“yÔAc;`ÆñÀäi;e·:ØŸPêp2iÑ3DÒ&aÒ™eDÙ6áì7{„É­—W±ùæÃÉÓÄƒcø>O£æ]\rO@¼,­›j)Œ.ÈÜ3B¢:9)lr<£C°\$,‹2Ğ\n£pÖéì¢9\rã’T\"<OC\"Îã¦nøá§£‚È9ñhÈ•D*Î™A)P=,@5*päİµÏ èÂ¨Š42§*\nÔ Ãh\" È¢%\r##‡/¶E³£\n.R;®¸	A†YO#Œ¨ºxëÀMKî×MkØ±ÈÓ±f	HÔEˆHÒ˜8>€PŠ6Ã-`ô=”ÂdĞÔ‰©ŠpØ£\$“€MSU¨µm^1U€T8¸:`PHÁ i^† P–Ít©†^'k…hPCC÷/Å£HÌãe\r\rqj–PµâiŠXº,ƒ^%»DBÑ®jì×Îó¸t8ĞÛ1cÍì<‹¡pÚ¨Â9?Ãb/T&£jŞ‡ƒ\n~'h äÊ2iä9#ÈxŒ!òU„ábŞ7ÃHÎ:§x¾3'4^š=„¸£vZÁ'áašŒ£Âœ7cN>ÂBbhÿHÛjæ9×û7ŒÃ2f¤5\rPäè¶·\$3JCB Şê\r¬˜ò¹àê1Œmhæ3¬F3´˜X‰ZøÂ3Œ9NSW­\0ê§˜RÔ*6\$ËRo¢T*,q2jı[{	„Ğ!›xÇŸÍ(`A„¹éØÌ„C@è:˜t…ã¿T<š‘E£8^è¶d:gÃp^İ³ÄĞù65ì#XÂ6¤	â”2H6··lƒãMS‹Ä5bZÉ):±ü‚9UYÈà4§r6’\r÷AÑt7QÕıg\\†İgİg9Ş{÷xX’CyÅá\$çpIİ%¦°%ªCéH>&ƒ\"gòJr'¡¬7ÖÌîRi æl¦ÂtRymEä4¤*pIcw|s,b±N1ä* Ââ9l™´6 ŞÛ¤68Á ù›=¡¤0†Æ€\0c&©A)%Bè¥bÁ·7-}I£‚¤+©ºWF€H\n.Ÿ„\n	úMdD•1àÜR!“ÀGXì¨Fª‘Û\0&§™’0ØÀƒ¹›%CDµ2˜rU1 \$g„º¥çf i5\$Í6Xe‚4¹½ä…\rO(c\r‘:\$`¥LP%¾II,*dÁ¼2)… ŒHÃÓ.Æø0\\ZÇ\r‘U7°I\nœ[è`0£´®JÉi/&\$Í‚”ó†ƒcXo'±•€I @ˆêáUO 3œSTk :eEğ‡“šIG@‚=­SÈy‰Øq¦´†dXDœ“yuñ0Ê6è95GĞÃ›3B á\$\n<)…I6x k[³X|Cœ>Sµ–©[y’í|³¦â5’qx-,ß 	¾Ò%Ñ”;ì	Ö’4È\0S\n!0’ @vI F\n‘ÌÉª¢a\"Iì¡Ä^‚Æ¨aLj¦T‚ÙOã£Z)\$©Íb›úŞÁ!M®H0ÙC0ªUi®E0ëŸx•Õu®&\"ÂœèÁu‰¯5ªÆØk ‚tC\0®‹Ç!¡\$2A²…À¥ÔHfkéD•j¬Áƒ™=zÌ¤*…@ŒAÃ1rö¡	’¥^Bm_G… ÌY+`Ã&Wß³„À.!§!å\$Šb\$”²æ5ÆL‘CFiS*!6¤<€¨´Giq MaII\nÆS¸\n>ÁÈÖä>ˆR<Ó©×ÎúÅósÎÁâ[&ÈƒÎ³Te,!ƒ„Y‘jêº2ÎD¨&úšº‰Ú4F-[Ä(~Ï\n§ogíb¨±†%ƒ™ö&§äÒŞÏù@W49\\d[y¼†Fma“¦”°x¬òäİd€Ä@×xƒ^BÖnB¹4Ó-XÕ(ÅT©_ÓÜ@";break;case"id":$f="%ÌÂ˜(¨i2MbIÀÂtL¦ã9Ö(g0š#)ÈÖa9‹D#)ÌÂrÇcç1äÃ†M'£Iº>na&ÈÈ€Js!H¤‘é\0€é…Na2)Àb2£a¸àr\n%DÍ2Ã„LÏ7ADt&[\nšÁD“qŒäegÒQB½¯Æeòš\$°Èi6ÃÍ3y€ØiƒR!s£\rÃ6HŠqj<PS­šN|L'f1Iër\"É¼ê 4N×#q¼@p9NÆ“a”Ï%£k§IÒät4VñÆ-®K7eø÷¸Lâxn5b#qç)53ò¼ˆeìç™ÍŞã›_K«b)ê»\0¢A„àu‚¯R`Q-\n—‚Š³miŞpCxä‘ˆ{¸¡ƒ{pÖ¢›v8@H‚9c¼2\$Ohä÷ğœ\$Éâ4“8Ê5mğÜï®jT¢ÁÈJ4Æ\$K Î Ézò6;©Àî…D¬¸èØ²ÂjÑ+\r(üˆã(Ş‰Hl@Š¿ê¯CÌº^¿Bj@:rì¾…«£“7\"`PŠ7J(ÚN:A(È#¢Ä<¤ SZˆZsøjr@Ö7c{,¿¢){÷¹\"¨äé3”ì‚ ë,>º¶ƒHÑ=\rã»Ø\$Bhš\nb˜-5hò.…£hÚŒƒ%8Ã£MêÄŸ¢/‚ )ø|·Ä£’p½¤ğØòã|‘X‡EÃJ*·Ù–ræ&±h\"+l{kmÀA'q.UåË^£ÄTïÑK¤\n3NÃ6H`SJÓ¤ëŞ3Ì<ĞÉ#­]ì%Œ£`ê“ Ã{]3!-cJ9ŒÃ¬x6\rã:j9…Š0åˆŒ(Å»nÎHŠjé¡@æÅÃ“RïÇƒ^…Àh3R›%ƒ5Êø;\0‚2\r©ª;ŒwŠ„P·ŒÁèD4ƒ à9‡Ax^;ëru¢Í3…è˜^2QL„AöĞ°²£=³g²é(ÒÔ1êŞCšCôl…§˜òE/E:d'\nÂ÷0ğ8\r+|1¦±£F¡©jš¶±­kƒ¾½¢hÛå²l×mŞ4ía„\$»m«¨:nvÜ 4\róœ«FæÖä\$Ìè˜éLÃ½»ošY \r#2D*\rÂ1¨ôP@;¶‘â8×£¹ôÌ2Îc\ré¦b˜´)Œãxí»è8\n7’‚(@·{ĞÒ0‹ ‹tj3¥,_Ÿîâ€90/á‘Ş“\0POÁKö3Ïà‘-0ÜšcAvfÀÙClö\rz%‡	ØÇ»ØF¨ ÜxIƒCÇy%½n<2C˜²Ü'\0¹SŞ„‰ˆIÌ;œÆFš(gj„¥ó ÆO€exá½›€†ÂFE&à@Üi€bG<3‚¬XÓ2AÕj”ñ¡µ&QL\n(0®\0ÅÒşLÉ©„iMÖğI aäÓçC™vG–ğâI)È6´'8Øb)8lrD¾c„Cº@À€(ğ¦\n1'Œ(”’dÃª\"İ]”ìiÓ)•ÄÄ·†²@Ô8nZ«\\7r~i\$,G'í¢r\"èS\n!2/A`@lÌhF\n-­Ã¶„]´^’„‚‘ÒDšÓiãffĞ˜š“Şü“a±/“­ùr›ÃLç!¥^N©‹=LÉß<!¸‘2—u<ğr+©-IÂ•Ã`+“a¤1·œcC³ğ’äˆ»ŠÄS©\"ÓZ0\"˜ˆ·B¨TÀ´.bÛGˆ¥Nf„Ø’ÉüÍ	‰Ç@Dˆ#²WàDC+EW\r„¥Ú<ƒÈ\n:’Ä‹\0 ¤Âf(\nAÉ°õŸbÃ \r‚'„ë¤¹–›@\"ÔĞ‘ Ümw&³äÀß3ƒj.Jê¶Öòşei<Là*„£‚½‚›7¡­áš'fhˆ-6¯1ìT¶¦ßÒ^Î¢É(CDwS±İ¯U6#“ HìAú¬ €5 æ";break;case"it":$f="%ÌÂ˜(†a9Lfi”Üt7ˆ†S`€Ìi6Dãy¸A	:œÌf˜€¸L0Ä0ÓqÌÓL'9tÊ%‹F#L5@€Js!I‰1X¼f7eÇ3¡–M&FC1 Ôl7AECIÀÓ7‹›¤•ó!„Øli°ó((§\n:œãğQ\$Üc9fq©üš	…Ë\"š1Òs0˜£C”o„™Í&ë5´:bb‘™14ß†‹Âî²Ó,&Di©G3®R>i3˜dÙxñ Ã_¯œ!'iÖH@pÒˆ&|–C)yN´¬Èƒ2bÍì­Öc±‡¦lêÒD8éÓ&uëú’˜ÖLç¥ÃÈÄŞ°érëõs<Ix(Šl•äúÄÌ™ŸÀ\n¬Cì9.NBDí:Ô7¨HªÊç¯j:<Öƒ æ	˜æ;²\"M\0¥-jR‰¬èÓ˜‡%Èê¾¾®ª\"t¤©jh@Éef:¡¨H\"1¯ @È</{ú4-¢õ\nC*šã(Ş†ÈN°ŠÃ\"”\0µÉb„5C  ­Hs¼…IxÜ¶‰ƒ{\$8’e3­Hê7ÈŠ(2°CxŒ\0Ä<© Lù?P\nP—Hà¡xHÑÁŠÒ´C´æˆ1ô°Üå-¢|ºı\$ïÌ– Ì©‚İ;Ô¬ 0¢i¤ô\$	Ğš&‡B˜¦ƒ ^6¡x¶<ØÈº\nÌ;ÀüCÔFÆµ	ø@6¤# @)ø|¼‹Š<0Ûp Â<‡xÂ\$Ö¥¬!£¨»ü¼Ü7|9„Ş“Œ;Å¹¶r#{¿Íà7Â«ŠùxDélÌ4¤Ïâ˜¶½Ò°¦2¬J;3ÕÉ69Í+ìÓ«k”*¯ËcyxÃ0äµ[0N\"\r“S& ÊòØ¥M{u:!*=A®úJŠ˜ò¼çLYq’äã¦R—f¹l×˜iĞËš0Ù²äê¹–yŸaNæŒ£0Í%[1ë¶¤5ƒÆÕÁŠÛl7\rw€ÌÈ=ipÜ3„ÉÌã–`î`È˜åi¥#Bò3¡Ñ¥˜t…ã¿\$U›ãJ28^‡ã\";f#¡xDsÌ£½»İ·%NšLÈÒEV-C`0Ì(z„iš¨İ9Î¸JS¦’äúİ2i2£eJ…Œˆ]Â6ÃÄñc§Çò<Ÿ*ÉòüÈ^’scÂ×İ¬\$£‚2Ág=Mß³¡¶|”Ï¢3j>‡pCg~ÒÍóœIİq«`äİ:‘Ä`yÎBü1…5ß%RF˜Êb…50êc“\0\"'±§/#ª~óg#Å…“B4^)\rÉ”³5ĞŠÂØ2*BFü¼wljMZ\"&è•>8@P Õ\n¡sFA?\$¨9¡bÄbŒá'0‰Š>B4ïĞ¹x†ğŒ™(ŞI¿†¬é2ó\n¡aø†p|4‘2MÌ:25¤F&†JFÎ‰¿CA½á2HD7á¼÷†PÄt 9¥#Oèí»CI‚ o\rd80¦‚4k…®yç¤“&o	LŒ0 4œÃ¤ĞXC0%yç…‚ƒ\nu(á¢œ„XÃá™¶hò€Á­–XJe¥)„¤÷ÂHF›Ó–paÁ9•ùC«‚‘nüáŸ© Ğ‹\"Ì€\0Â¤¥Å¬‘)´¾å¡g2ü•-ƒ¼vZ«€]EÆ;:Õæ‚„Í‘¦uÀÎ–á0„h)…˜s¨=FA*D(R¼_HiFd92ÙÌE`Dï­1'cğßkm>éâœ#èq©\r¤„<7¬ãÌzqC5æ°÷RDŒYÑ>O©İ<‚àL0u¦¦\rÀS†INŠMvÄ˜#y*{Ã`+gà1†¸†zÈô2hà†Ó>M	e¨6ÀH¯a=_¬ €*…@ŒAÁ&PfD”’´-DÑí>®fÂAWh9ğîFøòGñ kçXÏn®KøÀ)\$¿¥Rj*õ4Hx‘SğÜ}jvñ‰ÁÙšÀíÎBœ)„F­’üB-^bêsâ\rl\rŸUUT#¼LM.TÎßRöŸk¢‚¹ğ¶°û™®0Ceªª×s2]_LFRaÈ…Çˆya‘)[3Eœë+˜}ŸĞbH\0";break;case"ja":$f="%ÌÂ:\$\nq Ò®4†¤„ªá‰(bŠƒ„¥á*ØJò‰q Tòl…}!MÃn4æN ªI*ADq\$Ö]HUâ)Ì„ ÈĞ)™dº†Ïçt'*µ0åN*\$1¤)AJå ¡`(`1ÆƒQ°Üp9„ ÔÑØbÚ:åW&œëåK•<‹^î…\n2·&Ó(·zñ>\n\$›Œg#)„æe¢Å×„âu@«¢±xÌnèƒ Qt\"…ÊŠ\\Òq4Ü\nqCiÒÑ‚†\"±ùVÑÎ·T:Shiz1~åB©AXMöû€‚áWe[†W¡„îPqäî¦‚I9“kG2Ya³A\"ÜÊ…K¥2ŞÈzıšõ‚Ä—…ù:ª“\0TªÌ9Så±3“P41¤yĞ_—­yA	AÄ¹\$#†L…Ñ+D‘O±Hé•ĞUĞ1z_œä¡QiÌLÉ	T†+DRº\$M›ºAë¡_¡*cÆ†6-RHÊI^Óµ%YÊW—Ç)~NCéDí8‹h¢©B“Hc|E¤%qÌEµÅájs,^¢g\$f”i@G¤%B…Ír;:©4aÌK¢Ä´«+\nÑ®+ÊbÆs¥‚ªK¥ä¹Js)sÚCÓç)P!/Âr„¸DT¸¤¤0A‘´”W¯Ì\\!‘ù‡WIU^¡ƒ\$T–¸®ry»<g) F®2¡Ør¼Ô.(á÷Õäf—–Ä s“S\$AÜW\"us½¥ĞSP„X†rÂ&s9Îƒ¤ŒŠAW'DB@Êñ1ÌxPH…á gŠ†©yHNå¡DåIueÙÌBöLùoáUQmŒ	JÏÄàt%ÁÌEF'9.«3QÔ©}JUD0t‚ âØó§\"è^6¡xÈ2.2M¤_SB„*(|t¤mT=¹(Ä‡xÂKÄ-î@¿-ûƒÌáH9Í%rˆïjCÑ°lGAPV¯ÖÛ·Ì\$\r@ĞÄ\r,zóĞÖ5Í‚BĞ)ŠC8ç:ÎóÉÑ=³3J¼O¨]MşN-|¢Ğ+”CÌM¥*×\0PØ:VM„…ÈJ‘fs‘E±ÌBÎ*—İ'­;ğñÜ(eÈ‹VÓ\\~4â™–4á/N=íRg6ùä{2SD	¤œ¥ó}-ÄiĞT”Î§Çò<Ÿt¼±£{\$%\\©s†Wac!6†ÜC’q\$\"QU\n”j¥ÛuRÆä@¶±nÓZd4%±9Úa€ƒ(è\"\rĞ:\0æx/ğì €.\ráÈ3‚ğÊš°o\rÁ„:˜”Á>;Ä„œ—JÉfArMWé¡&|û	T³1-{‹È„Æ.9Ï[ûvîIÜ¤q<•RZSw‹xò•'#!÷Œé¹Í£æï‰	P²Xa¡¤6‡êCèÃ”Bˆ‘\$Pğ\"8s‰Ñ 6(J+Œ££_°SÈ!­Œq–B¼÷<¤jh\"XQ>B'İËìzm[È¼ ã¯v.j\\@dH_ã¸Q\$¹kä< Á<Z+õb’!Ì+TÎ_+ÅïKgš*¨æ«ˆI'õ3”É´tc•;'„ôŸ“v;ÿ\0  ƒBN“ÑÒÏs\"\n)Ä2b+us6\r ƒ+…@ß\"ÆNá.â\$\\Ÿ4† Äé§€«1áJ„\n†#”G°Ú( ¦\rLÅŒr#™¿8Vä\\±„¥>Ój)My)‚ß:Á¶–ä|#îm‹¡Î&gÀŒZÆ€“ÔJ`šå¹¢H®„Ò¬ù„pŸ¢mÈ§p 5_¦Gı#©À‚S\nA+¸Úl|j&Â\nEz+õ.…\0éœíœ„Òr“P¼—²ûK]<øDÀ™BC\nß]â&_6‘lv`)^„dµËNÑDÚµšÍÿ@¨&ù5&‰YÄ®²ä)HıQ@-´a§~Ú&Õ¤°D»50ªÄ?ØêK‚€O\naP„µ¢=_5–\$•ô¾¹ã^®±IEWˆ\"ƒ`ƒQ=Î¼Ñê\\3ˆ/\$L™”«¸ãíSµ—<Àİ«¸*‚˜Q	–\\¨®S@•İF\n@s‹SV%‰²¢‰e¾jmSÑ[çàÅÛ'½H)r±1Òi—ö ¦ä}¾ˆ¡ «E\"_ş'Å5,ô£Ø{±r\n‘Mt9œd¿±«:8ægc¶Á±ş_ç­zäKFK‚À\r€®Û;¹Š ˜h§¢\0ˆ„œ«|ğHÅ:½™ãxz¨TÀ´”1ÎÄ¹ıŒ?HYfNÆ	ñCÌO“8gLò¼]ÊÖÈAÊ9‡ØF‘A¢åâ£Z\$å°»‰–òè’ËôÜø<Ü•—îXO¨Î`X{WˆíiN¨ifê¤«Æ\$Œ ¨â‘‘¢ò8 EøèÄ{\\SQZ\\n5×¼˜àÙ¨!uµ˜O3lèaY»9gb{_,•¼GúçHUmœğyİ;ÛÔ‰¬êåşÆØê‘½¦àİ¦a_\"¡N'L\0";break;case"ka":$f="%ÌÂ˜)ÂƒRAÒtÄ5B êŠƒ† ÔPt¬2'KÂ¢ª:R>ƒ äÈ5-%A¡(Ä:<ƒPÅSsE,I5AÎâÓdN˜ŠËĞiØ=	  ˆ§2Æi?•ÈcXM­Í\"–)ô–‘ƒÓv‰ÄÄ@\nFC1 Ôl7fT+U	]M²J¬ôHÌæË^¿à©x8´É94¡\$ã“{]&?MÆ3‘”Âs2Ôuiz3`ÂÈìÒÌ*Zƒ¥%\"±xÜ¢o¯­Jiğt”ÒµTAèÈ=D+I?‹« êy¼ı12¶EéQ~\r…ªƒâuúx†.Òue}·2TÕğØ?¦½¯rµİÚö¿¿¤‹¦â¾NÖSšŠ·¯zhÄ¬	ZØÔ¸H:»±Ûë\0'i.ğµo‚.Ä·I“ÄƒÂÎË[2H³öÖ¸¯3§Ğ‚½\0µå[W-o:\rpé\$H<C'ñÂ€Ãor.Ÿ©ĞÔ+Ãé“äê(„‚d•ÂÉ’.×½É\\3ºğâÒÆ)›VòD+ëŒè&œ¼ëJi¤¿01ÚVµnÛŠ“Ì\nÔÔ4ó-¨cø+(ªVÜ³Š@úOPS­-P2D©´Šê.î:Î2Ö¶L\n°Æ-KaBÒ<ˆÊº×ÑÈÂeµäê+¢ªÔÒ Èbd”¥q„™-UL¹CÔÈ:zœEõ“xÕÌLSk9=c7uûk\\ØJù6ZO«V¶V¶Œëk1q3Ÿ3¥iúRÊ¦NsÚ¥jtÏ.vd©#Ù0ºhÔ\"²´øĞÕ0J2\"®ŠnÚÛä/^Û’pH…Á g„†)”]ŞòròUèÍ³‚PİjâÈ›gXjú¯SŞi¤RâM‚X¼¸ª÷>WêWå’O*É2DM”§ä¸tƒ¾Yö€¦ä—h]^BbŞ†A¾Zh»›YYÆZ*a\0Ú:c @*˜|Îƒ¨ä7È0CÂ<‡xÂ&:æ¼!ãpÌ4ŒûË¶íí@4ì”‚eDmıÀoÉ¡9zÑmY/Ä½ü‡	I\$ôe¯1°èÌœ6ƒ– ±|\$ŒÜiº/Ç¦‰M£¥É¥CÌct0£ğoSËr øz/¢ü[+\$å|âŠÆ™<Ïİ·ÉÕ§Zµ}K‹İ•bmÕóº5RÊ½šqÚ*ù8jÓªLO,„ùfi¿Q°¨=Ğõ£6a8Ò]Wj‰NªñR2ÄÄ(ÏC mp”9(R€úÎA'xÈÅ?„~¯	I…ˆ¨Ë3§ÒbŸYl(G°–¸AÀô€è€s@¼‡x\\ƒ€aÈğäÁxe\rÀ¼27PÂKuàˆ8uªZ‘5ğq>øÜ)«\\Èıå-ÖbjŞäAÙe*§Ì·HËà\$âB	²&`TÌbëÈ¯/GRû“âÈLÏiÍFŸQ4iÑ;D€n™\"ŒÉbÈÀÖÃ „P’BˆU!pw†È7@Hk\ráÌ;¡à:C çaØ\"kÒ#“tü”tOŠ¥e>?ÓEVS§G1Põœ4BP\"ˆgPUn¡3ĞãJ*sKÁé'ÔøÛìvñ_¸.Ê\$R\$ñ2.MnèÔÄUX¦%úªC‰_ZK\$ïÕ÷ÀÉ¨»»ª\$Ş9ºùh Ñ¡‘5bIò•ç(²œ³L-ÁT œ©ôZŠ½f'æÈ³“˜\n5±òY6‰»T¨X‚¦\nM\"ÛY¹°²•,‹t×›U\\‡ù­L'­C†uU£¿0ÁW¤6–´´ÌZ³r4S%½Cğƒz„ëŠ3Q3ã¡Ë! 8JtêÏ+(–ĞŒ°õm,×	ŠèÔÿ½:‰7Í[õSäÈáÓª·ÕPC\naH#@j ÀåEF+“­\"’Àß‰«´|F¢s%(YúvX|‰\\ç–k2˜9yƒOİcIú«ø¦ıKÃ;dTûJÕá£%€eKr,¸÷¨ƒ…y…ªÁË2´¯éŠ5U§\n¥Ú*>€l}@‚\0@)+I»ò–0ÄÙ§_÷>	‚‚âbxS\n„TŒŸ»N¬îAÎê™¹^J‡ˆÉî:xÓhÖ*w¶Š9šø²Nn…Òº‹ŞëÎ7\\MâÙJ½4šÌèáHÓò4F‘şİúæi¦˜’ÌÚØğF“ãÁP(@c~ªåœñ4zÑ)g„ošÕ[tá€=—eT]d\\w¦‘{UÅñA×dâ(UälXâ‰¥ˆ(ö‰ I\rĞ/ÄâaªÒisÚ1DdòHvT—æFÈ'%|¾DŒb KÚt¦(Ê£«ENˆÌ§^ê!¹ĞØ\nÕá@4q 2Å(EëÂ‘&ŠQv<>óÇ\"¬µ`\\a[\n¡R¶ƒŒ	œÏ^#\$æÕV=ìRèÕÌY—{­].Ë¦\"¶ÅĞSª9¢ªW<h’~°:®öâkê¬” byòtI”ŞÅS7\re¬„U>\0Ö±˜dæX¾îİ`½¼¾Ÿ3Ê¸æ2\r§1„ÃÑ¦MI£]ØUŒ}.ªŠ¤”±‰ßŠìI¡µ£†áœî½¸¼Ÿ¹­|¿ÔÈ·K\0Š+*sìÈN~YDeÑ¹ü¥bL!2ÔÉó]Şd“õ¡?Ô•h‘’…Âïû?˜§SÔ§sc’İFüQh¬úƒ]i";break;case"ko":$f="%ÌÂbÑ\nv£Äêò„‚%Ğ®µ\nqÖ“N©U˜ˆ¡ˆ¥«­ˆ“)ĞˆT2;±db4V:—\0”æB•ÂapØbÒ¡Z;ÊÈÚaØ§›;¨©–O)•‹CˆÈf4†ãÈ(ªs2œ„CÉÀÊs;jGjYJÓ‘iÇRÉAU“\"K”`üI7ÎFS\r¢zs Ëa±œV/|XTSÉ‡Z©vëHS’èŒ^ç+v&Òµšâ…¡­k„¥C¥”iáåÅ=#qA/iHXEÛlìKÈ¤˜ÅÅ;Fvì(»=ªv!È‰£VWj)qº–ÈÈÚsÉÜs]Š)Kqö{©®¥Ÿ…f„v!‘±­æûæ¾i<R¾o¨@”¡ˆY.H …±(u3 P¦0ÃHÜ3¸kÖN.\$³zKXvEJÌ7\rcpŞ;ÁÒ9\rã’V\"# Â11£(@2\rëxA\0æ:„c hæ;Æ#\"‚L’¯s‚„ŠğÎLáJ^ „G©«”4½„ÂT–(izŒé•åÂO4[M3„AV¥Š÷€QÖV7¤ñ äD**Ÿ>d\"èï5/\"p¨\nm!I9\rY(J¤!aD…Lğé”ÔArBlÉÖYi0×\"‹ánŞ“­tÊ@E¬FP'a8^V0:*uVhc\\•¬éS”äb.RR€PJ2@åƒªZ;µ…E@PH…¡ gp†´™dödLıS‘”Ò¤düU¹@ø§Y@V>1*^ÏK¢ì¸…ÚA“¨A0ü)h\$öXO«‘8e± ¨8¶<ãƒÈº£hZ2“ó´îSÇ\\¶‚`¸9;0‘sÍ3ZC˜@6£œ‚)ğ|Åƒ¨åŒ: ä9#ÈxŒ!òWgÂŞ7ÃHÏ¡º^›)ÂÌ|f4q¸Ù'Ác\rÛÉ›lû(Ê<ƒ(Ü9:›!y•(¦6ƒ’ã3¤¥P¢V®˜İ„\",YQŠJ¿Ù\nƒxß›è£È@:îc¨Æ1­ã˜Ì:\0Ø7Œğ°æ-c—40Œã»®Úy¼,:î!@æ¥uˆŞò\rI‡å…‹èZ®³Š¬ÜÌâ Ñ¢y°Í³ç0°Î# Û-=`Ç»\r#\"Òg\0ĞÅŒÁèD4ƒ à9‡Ax^;şpÃí{‘”b3…û^Htn¡¸‚ }\0«ÖkM9‚Ñ\nSzÌYÂgòœ9*@åÍ‰,%d­ùyLLI2¦pšc[‰jI!Í%¤Ô\\€i1i9òÌúSì}ÏÁù?@îıŸÀn- ¹ı¿ØÛÛ‹s€pŸÚmnAÒ¥8¤@oZÑË†ĞÂÑĞiI¦/D Ü\r@„ÄÄ¨•u’ê_\$l¤‰U€³KÃyNd­ç# ÂàSÜ4†ÇJ‘ÑnOL9E%¨C4)HNyĞBÇFé];©E²,°Ğ›R(2¾(ÎClnãµ(ãÎrY)A@\$5+P('À¤ã¨¡Ø.LäD	¼ÒTÏIºjAº!H¬ZÜ¸pA¥\n¶0Ï)AFAÑ›6~Ò©táŞRšƒ6gLø#.ùb«Xù'ÌX ›ĞØ9¤—A<‘lXŒ!¸8;‰²’’`rZÜ4Æ°g}€‚DIätÃ9³˜Îã@FBS\nAf NØ\"qî\rŠH:k\r‚)\"Œ‡3H™@ J\$À!\0:ÄR¤d™BlFÕA#Éi.1“è/LÉƒDøğRÊ’å83‚™ÒäÉL9!¼:£·Ã7Ò	h\rÑn‚³cC©oH!™×±_úE¡Ó˜è±7’L×%a@'…0¨Ù9ÏS¢Â› qJaĞ\"ö‚ë\0vì&ääÕ¨~xê\"Àu¥”¦âi52x•HuÕHGUÓ8í¤\0€)…˜FŒ‘/&Á*,;D3È„B\\VEbeÓ\r¼e,ü ·Fi±ë7¢bŞ§Rbœ*QA«\$U*(à;r¤§'™t®¥Ö\"@X—&% Ä{f¥âğ¼K°p©İ–&éi¡2²SÄ¼—šùXĞÛèluÒƒ¶ÌÑ\0v•5¦h3x´C3šŒD¬#HTtÎÕŸ`€*…@ŒAÃh{á‘ë»ê,/¹Æ\$P&áØ&M­Ş9§DØKÜËXAà¨fß2âGÏZ˜èhD!ÁÔ-ôO£´şŠs~m€’½Ù™Õô)X©:5+±N mP¬²×b93e	]è‹0GÂl:ì\nØ²LV›ŠQh´s£*6B+5ÄªnàI*	øSÌZn)×Q^¢Lğ‹+,ÅâÒ—Ø07Á˜A 	øY‹T6Ÿ©U(³¹İ\0bÌ\\™–4:fÑÒ«Ê	á}]«Uó¨qWQ©7U‘„KŒx";break;case"lt":$f="%ÌÂ˜(œe8NÇ“Y¼@ÄWšÌ¦Ã¡¤@f0šM†ñp(ša5œÍ&Ó	°ês‹Æcb!äÈi”DS™\n:F•eã)”Îz˜¦óQ†: #!˜Ğj6 ¢±„ät7Ö\rLU˜‚+	4‚Š†“YÊ2?MÆ3‘–te™œæòªä>\"ÄK›\$s¡¥Š¡5MÆs¤ê:o9Læ“t–u¼Y„Ã)¸é¿,ã¥#)Âg¡ÅALEušşyÑ²&¶™C\\–MçQ¢p7C“´j|e”VS†{/^4L+ÆR:I¿œÌ'S=fÃĞPôºkéÊ¼ÄLœâ¢nxÏ\n‘±¶O«ã4÷¢íDXÖi:zE?FÄÄ²Ë–’ŒC\néŒ°*Ã[r;Á\0Ê9LB:\",,\n9®K€7#¢âDD„c˜îÄˆè\rï»Ş²¯Rú¿„€Ø¼\"£s2¸®hÒøŸ(†¢£¢˜ÖŒ˜„hÒŒPÃ\nî²hÌ–‚Šƒ*BÂ ¢ ì2C+\nÆ&5Œx2ãlÄÄ¨ S8Ï\rjÃ“ªŠ²°­1âœÒ80¦)|7¥è( Ä¤LÑ\n	Ò°;\$ñÍ4ÑLZH‚ÅãªŠ”>c\nşÇ„£ A¿	ĞPÔhËÎ4ÔõH:+€PòàMÈ!hH×AªÒ2¯èkD¡/òl7£˜ê9NK ÒĞ¬\"bGR ¢°Ê²t¸ ê°³Ğ-˜\$	Ğš&‡B˜¦cÍÜ<‹¡hÚ6…£ ÉmÛ³;Æ2(st|‚¦\0Ú“ÄAàÂšËj„9B#\n#ÈxŒ!ò;ƒaŞ7ÌU–2âxªâ¾Â)¤.˜9Œ¦FÄBLkš¸`9f2¸Ü‘ck“öõ5Ò‚)f6M¢¬¸\rã0Ì½'’L'/ÑÅÁ0‹¨7¡É Ü<¸y¸ê1Œn\0æ3£c&7±y¸X¯ZÃ>ŒÂ,bgQ\r¬`êË…˜R‰F)V”C:{7\n Œ£hß\n´”ÙbNÆá\0‚2n)àå³ŒyÈÒ2BaÚ­£0z\r è8aĞ^ıh\\0òŒd&18^Ê…ğKŠãaxDwPÏ!âÏÛJpáÎ½(‹ÚQRëÂD:aJ0Q’à¿1¦5#ˆõÎÅTY	ƒâÎúœøÑĞô}/OÔõ}hï×ö<·h9vİÆhÍ™ÀnwÌ \$†ÔtáL¨txŒ7@Ğ¡\nóU\$m¡6­ZR‰.¤%4–@ò¥ÊòË\"Á‰½2 ¥Ráq%À0†7x„C¹lAˆ¸&ãC’nTA„3s¼²šã^l\rˆ¶PA`qÈ.I7Øg`JO\rŒèÕ	s\$‘ài\r!¼ÈEcXO²47(İîÁÄ¬›K¨P	A£±.Qø(&Ü1 ÜO!s‚*H4›Ó(Ìê9DÄ å\"@IHØw}„1-rD`È±»zêT‘åø›L¨yq¡	½¶øAK]d‘)’æIÓæJˆ;œÆŠtªŒ¸@âàÎšEÒ5ë˜2øÂ˜RĞD‰Òä%¯iÁ•ÃÄE¡Ã†%]£<w’YW=Dt+N— \$D“ƒ(æÔO\r¥Õ#Èl^‹©÷KDÁê(Ò^KĞiøfè\$0òlÉc›Dd‚UEQm!Ôà\" Ì…Ck’.v[!ÆFĞáÈ9D&@Ğ çLñÔz¡<)…BX¿L©°L:’IÓ>N<Ô¥%vy²!!Ã’•İ3Ï @CXáŠoE˜1¼‚vÁÌFÌ˜4œÃ:\rîÂCª3JÂˆL\"æøÓX‚¤l™Eö\"bûC¨…M0šF{!úù2ÆÑšU<(Ká	\"EºVêîN˜\nÊ\rk.€¥^Ï|U\nuO— aäqã±b½†]cà|%L×ê·C`+¢á¤1†°@Æê¨v\$´2=¸b^šÁø&õq)i(Â¨TÀ´2×3>9³•ıN&al½ÃSäXˆ\")e•i­®åÖæÜr#ƒVM¦±­ãÆe±zDyB›`©p¾/ªJ•ëT’Zr%ı'–ÊgÖ1àÏ7ÈgoÁÜ-¥¿†ÓÀ÷ƒª	™—Üó›×ˆ\n“lÊ¦ÜBC(;!a‰:L¨¢ÁÇªÊ	AmM„Â¥Àr«Àsº62¼*Ì`¬B™°¸Î€ –FÃuù¤…•sr..	+\0P¬\"-Lƒqu¼^7!Ì+o—®¶WY,WÏP";break;case"lv":$f="%ÌÂ˜(œe4Œ†S³sL¦Èq‘ˆ“:ÆI°ê : †S‘ÚHaˆÑÃa„@m0šÎf“l:ZiˆBf©3”AÄ€J§2¦WˆŒ¦Y”àé”ˆCˆÈf4†ãÈ(™:éèT|èi8AEhà€©2ÌÄqÈÙ1šMĞã¡Ì~\n\$›Œg#)„æe¡å\$©Š¡:Úbq[ˆ‚8z»™LçL4¤Şr4±w©´ˆa:LPãÔ\\@n0„ÃÖ=))Lš\\é€†X,Pmˆƒ@n2e6Sm'‹’2š°Â	iŠÄ Ç›öf®ÜS0™ú·Îÿ‡ÆŒMÛ3©ÊÓ{ôq·[Í÷—ÅÜ¾H=q#·\n2ø\rcÚ7¾Ï;0¶\0PˆÖ’c›~¶\rƒxÈ0«Ïò2M!˜Yˆ^¥\\&”´íKV@LB”ÔCÙ%Ã€Â9\rëºR\$I‚ô7K:Š£ãsµ	k\r9ˆÚÄ¨Éb ¦&pr ïÀ#J^-Q(êæ¿N¢8™-cHä5©H(…\r4(*…X×F!D2ãhÊ:4ÜÂcZÂÂ³ºÄ‡\rH(¡Æ³P’„NàP‚:i#|‰\"	€Üµ	CÔœ¤é’ú£ AÊÃ˜PÔhâÏSÔU\"Î	Hr‹A jpBj˜Šp<Ôlz\n5£Öö¡Áû;ÖBZê°ØP¢¾6Ô°TfÕ•qk\0=!¤€³® S83Œ# \$	Ğš&‡B˜¦ƒ \\6¡p¶3ßƒ8»oÜ7(BmJ\n¡¥ã¬ƒ\n†0HäõÁ°ä9#ÈxŒ!òS†áâŞ7ÃHÎöŒ¸Ş:Ä¢/Z‡'àØÄaø@2¶g–fØ@Ê<™Âg‘±O.1O-CdñXD2~1C›JDšR)¤‰3@‚Ç\0(,EŠPÁu*‹´RD¶rªú¹Æn¢\$Û„j.3¯QòèIŒjEl\rÑ‚Å;ï±X[2_´4oƒ™¶mÈ„d9nH›•ºîûÌî¹¢ûíQÀpH½óÂì#NÇ²¡üY'´ñÛZ	Èî£3ºnÄñKó{ß=¿¯]	'|é…Z “tÃšjUïˆô3ĞÌ„‚)İ;¯ÅÍÚâC©ru,1è@ ŒœIìËnø“ö—\r Ì„C@è:˜t…ã¿ô]?äŒøgìà†FFjK#àˆ!“t[*cÇ|¸†“¤D	¹2\".Q¨Éƒ›Ø2MH5°7tQ.J%=ßâRL3A@µÉ¨“^qHXrM« >°@ûC+ï~/Íú¿wòşßéz\$\09@(ÏÚnhaº°ğ’R(eNÁº#Ôï‰z‚7f%ìvà„áÓÈ#çeØ‘Nƒˆúv,&í«ÁRÊeƒ¢-Eæq%†¢œnk\0Í \$H¶Ój-6o¸AÙ\nçãRO é™&‚éœK©…nŠCd‘0¡mDÁ¬ø¤€H\n¼í\"Ä\\”…Yœ‹É.PëVÄZ\0 ¡‚˜o'È‘Ñ@MÃL\"æhéw4JN]>¹Y°6F¡©£8JH;“!êåKˆhA‘á–ØjÌÉJ‡FŒí©1(ğŒpi{rÃ¢0Gä±r¨l©&È€C\naH#| ¢”=	Á¼9¸\$|Œøm#ìg ÜGÌ!	\r-\\Ã;ƒ6¥I‰3&¢›Á…4²İzª{D7µŠ/ Í‡ªÙ¹¢2ËÕ.L®„UÇããpcÍ6¸ùX÷ßJO`Î*#.Ö›Ñu\rˆ…\0@ŞÉçHO\naR0Ç3+PjEKd@0‡\0Ô\\“qvn]GĞÙb‰­5V²Öt9X5b¢Hü@¡ÖFÉY<:%æ¤ÂÖŒÍDn\"‚‹‡*G\0S\n!0Â>HI%\$év¦‘±3Íñ³\rèİN â!Ë‰}´ÓGˆP­QÀµ…„zªõ[èè½Ë ª•³pˆâŞ&ÒÎß£u³£`&âË{qî¥,ä¤&×¶i%\"áÈ§­ĞêöÜ\\;A\r<À@\nË™2©¾L\nˆ-íÑG%±ö?Èª0-úÆœÉ`,ıÜ7Ä¹TtGs0,3–ıQ`c;‚&v#O—¤&ÑB¡Ü6ÔèçÌ¥).mØ7æî=†ôÌƒ‚ÆCw2ÈÅ»¹×g]»ºmVsq¶Åµ¸µ\$¬A\0'Ä7<ÚKcHÍ`¬(àdtÕuQ\nowõYbpD z6ÅOáSUaÆÂÆ0à`êÀ»MA-—¼bI–Sñ_9U|›í	´6Í™5á¢Zó|ø×˜Xó+ÌásÃÓá;aô@ˆOÉú?gğşƒ»ü©%)ÿÀsaÜÏ†HªÁóåLD85²Ú ©„:f<„H\$€";break;case"ms":$f="%ÌÂ˜(šu0ã	¤Ö 3CM‚9†*lŠpÓÔB\$ 6˜Mg3I´êmL&ã8€Èi1a‡#\\¬@a2M†@€Js!FHÑó¡¦s;MGS\$dX\nFC1 Ôl7AD©‘¤æ 8LŒæs¬0A7Nl~\n\$›Œg#-°Ë>9Æ`ğ˜\\64Äåæ‰Ô¬Ï¶\r ¢¡¦pa§ˆÀ(ªbA­‡S\\ÔİŒÇZ³*ôfÑj¢ÑäSiÂË*4ˆ\rfZõÚe;˜fÖS¦sW,Ö[\rf“vÇ\$dÊ8†˜ÉNJp•Æ¹óiÉºa6˜¬²Ó®`ÑÒÖõ&Òs=2§#©Ìİ*ƒL=<ùCm§–Ã(²¿¢¨Ü5Ãxîë=cŞ9#\"\"0³šî2\rã*¿¡O(à8AhS 9c¼¦ºIÚ)\0:éÚz9¡#«®ì»i²~¡#êJ—	Ã{³±ƒH5§®ª@ş#C£H–?.\$|ÅŠL2jÇ3â8Ë=- \"¥ã¤x';‚Â–,‹0—„	ØØ„3â`Ş–¼Œà5MŒÚÙ¦£bàõ%\rê£ @1/P>Ïô,>\rJDˆZtxjrX5ã˜ß\$%ò¤+0¦ö¬ Pœ,é(0#K¿	&£“\rS	Ğš&‡B˜¦cÍ|<‹µ\0ÚŒƒ V1R>+é2Úƒ\n~.èäë&Ñõd<‡xÂ#6¦!Óc0Ò².–í¾¼9N´fŠô 6.éøA„	•çz«÷Åì2PÜ’SkÊ5}VLXÙ#,ëK!	:©hŞ3ÌPÜ2£\"€Ê¥SLÖÙch@äÆ=s4\\Ì=éÀõŒnëtë\r˜üÚ‡ã©j*‹…ˆ#Èe¬ÄÊ‹6)Êv”<Œ…6¹c¢38d<}?½l’TŒŠZJÈ&©\"zÙÖHc~±À8‚2i¸åœ¬#“m”ï;xßZ!\0ĞºÁèD4ƒ à9‡Ax^;ğrq²½at3…é_\n!Š-6„Aón´½i\rÕp`é @õES(ê×™ÊIf3‹Ë9µŒÃŒÌc£!“7}rCÍãiç=ĞÖƒ!¹Œ»®ï¼ï{îÿÀğn'Ãñ#w€¦X'”ZoúÏrá\0ÇhÊşŞ‡òc8Ë:³ÙKš7Ó	bm	s[LÎ—1ÃZŠ33Ú Ğ»Œ#ëì\n&˜åcÌ‚Íº~!˜õ\"CcBÁÌ3R&éN  !ĞÁBî‹\r»Ö.™RtÁŒiEçióò6Œ!( \n (“A?/±M>RZ¢ŠAJ)ŒdùˆHEN±³,vÃ”[I)¸2áöfüUKèse•\"ÀĞüYw+ÄÜ‚»\\Ûó]®€µ¢D“ğw\r¤1“ÖÛÃPŒ%İëÖ0c!2)… ÍTVw'9@Üë 0bAp½X£bNÃ9ÌI\$îÀlç±·^‰WŸ™¬MaÄHH–qi	<ë¼%ã JÍDT{…ŠCH‡úÙ]q69d¹%‚Æá 3£‰aÔŞ‡äaU\ní’H9×B Â£´L™¨\"ÄXefó“™šƒÑqE%„Ô:sÔë‚î`ÆœÈH¥éˆA\n!„8JBdE%¤Æk¶LlB0T…©çÄI4Nƒ©±,,p•‘’£%ç@œ†Ø¡CÍevÄ¼ÙC†qhrx^Òa µwO i¢’i Êd˜0CHÁ°†3È}2›¶jÛ)9F“ØÛÄ‰xĞÙYÀS	*…@ŒAÁP†-ÒÂ_A[T<i\$›s“	ˆrjGõV0àäÃ©ì3á î£>ËûÒKÁ†b³¼)ù·¨4í›–ÒÃy8L¡–N,§\\›ì	/I\n¡EÙÙt´›4\$™Ïd†O‰úªVªƒ‚­;á-˜™ğŠHĞãj.µ©I{7ŒƒB;DÉ“¢·mS±ú4„Ë»j\\qC˜";break;case"nl":$f="%ÌÂ˜(n6›ÌæSa¤ÔkŒ§3¡„Üd¢©ÀØo0™¦áp(ša<M§SldŞe…›1£tF'‹œÌç#y¼éNb)Ì…%!MâÑƒq¤ÊtBÎÆø¼K%FC1 Ôl7ADs)ˆäu4šÌ§)Ñ–Df4ÓXj»\\Š2y8DÁEs->8 4_F[QÈ~\n\$›Œg#)„ç¢Ò)UY¬v?!‘Ğhvƒ¤,æc4mFÃ\$”Şr4™î7Óeû5ŠÄ†Ê°*’wµšÁEI}Na#×fu­©Vln¨SoĞ³i‡@tÔÆ\ròÙ2aÙ1hÌláÇÉ ÇœÊ-ãòöæ¹“µÖ×6Ü­FïG›×5©›!uYq—|ı¾¯P+-cº‘1 ¨íµ«\"Ì´7H:\$ù®Ã0Ş:‰(ˆ™»r6ªA Ê:¹ü;¹Ã@è;§£’˜§C-t´„ˆ@;;Ëö»£Âh9©Ã˜t¢l’(¥¦Ò:f1‹tŠ×\"­›\r‰`@ÌºPÈİ°ˆ£l¸Œ#K¨·ïhÜ‚‰Ê{âİ,šÔ PŠ2\r#£O,ëJÖ9ÎØÚ½Å1\\[-ˆ(°Ú†\$O „†HÔæQ\0Pœ§£º‚Oá*01(ĞM\"4êô5 PÜ×A j„XBÒ^ùM¢`\rïhä0µ¢›NA-°Ê¼¾@P‚1ºĞ— ÔÃ¨\$	Ğš&‡B˜¦ƒ Z6¡h¶5\\Pº×*I†Ç¶–BÖà(¡VŠàÂ¢‡Ìdª9&®àÂ9WcÈxŒ!òKy^‚¨¸ã«à2\"‚š² P¦Ğ4HfÎ\r:	Š4r¼}sH8pŞİ«ŠJ&!Ñè6ƒã\"Q.F3!µ:J)­uÕŒ3Pí#€*?Í`AC\nsÚíPÔ@à…./ØX	â\$÷ãƒ•J¢+Oˆ#jâ:Ãp =¸¶‰dèïVz2imPİ§j’ú9c:µ)KZÓ3PkÃt0ÚŠc¨Æ1¢c›òíÔƒH’ŠÜ5¨p¶è:Èx@ Œ›ê×§Œj¤^#\0ÆÁèE t…ã¿\\\$<Ğä4ã8^‘…ã\"¨™* ^İÔúèmøf—ÒÌxç #jnµ£ëÊH,ˆnƒºêô³ÔîİÔCj“Ä[¤PçÅ:1]z*·CÑŒ½/NŸõ]g\\;öËWÙö½¸İÛãó?zşÁô	!´Ur›ÆÙv¨pèyv:h˜¨,CšÖÊ@9§Í›¸VBBJy™!g8è6T\\Ñ<A2hHS¡B¼#‰Ô©øN‹q™O°P‚8'áƒxli!ïXáiÅØ…£3\n[W*g\$¥Ì£Ô~S	5&)Ğ…‚\0 ƒŠäM\$šÒÛt’T›Éi°ê4¦F¢C°i6†©¹—f–¯ÌËÎ9Pd—™â*E‰)‹f¤ÄÔóÈ\\QIáº\$¢ kÈ!ßY,>>4TùQsOEA¯<¸Ìf`‘§Glˆ)ÛÔA©)… ŒK à äQ°7CT‘+zÍÑª½’JIÉI+%§©(¼ö|MH‘Œ-(f]¸ B’!%!°ÍÄˆ\\²!„ƒFá¥Ñ9‡d‰ƒP!aõ4òbÑˆÙÕ;Á…·:)ØlÙå3†Ô(ğ¦lì\"¦iî²{5#xk6…Æ\$zki#	Ñ27¦¸g¡«µÎ†æÂàe\$§5É1A‰ğ \naD&Ò\$‘È¹#ÄDÎ7D‚¤a32ü ÀI\0‚iµ\r1áÉ]P’¦(ºKEÖ£T…°Ìr1¨ğv§dË!M(\nT	ÖD2wÈ£b«j`¡ÔúÀŒB-\r€®…#Fª•ÌÊ(_pHêD—¸D“Yµ	ú—“†ºKå!~\n¡P#Ğp•ÌT-m6B¦êg‰‘û\$ª€á‚âFXÃôø9ª˜§ÈÁÃ®'º-[5g,ò.•ä&wó®vJè«©`¨PİX;}\$\\\"HH-x7)áPÖjD=Â>@Œ×S­\\¡ËÜ©P¦ÉfçĞã&J%cÊyvÎ\0P‰Z°èˆA6‘q&0êË/5Z´\$:Ğ\$Âp%²]EÔC,\\!kNAá¥ÙÕÖ} XYIMh¤h1æììË{}ÔRë>`";break;case"no":$f="%ÌÂ˜(–u7Œ¢I¬×6NgHY¼àp&Áp(ša5œÍ&Ó©´@tÄN‘HÌn&Ã\\FSaÎe9§2t2›„Y	¦'8œC!ÆXè€0Œ†cA¨Øn8‚ˆG#¬<ät<œ'\0¢,äÈu‚CkÃğQ\$Üc™Ä¡s¹ôn,pˆÄÍ&ã=&Õ”%GHé¼äi3ŞÌ&Ëmòƒ'0˜¦†ÉÄt¤e2b,e3,®	ÆßhG#	“*\n\"Z\r¦æRs3•â\rÚ,æo“&wÃœg a©hfã\$ÌA¦„à29:t˜a3ÌÁ\\şŒTÏ¾¯Í³ÜÏ3}éu8Æş¿héŸ¡B¨ı>„Ìä\n)å%Ë‚k­W?Sq¬Ü7êp90Èèˆ0ŒŠäãã+zÿ¼ã˜ê‹°Ï8à‰c»2#¢²‚7\rŒB&OÓ†#²ZÊ8‰¢l…'Cšë%®Ld	É(Ú±Àã Ş<‰8Ò2>\rÒ‚ğ+KÙ†S:Bs::ÉÃğ#Œ­Ã:ÃL»¾œ/c;ò®§tR7B„1\rºo#®½Ì‚‚pÕ¡CsÎ6<‹Üp<»­(Œ‘ òŠ3äüŠ £PÂ!CHÁxH bø&Ä|6C@ìÃ6ãkfš„*Ím#ú2BCX2D+à\r’ğÜàÕ\"@t&‰¡Ğ¦)C ^“n\r„R¸\"í^š:ã\$C§aI‡ƒ\nv6¨ä¡5‘Bd0!à^0‡Èí£iˆcxÜö°Ëo\\‹?ÀÊ3\"Ê]Íúƒy®mëfŒ£Â(7\$W4n&(È£ß(à0ZÀ…£B`<ºBÈŞ´/Ì3UªcàœJ/x™5­B*\rñüá<!Æ1ÁÃ˜Ì:èÄàbN9O€Â½İôÚ½äC(P9…(èª:fy£¯(°CÊ–ÂB ĞÖ\$.ÆB‘/\0‚2gèPå›x†¤Ú!\0Ğ™ŒÁèD4%c€æáxï¹…Í.ºÊ°Ã8^ …ã%Í\r70^ÛôÄ”/—eÂdÀÖóäCb`8<áhA„DçM\0àî\rÓş ¨ã[m¸å>©<&Â.Î2í;^Û·î;˜ïºë‹Şğ9o[åûà#wi‰#jğ2Çs’qÅDñÜ|ÓjÖhÌß»ƒ8Ô˜8Í4-7µŠ¡Æñáj^\\%hXÚîÒ[Œ9Ô£¨É>óå*;©!cÆ”\\Á\0îJQ!,AÉë‡\$vŸCf`Ä:²¶ZËÙ‹3gü:‚NlÈ aG`€¸‡ÄLZ7-Æ‰¥ÆMUD#1è¤ã›`èLKÔ\n (ÖD¡:/!` ‚’†NLÚHäĞÍÕÊr_Ú]\$èüú†˜peëôB¦ô£ Š\0003\r3‡ØfZÊli)u¬@SVÃRæ~æÌãEPÂA»,]ğT…—°àÈœòCN­ÿ€Òà³?í²\0ÁrÃu&Ñr/F®Â˜RÌ,7ò„åŒáÄ=5†§˜LéˆA¸‘òŠI	1¬A&Œ“@è˜ƒ±B\rìuøéBå˜B!èJ¯Ú@ÃË8é+ô\"PSìŠ¤Ì8‡TyÃ2\$Ím»ÄfPƒt&Ğ^*¡rúOù7ë)¼ĞÎõLœ“Æ¬Î‚à@–¤QIœi#œ†ls	Ì½|äÉ÷êŒAhk\\Ë¡k(ÒÀÉ3qñ:vš‚ĞÖÉy%…İÎ\"rÂˆL¤i˜ÃˆŞ‚ PH	­å*7ºcæŒo&ÁÉœÔ²iLyIgÈŠ5\0¥L9.óœScJ¿	ÈsA6Õi¡Á‡È‡˜EŸjAI~µ.¦ÄctXkD\$9ÏÃÇLËV«Ç“Ê@OÑ£ë8Oµ&GP@B F,2†súäuFª–ÊJWéè.©éäÓ’ı_Ø3P	kd¿¹urŒ‘‚(%’²*†»%2Y&”s3¼fy[+§tÌZ\0Z‹'9€§\0áLŠÊrls«\r4¸ç¢4vY–Yìt§Á±€¢DzÂA4!dÍ	ÄMSİ{–¨ğ¬bÃ:¡	iÑ2RB©m±n^Ï2d,‰®j',ÁRûe)€,!”";break;case"pl":$f="%ÌÂ˜(®g9MÆ“(€àl4šÎ¢åŠ‚7ˆ!fSi½Š¼ˆÌ¢àQ4Âk9M¦a¸Â ;Ã\r†¸òmˆ‡‡D\"B¤dJs!I\n¨Ô0@i9#f©(@\nFC1 Ôl7AECÉÀò :ÇÏ'I¡Şk0‚ˆgªüºe³Çà¢ÔÅˆ™”„ù\$äy;Â¨Ğø‹\rfwS)3²	ˆ…1æêiˆËz=M0 Q\nkrÆÉ!Éc:šDCyÃªÏIÄ#,ĞädÃä›Ôá	³C¨A’2eÓ˜ÍF™á”Õ¡Ñšd…£	Â‘˜ÍB7N¯^ š‡“q×R äyW~çXçzæqµÜùu&îp7vúìÊ\n£šÂBBRî¶\rh0ò1!ƒô	È`ô—?(¢.ÇŒØÂ=%ísò1\n*7ŒCš.:ŒíJ4110¨CŞ÷®ëÄ›±.C(3£+Ôd==ã,2¸aÒö9¨J Ô°—Š:’¥©¢pê6ÈOT&ÿˆƒè—¹ŒŒZ­¶ªHŞ‘3£J\\92€P¦†&àP2ò è93`SL±ã”\0±,“Î	j.7»ƒ`ß¹ã˜Òö?c4»ËtHÊ9O`1«í£l2A(ÈŠ5ã OTSÇQÓãBÜRÜA l¥hÍCĞê´5C€ê¤¿ã8È=!‰ Ø˜¶³Tò:5 P˜4³C¢Ô¶\rÃrk6zĞÕ²7@	Ğš&‡B˜¦C#h\\-Whä.B…´0£î*ÿ§á\0Û_¹àÂŸ‡ÃĞî90ÃœKc 1®áà^0‡É%ı€\noÌ4Å#“S‡b:a#¯¬kÈãÃ`ê1&»!\rò\0õ!eèZ_|äÙBùg)6Np-&:H„Ø@7ŒÃ2 Ê\$‚œ„‘OB¸óCQ%L»,˜Œ£c¬<õD-û\r”j0…ˆ&¼YŒO=³k8(l8½ÓìÓÊ:.ápêjÈş°†kNÔ@‡`›@A°°9–Ê9ìûKŸµÏÊ6ßRnC«Ç»o&¯o°ş»Àì>ÅÃlÜæÔÉñ»vg¸ÌÜ–CÂ!HÍ²6¢ÄÚZ†\$‚%ˆ»¢Jg`mòcú„É¹[Û,~ÀŞ·ğ@46ƒ0z\r è8aĞ^şÈ\\0øs4„O#8^‡…î2\\:\r,˜^Î4¶õ“8æ#5RcR^;#=ì°æ yÔR5q´¾¯Axµ†)6'±ğÎEÍÊ;#/áı@Ğ\\SÌ¯9è='¨õÃÚ{9>\0äøŸ e\r\0007(§Ô˜ÔZ<SÄÆØx>e(¼9†ã¾ƒ)=ˆÔŸ¶SDWÕú[.Äà¦¢^RW ‚)­8•¿¢Übƒ£CD¨ıGês@P	@’³É S¼Fimà?ƒÂB‰zÙ!ÉÅØ’ÒTï	cp¡¤‰›òØ…áê„ñ®E!ZÛuŒÈİ™ófËR\nCf@(b‹·¸eäeÌÁ\"HÄ#ä„Í©S!!VË*20Ø£@3&UPz‡ò%‚‚~\nQ91gû	“ğSF(dlÄ£hæƒ¡¹,øÀØsœü…‹ˆã0 Ş¡šB!†ü0“È Éö“,Í—×–½‘\r\$Aª°e0]á.\rNZ&ÊæÌNSd<\$Y•ôG…á\r\"¨âfÈcÉ\"³roN¾OC\\6)… Â›‰<ïâ£€ÌWØl(:9¶Â6ŒYxkFFEÒ/¢NJIY-‡ì,=(a  .­L¼²|GÃ)—/à;‘ä^EÚ	ÍQ)ÌÉ•òôğ¡\$úC´5³OZU\rô¡I‘wZï¬YrÍêq5È.ƒiK=ñiJ‰’{QÄ´plŞbñZÌÉZ°ı‹1€êBCy\rPøBÓà¯O	9\"'0)…™?êkä 0£‚ÜÚZBqøË‚\0Œ%œSèr¬·>CP\n\nFÕ<Ú8×K!j@<’KV¦ãTP\"ñà5¬ÂXL“…¶¶ªÖuòd­Ğ‚8¦´í35+)j¤SVµÊ\\òtTıÓ6öÕ»İgÒµ›³¸¦àÃÆTæXk'UX˜R:ß¢110üô“ÓMÉ K5¶œ†˜Bv¾ÔQ(â\$ÍÛ¾CKpU\nƒ‚It¯¹KvîÒÕ³2&‹vÂ\nn§Zò/Sƒ\r¥Ã‘ª‹Ä¦æUÈÂdb<b+ìßƒE	(ÍõAµ(‰MqÆe„ÔÒ].yÃTÄç25	jéÊ?÷Õf…RÄsÚ9;='(4ßw‘Ãt•æW_h2‘&\rL1œêÊˆ6awä15TÂ\\­yJÇí93`{ÃÉI\ry³\rÚÜMv>¼j°ê!9ğd“HD7Äéf„RQ…Öaª	8Ye¬Öœ{ÕÙ¯&«<g ÒßñîI\rlÑTÒ³ÌŠªÄöµV¤äE*!AÔ";break;case"pt":$f="%ÌÂ˜(œÃQ›Ä5H€ào9œØjÓ±”Ø 2›Æ“	ÈA\n3Lfƒ)¤äoŠ†i„Üh…XjÁ¤Û\n2H\$RI4* œÈR’4îK'¡£,Ôæt2ÊD\0¡€Äd3\rFÃqÀæTi‡œÄC,„ÜiœØhQÔèi6OFÉÊTe6ˆ\"”åP¹ÁD“qŒäe0œÌ´œ¤’m‡ßÌ,5=.Ç‹Èèñ¹ÌÃo;]2“yÈÒg4›Œ&È6Zši§ŞC	Š-‰”“MæCNf;ƒ7b´×h<&1N¨^púŠ|BRY7DƒV“\n8i£fÃ)œËb:[NLş,èhØlö½ÉIëò]½ßìbøo7[ÍŞøõìÊŞ2ŠXùO‹ìÔ¸I2>·\$àP¦êµ#8\"®#kRß‰-àŞ–B«<»\n£pÖ7\rã¸ÜŒI8ä”ˆŒûjÄ±iË¾ëˆ ê821¨àÄc»J2%\"ƒJÎ¬†:±A\0ê–¬lK¨8&k¨*\"Œ„Rfã¶\n‚Rƒ—CjüˆQkhû&Âk¢K5ˆMJfÓ\ràP¤ÿŒ°Ë\rŒ££M5ˆ­ãÂâOŒ¼”·®+šë#.¤&üÍK°š¼4«“ê½±I|Ö'\rã²;¹Œ\0Ä<ª\0MISUø<ÕÑ ¡xHÖÁˆ(\$ãëHMô4Î£‹Ş6XËğ@6\rãV	ğ*2ß‰‰°Ò¨B„ÑàÇb5K°Â3´³X\$Bhš\nb˜2xÚ6…âØÃ{Œ\"íº2ÛïÃêò-5Ä6É¨x0¨!ó:Wñ:\\½E#òã|”àøH†ü\$#=~2â˜³5,dXØ=lZ‚£Ö˜ÄîdrV¦ÊÀÆVªÄCdü:’:Åã0Ì¸Ê9#tX¨¿B×\nï¢Và¨7¦)pó%8#¨Æ¾¢#0ê‹ÙC‚6c–·qŒ9.KUê+pÊaJR)êŒ² 9j-î¼zö¨”%N¤D±Ò>l7á\0‚2j+öĞ1¿²È`î›3¡Ğ:ƒ€æáxïÓ…Ë§#´£8^Œ…éËT·¿xDvLü#âôb\"×‚LÑmŒ‰#e´„bÅÄ”órÄïhty@®?„ÃÈ24s|ï?Ğô}/N;õ<ƒR¿İo^7uãÆkpvØK8;“óŞdsÎİ¶zÈÖbÖ¹j¸Œ‡BRKŠwéL±B*^Nù´?¦€Ü8SC´DáÜ¸sm\0ƒ“‰o‡ü½g¢àZûTlM7¶`A9³\$„–³É\rŒàâ Ğ–Ù¡j©Ô'(Ô–^Ï!bˆ)F\0 ‚6G&ˆ±‚‚\nHÁOKfğ9òÎECa)ca¹¤Á”\"ÿHqÚ.v#’ÄzQ\"55k0;ŸóY!É¾[d(Ô‡bf\\O!)\n„Ã²BÄ¦Ù}dˆœóÀ\0ÜsÒ\"/QR‡sĞHÁî}Shf³’CJjïı„0¦‚4‹Â†@€@HÑ#Ä€Ò°@ØGV!×4/Ù/\0 ¢{áË\0001dÈšh‘\r‰aa„X·–“_MáiÑ9!¤U<R\\eLà\$0òtKhFL7*SÎzL:É)é“——êá‘/‹1\$ÎrÔ¸ÚPP	áL*\$ÔÎY	k6.„(3¦©ÊTAwÔ¹‘óÔKIy\nœ†lŸ˜/8XXi4*Êæ:¯Ì™•8ÉU Nœ‰ÑºÌ.…éSp@ÂˆLH‘¬²ö‚¤Q%Ê”›#–HC4íjm1ô'cº—JbBää”ª\n‘&MÙ½\$T¦ÔsšÊĞA~-U­sdC2}ªŠª3XKX¦’lOÁ°†2â²i¨eHÌ9†ğÅ!HQ¡Y„y¦¢0 ƒ-Y‰Y˜0I×Ù(U\nƒ‚0aj‹Œ%*¬åÔúziM¥L¬€‚ÊœØŞd,Å‡†XŸ–mšŞ\\\n\\³m-ÀQº7˜<®õøaÃ™}¢Õ[ ‰½³ªhD&ˆ²ÍHkª	gWÂ:^Í[{#¤¤&úVMä)ä6ŒæëÇ®É›fMc]3âlS»gÔ\r¿¿Dúl¥V<6nÎ\nŞñP‰±f.&ğm	v~x]õŒÊó\0003†¨±ZãnApu°©Öy	‡5’“#ì«`¸";break;case"pt-br":$f="%ÌÂ˜(œÃQ›Ä5H€ào9œØjÓ±”Ø 2›Æ“	ÈA\nœN¦“±¼\\\n*M¦q¢ma¨O“l(É 9H¤£”äm4\r3x\\4Js!IÈ3™”@n„BŒ³3™ĞË'†Ìh5\rÇ™A¦s¦cIº‡‡E¡GS™Öbr4›ÁEcy†ªU¢ú¬z0ÁD“qŒäe0œÌ¢\n<œ’m‡œ†£iÉÈi·QÌÂb4›(&!†No¼í¦d?S4ÕL¸<ÙŠ-‘“‘Lš³–,İ’¼q`ğ˜ÅS Çìª§(„œ²o:ˆ\r­>yx›¦s- és8kjØFç§ñIÊ{C´tó6}cÙ3¼Ü¡\rÃª:Œ8lØÜ›¾ï¤É­®@Ò;£©£cpÎˆ°ÊÍ¸¢K†7¥`PªÓ8¢¨Ü5Ãxî7#“¨9\$â ÂŞ²,šnò»Ã¢:Ìt82#˜îØ‰8 Ø3¹\$.Œ˜ê•­,‹š«8æk2 R–)ÃRbæ·*²Nƒ—Cj|ˆC˜ÂŒ;M€ÂûÂKhÎ‚ˆKjî¼BÊµB8ËCkÄŞá(-„DºÎëĞ¦Œ»`PŠÿO°˜7¶QbzŸ¥ˆÊ¬ƒ\rêtôéBKĞJ2ò«5KS¼ PóVÅ PH…á gZ† P êc­\"â¾£Ôğ‘6‰|\r‹ëh	ã’Ô7RI¢‚2ËLê¢ûÃCóh8£Ï7B@	¢ht)Š`PÈ2ãhÚ‹c\rä0‹¶ÃÅm?ĞZ› ª8@6®‘Ğx0¨áó:WÑbX#´ˆòã|“àx(†ÿ-ƒ=|2â“(´-¸übÜ¾ı\$#9.>£×ò§*vØæ“‰“\"\rë»(ë&7ŒÃ3T7*öÀä¦î+ç¥z^–!\0êã£\nˆŒÃª/dÒ£˜XÀZ¼0ä9\rT @*°P9…/“8Vµµ+w°®Z“ŠÜN´ÒfcÉ´'Úûúã¨v>í1ã0z\r è8aĞ^üÈ\\Öp‘ƒ`3…èÈ^›Ú\nü„A÷IÎXî'l\"-¸ğ8Aí`Ã¯³2L–kƒ¨Ê²Nóë3?ú(šÈªÈô€ˆÈvs›Ú1ò(AÅoÈò|¯/Ìüß¶§Áw?Ğİñ˜[}F\nËˆ:uÙ\\Ì:%Õ#ÏŒ#\\§É^ˆÏ’<6­5™'b•ËAj;§•£NjMY7o Ğ†7L‹¹©\"æø¸‡&úOÓÛÏ%º5pæÖZÚ”d0eúÃ&i“1„z*hÆfm`hKì¼Œ³üA”bOMf´´CÄ«\0P	A£çlZAG\$`ª¥ó†ÈyP\"„X“±pÜÑ ²r0'|’“ÇQó -¥E’ÓÙ–Yå¶38gamÄÄÕ7rQx =Ğ Y‹¢\rÁÀ:£ô‚óU w=¡Œ4/ğÒÜ‹r…Æ0¤ÖğßÒ,aL)iÅ}\$ÀG‘´B û\0\rŠ%á¥4ÑBŠ?åÖ2`L‰¤C?„¨³ÁÒ€P—áÍn¥½™Ä”’‰)“¯½“Cp’@ÃÉØ-%\rB2ıih1ëª¤“¨`Ü…È°1¬—‚ır@<‰'…0¨”LÙ %lÄÖ Î¤P9W	è2SGÏ{ş%Ä}¤ùnÃ&Û\$“Â1‰.¯£¹ŸK,òN\"Äx²Me5œ0¢Lf¸`©	b¤&ˆúA# Í9æóG|+24ÂuHo\r&€ñ¸%Oı?–	†0ƒhLEG	ôõPEüßj\r'Å¼“ª¥¦`êÄ\"Csô*šdÌ*\n‘0a„3V5ùPls´4¬bÃ!GÕd¼¢:šÓÙ««\$“„jTÀ',d!T*`ZÁ\rç\rUÕHtjUA«&bÖJÌt)ñÓ.|•H+8f§ì79d(†â D¢Ù2Kİm 4ÖJMğlMá6É†ÌWPe1Ç”Â¼TòoÖ´V\"\"„jƒÓ?Y´5 CˆC«úÿ·±Õ›ªVm\rƒ4\rô„šÈÔ›Ô™¬NJè˜©TŞ0ÅÓ9&…é=Vs?h©˜¨ªŠËÚCgìÅ¢ ª-{•`È‚É[Š9ÙÙÔ;iîhB.‘Ø/²à´E¸7ä\r[«US@Ek(G¨ùW\0";break;case"ro":$f="%ÌÂ˜(œuM¢Ôé0ÕÆãr1˜DcK!2i2œ¦Èa–	!;HEÀ¢4v?!‘ˆ\r¦Á¦a2M'1\0´@%9“åd”ætË¤!ºešÑâÒ±`(`1ÆƒQ°Üp9\r0Ó‘¤@d“C&ÃIèÂt7ÙAE3©¸èed&ìÇ3IœêrE…#ğQ&(r2˜Nrj­†ŠE£Dj9¥™M—î 4İ¤'©İLq¾èL&ÀV<Ü 1mÖy1’ß&§A.´ƒ¡„Åš2ÊÈ¦CMßeÂ›±yS×\"º»Dbg3–Bi•–MğƒA†SM7Ã,§kY”ÏF\\S†Û>t4Nı;ãgç«”ñĞsgçšA„À@1ë³B:¢ÌëˆŞ²¯ãıÀIšÌĞ¹lKşû¼ƒpÎÂî9<àP˜›6 P‚úÄ\"¨Ü5Ãxî×¤#’â•ˆ‹{|Å6£{Ñ©›Ø8.*@àÅcºâ2%b0ŞÍ¯‹ÊÌº£N#^¿¨²àÊ8èğ»¹CJÌÆ¥ŠR`’*	Øé­PÂ• òª.F­ì½)²ÖC£JD²¶(0ŒpøÊ°¬mƒT9B’ğM± P2ãhÊ::ÀS†â®(0ëIO«RØ·.@ ÊIc“J9`Ş3Ë	(ä™Ã*ìä5Î£¬Ä<CöŒ\0ß@Ò`My_Xp<¨ÀTZ¨\\vxbh²,C<–Ó¬«ªĞ¹È6ÄF‘0Ç'.´¦àXĞ@S`P\$Bhš\nb˜2IhÚcÎ<‹«ØãBêd:¨á\0x0¨ó‡MxÃ‹CÂ<‡xÂ%x~\"!À¨âüÄc¸ú~49\\h¾ŒbxÎVYW_)˜R¡./(æ•Äj=6R€S´„U£xÌ3-•ŠWuIM\$¯û°ï\r`<½nPê1¨£˜æ3&,ØÏ\nHå®5f[–ØiÒœ»…˜R¡ÉÍªâÀhD}°(ÉX¨îÅiàÌÎaÎ)ËBí0\$º´)˜x@410z\r è8aĞ^ı(\\œnIRâ3…õ^2@«xÓ…á}Ø\rİ”5”ä Û;¤ëâë/!2Ğá=-ûLeÅpHíòC-0ï2•r•‰¬T>†H’0å^ª;\$aÈ×02ó\\ç=Ğt]'MÔqã]Õõ£w[-ÖYïìb\"O~}T‘vwŒİI\0Ş¯OQ3\rd™¾B¬tiå4¼3êÚƒ(b¤€‚ ’8I’8§ÙÁ’e\0™¯å¬’õ\\áÕxeW¡„3=¶şØ[eUl¶ŸÒ	0s!D˜êÃ(\"M[?SFu¡%¦xH\"Y„Çß´ƒHc,Ç…¿˜8¡	½Lè\0îµ*±P.(ôàxÏ)çW±…–ÄxBCa›ğÉ6)´²Z\\(Så¼ÎD\"Ñl#QHsf#‚ˆ\nàù æÊ) 5çÂâêGÜø†0ĞL‹ë6šN'æ}‚S\nA6À³^ƒy|E¬¥	´##eÄ™ÇÔÜH‰!3&¤.@#”uJYf8’]†™wÌ´¹)éŸ‚²‹‹Ô/L¸Â0òvË8iW±ÜÅ†è|Iáˆ!ÔÏ´dBÜk©)’p×†4™+'ƒÜ<ï`£ÔLB€O\naR&øŞbI’X—¡P²®EY‘©9X–snA	˜ävqÈˆ†¥	‹’dÅşq	¤”(%)~%4tü”Ã›']¸°¦BaI‚ñP`¨Lî4ı š§¹'j\$¨Ö™ÙÊ;We†‘ãŒhƒ1ÕXó&jµ	¦\r™È´âšc]Uë2Xbò®„(HKy¦5¤¤ØHáŒdı^ÌålfAp	\$!Ã°Úüq,\rXvhrVÔ lsú,†²J¯\0v5a½VÀâìH+Z¯öE\$VÃas-\n¡P#ĞpgH¥CD­a‡)œ¯eÀrŒV&ŞÛòş„êeÂ6ñô„¹>DYõztxÏ½czoÍ`Mµaå}”hŠu²™QR)'*Ø\nÖÒÚC>G	•«‹5å\06‘AÕ	1ƒEÀ§È6«±ö,‘a„.Ú¤Wµù\\åLª‰ÃŠJ³»HáA3k Ä¬0[É\n²gNÊ˜……aq\"·¼Ë®épÈµÚ±]Á<FrcÔ¤6g;° Â¡¾3UVgâ¥,‚Ğ²	.Ğ";break;case"ru":$f="%ÌÂ˜) h-D\rAhĞX4móEÑFxƒAfÑ@C#mÃE…¡#«˜”i{… a2‚Êf€A“‘ÕÔZHĞ^GWq†‚õ¢‹h.ahêŞhµh¢)-I¥ÓhyL®%0q ‚)Ì…9h(§‘HôR»–DÖèLÆÑDÌâè)¬Š ‚œˆCˆÈf4†ãÌÔ¸h/èñ¥ı²†Œ‘¯¦±	4&¾µ¤Á’Y9Ú¡LĞQ‘cğQ\$Üc9L'3-çhKÇcòlqu0hÊ®üÒÊésŸiózxÔr#’Ô^3Òõ…¢KBÛ!ú­A%XÖ¡Pèì¿TÑBİ/ğ»äGÃ’¡­\nô>#=¾Iiœ\\äÑ\"Ìê\"›\$¯ò„=i’9*JĞQ£I±`‹=I3(š@n:4Í<){ø‰µ)úh‘¬ë4¥@FƒßÊ:ĞP¢D0ªÀ¨Â\r\"¤,f—Æ¨ÊI¿o#4‰–Ğcü¬´± üA’%!1¼c)ˆŒx“%úú½£°\$±*J§)G1Û§Fìë”¿ÆÆ^ªåÔ\0¤0Ä¿³Ì‚8Ó@+ã¨h‘ğÚ¢Ÿ¼-ûªƒ*ˆ‹œIO2ô=L£´ò9R+!'Á,¬' ²ëA0Ÿ2²ˆ§!¥ÌÕ\r¼ò5=!q+óHNÈ¹&Ìì‚”²­\"¼]¡hl‹¢ä¤¨K…’ü“-<™.H1(ş¦ÕŒÔ(Ò“È-ùqg3lÒJCªÊDPü²2”Øÿš›<Í(3b˜’‘è3é\$§‘2@ƒ­È²ÕÙ	MH•¬hìå96’KeLü Äôû.¿¨Ä}¸Éó¯€İ³Ãù	ßnuù‹¿á(Èğ%i0‘d‹„“äqVJ¾8¶áa</àPH…Á gœ†(6o'Ó“\"»Ë…òã`Dv]v#WnZÌ‰:ı_øĞM§ên45‹4;Ê¼ë*ÎğmMpBd\n\reáÑ “›t`P Èyh+eà\\-­¥ú.ÎqTí,´c,hH® @+(|å¦Š5…#«{N,š‡xÂ'<gƒ³‹Úi ?ÜÏ6ÛB-²Ñ‡/ú†G7\nºğ©ûáÉzOc#.µªD7‡QÛGõÄ¤ÊºıÍ¶6ƒ”çH¯ªEB¯ÇŠùq½Ô^›ò…¤’“áA·úb·-¥ô*†¾İÛÎôßŒÓ\$EÄÒ_°ì³O°\\gú‚U*§'Ï¡	µÀšÈÙde­h«92€HĞFY¾R€ÓJ©šoÏ¨ª•ØgïROÉG³ì__Áä:¯ğ¶Ù\0\nL&Pà@rŸYA[2I,¸!³ä-‚ò>ˆ6úáSîQïÁé¿7ê.Ÿº“…lº7\0KâÀƒ&läCe¨-àT‡eÜÀ<öˆ¿\n‚³UOÅå@×¬LV[ïĞ¨BÜ‚•Z _¦`1´ B‚d\r¡¤7Pä\nË–&EŠ­‡xxÉ±š#’1ÄA”úJ` \rÄ3ĞD tÌğ^å@.2\nBH`\\Ãgá”7ğÈÃpa¦\\ğDãŞš¿jEòHÅÄh 3œM+eÃ5Ö—”	É@\$ÈÈ=xâIÊ º‘¹Ä–èÕKšİ†'åÔ±fµDh'nZ,’\0Z¿Ü{.Šl¿¹3&Ã,“ò†QÊYO*e\\ƒ¡ÊWËg-C(x’Ğ9ËÉjœúó(DıRN22`Á;FÚD;êD\\p!æ€¹%ò4h\";ñaäTG•S¸Ş—4Ó'óX‡¤9*j%K\$Ù×\ra×	hYó9¯™Ç)z1°‰’ôC	>D\nö=F˜·JV4)>Ó–jNqxC	‰ì3Íh+ú©\r“ñW¬ª—V¢HZBwÊÍà%rÍ—!¦V5£ÅPvËdG®jÑà¨Wccv{ŠZc\n¾ıH(, ¤´?ÒOiúµ.°)w‰J¤~QÅ\"\"R¢B{`¼pĞ‘a%LÉ9õÀÏBäíŠ#è<fB¯¯·¦eÌÌ\\`%\r}™o<\"²4NàÓ)H·iTÕÉ³k“?è^g…İUÑŞJL!N_£”³®î£m¥D3©z,°#0w\nPC\naH#ØtßØĞu—ZÛ»4FJˆ©L«â“—‚§\"HİBF)pÈ7èxÌ Á÷råˆÜtsJª¨TñÁgÇ\ršüá¦µé~B*˜g…Íùg. ˆv%]QµIi;UJˆ¹H.Q½/k¾‚®±60H!¦Û'©ğ\"5]X\$ºU˜è¶t¾\$b\$TB1A¢uPDUc@'…0©;Ôjá=åŸ¨7ÇŒ\nÙ©bUĞ¬Bñ¢\"Š©Í8šã#óKvf.Ë™Âèå&œ¶Ô(Ä«—vÈ!‰ùš<Ì)ÃWT˜¥Ì›Æl4„­™«½fòT jÙj÷eÒ°ÂˆL¾Ç¤’×â0T\n\n¯!;Ûimå¦jqY€9K@ú¤Ò)‰ñ*›‚:ò	\$I)aŒ‚Ù_‹dÛfMÇöKµ¤~p¬d¹mö©-D«—,‰œ`îŒ9dq÷&:KIëtTåÃºØöí­ğGxœMÎ»wJª‡;åeâ‚ĞhŞ“‡JøÀ!¼ĞØ\nÆ€©»ìÍEªı\n–\\Ğ'ü6ƒuÅĞšRwÓ¿Õª_W*îÕ•õ~QoÕiX‡µCøoÈPU\n—Ô)â·Tm=¡˜Î)”eØ¡¡úù(=]¤ãÊ[º+¢[;ìht­t«úrVê£q[Ğ¯kaÈIfß—weusÙW·p%¢³âQ×Á#à-6ªÁ¶ÒzWÌ4Î¡åaWŒ7IZ_^5nã\$ùJGÇ¸@‘ñë4xîì¬1²F`n4]±É_[@€a\nº)½R1Bïk›²DGîLBEÃu£¸Ô¹\">˜Ş»–g’ÆRJúCÛÕ˜}J°`l&êl¨¾HŒº\$À+¨ûDav‘ÜT’}º’Ücéş¼ƒ	£‰1úÊwµ%Ç¬òú¯?F6ŸÇ“Å˜­¨íÃGŞƒGŸ¼ZñY¡{\"<jE|Lï^RLvoÈÆ`";break;case"sk":$f="%ÌÂ˜(¦Ã]ç(!„@n2œ\ræC	ÈÒl7ÃÌ&ƒ‘…Š¥‰¦Á¤ÚÃP›\rĞè‘ØŞl2›¥±•ˆ¾5›Îqø\$\"r:ˆ\rFQ\0”æBÁá0¸y”Ë%9´9€0Œ†cA¨Øn8‚Š¬Uó\rZv0&Ëã™­†©'È(œa7Œ&¡ø(’n1œŒ¦!»Ç%iA¸ÓD9Ï¡fó´?B¢Keó|†i3šfRSzi0™\"	ˆë75d%S„tìi‚ŠÑ‹&áK¥ÓêuqmNÇe“¨mB~×ÇQ%b	®¤a6ORƒ¦ƒj5#'Mn¾q²±oÛïI¿{<–ÍqÖ\"7)RÍ©‡PŒcCÚ÷¿(pìõ7ÁˆG»)B³,CXØÔ¦c˜ÂChÂ½7\"T6<mĞò1#­Èœ2M4@1Âˆ‹KZ”/Jj\$2\"J†\r(æŒ\$\"€ä,ÃjiŒ£“–¡µJô',(Çj(æ¤²Hb4ª*˜Ê˜„bÍÂâ\"P©HÈsêÂBÊÃcÊMP9\"3Üˆã(Ş6Œ££ˆ£ Òñ@P 7«\"Ì´-I¸\$GÈÅ‹\rbP9Á0°*KE4¸6£M3M¦,zìÊ&h“Ô5Âá(ÈKØèÖU¥l<×jğ²€S7NˆÁpHYAŒD4&íKğ9ÅôÕNcXÈ2§)ºk‹C}ÇĞÍÈ˜“PàP‚5»°¸¬¹ÌvšÔT˜	¢ht)Š`PÈ\r¡p¶9`ƒ»Šx2Lõˆæ\r£ª¢ƒ\nŒ0J”ğê\rÎ¦0!à^0‡É+‹ˆcxÜ34«‘d’°@ÑJÉ´ê¥#–£:‰‚3I»—!PrÌÁ:9“°2çì<0¼¥cjê6PH3–7ŒÃ3²ˆ)2JkD‰ã%NPÓÌ„³;0rhµ¾#^\$Ê9š1Ml•HÊ ‹qt¨¨A¾ûú\\­ê©ÀNÎVí%×hè–ŞÊ£5.ÆÂº;ÂÚ„o‹Š3À-‹Yğ£wÙqC–ĞÜqÉ¯ ‰mÃáÊîuG3¼ó^ıĞ!Ñ¯	;C¢C°òû¶å¦´Ê2ÊÇ-@É·\$\"´ê÷¯²¬ã’ Œ,’:ï¬xÁÁèD4ƒ à9‡Ax^;şsSïAr3…ëh^2eKHÒÊx\"Ê”9‡\0ÊxC+0Ähš1zKƒS]jq•\$sjƒÀaoYº9Ô,Ü’ ­HÎñ ¡0è‰Ñ<S'ø™\nĞFQêd‚ÁÊ!0\\Åq¯•ó¾—Öû_{ñ~f‰\$¿`äşså<µè( 	!´8\0Š”LA@‘2‚Q›Á­-r2JÔ¢\rğJ'¼x¯Xƒ5\nĞ<“Ã4L!i!]k´†¤t¢«HËœ€È:\$h8í‹1	;²Ü/\\+rTÈMÙ€Æ‘CIûDP58RBiqÃ/-V´…TŒº;‰t8GzpŠ\"OFèäû“!z€d\\£Aò”Î\nĞ@\n\n0)H\$X‰6’•U)·&ZÀéBÏFi(î;™ÁRqâ©ipÂsºQDœKæéÁI\n©DGj\0002¤“’‚ˆ&dœZBˆ£©ù9Â´úš%6!4¶sÁ¬˜‚\0†ÂF†í„÷•Q1b-Ğ†BDèKŞ9ó\rçÔÎÖ I	1(%QŒ‰QÀèäItxÒáWhnŒ`½AÇ,ÚÂŒÓ”Xk5Ü‚;¢bßˆ”¯\"„¹*‘&N™Ùoàî?Ià^ƒ¡.IMô„7â¢ê…nz\n]Õ¶§\\Nx \n<)…@@ÃR/d˜ êYØk=i~˜Ó2‰ÍRâ()E–v\\hàj¬á¤ÁeÚpV-r<0¢\"Œ'ì@aÏQ| N‘D³é?†ğÆ£\0F\n@ê38©4¼*sädÂªŞIyî'8…!4†‹Ñ£¶\n¾ÚòuN¹ÙS4PÛ»d¬åµ@Ç¨Î\\P®Uu²JŠÒØœÀíCÌ£¨„5Z\r!¬Ç³§Kgà'-àëºÂ€S‘ËQ\\ß%‚–C‰‘—ÓàRÜ,ßáh&Rø*…@ŒAÁ!T`“#“Å€ s£oèÑ¨¸œR°m¨ÂÁ\\µ¬Ô]F#8e%LÙn0–E!„6™BB)°(0|ˆ#r+ ³\n7õ°Ç›‡+ÈVh¢”—PêM»f0Mş+øß«Ä(.NEëÖò&¬~G6×Ô»XY!<za²·JĞy2éõÌø‡)à¦ Œj°Z• 2š¨‚eSTz˜£üê|®z³8ğ5«Ùó†Ğdä;\0 ŸŠèä-A,ŒB‚ĞnB(xÁÅúÓ¤¥´H£®&Tòã`w0ÔÖÍ6è‚êÙR‚·×RÛ:®cnh.";break;case"sl":$f="%ÌÂ˜(œeMç#)´@n0›\rìUñ¤èi'CyĞÊk2‹ ÆQØÊÄFšŒ\"	1°Òk7œÎ‘˜Üv?5B§2ˆ‰5åfèA¼Å2’dB\0PÀb2£a¸àr\n*œ!f¸ÒÅPšãs¤SËY¦Pa¯ÁD“qa9Îr\"tDÂg¸Nf‘Êƒo¢BŒæùA”Üo‘BÍ&sL@Ù±×¦ÉVd³©k1:0v9L&9dŞu2hy¾Ôr4é\r†S9”æ §Õ¤èh4ïÎ•ÍÜˆ¦h9\\,ÜşxA•‡˜cFÃQÔÔ =p¡’£täÛgètºæéfÇ™YöyS=ÌôbÜX,Ä£)ê^¬+NˆÄ³\n£pÖÇï`Ê9HZ|«‹Â2\ríój™n™Àæ;¡c\"D(A£˜Ò6…®\"ÈCŞ%cxÈŒÆHÚ`;	ÜÂ#ñŠå¥IŠP#ÍĞÂ'ã ê‡;z\nÓ?ÀP„ÊŒ#’¼	¨b)+ˆã(Ş6Œ£ ä”JìğÊœÈŠ¼5À\nÚº¯¬-´N2Rèæ‰Q Ò•4ˆ\$è*:#R ÛÏmœ'\rñÂÖ2Œ“\0è±˜Ä’€M\"RcS—KRT ÓNS¥-ğÇA l¥X®kP³-cõT\riÈä˜%Ë:‹Ğ P™¢Ò8‰3°3 ’ûÁk0ô\r@P\$Bhš\nb˜\r¡p·A‚ëì1Y«Tj:\$ƒ~6©@)Ø|†IR°@¸^ğ`Â<‡xÂ\$W…ä!±3&:¡—íÿ·îÄu\$9.bô!a‰a˜ åŒŒ£Â<7DÌBö³¿¨€Ú³\r“×6ªŠ3ÉrˆçLC,¶ÿ‰ã\$øŒ/ P¨7¶Ãjà<¸Y\0ê1Œmğæ3£`@‰2™S1h‹øÂìaÑN7¨ğP9…1q>%whá›(‚D*6P3~3b·kè4k+Å#D4Œ]Ş•º(Ì„C@è:˜t…ã¿;ZÜ!c8^Çğ¢ ‹±xDsHÛé…`\"TÃ!\nÒ‚6)AH˜Äã|R­1ã³?P™Ú<‘	«Â=Ã£œ?âÃÂÊÏ…Ûûb†p|/Äñ|oÈîã—(9rÜÆ;ä#w=y	#hàİÌpéÒc4pÑ8Â:ğä¡(Ÿ4GEoV¼°HS¢¦±Ká¼Å˜ÖÔÏƒA!ÎÀîCcN\\…©·\0ä˜˜aÎü‚4v’ğšcNjbœsH aL\0€»(ÃEÈ‹#D0ğ¡CŠÈ1F1ÌQ• ÈZÈ¸hFİD¿§\\€ ìò0 Ã°o;†@0@ÜQ cs8%´İÅ¹ƒta¦üÇ“2\"DƒºŒ/”–§ÂˆmÏ›Imp\$†s~şĞëI=pœ„Pà×KRDÉ‡sâ'îLƒBa]¤„ƒFÃ6Ÿ!Ù\$°!…0¤HP\"b„î\"ĞA—c¬ce c¢á\$\$Ä Á‹\r²Š'pÇ˜„V¥ã¨6æù›ãdr%v.ÍD’q¥Ñ\"	\$<šò2ßº~\rÈÌãœ@Cˆu7ÄÌ3 Òİ\\“‘ç°1‘\"ó	æò7­ŠKâ`¹¢\"8D¬(ğ¦HZ~¨°M\0A-£d¸ŸÚ\"‘0V¥œÏSD ˆä‹˜{,…‹àá‘#´½à\0gL(„ÉOA¤%a*D’àŒÑ:~2s'àäI²y3ê:a“în™pŠ50Ì\"´Ñ‘F—º¡¨âw0ƒ±£©g9E¾Rr{—QuX\0§ULuWg-ÃÚv˜JU^¬–^CmV«9Õ¨†Ui{ªÍfQŠ9D’ †˜ƒ`+\rsØì’Ã>c×tû<K\"¶ JJí&pLìP¨h8bÅÕC;H£˜J\\Ô©#\\Â­ ˜ÇX¤V[\riƒM¨µH¾·ZëJEã	µ\$NÕËëFŒí¹«d\$…Ó¯OİÀ3Fí,YĞÌ@RZ0F§@¤fàZ{eˆ0…Z-7xò* M™±AÎª™[Ë#©M‡&5Éaú>ÿš¾§hútÄ\\\0PZ;G•#„377Õ³M9j>Ò›pUÁjÚ`Ë„pq«d†î(ÀÈ‘^X+.(¥v”BJ²º´-t:“lr×#¬\rúå¤p­†Cg­eŒ4‡\0";break;case"sr":$f="%ÌÂ˜) ¡h.ÚŠi µ4¶Š	 ¾ŠÃÚ¨|EzĞ\\4SÖŠ\r¢h/ãP¥ğºŠHÖPöŠn‰¯šv„Î0™GÖšÖ h¡ä\r\nâ)ŒE¨ÑÈ„Š:%9¥Í¥>/©Íé‘ÙM}ŒH×á`(`1ÆƒQ°Üp9ƒC£\nD¢?!¥GÊâË:™® ÕÚ'°a%eœ•£|¿ÁD“qŒäe0œÌ¢\nÅm=c£/\"í¬šmF¯°’:¬¢‡D\"UêŒj8°­Şék:]\nHÆ–øH²Á ±•Âër9æ«a ƒ(ÚhÍÿ‘ÊÂ_(Ó™ïHY7Dƒ	ÛFn7ˆ#IØÒl2™Ì§1Óâ: Â:4c Ğ4¿ƒ Â1?\nÚ†é+Ê†4¤ÂIœ(ˆ°k³¹¯+“<F‰\$š70²)pšE0‘k¸‹/ì’ñ¦x)½£HÜ3 Î£Ë©C°hHÊ2xÃKÊ¾¾\$1°*Ã[à;Á\0Ê9Cxä—Ğ´c Şı„€è‚@„½5\ræ;ËÃ\"¶N5hS^\"(ã…(°¬Š­9Åê\"ˆò))9ĞÒ6›´¬[x°QìjŠš¢)R1)-)¶HÈ‹M\$†P”ø(’#R‡ Å|ÈNû¡HŠNUFl… Äš4Fr\"B‘–ÍU)pÒ“'Oé:ÆÀQí’–ë É²u%¬múOØJµ^‰\nCÔÍ»bBi{W\r2HÍ¿m£\"\"èBˆ¨xŞiİ{ŞN}ô¨]-İŠÔA j«väKR¯s‰—÷v±—È3 Ã%ˆ‹ˆ‰–¶ÜšDğ¥{‹/æ‰\rpŞT¤X’.ç:­„YÎqh	@t&‰¡Ğ¦)C\$ì6¡p¶<éƒÈºéæwöm(S-Z'RQù\n°\r£¨ç5‡ƒ\n°3ã ê9K#Ó.#ÈxŒ!ò]°ìbŞ7ÃHÏ³Œ»~âÒ?‘÷0A[ñÀô´7pÍ·Åkc(ñ\rÃ˜Ó»jÚm@*@PØ:O;Ò,ÿ3Ãd|2ÁÉ¢ùK3NZ[×H¨7¾#nÔ<„¯*:Œcö9ŒÃ¨Ø\rƒxÏacú9wƒÎ0ğ\\ğ6ÇÃ¬aJ¶QÙÉVm#)º·k\r%Â£Ù*¿ƒ7¯GÃ8@ ŒÀİ-ùÃ04Œ’Ø@ØOYŸÀô€è€s@¼‡xƒõui}/p^Cp/LÎ0:9x2ô@oÅ¿7\"ÉFŠF(k9›5¼lË#œ7ÅX<âR¹ÈZ\"PD İ)¦0yIh\n	¦…#äœ“ r^NH8“>Ÿøa€!–ÀX`\\\rğEû%°]`¼rNQË7p^›I\r¡ÀüØ0!+ˆÈ7¯#ü×k4a¥8%·tıÃptNì¬¥€jÍi5)YÃçh !K.dDB¸x³–}! Ñ†Ç[¸ çİã†#FÛîQ¹yÍScÁxoã¼—––e?²p‚Üğe²4†ØæNúJ)Ê ¯å´4	„™P…1C‘&ª¢\"»P¥ B‚\0 „Ë`K¹J‚\0PVJŠšS-X!\"T¡Isv\rÏŞO¿ú|O™õ>çæa\$ŞàOâjt±ä‡y„VÉFQÊí›ùÄO	ò7“in=ŸÈ¤Ó‹Â	G°ÜÔGiÍ:Ê\$ÃC\\oPJivhÃakÎ±uP¨sC\$QƒaL)dúÕqaÅ™›²BT¦¼?'ÆB21xIXj¹5D1\$ÍEDj›.2gn¤jËái¡ñ\0h”®Hfé‡¬§Hˆ§‚tÚ1<´5•yHyta‘ş&ŞšÍnÈşğâOÚkÉt6¿8\$ıâ5K!äš)v‚\"9úª'Š¹˜å`Â€O\naR¤µyÈ.ªĞ­ËŒÈ°\$mTÈl‹,’:f‘´8cªJ—&ËÖ¤ÕrDÍŠ°§äè^še³eæâB&Hlàº0µÍÁº#ÕLRÊ\0y0EÒRÖô˜Q	€¼¸sí‚0Tœ-©y˜Ö›èİŠ±„-ä¾…§!37ğÀİbx,Vé(›gü\"ôMê¹K:¨  b~T«[Á'’ÓÒ\\¾„9Àğ¬ÚT“ˆF9×8ñvàf²»d™‡5…š€]‰0ÊïU&aõuâCt°¨úà.”R–·ìbá3;o‚Í…PA}µÆ¼šés‚\n¡P#ĞpâßÕ|Gø]y[ûù\\Š*º(8¿YÆ¯Š3¡ˆ{2eÜÌºğFC9«Ä Y\rQ:5¥8ÆßèrÍj‘ÌÏä¬’Ä(êC0y ÄêwÎ10e‘ĞĞvyÉlüu–zÌÀ&¢¢K%—(:8¡eî\nR-k¥T|5SáV#\"ÒÛYø5n9Âó²…À¦–q\n<vÅŠe~xtò-k¸Š€¬ÊF…¦0ÍâÃimà±B˜e?~a¹Üc­b	\nˆ‹s³	ÚÛXìB›ªŒU…q>ŸœŒÖUÆ«™¡Ñ`bƒk…Àø3y‘„¤";break;case"sv":$f="%ÌÂ˜(ˆe:ì5)È@i7¢	È 6EL†Ôàp&Ã)¸\\\n\$0ÖÆs™Ò8t‘›!‡CtrZo9I\rb’%9¤äi–C7áñ,œX\nFC1 Ôl7ADqÚznœ‡“”ä\na¡!ÆC¬zk³ÁD“qŒäe0œât\n<pŒÅÑ9†=‡NÒÚù7'œLñ	²ænÂˆ%Æ#)²Hr“”Œ¦L•˜Ã—3ğ¹|ÉÊ+f“‘-¦Œ5/2p9NÔ\\šC*Ä!7øÜK\\ 2QŒÑ‡9’ÉÊg6Ÿ§ÕÌf‰àèsğ¢+¾Ï¦uøı®äCS™7Oe½n¦ºÎT»ŞÄ0Ö­«ªøÈ	ãZ¾„ŒÌëb0³kÊŒÆÃ Ôß\"’0•/Crp2\$â²¾64£r)\0”‹\0@£1J:e¹#bô9&¡ø(‰\$D‘\rÊH@òŒ)0'£hÚ³ Ğd‚ì»lĞÓŠc äÆ°ÀP„ˆ1¤~ğË3ş#Œ£|‡'\r SDó4Ë¼\0¯EŒ2j6ÀP™\$°ÜÜÕ3(€(,Ãlz’¡(€Ù:0lÄ~< ƒş¢ãò‰4@AE\"H(ÔÓÃ(ÔˆZtØjô¥Oøé/-´²×3Ò°¦ï=Ê3Éñ(ç@ŒëA\n:!iôƒe.7©@	¢ht)Š`Póe!l„¢ÀUy_9cN hê–„àÂ Ë¸è:Æ(95ÃÈxŒ!òOo\\Ş7\$C=È2İwld¥\"Û¤:1¨ í}Äî‚ğà†`Œë?‚&òz3¼ñğ˜4¦ë8Ù' Ëk˜7ŒÃ4AJºK5O9âˆàë+Î²†3àÛŒc`ò1mz—?â•£7å°äğÉÜBì±’/™Ø \\åº^Ræ6Îi›gÔı@0™şƒ¡Ô(~+¤iZ¶šØc©â|Xèé0°àügûÆ„­‘Ğ°7ÂÉ¦,Ú÷lc0ë¾8éUO»Û¨àĞ»ŒÁèD48C€æáxïÍ…È¯\0Ğ'8^’…ã%ç\r7˜^İ5*×_7sÒ2cŸS6ˆrd[éÁCú>à_±\nN\$£°ê2wpÌ6„Ì/ŞwÁwèò—(:rÜÇ5ÎsÈƒaĞôcwF<\"JOoÕÜHÚÀ²™Ø]öKãz.3eĞbBÚ9\$\$²‡\$ô¥H‰Ò/çy»§jíÍğm!„´‰‡c°ÂÅ)'jİ\\‡#’ˆƒZ^Hgß?ÈRƒ(f8È5†rEÚÛ=0‹dÑ¨T„ñspVÌ¿™8TÚ¡ç!,E±CXGIÉ@\$\0[›cix‚0\"RA@-¨‡DErf a\$åqš²µh’Œğ 7èŞ“MƒŞ6§1\n‘8 )H“N@†M™(qŒ\$<#ğøã Ñ!)Ï#ÖjC9zo:‡HË	g(ŠtzI\"´:1ì‘“	\"Â˜RÑ<7—L˜k\r-dîÀ`êŠ×b”d¤•Ÿ6Øah&Qá¿ÅzBNcŠ,ÁÌ5›˜õ•ä9hç,[¥üKy¤Y5˜”Q¡0rÉ<R˜To9~så¸R‚	6riŒD¦f½˜íˆÚ>\n<)…E µ‘Ìœ1Tœ=`šD&dz8ßMI¬P€i.á¬˜%˜ôl§r=#i•øYApS\n!0’ Ø\rÙÁP(\$ƒÚûÕaÇ€d†tFLY‘ğEDAÍC=jKŒ™ßQçx“Ó¥\nNŞ’5Ç|é(e°ô¨IÔ©€)GSÊmC 7h àT¢*Óêª†¡µb­š¸ĞªñÉ\$á\r'ÀVÑ´ƒœñ&‰*İŸój,È„Ö…,¥`4‚\0ª%X8¤¤á=G¥kˆ»Ò\r4À±j˜£H½¬d:ÉÇ+RÃ™'	n%M´Ğ¯‰yÎ¡‰´´×³NŒ©o‰ˆÎº…æ‘)¥5É#³Gn¤\n+\rÒº¼*½JDÏMÔrµ­rÈ²Q\ni,Ó»bZJ-­¾‡ŒmŠ©fH­˜Gf¼³…c*€ÂZ& (\"‡‹<q;î½ç )¶ŠñiÏ3íÎÕäÅy@„µÆh²\0";break;case"ta":$f="%ÌÂ˜)À®J¸è¸:ª†Â‘:º‡ƒ¬¢ğuŒ>8â@#\"°ñ\0 êp6Ì&ALQ\\š…! êøò¹_ FK£hÌâµƒ¯ã3XÒ½.ƒB!PÅt9_¦Ğ`ê™\$RT¡êmq?5MN%ÕurÎ¹@W DS™\n‘„Ââ4ûª;¢Ô(´pP°0Œ†cA¨Øn8ÒUUÉ¼†§_AìØårÂª®Z×.(‹…qg¤ª+S¤¿\\‹+²5¹€~\n\$›Œg#)„æeµœíô«•GKN@çr™ú|º,¯¼FÕÑİ,u]ÇFÉdò™X¦Giƒ§óST­rPÅå+ú_Ë5ÉÈ•Ê™ÆîÉaÊ^i6OCµ”Ìåq)ÕJ½·jÉ^E.QÅ@Ğ+°W@J„§êã,W(I{ø»¿ËÒ\$¤#xê\rìÜ\rÃx@8CHì4ƒ(Î2a\0é\$ã Â:7 Ğ4Æ# Â1E­ÛHµ%ú“!¤p¢°š”‹#%9nÚÒ—@P#xó;èj¹\"r\\ìÂK<ç´<‘2Jj°ï2èt ª8ª³³1ÍPd··Ï2âóN°x)ÄCHÜ3´(Q*Ú’ãÊÅ¢‚¤‘2Ó(š7¨L(\n£p×ãp@2CŞ9)\$o¶ÍÀÈ7ÆJ: ‰8áQÖƒl9õÉ\0´ïÄ—4‰’xÒÁéq\$ƒ²Òš~­Bpª7¿‹bJ2ÀÜ9#xÜ– ôŠ»I©p, ¸Ñ3è7kŠ™,Ï)u¼â*L¼@·drı´®ë‡2ÁR³\n8Êã˜¨93 —4NEÔ_ƒÔXa(P)‘MÁ\n2§	Qè>a>¶\\¾Ê»—ãòõ+ò†OeÊkÔ¼¤¢8Ê7£.*<ãô­=®o ÉYãÂõeÌ{TÉc˜ªÛYËY”\\Ï3H¨4’³åLtrBÊƒ)„ÁÓ(ÔµwÒs¬AzÓ8›j.¹ŒÏŠÖ]wh0]Ù¡Ît¸J2%ÊvÖú;Şú‰No\$Ç³h\nx<Æ\0TJ¨^|˜b¤ÀIÛÊ¤­\në¢a@Ê6¦p¤ŞÅq3’èéå¡sß’k)¯=X<Ÿ\nÙ¼æÅÚ¦®˜¦jæ¯~áY#J´.Ğ±_aR›|0ßgÙµ¸­ P\$Bhš\nb˜2xÚ6…âØóó\"ï0Í,şñÌîª‡‘`xßi\r£¨çXƒ\nØ t¡ÉO\n¨Cy€¼0ƒâÿ_øC[A˜4†x`\\\r7 A•Jú-ƒjO†èBn”\$ZKQk-…´nŒQ^&¬¥\n´Q\\|ƒcHu†hxfÁ±ASvÊÅp´+­|”JÕ#i®qú™öZr˜(\n\n½ØA\0uZáÔ1†4`Ã0u\r€€”†uÁb2Q€0†pÃ`ë‚\rª:£€PÁLG~¬§eXc­y¤¹Î´®ñNt5gî¹\nÈC–Š@TD*i†hHş”g2G°Ü¨#ˆc…á¤2*\0@ÿQ´Àô€è€s@¼‡y|‰\\¦T\0¹Q†p^Cp/Up˜:-™’ôËFò†Àç~òÈ8·+‰Àè®#VFlH_òIÖ©a#\nÓ!ÇÜõ ğ\\q”2÷†iÛ\næy›SÁWÎÀ§”€šm‘ÀrDêİ\\‡&øµ€i6ŠîW†beœµ–òæ]ËÙ0b*¤˜³dÂÉ‘¦{ÿ	.‘³PÜ&¬f¨è7·Äf\"ğk7¥Zª½)é:B~³hÊMÂ\\°“j'õ¶EÈpSw+É8Ì¥ğ}§¡“! ÜÇ3VĞ è²5†#pLÕ¾Í@UŒeŒñ¦5ÆØ;VÑÒ2ª„0³P@lÃ,­¤á¤0†È`ËÁZ~n‰jÑ÷±ÏY[‚¸äŸóø‚€H\nı5»:‡`*pæx’‚Ùbìj(Å\"	éOUå\n2DÈ¡\"Ä]^Q:´F(ñWõa_IHw¯)	EwZ{\rm˜[õÊDÅÊ¾ÓŒC'ø¾I¥ALÑjÚ3AÅ<©˜nö‡5p®ªÒ;a¢˜AYl*õr7Œ0¿¨Œ‚S\nA—2™QÚy¹+åÃy';›ì2eIİb'ƒ¡ÅuOp¬Ë–Dà–ëmL“ôùß¦×ÜM†şJ˜Y'HRL °TÅÔÏK®\"<î¾nŠçS‚°L†d¥x\nü,pÙàÃ§Û/D¨¯OSóLÏÕæ¯86EX5søLè­\"f1†·¨VüHwÛŠ@I aå\0É+-ª°6áº—#´bmˆuF\nÀ3* Û(å-½Jx1’“o\\­¢¶Eæí\"Ô‚Æ’×‰5w\0œ…\0Â¦?¨Ï´øÕ-z²³îÆˆ(óÆ×•p”õ,…ä™E‹“ŒÆYÌEµ·sø\n%Pn‚X9M:³—Í¥¨Dv-²HS›¦j”;Ë·±O2UYoDL(„ÀA!+¡Á*Y8ßK¤Vs6fâ	fn¸@ß·™ÎÚ0F˜2•(ñC<znÖòÉÛ˜ër°Rñ7[·Q«rbL=§¤ÆÚxÛÆáíâJQÀm³ïÑâ¬•†·Ü»Şvï—·vŞ(ß³®'\$“W%±ÃcÃ­­°h	Üà[çß|I¼qMr1nµ’úßp¾8“&Ùí¾T6¼êCkˆjûš­U0¥¡¤3F\nhR5®¦éX^˜:B F âÊ¬¾ ÊGÔ.ÌÑ§‚wÆt†3Âûá€ŠF¬Ãt±wñûOª“Â·¶<ß¤r	”ò¿'äõô¥Á×>œı›iv×‘	¬6Ş®¨¾dœ¸§B'éÂ,4ºñ	ßdO'¶dZŠÙğ™æ†ºáiËüÖÂoCè¥'¿\\^ÄuÓÖ‚cd‡}v7Š—%\nA”6jå®Ó’®”õ¡°ÜŸà¹!ZN_•q‚qYÖ”ù+*åcõ©—[Ü•J_|d¾uÆ	šÿ¡Côl€Wã%r†ÔnïV*<m¬˜)`AŞ› Éöş¦D°DN´‰šï!îÏ&aîºïI^ş÷€Êªêò4'ØÑ®ÊbR”æœíl`‰èğO†J/Ü‰¯ììåhø*È-\$òãèÜÃğ+…ğmbhJ`KÇ5\0/ IÙ)äkp|d„ 4.³âÌò¤úò¥(Á‹’ø\$®";break;case"th":$f="%ÌÂáOZAS0U”/Z‚œ”\$CDAUPÈ´qp£‚¥ ªØ*Æ\n›‰  ª¸*–\n”‰ÅW	ùlM1—ÄÑ\"è’âT¸…®!«‰„R4\\K—3uÄmp¹‚¡ãPUÄåq\\-c8UR\n%bh9\\êÇEY—*uq2[ÈÄS™\ny8\\E×1›ÌBñH¥#'‚\0PÀb2£a¸às=”Gà«š\n ‡ASZ‚åg\\ZsÕòf{2ª®q4\rv÷ Œ®u´›Tq,É..+…h(’n1œŒ¦™–æs»®6t9òK'”Ùv”KüÖ—!ÎAvyOS•.lšU²†™äØ´t.}pšçûâTkî½p‚ü+næ†C®í“´³>„æË>èB¶¼¾i’ô¾\"êËXì È*~’-h+øæ#Ğ\0,ã¨@4#³Œ7\rá\0à9\r#°Ò6£8Ê9„¤R: Â:8Ã Ğ4Æƒ Â1FKô“=Šº\n[;Iì·+c¿:l¤¨Ö´p,,µCŠ éÃ‹ì…—\$45·¯Ëâ Ê0¨¥=È9s?ÂûB.j†Q@oëš‘BŠ™`P§#pÎ¥Ï“.(cæñ´OsÌ…B¨Ü5Å¸Ü£ä7Ià‰Èn#Œ2\rñ˜A\0ç%b88ƒ˜ïRKfæµ-ZÚñ-R6û6Ó\$§9®ì#O³ş×=Ö€@ Šld²ª›sg «â†Ù«	3Ö¶7‰(ã¹0Š´ú0	²İwÚ¨,ø§«s{jô¾3”FÀÈ+m\$L+A\rµY'¯äÛCCgH£ğ:jŸ ¤ƒKoC>,· ®lÄ¾c²İ¥{cîj~¡¦VºJ§¬İñ,¶rÃ?§«Ku43NÌ³J<­*zæ·BœÍäê>•=jœÎ¯k(÷¿)êø˜ág>Ã“L÷iÙö¢ÉÍZ£÷‹ÃO¡£×»—´J0£\"ú?Hz–1˜A j„Ÿ´ëR‚Üa˜iëÅÃ\"¥\\Iê°ÔË\ršXûcÖër­ºÙFënôMjÈ/JÙĞIî±­ò*º íø¯=F·pÁ†«’JƒzB@	¢ht)Šiî(/ËØ_ÏkÊ‹n‚².ãıÇzÙé—ªû|);¤h6£b+ |áƒ¨åOŒ?…D0!à^0‡ÉçÑõcxÜƒHg}Á•û?ƒJìA \$4aÕ\" \rĞ8ã@A`°eğ70ÒÿCl?­¸ğ\0 Ø’D €0œ(.ƒ0lQ!•\0 W‹n\ríŞ»¥ÂG•ûdlÎ *ôR_ˆyÖ‡PÆÑ˜sÁÔ6\0ØÃ:‰`±‡(”C8a0%º‚\0Ú¢Cª<Ìœ”1šÌ@ná©Ÿ£àäNk ^çèğ½å¢Á—Ø\n\nˆ‘M£@Í_*‰à€ †HÎ•\\p€4†EBB#8A˜‚ Ğ p`è‚ğï)Ápa‘ĞÍRª@ÎÃ(nê²	‡H?,>–ˆîE@Wò¡¢Œ·[-Èï!„öW—Ñëëf ‹…‚G‹İ#Í=µ&ËÏr>ÈaĞ2dsW2–'4â#ÀäŠ•Ê»MÚ\r\0Òp•ì˜2h2ÉÉ=(%¤”Ò¢UHõB¥t°–PjAçüÁê	!´8#\0Û,C¤¾€ô=öì£0a\rg4«uC\$€n‹ğõ²W\"w	ÊÄ<‹œÒ‚\0§0_ñ<g0†9lÿ\0wEñT1`à¨d8r¡íØ0†iÌ¬¢tPqJ*Eh±iÒ>F¡ ã0ÃCÁÁ²Z‘Â!	xAä:Ÿ¥_ã‘Yˆ)V\$»ŠšÈ5Ë.9®0s‹X \n (\0P]JÙ+¼¾â†„Pà¸\$T©JšCú™	‘E'`)ş†é!M¤R5E(­¢ôcVÑR¶F‰W†õcWâ¸w«g&iW„¼urP	|Ù§¼‡©œ.´“Ì9«ˆ¡ú>£¸8F™ÑRçSvèü1†ˆÍ\0dø §µLã0Âùa£›ä‘»ÕĞGa aL)fïlOµ³O¶Úï-šÏc­j];&Ò•M¦H_û 73„¨œ–´[–âÚ|°§–°…ìqH;ª4¢”Ä0PMI`?§ªl DlİœÌs=a§DìİA\$‡˜V\$­¥V'7Qt~q¨ÍX†eFdd«’á)ğÇÎ-S´ŠáZÄ¹xú°h.÷¡™¢Z¬=ø'å¡”\\*.¯r8`…-“PAV†¼\$K7|<«x-bø·eRñdŒÊ/p‚“'JS—1|-Ôæ°æ‚È‘<DŠë)ôqåT,ºP—˜ EÓÌ#Júü[°i¡ŠÚc\\n¬•\n&T°ŠcßœÉ|äT™)Ää™ÀÙuq^C'ù{5Èw &Rí\"Z9ê\\ø¶oRSÌéİ!;é«	êî.mÛ^ë]Mö	³uîyÙìù±“ƒWÄ¾ñB€Ø\nñøia¯3VK¸Ïº&DüŠè¬ôf|ªÆéÀª0-JIâ…O#.ÆPÛh–ç`‚-i\rÕ{i‘Óí˜ş§r/j¼[SÀ‡sy:A6‹ÌvLr>†E2Gç…Oê)Î•:°B\n&	±óf»©)5ŠzÔiİ-›´F7~f–w^åA,îm½m_ªC“y¥ñ{”Ú¸—™:'07èÚ©Z¨]RUH¢zn9*’:İC`hDm‹2­Óf%N0…6«nYÕXnzÅ™=É¥²7¤Şç¸K‰=n.h2‚†öikw‰ùó\$()ürSfû¯8ßÍg1 ";break;case"tr":$f="%ÌÂ˜(ˆo9L\";\rln2NF“a”Úi<›ÎBàS`z4›„h”PË\"2B!B¼òu:`ŒE‰ºhrš§2r	…›L§cÀAb'â‘Á\0(`1ÆƒQ°Üp9Î¦Ãa†l±1ÎNŒ5áÊ+bò(¹ÎBi=ÁD“qŒäe0œÌ³£œúU†Ãâ18¸€Êt5ÈhæZM,4š¤&`(¨a1\râÉ®}d=Iâ¶“^Œ–a<Í™Ã~xB™3©|2Éu2×\"ÆSX€ÒÃSâ8|Iºá¬×iÏ1¥gQÌ‘ŞÌš\rï‡‹;M¸no+¡\$‚ÍÇ#Ó†Ò™AE>y”ÉŒF½qH7Òµ\\¯Š¦ãY¸Ş;¤Hä'Ãd1/.Ş2aüÕc¨à8#MXà¼cº42#‰Ø@:Jğèš+©Âj2+É`Ò‰¸Á\0Ö­«ªøÜ¿(B:\$á„¢&ãØÔ–1+,0¢cC£;OÈˆ¸ïR<ÄƒH © P2êè´ ÜŠ#HÇ)>ñŠh°– P¡\"\rêì­&)>×¤ï8Ñ&óp³:ÅÍCT£\"XÔ€MA.j†ÃS¼€9A l¥(Êo –ÎTk~SŒQ[8‰\"/ZnÊ P˜4¢îj=9\"£kÖ2¸\0TåV=ïX¦‚ t9 £8\\0…ÂØóc\"è+\$¨%hSĞhÚ:mXx0§aòî˜IS\"<ƒÈxŒ!ò9kÛ\"Ä3\r#8ê»Üw*øß\$IÚ|4mš÷#L0İ}^wì<2ÀÜ·<KíU|G86K`Q†C¨l ÈšNI æš+ãÔ|:Ãª‹‰é ×ŒNx›ZÃô®„}¸:»c€6¡hˆÜİ­ÌàÂ8D˜¹bPÔˆò#Z9¼O A\ro äÅçsB~fíÁ”ˆš9†™/«Ú8 Œ‹Z%F*h¤ã‰ ôß8Tq97ƒZÉ©½É.çk„Bî3¡Ğ:ƒ€æáxïÇØ{JB‰ÈĞÎ¤ázSƒKÄ„Aô¸3–|8g²NM»oxÜÚÎ¶‚opcÎ”ƒ êbÈ:ÑÎ¡©!“‡L…h	®Ï„¸Ì»¸ÉçÚç½Çt\r8Ù3§3nq–;£‚‘pËÂpÜGÆqÜ€ÃÉghß-Ì\rÜÆ\n“á¨ElÃÊRî™°m‡áÆ´»Ù¡‹Z¦0¥ÃŠÌCc8{bÅ¡²:,w† ¯¢åX”Mê¡\rœ“6Â^ÚèŸkâÅ°¶e©Àh,˜Ù=&š`‘À7„U99Ò&EC¼‡Äp*¢Üˆß	 È1¨VvH‚€H\n¢'9ÒÎHYĞ ì¸>¨Hš:(eÕ”‚”8yDÁŒÊ2h& ÊJ1‰8Ö›\$JÇ[£èÅÔ²àb‰\$?\"äf%xt‹ss1!¶\"¨G	CI\n ‰µğZˆ+Ñ§ÉEè\n2Xh9( îF\"áÃ’Âö\$ôlZ”HaND¤LZaL)hºŒ9â¯`#¥¦ÓÍãÄ#'àâ@H¤	&@\$‰–²òª’~/‚L1”‚Ù\0Lwïz ØÌD¢º-H‘!#3^L˜d<í¡µ OL	\"OøŒ¦®K	’ù,µÕ4¤/'s*„%<–CzxS\n‡D‰–çy4S˜D,A3âBĞñ¨H†p‡?š[‘hu+¸‘&Ôˆy]:\$mÂÆv‘ÀŒ\"œË¥LÀ1PÊŠš˜S\n!0£—ù¹CEáé8p‰Q\"ÈJ*(\$J|½Sdy*dˆ-í`å¢|„‘9É\0ÕÑxn\rÕa#•tX‡FzkR€PM*²@é¤{Ú\"‡}æ>×S‰]ëBTS0Ædâ'áñB7ª|ÅDX^Â[\r€®\$Yµ;‰ù+\n¡P#Ğp‹ƒxd+¯}\"ˆ!‰¤CµÎmº©j´+w\$Ò¼¨*öjë¥^µçØ¢kfĞÒ™3eéº«Ü”’è¼4)|õ6~HòQNI@‰\0£§r¬K)+6ÃCÔWbŸŠº÷.ãGöˆ˜Óq|(¾1Ä\nÙc.fF’T“FhtN>*A²ĞØÌ±d;mŒ:«UnkkE“CĞ¦³È¢¹	a¼8¤ÜD+5Õx/!çH—Í\"[M})~\$\rõŒÕ\$ ¨ŞË!‹1·x9\0";break;case"uk":$f="%ÌÂ˜) h-ZÆ‚ù ¶h.Ú†‚Ê h-Ú¬m ½h £ÑÄ†& h¡#Ë˜ˆºœ.š(œ.<»h£#ñv‚ÒĞ_´Ps94R\\ÊøÒñ¢–h %¨ä²pƒ	NmŒ¹ ¤•ÄcØL¢¡4PÒ’á\0(`1ÆƒQ°Üp9ƒ(¦«ù;Au\r¨Äèˆ*u`ÑCÓâ°d•ö-|…E¬©X~\n\$›Œg#)„æe¬œëÉxôZ9 ‘G\"HûES°ÎÑXÄj8±ÀRáÙ9ÚÖ½|_b#rkü:-HƒB!PÅ„£RĞÜD¤¨iÍyA	Ç–x]5ƒÒà¤KOc™J×vf[5•{¸±ÙfØt¤™k Òâ‹,TIjh´…’0Ÿ'\rz~²8È‹°²\$\ry¢ê*©.ç#Î‹4n‚À¡NÛÆƒ4Ãş¥Ãª*Ìü0(r}¤‘48ì£ÙÃ'plA\rDnÄ<“©èÃø@¤Èã#)ÛŒ¡Fñ^ÕÆ­sš§Èã¤ï	„X Äó À°ğúì?œVù¿	ú/å‚¼H£Í´,‰)\nø¾êZ\$,\nŒ¡\$¤ÊÃ·H‹ªƒ,,ğF#“šM!d|š¸³ÓÁ#ÆeËìŒEMëj¥)†‰ÁDm­+Ëª±)é›Zµ+Å;šQH1(áµ1;…E ÅÒŸ/ï!¡YÌ&‚Xâ¢ªz_\rÔ(„°hnÂ†?ì!T‘õË±(¯É3‚@ ÄÓsWÏ1›ºC Äû‡@§bQØ­-/\\ÈáxµÒ\\š?ò G<°JÚ£ËâJ@A(ÈŒ7\nªBàØBW á˜<5…0` ¯È3*¨\\xøcr°C\n] ÎR‡ÔHjXĞÉÈZì–¨1BÜÜ,4©-7†XÖFÕı©'¢™‚—.¢h3m.#(5šI‡FK©j9¹Bƒ'Å \\›p¶¨ì™\0.è’Ş\"[Ú´Œ9“è@*Ğ| 'è´r´È›jhı‡xÂ%Û†å/ÊNLÂ\\U¢'¾ïí\nP¬T›*¿²³ÍF9£\rº.î1\\›Z¨0”FV[ÓÕÖµû\\*‹ò&†oUUè6ƒ–‰L%0UN^«Ù¼s»*;-ß¤š\r#NO	}‡F<W–Ş£¶Ä§9ÕOÊ~XÇÆ‰\nŸÏ\\Œuù^aJÒò,b«r…êÎí#¨£¥U…ÀO‹8y×˜h~tèôI¹‚z„Ñëç°±^Ú*(O}×'eøÖå#Oœş>‘²Ãía¯Àã ¢(œ_«÷xäqä“ÆÿóĞ\"ÏJˆøÌ	'°-ğ@ç®a^u‚mÔ¡ˆ.úÈËî%n¿7xíà…yğuK)ó¤¦ÕS>ÄØ\\ãê#ÖÉj&ÂùG\rO‰ú0iğú’%òNA\0A´4†àÊ”stHÌæAÌŠ\rÓo €4ÀÌAhĞ80tÁxw‘@¸0Æ¸ÛÁpoAœ†PÜÃ o\rÁ„:™4Ás2Hlšátã6”‹Q€ÃÖk`»ò8i\0FG ¡ÔL³/Än.®#KÃ Ò(—_4<{Åæãf/P©BgÎ¤ÌTSû+@­°š–’*÷Û„|ÒAHI\r\"\$Pw‘’:7)#\$ä¬—¡à:I`ç'¤¸\"pg\\…%#é\$f•qh½ÃCbü6¯ˆñÒXCŸëÏeSZ_“²ŒïeYPšgø¤Ñ,ÂáãMä8bœüV•‰m“äëV\$Ş;§Œ¢ b‚YÔ(p¼¨)÷ÏDa‹Ü™\nbÍcÍ2iEŠ„`[)„4l¥Úş©hÂ‹EB¼r“#£t¤•P’Ô4\rš™2jl(€ jÒŸ«ˆ.4‚´\nhA<¦ğÍiRœPÄs–¦±Æ±“ƒE™|2exO§yvN+Ì?I¨Ç‚j°iEF‰Åí&U¶Od­CKmºq}UÎÃÖHDÕt¹ôŞiÑì¨S,h\n8caŸ=w›+¡¶èmì]‹\nõaMÂjéC¤PØ’ËŒ4Du !SjÕR’Â˜RÈRS ¸B!\"©‰Ê¥Ğ›9l¢ñei‚Ş+ëxV³P™7¹©iK!Í[&ÎÒ¸Æ¢+ôc°4ZÊÇ%{ b…¥³\rE;xÀ…ô'*zîF‡Z+ÖÍ	QçÁMG(¢î ñ(Ç`¤¤R8ì›Q²vÇl¶OÔv¼GPGBå´Ø&|YC0ºÆí¥ÚÿzëÑY‹À@xS\nŒÍ´À(iñ dÓ\nü@lxBÜÍ›Á÷ä·-lÈòĞO%HìÖ²LñqÕDÖ’¢(Œ’âšG¸¾Ë~†ã¾²laÜ<nüíáIw*œ]‚\0¦Bd>ƒ°`¨¡Ís¸Yi¸†upì•@ˆ*©ßÁ„Ôğú¯¸(*%¥k,ş˜nŒM‘'SLQ¼AÏQ“7U53Ù¨Ö”jµauŠE™ÏAD,äì!ÀZq€k½Të–'ØOak‚yª1 ×Û)p`\0İ¨lvÅ9L~/‹\0¾/ªŠt6.©¡¸SHÕJllµÕW\" ,È÷l¦\n¡P#ĞpK ÚÓ‹	Œ]jW6lÈc*Ñ­ÕüYö\"z89àÏ/Eğ¥µ¾xwWwÌÚRÍ‰ÅYÃÊã*-²ÇçI°›fqGÁŒ!c§r¾Æİl¥ÍÀGş	ü~âÉzE‚4ì‘zØFZ=âú¢¤ª2”ÅIÖ‰ÚNpk·3×İ8Ä’Ò\$|¡Ù‡±H0×jÃLkN~uG‘}:¶•³ŸatíPâõ§ŒSI¯ìc®-Q cc;¢T4AÅì ˜+ïàUø¿pöÁÄUï†ğjğÅ&ÊÑ“‡g[Ú™UôE|%VÏ6²ş\0ha¾ÈNç+ Â¡“ò»Oi¦!ÏåÙ¨G½©˜æ’™ÔîÊëÄxİE×ÍA#­”}º(§ùº…À";break;case"uz":$f="%ÌÂ˜(Œa<›\rÆ‘äêk6LB¼Nl6˜L†‘p(ša5œÍ1“`€äu<Ì'A”èi6Ì&áš%4MFØ`”æBÁá\"ÉØÔu2Kc'8è€0Œ†cA¨Øn8Áç“!†\"n:ŒfˆaĞêrˆœ ¢IÌĞo7XÍ&ã9¤ô 5›ç‘ƒHşÙq9L'3(‚}A›Ãañp‚-rµLfqÜ°J«Ö˜lXã*M«FĞ\n%šmRûp(£+7éNYÑ>|B:ˆ\rYšô.3ºã\r­Œë4‘«¢AÔãÎÎsÑÒ™„ãÂuzúah@tÑi8[êéÚõ-:KíZŞ×ºa¼O7;¬‹|kÕušlÌÚ7*è'„ì€ÒÖŠ‰‚´®+ÉœÓ‰ËĞÂ‹£h@<6`Ò5£¨ü(ÿŒàÄ0L8#Ş…!ƒŠ,6\"#ZçÌÔB0£*â8¶\r‰{ş9…Šè„‰\$R®¦ƒòß'Éª¶®«ï£¡¨ª8ÈŠN´p´»)›àï3Cˆè%ct¸\$oÄ45Mäÿ P2¯IrÔ<Œ(ğêœM\$(&k\nä\rËÒÖ(E1[Püµ¢ Ş‘#ÊñF4âË€†£`Ê6Œ£tÆ©1ò:5)\"K‰Èé6 PH…Á gX†-pÒ½m:*µ©ÈÚŒ·R|òêPkZ!* 7ÀP‚’M©›°·MS²)‡Bhš\nb˜2Jõø\\-7(ò.ÌøßMóÁ34ÀTš)ğ|¥¤C°@8È‘L4‡xÂ>6,9yt\"{„+“—z^Ã˜Ş=+ã>£²k|Ç¬‹RèÎ¿6éd2h×N¦U!ã)B’,<Ã`NÊìÇÈˆò7+®K¨2C¶ˆYN^Ö‰JCà¼„ã‚ˆ°Œ#2m—Ï£’âe‹rÓ ÔÊî_~I\"-ß•à³®r¦¹z†¤6êš³­H¥zŞØ…ëÑNÀ¤ìZR4™ìÁ>Ñ~ë›jéîº¾ñ¡‹”ú.Èè‚2'ƒvˆh+2Õ²ŒÖ+.™ÛŠæ¦–(ĞŒ’\rèæ0#0z\rHà9‡Ax^;÷r5Ìèsb3…ôè^2.	XÒ¸á}3»á—ÃKr‹à™œb¼\"ØğÏ¡iñ©ÜS¾:¦HÜ‚˜GÃ|}ñÇ½Ëb_AEI.éò¥ÿ?ÒG\0U<Š<)'¨ƒÎ!Æ&‰4:‡0ê†ó¯4a•Ù;GlîÓ¼îùÌ7‚ğŞ(nx¡à:)ÒDóöGx™âÜ’Ø³Ïñ=4^ŒQ™aFÇ	– `L’J/_ì9bHE\rR&	†Ä*ºÑ0pĞÍ7f°JĞÒ“W-•‰8`Î!Y’…Ìv)²:ÙBDX\n±8…nD¡Ê5‹	xÄ\0@@PHL¥\" ÖXÇÁA>'02–8Z¢aF)(Á‘Ğ’IyÒbˆœØ®È[Ğqn…¤œ”’²(Ñ	ˆu‡B,Á<ˆ#+©vFsòKÌ‘4¬å«³ŠÙ6)1’TˆÈš»z¥‘©†Óğ¥\"ë”ò¤†0¦‚4Ÿ&(\02rD\rói<(Š¿6bJ	Q,%Ğş)‡¼\r›cp¼‰²pé\0IG¤÷6:ã#‹YÄÈÙË×\nŠƒ±¾rï\096R7c´Â)j\\=\$'ñ*,„Øß àÌƒO%sl(ğ¦\"d¬-0Dä@ZO¼+TpÁ0·¸ƒ0iå¼·‡8j–Q/3GØ†>éò÷ÍE>L(„ÂjMÛh ÁR=ùHq\"T8ßFŠÑ)#U}“şéK»t˜ÅÌ„U4§ñ@±6F`Y”Ú%‰áÒ•úİ\\+@tdÒ¼˜VåL¦ki\"Áê~ªúG)I°äÎX;\n~kj;\n*EÖ1°OaM†1®œã‚\0†@+\"§Ú \"JB F áîC…-b/ˆŒS/ÉLlêÕ\\€íÄ[S`l¹D²İÖã0Ò,;ëH‰K¢.FHÚ²ö=\\€ ×J B»>e<„Óûˆ”sg'€Ñš£°n*èy²´öéÙ•)]EË¼¦Ìœc­Ü#¡A‰F}²,Pq¤4™u˜ª¤kBÉ‹?ÈZ&Cxx5EÔÛ‡RÌMsÊ7kÈ¤Ûccb_…·K†…x\$0Üjap;Õ»†[n.Lwä–“—*ğ\"ì›*İJsxe~\"E)µeBõsZ5‘À";break;case"vi":$f="%ÌÂ˜(–ha­\rÆqĞĞá] á®ÒŒÓ]¡Îc\rTnA˜jÓ¢hc,\"	³b5HÅØ‰q† 	Nd)	R!/5Â!PÃ¤A&n‰®”&™°0Œ†cA¨Øn8Á1Ö0±Lâ³tšhb*L ¢QCH1°Öb	,Q^cMÆ3‘”Âs2ÎNr=v©–›˜ˆ8]&-.Çcö‹\rF 1Xî‘E)¶C™ŠÒñâ	ÆÜnz4İ77ÈJqm¬©U`Ô-MÈ@da¬±¦H‚¾9[Œ×µê\r²İH ê!š¡Äêyˆ i=¤×Y®›d\$ÉIÔäXWÓxmmt¿ÑWjYoqwµóùD¹Ä:<6½¨£à\ncì4¡`P°7˜e'í@@…¸°#hß¢,*ÃXÜ7ê@Ê9Cxäˆƒè0ŒKû2\rã(ç	ã¢:„Møæ;ÄÃ#Šê@à‡¢¨»€\\±£j LÆÃ¤JŞ”)l\")qvO„”Æcê0Iê~¦l²èßKÅÙ&¦BÅÙ#Â%\0òîA:0£ª K P›5\r°hóA9ã8*\rës)'­ƒJ<¤(¥ÑZ8Qtœ&3¥(;%re&¢…ll ¢…KÌb[]\$RàPä8É\\†,µ*7-J	=<‹Ï#İE·\rĞJ23Èè2…˜R5@S¢¡A b„°Ó%d\0005½Åûu)4K@RÆÃ T²†Dó6eÑ^1Y¥h¼·BBn²ÑåÙ3‹+t49=(·P\"@	¢ht)Š`P¶<ãÈº£hZ2V\rn0Ù„¼ÉĞ@6£œl)Ğ|¾ƒ¨ä¤XA\0Ã#ÈxŒ!òC˜fBŞ7ÃHÏ›¹ş‚ÁDÊƒLÂ1\rŒU(İk:†¹•£Å–7cNÁê–L9Á©(6J@P²7«ÙÛ7ŒÃ6N7©\rPÎë©vSØaB:9ÊSDa–¨ Â‹‰º. Ñ­ª:Á°ì«Ãd°AG;¡Ğ¿D¥\"ó\n½c„9†]FÃsŠ0¤5dÔ‡qQ°ñ£ŒéèÑa\rq˜Í®e°B ŒƒlôƒÖ4Œ‘A˜t«èÌ„C@è:˜t…ã¿Ì>oŸÄÃ8_¯ñ†¾:mCp^ß”UåiÚBš#¢\0 r~KSI—Iü::GLÕ±ŒW\nÀÈ°Ç©U:HUK¶ \$l¾É×&¡¡í=Ç¼øä|Áİô>§\0û“î~\r•³¶–ıÙ‘·BåÅ*’h_ë\"Iäâ™´äÀK hHbé\"»cÍ¦ ˆÊˆ`Cc~pÒa˜0!Á¼päC*È!™e‡\$nÃTÁÖ0†ÀŞJD^!ÄœÀ¦Ğ]ëÕ\rÏĞ0†ÆØiÊk.i^&%’ —kQEµÍ@L„‰èØ(€ I×	^0Â\\†¦Ó.IC{ShÁ¹ÀE§”CeHpA¥µÏ]Š;FmY#iƒ¼i'®X9‚(k\\’p6èŒÀËÂjÑìqj*P4LĞÜª<GÉ\09,€îÂ	++iOz1GÂè˜Œ‚MK€® ¤MBS\nAdÀã,ÕÊ–‚®°Ì‚ºÊÈ «¯	p£gV¥Ú‚ê‘¤5[’sş\0(wF\\;)Ñv(Ñ´Dbm}óI)h°1§È˜™Bk*\"pÃ'Å)Åxƒş+É8]P RHBI-àß½I{ÃrÈ!Òğ¾ddƒ2%@1çBøø\\#±€”ÍËtQY9?+ÊTÇ™'ìxS\nmÎº*7Wó§O¸Q­òG8Hu%†AÒ¹‘nKC¥\r¡B–Q1XÀCŸ#,.~ªnãK|ŠWÄDÇ9DÎMŒšK1¡*Ióøç•Ñû6Ğc\n	#Ù#&eäH&§~ƒm\"j¡T\"|ªkReÏñ¹'D…ØHÈ›I;=`\"U8ä†\0CJQ†Ë®xfÓË\\‚ìEÄ+>}İÂ‹±ò0Ç¨XâŒÁ&¤4'át*J@U\nƒ‹_f'Ñ97çáÕ‹FnÎ•‹\"Aá‘œFJqêÒq?Š ‰#@PQ¬äÃp}ÀDT'Ú\\óksˆuº,BîŞV»”›niË<LŠw,[C~H\nU‹	\\å1;ĞHcOÉÉ@¨ÅÿvXÅƒÎnc€¦TpŠ0¤@F¬4*I-*Sbï.„&,<ÁR¿-õÃ¯uZ”¯5¥[²hdœÇA&Î#ãŒ¶wf-2Ãõdä€";break;case"zh":$f="%ÌÂ:\$\nr.®„öŠr/d²È»[8Ğ S™8€r©NT*Ğ®\\9ÓHH¤Z1!S¹VøJè@%9£QÉl]m	F¹U©‡*qQ;CˆÈf4†ãÈ)Î”T9‘w:ÅvåO\"ã¨%CB®r«¤i»½xŸMÆ3‘”Âs2ÍÎbèìV}ˆ¨\n%[L«Ñã`§*9>åSØœË%yèPâ£uâYĞ¾HÇQé)\"–:—‰¥Vdjºæ²dò©ÄK™:ƒt¦RdÚÒ(°t/Ó0•Vc5_§hIG*†å\\­œëµ?M[œÏh9¼¾‚ÙÍ£ÒÂQp”·C„«qˆ…åH\nt+Õ®…B½_âc©ÅS>R\$¡2ø•í{TÖ-Ñ& Ä¡^s„	ÊWÇ9@@©‰nr?JHŸ—kÈâÄIRr–\$ªÜM'\rzü—–å“¸£@Å‚„K´å*<OœÄ¹lt’å£V’ç9XS!%’]	6r‘²¬^’.™8Æå8Jœ¤Ù|r—¥¬Î³ä‰ÊL•\n²°­ç) F±¤,@ªÄÊ¾^’®l ÄNÓòßœ¥Ùts@íhŒntIá0W±„Lˆ\\t¸j’áÎZLG9R]!Ùvs‰¡zFœåé\\‘Í!tG¾a.ÈG)TC’¹Ò@6¤YR«Ñ²Œ	@t&‰¡Ğ¦)BØói\"è\\6¡pÈ2Uı‚TĞ]œhê9\0x0§òê:£Ü7˜Â9Còã|¾]wh†7Ã0Ò3Ş#-ù/A\0Óy§ãÄ+j´KMÅ{‹¶-Eª‘(î06ƒ’Æ²”ÅÙÎ]ç!tC5äâ‚E3<ÒsÏ¤=INòáHX¤tV2­S‚C¤\$Òİ&•åºoDÊ1ÒI1ätèY_‘1jÑ!]úËŸ5ä!>t8ü«È6áã(å§ÃGA\0AÉ\"š«[ùvV]a\0ĞºŒÁèD4ƒ à9‡Ax^;òpÃ¹î£\\7C8^2ÁxÉ‚# Ó‚á|Ö7ea|·AX¥§IBZ\$éD·8X|¤\"\$ÓâåF:\\w¼j:áßìÓÇ€\\+ó”.rø¥ù§|/2ñ<_Çò<Ÿ+ËîƒvíÍó¼ÿB2§@9ôıEvÃQ,O÷Ø±,\\œÅ™*IÑ8®c˜XÈ.D(å‚Y‘‹ñ\níŠøcEaà¼4ƒĞ‰¯¢s	1¯I†(¥dD61'Ûy‰âl\\&áJ„#g¨!æ¥Æ´‰E9˜†¼ÊgìÈHì=6]=Ã\0@@PıQÅC°'ñç”JIÅÁÁÃ˜G\nT ,8‰†ÜsŠ±_…¯5\nDC\$kË!Ÿf–!4*…ÏI–Dbàşª1Ì(ãx8Q8WÁz9”zƒ C‹D€+‡H¢ÔÇ%ÑAHˆ)… Œ¸¤Œ0ì¡§” x‘Wä˜ÌQØàèÂãöšI	1(©s\náj¸Zà'hhrŠ!<®…zdb˜Aa<'\r€¨;¦Ê=±ÜŸ+v‰Ò¨@f¢9…ˆ‚y	´B‹4àÛ¶wè¤\0Â¤KP¥ R\n¢a0Ú‹gäÈH®ÅÛÔ	˜Ó!†‘\n«på\$8]ŠHá4¢y@á=`Œ€\"DÀ°°¦Ba=%çD‹ˆâ<ÍÙÊh‘F•‘£ {DÈ¤vÂæšãõ‘¼7ÂXXS:kKiÉà¨\0PÓA6+‘i‹Q‚>Sê„¼_LÄrÁã^#Já\r”†Ã»+’à‡Tâ|HAÊ \$X¿pèrñ\\ƒEÊ.%ÂØrÀè!\nJÀU\nƒƒ°œÊEK‡Tâ•Ô8«!–ğæXGÄR²jÛ[Ä ¹[¢d «39G@•´Êˆ v)÷n•2Ò\$Åô\\¶>¸XÊÎ/Š0BöC›Ö›?”4ÈĞô­×nîS\$\rhˆ‘\0e˜“™É^Á\ncŠŞ·¤„ ¥â/:¤°¦šœhò§Tø¢·\n½“C¥Âr,…4+Ôt";break;case"zh-tw":$f="%ÌÂ:\$\ns¡.ešUÈ¸E9PK72©(æP¢h)Ê…@º:i	‹Æaè§Je åR)Ü«{º	Nd(ÜvQDCÑ®UjaÊœTOABÀPÀb2£a¸àr\nr/Wît¢¡Ğ€BºT)ç*yX^¨ê%Ó•\\šr¥ÑÎõâ|I7ÎFS	ÌË99‹SùTB\$³r­ÖNu²MĞ¢U¹P)Êå&9G'Üª{;™d’s'.…šÌ–Lº9hëo^^+ğieƒ•DÁçô:=.ŸR¡FRœÈ%F{A¢‘,\\¨õ{™XŠs&Öšuƒ¥\0™r zM6£U¬!TDÇ‡ÇE‡©œë•ãtŒ×l6N_“ÓÔÛ'¡è¸zÎVÊÁ~N¾ÅÁZRZRGATO\$DĞ­«¬8UäùJt‘…|R)Nã|r—EZ„siZ¥yµ—‘	Vş+L«ör‘º>[!åkúì‘g1'¤)ÌT'9jB0, 1/:Œ¤8D©p¢ì.R´\$ùÌLGI,I£Åi.‡–JÃŞëJÉ„Å‘Ğ[¾eÉ|¬’¨C^t6')2T#ÅÁft+´[”EâL—hùJÁ–ÁÈ\\§=G6m«nt”©6WA(ÈCÈè2…˜RÁ“™ SA bh°d8™¬ç9R¶QÈ]—g1GÍÇ9{PÄq%àRZ—­èñ‡¤å¤@—„åò@	@t&‰¡Ğ¦)BØów\"è\\6¡pÈ2[vé&CÄMEB9„hê9\0x0§Aòö:£Ü8xÂ9Còã|Á`øH†7Ã0Ò3á£.10\0Ó‡§LÎS,®Rs±ÊJ“ù’tf„¶g!£ò4‘%ŠTpK»àPØ:Lˆt“e\\M…Ñ¥…átĞ¨sıFåÖ¿‰bs¤»œ…ÊÇIG&ÒèFšsQÇ1ÒH(ÅñtäiĞT”Î—‘YÍ/°¸D!>t„ê³\nÈ6åc(å¹î®Ù\0AœÄ)\\›kdA\"ì’88@4/c0z\r è8aĞ^ıÈ\\0òüÈä\rãÎŒ£p^2dè4äxDÄi çË¡t{9,C±…•8@®„NN)B˜Ê9~f(íÄt»ÎF|êRÔi\nRëÅFfŞ—Iëªu•×;dí³¸wNñÌç4ğÄxÁ”<*pÜú^h\"a.u!\"Áb%_3Âä†	QĞ+„™nt\nEIˆQÊ#³å|ğaõ‹òÂ(¹fìä®œ!Z)+*ìXuÆÍ@ŸrfTs‰´®\$&ÂH¤!4ı[ åoÃ”O‹sTpL¹	!m\"¤t’’ÍÉÇ+]§„PŠ…¡%I@\$ygX>òPÉÉ:ÂfŠTp,8‰„‡>.\n•›@ˆQLHZ\"NÄO‰]ûÇ%Œé»Gçi_5±@/™Ö;0t1z9•i3Ã C¢Á*#ËQl¦dr‰¹HT‘@€!…0¤\0t\rá­â“×:üŸ ™‡Xš±Ê%…r!Å…·‘òBHäòfÂ¸Z¶pà	¬R‚\0˜%ƒ3‘™ù¢(P\$Ó.9E0‚ÂxN1P}ÍÑ¢‹ByË@×4¥¦˜€\$(ÌsüXéâq1‘ÍŞøé¢ˆ¥\0Â  _§`R\n©ÚOG0¸dÎJ›ÓPÏJü.6óÎz³¸äH…in.¼ËÏã–+Öiö„`¨	Y‡¥”E…0¢	Aé me­‰Æº*%’D\"¥ï)Øš+’‰2(%´ctN™´SŠzlÏzÎmÍk«óØª @+’Ÿ9hèDQz,GHŸçFF±z#ÂL\r‡ŞÂŸÓö9„ù/;Gf„§#€9ø®Eå'	AË\r”áAF¥t*…@ŒAÁñF˜ñ¡ÕİéÀ‘ó†¶åô¿*å#i6bÍ	Ar´„ÉCÂ¨†Ü'©H›UtHNlŠ|6Q,®İc ÒP‚øC[Ë6ˆEà‰QÕ¡¸Ò4xTr=ZB€@¡iØßëß0b\$@»³>ÖÕv‹äxÅ[kÔ:\"übôZæÃEz˜\\t\n±Ì®•â\r¥Æı@EùÌ€­è¹,E³\0";break;}$Ig=array();foreach(explode("\n",lzw_decompress($f))as$X)$Ig[]=(strpos($X,"\t")?explode("\t",$X):$X);return$Ig;}abstract
class
SqlDb{static$instance;var$extension;var$flavor='';var$server_info;var$affected_rows=0;var$info='';var$errno=0;var$error='';protected$multi;abstract
function
attach($M,$V,$D);abstract
function
quote($Q);abstract
function
select_db($tb);abstract
function
query($F,$Rg=false);function
multi_query($F){return$this->multi=$this->query($F);}function
store_result(){return$this->multi;}function
next_result(){return
false;}}if(extension_loaded('pdo')){abstract
class
PdoDb
extends
SqlDb{protected$pdo;function
dsn($Jb,$V,$D,array$B=array()){$B[\PDO::ATTR_ERRMODE]=\PDO::ERRMODE_SILENT;$B[\PDO::ATTR_STATEMENT_CLASS]=array('Adminer\PdoResult');try{$this->pdo=new
\PDO($Jb,$V,$D,$B);}catch(\Exception$ac){return$ac->getMessage();}$this->server_info=@$this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);return'';}function
quote($Q){return$this->pdo->quote($Q);}function
query($F,$Rg=false){$G=$this->pdo->query($F);$this->error="";if(!$G){list(,$this->errno,$this->error)=$this->pdo->errorInfo();if(!$this->error)$this->error=lang(23);return
false;}$this->store_result($G);return$G;}function
store_result($G=null){if(!$G){$G=$this->multi;if(!$G)return
false;}if($G->columnCount()){$G->num_rows=$G->rowCount();return$G;}$this->affected_rows=$G->rowCount();return
true;}function
next_result(){$G=$this->multi;if(!is_object($G))return
false;$G->_offset=0;return@$G->nextRowset();}}class
PdoResult
extends
\PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch_array(\PDO::FETCH_ASSOC);}function
fetch_row(){return$this->fetch_array(\PDO::FETCH_NUM);}private
function
fetch_array($ie){$H=$this->fetch($ie);return($H?array_map(array($this,'unresource'),$H):$H);}private
function
unresource($X){return(is_resource($X)?stream_get_contents($X):$X);}function
fetch_field(){$I=(object)$this->getColumnMeta($this->_offset++);$U=$I->pdo_type;$I->type=($U==\PDO::PARAM_INT?0:15);$I->charsetnr=($U==\PDO::PARAM_LOB||(isset($I->flags)&&in_array("blob",(array)$I->flags))?63:0);return$I;}function
seek($we){for($r=0;$r<$we;$r++)$this->fetch();}}}function
add_driver($s,$_){SqlDriver::$drivers[$s]=$_;}function
get_driver($s){return
SqlDriver::$drivers[$s];}abstract
class
SqlDriver{static$instance;static$drivers=array();static$extensions=array();static$jush;protected$conn;protected$types=array();var$insertFunctions=array();var$editFunctions=array();var$unsigned=array();var$operators=array();var$functions=array();var$grouping=array();var$onActions="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$partitionBy=array();var$inout="IN|OUT|INOUT";var$enumLength="'(?:''|[^'\\\\]|\\\\.)*'";var$generated=array();static
function
connect($M,$V,$D){$g=new
Db;return($g->attach($M,$V,$D)?:$g);}function
__construct(Db$g){$this->conn=$g;}function
types(){return
call_user_func_array('array_merge',array_values($this->types));}function
structuredTypes(){return
array_map('array_keys',$this->types);}function
enumLength(array$l){}function
unconvertFunction(array$l){}function
select($R,array$K,array$Z,array$Gc,array$He=array(),$x=1,$C=0,$mf=false){$vd=(count($Gc)<count($K));$F=adminer()->selectQueryBuild($K,$Z,$Gc,$He,$x,$C);if(!$F)$F="SELECT".limit(($_GET["page"]!="last"&&$x&&$Gc&&$vd&&JUSH=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$K)."\nFROM ".table($R),($Z?"\nWHERE ".implode(" AND ",$Z):"").($Gc&&$vd?"\nGROUP BY ".implode(", ",$Gc):"").($He?"\nORDER BY ".implode(", ",$He):""),$x,($C?$x*$C:0),"\n");$gg=microtime(true);$H=$this->conn->query($F);if($mf)echo
adminer()->selectQuery($F,$gg,!$H);return$H;}function
delete($R,$sf,$x=0){$F="FROM ".table($R);return
queries("DELETE".($x?limit1($R,$F,$sf):" $F$sf"));}function
update($R,array$N,$sf,$x=0,$L="\n"){$jh=array();foreach($N
as$w=>$X)$jh[]="$w = $X";$F=table($R)." SET$L".implode(",$L",$jh);return
queries("UPDATE".($x?limit1($R,$F,$sf,$L):" $F$sf"));}function
insert($R,array$N){return
queries("INSERT INTO ".table($R).($N?" (".implode(", ",array_keys($N)).")\nVALUES (".implode(", ",$N).")":" DEFAULT VALUES").$this->insertReturning($R));}function
insertReturning($R){return"";}function
insertUpdate($R,array$J,array$E){return
false;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($F,$yg){}function
convertSearch($t,array$X,array$l){return$t;}function
value($X,array$l){return(method_exists($this->conn,'value')?$this->conn->value($X,$l):$X);}function
quoteBinary($Gf){return
q($Gf);}function
warnings(){}function
tableHelp($_,$yd=false){}function
inheritsFrom($R){return
array();}function
inheritedTables($R){return
array();}function
partitionsInfo($R){return
array();}function
hasCStyleEscapes(){return
false;}function
engines(){return
array();}function
supportsIndex(array$S){return!is_view($S);}function
indexAlgorithms(array$ng){return
array();}function
checkConstraints($R){return
get_key_vals("SELECT c.CONSTRAINT_NAME, CHECK_CLAUSE
FROM INFORMATION_SCHEMA.CHECK_CONSTRAINTS c
JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS t ON c.CONSTRAINT_SCHEMA = t.CONSTRAINT_SCHEMA AND c.CONSTRAINT_NAME = t.CONSTRAINT_NAME
WHERE c.CONSTRAINT_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
AND t.TABLE_NAME = ".q($R)."
AND CHECK_CLAUSE NOT LIKE '% IS NOT NULL'",$this->conn);}function
allFields(){$H=array();if(DB!=""){foreach(get_rows("SELECT TABLE_NAME AS tab, COLUMN_NAME AS field, IS_NULLABLE AS nullable, DATA_TYPE AS type, CHARACTER_MAXIMUM_LENGTH AS length".(JUSH=='sql'?", COLUMN_KEY = 'PRI' AS `primary`":"")."
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
ORDER BY TABLE_NAME, ORDINAL_POSITION",$this->conn)as$I){$I["null"]=($I["nullable"]=="YES");$H[$I["tab"]][]=$I;}}return$H;}}add_driver("sqlite","SQLite");if(isset($_GET["sqlite"])){define('Adminer\DRIVER',"sqlite");if(class_exists("SQLite3")&&$_GET["ext"]!="pdo"){abstract
class
SqliteDb
extends
SqlDb{var$extension="SQLite3";private$link;function
attach($n,$V,$D){$this->link=new
\SQLite3($n);$lh=$this->link->version();$this->server_info=$lh["versionString"];return'';}function
query($F,$Rg=false){$G=@$this->link->query($F);$this->error="";if(!$G){$this->errno=$this->link->lastErrorCode();$this->error=$this->link->lastErrorMsg();return
false;}elseif($G->numColumns())return
new
Result($G);$this->affected_rows=$this->link->changes();return
true;}function
quote($Q){return(is_utf8($Q)?"'".$this->link->escapeString($Q)."'":"x'".first(unpack('H*',$Q))."'");}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($G){$this->result=$G;}function
fetch_assoc(){return$this->result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$d=$this->offset++;$U=$this->result->columnType($d);return(object)array("name"=>$this->result->columnName($d),"type"=>($U==SQLITE3_TEXT?15:0),"charsetnr"=>($U==SQLITE3_BLOB?63:0),);}function
__destruct(){$this->result->finalize();}}}elseif(extension_loaded("pdo_sqlite")){abstract
class
SqliteDb
extends
PdoDb{var$extension="PDO_SQLite";function
attach($n,$V,$D){return$this->dsn(DRIVER.":$n","","");}}}if(class_exists('Adminer\SqliteDb')){class
Db
extends
SqliteDb{function
attach($n,$V,$D){parent::attach($n,$V,$D);$this->query("PRAGMA foreign_keys = 1");$this->query("PRAGMA busy_timeout = 500");return'';}function
select_db($n){if(is_readable($n)&&$this->query("ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$n)?$n:dirname($_SERVER["SCRIPT_FILENAME"])."/$n")." AS a"))return!self::attach($n,'','');return
false;}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLite3","PDO_SQLite");static$jush="sqlite";protected$types=array(array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0));var$insertFunctions=array();var$editFunctions=array("integer|real|numeric"=>"+/-","text"=>"||",);var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("hex","length","lower","round","unixepoch","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");static
function
connect($M,$V,$D){if($D!="")return
lang(24);return
parent::connect(":memory:","","");}function
__construct(Db$g){parent::__construct($g);if(min_version(3.31,0,$g))$this->generated=array("STORED","VIRTUAL");}function
structuredTypes(){return
array_keys($this->types[0]);}function
insertUpdate($R,array$J,array$E){$jh=array();foreach($J
as$N)$jh[]="(".implode(", ",$N).")";return
queries("REPLACE INTO ".table($R)." (".implode(", ",array_keys(reset($J))).") VALUES\n".implode(",\n",$jh));}function
tableHelp($_,$yd=false){if($_=="sqlite_sequence")return"fileformat2.html#seqtab";if($_=="sqlite_master")return"fileformat2.html#$_";}function
checkConstraints($R){preg_match_all('~ CHECK *(\( *(((?>[^()]*[^() ])|(?1))*) *\))~',get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($R),0,$this->conn),$Vd);return
array_combine($Vd[2],$Vd[2]);}function
allFields(){$H=array();foreach(tables_list()as$R=>$U){foreach(fields($R)as$l)$H[$R][]=$l;}return$H;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($uc){return
array();}function
limit($F,$Z,$x,$we=0,$L=" "){return" $F$Z".($x?$L."LIMIT $x".($we?" OFFSET $we":""):"");}function
limit1($R,$F,$Z,$L="\n"){return(preg_match('~^INTO~',$F)||get_val("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($F,$Z,1,0,$L):" $F WHERE rowid = (SELECT rowid FROM ".table($R).$Z.$L."LIMIT 1)");}function
db_collation($i,$Wa){return
get_val("PRAGMA encoding");}function
logged_user(){return
get_current_user();}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name = 'sqlite_sequence'), name");}function
count_tables($ub){return
array();}function
table_status($_=""){$H=array();foreach(get_rows("SELECT name AS Name, type AS Engine, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($_!=""?"AND name = ".q($_):"ORDER BY name"))as$I){$I["Rows"]=get_val("SELECT COUNT(*) FROM ".idf_escape($I["Name"]));$H[$I["Name"]]=$I;}foreach(get_rows("SELECT * FROM sqlite_sequence".($_!=""?" WHERE name = ".q($_):""),null,"")as$I)$H[$I["name"]]["Auto_increment"]=$I["seq"];return$H;}function
is_view($S){return$S["Engine"]=="view";}function
fk_support($S){return!get_val("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
fields($R){$H=array();$E="";foreach(get_rows("PRAGMA table_".(min_version(3.31)?"x":"")."info(".table($R).")")as$I){$_=$I["name"];$U=strtolower($I["type"]);$j=$I["dflt_value"];$H[$_]=array("field"=>$_,"type"=>(preg_match('~int~i',$U)?"integer":(preg_match('~char|clob|text~i',$U)?"text":(preg_match('~blob~i',$U)?"blob":(preg_match('~real|floa|doub~i',$U)?"real":"numeric")))),"full_type"=>$U,"default"=>(preg_match("~^'(.*)'$~",$j,$z)?str_replace("''","'",$z[1]):($j=="NULL"?null:$j)),"null"=>!$I["notnull"],"privileges"=>array("select"=>1,"insert"=>1,"update"=>1,"where"=>1,"order"=>1),"primary"=>$I["pk"],);if($I["pk"]){if($E!="")$H[$E]["auto_increment"]=false;elseif(preg_match('~^integer$~i',$U))$H[$_]["auto_increment"]=true;$E=$_;}}$dg=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($R));$t='(("[^"]*+")+|[a-z0-9_]+)';preg_match_all('~'.$t.'\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',$dg,$Vd,PREG_SET_ORDER);foreach($Vd
as$z){$_=str_replace('""','"',preg_replace('~^"|"$~','',$z[1]));if($H[$_])$H[$_]["collation"]=trim($z[3],"'");}preg_match_all('~'.$t.'\s.*GENERATED ALWAYS AS \((.+)\) (STORED|VIRTUAL)~i',$dg,$Vd,PREG_SET_ORDER);foreach($Vd
as$z){$_=str_replace('""','"',preg_replace('~^"|"$~','',$z[1]));$H[$_]["default"]=$z[3];$H[$_]["generated"]=strtoupper($z[4]);}return$H;}function
indexes($R,$h=null){$h=connection($h);$H=array();$dg=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($R),0,$h);if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$dg,$z)){$H[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$z[1],$Vd,PREG_SET_ORDER);foreach($Vd
as$z){$H[""]["columns"][]=idf_unescape($z[2]).$z[4];$H[""]["descs"][]=(preg_match('~DESC~i',$z[5])?'1':null);}}if(!$H){foreach(fields($R)as$_=>$l){if($l["primary"])$H[""]=array("type"=>"PRIMARY","columns"=>array($_),"lengths"=>array(),"descs"=>array(null));}}$fg=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($R),$h);foreach(get_rows("PRAGMA index_list(".table($R).")",$h)as$I){$_=$I["name"];$u=array("type"=>($I["unique"]?"UNIQUE":"INDEX"));$u["lengths"]=array();$u["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($_).")",$h)as$Ff){$u["columns"][]=$Ff["name"];$u["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($_).' ON '.idf_escape($R),'~').' \((.*)\)$~i',$fg[$_],$yf)){preg_match_all('/("[^"]*+")+( DESC)?/',$yf[2],$Vd);foreach($Vd[2]as$w=>$X){if($X)$u["descs"][$w]='1';}}if(!$H[""]||$u["type"]!="UNIQUE"||$u["columns"]!=$H[""]["columns"]||$u["descs"]!=$H[""]["descs"]||!preg_match("~^sqlite_~",$_))$H[$_]=$u;}return$H;}function
foreign_keys($R){$H=array();foreach(get_rows("PRAGMA foreign_key_list(".table($R).")")as$I){$o=&$H[$I["id"]];if(!$o)$o=$I;$o["source"][]=$I["from"];$o["target"][]=$I["to"];}return$H;}function
view($_){return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',get_val("SELECT sql FROM sqlite_master WHERE type = 'view' AND name = ".q($_))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($i){return
false;}function
error(){return
h(connection()->error);}function
check_sqlite_name($_){$fc="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($fc)\$~",$_)){connection()->error=lang(25,str_replace("|",", ",$fc));return
false;}return
true;}function
create_database($i,$c){if(file_exists($i)){connection()->error=lang(26);return
false;}if(!check_sqlite_name($i))return
false;try{$y=new
Db();$y->attach($i,'','');}catch(\Exception$ac){connection()->error=$ac->getMessage();return
false;}$y->query('PRAGMA encoding = "UTF-8"');$y->query('CREATE TABLE adminer (i)');$y->query('DROP TABLE adminer');return
true;}function
drop_databases($ub){connection()->attach(":memory:",'','');foreach($ub
as$i){if(!@unlink($i)){connection()->error=lang(26);return
false;}}return
true;}function
rename_database($_,$c){if(!check_sqlite_name($_))return
false;connection()->attach(":memory:",'','');connection()->error=lang(26);return@rename(DB,$_);}function
auto_increment(){return" PRIMARY KEY AUTOINCREMENT";}function
alter_table($R,$_,$m,$wc,$ab,$Sb,$c,$ta,$Xe){$eh=($R==""||$wc);foreach($m
as$l){if($l[0]!=""||!$l[1]||$l[2]){$eh=true;break;}}$b=array();$Ne=array();foreach($m
as$l){if($l[1]){$b[]=($eh?$l[1]:"ADD ".implode($l[1]));if($l[0]!="")$Ne[$l[0]]=$l[1][0];}}if(!$eh){foreach($b
as$X){if(!queries("ALTER TABLE ".table($R)." $X"))return
false;}if($R!=$_&&!queries("ALTER TABLE ".table($R)." RENAME TO ".table($_)))return
false;}elseif(!recreate_table($R,$_,$b,$Ne,$wc,$ta))return
false;if($ta){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $ta WHERE name = ".q($_));if(!connection()->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($_).", $ta)");queries("COMMIT");}return
true;}function
recreate_table($R,$_,array$m,array$Ne,array$wc,$ta="",$v=array(),$Hb="",$ea=""){if($R!=""){if(!$m){foreach(fields($R)as$w=>$l){if($v)$l["auto_increment"]=0;$m[]=process_field($l,$l);$Ne[$w]=idf_escape($w);}}$lf=false;foreach($m
as$l){if($l[6])$lf=true;}$Ib=array();foreach($v
as$w=>$X){if($X[2]=="DROP"){$Ib[$X[1]]=true;unset($v[$w]);}}foreach(indexes($R)as$Ad=>$u){$e=array();foreach($u["columns"]as$w=>$d){if(!$Ne[$d])continue
2;$e[]=$Ne[$d].($u["descs"][$w]?" DESC":"");}if(!$Ib[$Ad]){if($u["type"]!="PRIMARY"||!$lf)$v[]=array($u["type"],$Ad,$e);}}foreach($v
as$w=>$X){if($X[0]=="PRIMARY"){unset($v[$w]);$wc[]="  PRIMARY KEY (".implode(", ",$X[2]).")";}}foreach(foreign_keys($R)as$Ad=>$o){foreach($o["source"]as$w=>$d){if(!$Ne[$d])continue
2;$o["source"][$w]=idf_unescape($Ne[$d]);}if(!isset($wc[" $Ad"]))$wc[]=" ".format_foreign_key($o);}queries("BEGIN");}$Ka=array();foreach($m
as$l){if(preg_match('~GENERATED~',$l[3]))unset($Ne[array_search($l[0],$Ne)]);$Ka[]="  ".implode($l);}$Ka=array_merge($Ka,array_filter($wc));foreach(driver()->checkConstraints($R)as$Ma){if($Ma!=$Hb)$Ka[]="  CHECK ($Ma)";}if($ea)$Ka[]="  CHECK ($ea)";$sg=($R==$_?"adminer_$_":$_);if(!queries("CREATE TABLE ".table($sg)." (\n".implode(",\n",$Ka)."\n)"))return
false;if($R!=""){if($Ne&&!queries("INSERT INTO ".table($sg)." (".implode(", ",$Ne).") SELECT ".implode(", ",array_map('Adminer\idf_escape',array_keys($Ne)))." FROM ".table($R)))return
false;$Og=array();foreach(triggers($R)as$Mg=>$zg){$Lg=trigger($Mg,$R);$Og[]="CREATE TRIGGER ".idf_escape($Mg)." ".implode(" ",$zg)." ON ".table($_)."\n$Lg[Statement]";}$ta=$ta?"":get_val("SELECT seq FROM sqlite_sequence WHERE name = ".q($R));if(!queries("DROP TABLE ".table($R))||($R==$_&&!queries("ALTER TABLE ".table($sg)." RENAME TO ".table($_)))||!alter_indexes($_,$v))return
false;if($ta)queries("UPDATE sqlite_sequence SET seq = $ta WHERE name = ".q($_));foreach($Og
as$Lg){if(!queries($Lg))return
false;}queries("COMMIT");}return
true;}function
index_sql($R,$U,$_,$e){return"CREATE $U ".($U!="INDEX"?"INDEX ":"").idf_escape($_!=""?$_:uniqid($R."_"))." ON ".table($R)." $e";}function
alter_indexes($R,$b){foreach($b
as$E){if($E[0]=="PRIMARY")return
recreate_table($R,$R,array(),array(),array(),"",$b);}foreach(array_reverse($b)as$X){if(!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($R,$X[0],$X[1],"(".implode(", ",$X[2]).")")))return
false;}return
true;}function
truncate_tables($T){return
apply_queries("DELETE FROM",$T);}function
drop_views($nh){return
apply_queries("DROP VIEW",$nh);}function
drop_tables($T){return
apply_queries("DROP TABLE",$T);}function
move_tables($T,$nh,$rg){return
false;}function
trigger($_,$R){if($_=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$t='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$Ng=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$t\\s*(".implode("|",$Ng["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($t))?\\s+ON\\s*$t\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",get_val("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($_)),$z);$ve=$z[3];return
array("Timing"=>strtoupper($z[1]),"Event"=>strtoupper($z[2]).($ve?" OF":""),"Of"=>idf_unescape($ve),"Trigger"=>$_,"Statement"=>$z[4],);}function
triggers($R){$H=array();$Ng=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($R))as$I){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$Ng["Timing"]).')\s*(.*?)\s+ON\b~i',$I["sql"],$z);$H[$I["name"]]=array($z[1],$z[2]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
begin(){return
queries("BEGIN");}function
last_id($G){return
get_val("SELECT LAST_INSERT_ROWID()");}function
explain($g,$F){return$g->query("EXPLAIN QUERY PLAN $F");}function
found_rows($S,$Z){}function
types(){return
array();}function
create_sql($R,$ta,$ig){$H=get_val("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($R));foreach(indexes($R)as$_=>$u){if($_=='')continue;$H
.=";\n\n".index_sql($R,$u['type'],$_,"(".implode(", ",array_map('Adminer\idf_escape',$u['columns'])).")");}return$H;}function
truncate_sql($R){return"DELETE FROM ".table($R);}function
use_sql($tb,$ig=""){}function
trigger_sql($R){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($R)));}function
show_variables(){$H=array();foreach(get_rows("PRAGMA pragma_list")as$I){$_=$I["name"];if($_!="pragma_list"&&$_!="compile_options"){$H[$_]=array($_,'');foreach(get_rows("PRAGMA $_")as$I)$H[$_][1].=implode(", ",$I)."\n";}}return$H;}function
show_status(){$H=array();foreach(get_vals("PRAGMA compile_options")as$Fe)$H[]=explode("=",$Fe,2)+array('','');return$H;}function
convert_field($l){}function
unconvert_field($l,$H){return$H;}function
support($jc){return
preg_match('~^(check|columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|trigger|variables|view|view_trigger)$~',$jc);}}add_driver("pgsql","PostgreSQL");if(isset($_GET["pgsql"])){define('Adminer\DRIVER',"pgsql");if(extension_loaded("pgsql")&&$_GET["ext"]!="pdo"){class
PgsqlDb
extends
SqlDb{var$extension="PgSQL";var$timeout=0;private$link,$string,$database=true;function
_error($Xb,$k){if(ini_bool("html_errors"))$k=html_entity_decode(strip_tags($k));$k=preg_replace('~^[^:]*: ~','',$k);$this->error=$k;}function
attach($M,$V,$D){$i=adminer()->database();set_error_handler(array($this,'_error'));list($Wc,$ff)=host_port(addcslashes($M,"'\\"));$this->string="host='$Wc'".($ff?" port='$ff'":"")." user='".addcslashes($V,"'\\")."' password='".addcslashes($D,"'\\")."'";$O=adminer()->connectSsl();if(isset($O["mode"]))$this->string
.=" sslmode='".$O["mode"]."'";$this->link=@pg_connect("$this->string dbname='".($i!=""?addcslashes($i,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->link&&$i!=""){$this->database=false;$this->link=@pg_connect("$this->string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->link)pg_set_client_encoding($this->link,"UTF8");return($this->link?'':$this->error);}function
quote($Q){return(function_exists('pg_escape_literal')?pg_escape_literal($this->link,$Q):"'".pg_escape_string($this->link,$Q)."'");}function
value($X,array$l){return($l["type"]=="bytea"&&$X!==null?pg_unescape_bytea($X):$X);}function
select_db($tb){if($tb==adminer()->database())return$this->database;$H=@pg_connect("$this->string dbname='".addcslashes($tb,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($H)$this->link=$H;return$H;}function
close(){$this->link=@pg_connect("$this->string dbname='postgres'");}function
query($F,$Rg=false){$G=@pg_query($this->link,$F);$this->error="";if(!$G){$this->error=pg_last_error($this->link);$H=false;}elseif(!pg_num_fields($G)){$this->affected_rows=pg_affected_rows($G);$H=true;}else$H=new
Result($G);if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$H;}function
warnings(){return
h(pg_last_notice($this->link));}function
copyFrom($R,array$J){$this->error='';set_error_handler(function($Xb,$k){$this->error=(ini_bool('html_errors')?html_entity_decode($k):$k);return
true;});$H=pg_copy_from($this->link,$R,$J);restore_error_handler();return$H;}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($G){$this->result=$G;$this->num_rows=pg_num_rows($G);}function
fetch_assoc(){return
pg_fetch_assoc($this->result);}function
fetch_row(){return
pg_fetch_row($this->result);}function
fetch_field(){$d=$this->offset++;$H=new
\stdClass;$H->orgtable=pg_field_table($this->result,$d);$H->name=pg_field_name($this->result,$d);$U=pg_field_type($this->result,$d);$H->type=(preg_match(number_type(),$U)?0:15);$H->charsetnr=($U=="bytea"?63:0);return$H;}function
__destruct(){pg_free_result($this->result);}}}elseif(extension_loaded("pdo_pgsql")){class
PgsqlDb
extends
PdoDb{var$extension="PDO_PgSQL";var$timeout=0;function
attach($M,$V,$D){$i=adminer()->database();list($Wc,$ff)=host_port(addcslashes($M,"'\\"));$Jb="pgsql:host='$Wc'".($ff?" port='$ff'":"")." client_encoding=utf8 dbname='".($i!=""?addcslashes($i,"'\\"):"postgres")."'";$O=adminer()->connectSsl();if(isset($O["mode"]))$Jb
.=" sslmode='".$O["mode"]."'";return$this->dsn($Jb,$V,$D);}function
select_db($tb){return(adminer()->database()==$tb);}function
query($F,$Rg=false){$H=parent::query($F,$Rg);if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$H;}function
warnings(){}function
copyFrom($R,array$J){$H=$this->pdo->pgsqlCopyFromArray($R,$J);$this->error=idx($this->pdo->errorInfo(),2)?:'';return$H;}function
close(){}}}if(class_exists('Adminer\PgsqlDb')){class
Db
extends
PgsqlDb{function
multi_query($F){if(preg_match('~\bCOPY\s+(.+?)\s+FROM\s+stdin;\n?(.*)\n\\\\\.$~is',str_replace("\r\n","\n",$F),$z)){$J=explode("\n",$z[2]);$this->affected_rows=count($J);return$this->copyFrom($z[1],$J);}return
parent::multi_query($F);}}}class
Driver
extends
SqlDriver{static$extensions=array("PgSQL","PDO_PgSQL");static$jush="pgsql";var$operators=array("=","<",">","<=",">=","!=","~","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT ILIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("char_length","lower","round","to_hex","to_timestamp","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$nsOid="(SELECT oid FROM pg_namespace WHERE nspname = current_schema())";static
function
connect($M,$V,$D){$g=parent::connect($M,$V,$D);if(is_string($g))return$g;$lh=get_val("SELECT version()",0,$g);$g->flavor=(preg_match('~CockroachDB~',$lh)?'cockroach':'');$g->server_info=preg_replace('~^\D*([\d.]+[-\w]*).*~','\1',$lh);if(min_version(9,0,$g))$g->query("SET application_name = 'Adminer'");if($g->flavor=='cockroach')add_driver(DRIVER,"CockroachDB");return$g;}function
__construct(Db$g){parent::__construct($g);$this->types=array(lang(27)=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),lang(28)=>array("date"=>13,"time"=>17,"timestamp"=>20,"timestamptz"=>21,"interval"=>0),lang(29)=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),lang(30)=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),lang(31)=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"macaddr8"=>23,"txid_snapshot"=>0),lang(32)=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),);if(min_version(9.2,0,$g)){$this->types[lang(29)]["json"]=4294967295;if(min_version(9.4,0,$g))$this->types[lang(29)]["jsonb"]=4294967295;}$this->insertFunctions=array("char"=>"md5","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",);if(min_version(12,0,$g))$this->generated=array("STORED");$this->partitionBy=array("RANGE","LIST");if(!$g->flavor)$this->partitionBy[]="HASH";}function
enumLength(array$l){$Tb=$this->types[lang(6)][$l["type"]];return($Tb?type_values($Tb):"");}function
setUserTypes($Qg){$this->types[lang(6)]=array_flip($Qg);}function
insertReturning($R){$ta=array_filter(fields($R),function($l){return$l['auto_increment'];});return(count($ta)==1?" RETURNING ".idf_escape(key($ta)):"");}function
insertUpdate($R,array$J,array$E){foreach($J
as$N){$Zg=array();$Z=array();foreach($N
as$w=>$X){$Zg[]="$w = $X";if(isset($E[idf_unescape($w)]))$Z[]="$w = $X";}if(!(($Z&&queries("UPDATE ".table($R)." SET ".implode(", ",$Zg)." WHERE ".implode(" AND ",$Z))&&$this->conn->affected_rows)||queries("INSERT INTO ".table($R)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
slowQuery($F,$yg){$this->conn->query("SET statement_timeout = ".(1000*$yg));$this->conn->timeout=1000*$yg;return$F;}function
convertSearch($t,array$X,array$l){$ug="char|text";if(strpos($X["op"],"LIKE")===false)$ug
.="|date|time(stamp)?|boolean|uuid|inet|cidr|macaddr|".number_type();return(preg_match("~$ug~",$l["type"])?$t:"CAST($t AS text)");}function
quoteBinary($Gf){return"'\\x".bin2hex($Gf)."'";}function
warnings(){return$this->conn->warnings();}function
tableHelp($_,$yd=false){$Nd=array("information_schema"=>"infoschema","pg_catalog"=>($yd?"view":"catalog"),);$y=$Nd[$_GET["ns"]];if($y)return"$y-".str_replace("_","-",$_).".html";}function
inheritsFrom($R){return
get_vals("SELECT relname FROM pg_class JOIN pg_inherits ON inhparent = oid WHERE inhrelid = ".$this->tableOid($R)." ORDER BY 1");}function
inheritedTables($R){return
get_vals("SELECT relname FROM pg_inherits JOIN pg_class ON inhrelid = oid WHERE inhparent = ".$this->tableOid($R)." ORDER BY 1");}function
partitionsInfo($R){$I=(min_version(10)?$this->conn->query("SELECT * FROM pg_partitioned_table WHERE partrelid = ".$this->tableOid($R))->fetch_assoc():null);if($I){$ra=get_vals("SELECT attname FROM pg_attribute WHERE attrelid = $I[partrelid] AND attnum IN (".str_replace(" ",", ",$I["partattrs"]).")");$Ha=array('h'=>'HASH','l'=>'LIST','r'=>'RANGE');return
array("partition_by"=>$Ha[$I["partstrat"]],"partition"=>implode(", ",array_map('Adminer\idf_escape',$ra)),);}return
array();}function
tableOid($R){return"(SELECT oid FROM pg_class WHERE relnamespace = $this->nsOid AND relname = ".q($R)." AND relkind IN ('r', 'm', 'v', 'f', 'p'))";}function
indexAlgorithms(array$ng){static$H=array();if(!$H)$H=get_vals("SELECT amname FROM pg_am".(min_version(9.6)?" WHERE amtype = 'i'":"")." ORDER BY amname = '".($this->conn->flavor=='cockroach'?"prefix":"btree")."' DESC, amname");return$H;}function
supportsIndex(array$S){return$S["Engine"]!="view";}function
hasCStyleEscapes(){static$Ja;if($Ja===null)$Ja=(get_val("SHOW standard_conforming_strings",0,$this->conn)=="off");return$Ja;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($uc){return
get_vals("SELECT datname FROM pg_database
WHERE datallowconn = TRUE AND has_database_privilege(datname, 'CONNECT')
ORDER BY datname");}function
limit($F,$Z,$x,$we=0,$L=" "){return" $F$Z".($x?$L."LIMIT $x".($we?" OFFSET $we":""):"");}function
limit1($R,$F,$Z,$L="\n"){return(preg_match('~^INTO~',$F)?limit($F,$Z,1,0,$L):" $F".(is_view(table_status1($R))?$Z:$L."WHERE ctid = (SELECT ctid FROM ".table($R).$Z.$L."LIMIT 1)"));}function
db_collation($i,$Wa){return
get_val("SELECT datcollate FROM pg_database WHERE datname = ".q($i));}function
logged_user(){return
get_val("SELECT user");}function
tables_list(){$F="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support("materializedview"))$F
.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$F
.="
ORDER BY 1";return
get_key_vals($F);}function
count_tables($ub){$H=array();foreach($ub
as$i){if(connection()->select_db($i))$H[$i]=count(tables_list());}return$H;}function
table_status($_=""){static$Pc;if($Pc===null)$Pc=get_val("SELECT 'pg_table_size'::regproc");$H=array();foreach(get_rows("SELECT
	relname AS \"Name\",
	CASE relkind WHEN 'v' THEN 'view' WHEN 'm' THEN 'materialized view' ELSE 'table' END AS \"Engine\"".($Pc?",
	pg_table_size(c.oid) AS \"Data_length\",
	pg_indexes_size(c.oid) AS \"Index_length\"":"").",
	obj_description(c.oid, 'pg_class') AS \"Comment\",
	".(min_version(12)?"''":"CASE WHEN relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\",
	reltuples AS \"Rows\",
	".(min_version(10)?"relispartition::int AS partition,":"")."
	current_schema() AS nspname
FROM pg_class c
WHERE relkind IN ('r', 'm', 'v', 'f', 'p')
AND relnamespace = ".driver()->nsOid."
".($_!=""?"AND relname = ".q($_):"ORDER BY relname"))as$I)$H[$I["Name"]]=$I;return$H;}function
is_view($S){return
in_array($S["Engine"],array("view","materialized view"));}function
fk_support($S){return
true;}function
fields($R){$H=array();$ka=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz',);foreach(get_rows("SELECT
	a.attname AS field,
	format_type(a.atttypid, a.atttypmod) AS full_type,
	pg_get_expr(d.adbin, d.adrelid) AS default,
	a.attnotnull::int,
	col_description(a.attrelid, a.attnum) AS comment".(min_version(10)?",
	a.attidentity".(min_version(12)?",
	a.attgenerated":""):"")."
FROM pg_attribute a
LEFT JOIN pg_attrdef d ON a.attrelid = d.adrelid AND a.attnum = d.adnum
WHERE a.attrelid = ".driver()->tableOid($R)."
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$I){preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$I["full_type"],$z);list(,$U,$Kd,$I["length"],$fa,$na)=$z;$I["length"].=$na;$Na=$U.$fa;if(isset($ka[$Na])){$I["type"]=$ka[$Na];$I["full_type"]=$I["type"].$Kd.$na;}else{$I["type"]=$U;$I["full_type"]=$I["type"].$Kd.$fa.$na;}if(in_array($I['attidentity'],array('a','d')))$I['default']='GENERATED '.($I['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';$I["generated"]=($I["attgenerated"]=="s"?"STORED":"");$I["null"]=!$I["attnotnull"];$I["auto_increment"]=$I['attidentity']||preg_match('~^nextval\(~i',$I["default"])||preg_match('~^unique_rowid\(~',$I["default"]);$I["privileges"]=array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1);if(preg_match('~(.+)::[^,)]+(.*)~',$I["default"],$z))$I["default"]=($z[1]=="NULL"?null:idf_unescape($z[1]).$z[2]);$H[$I["field"]]=$I;}return$H;}function
indexes($R,$h=null){$h=connection($h);$H=array();$qg=driver()->tableOid($R);$e=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $qg AND attnum > 0",$h);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption, amname, pg_get_expr(indpred, indrelid, true) AS partial, pg_get_expr(indexprs, indrelid) AS indexpr
FROM pg_index
JOIN pg_class ON indexrelid = oid
JOIN pg_am ON pg_am.oid = pg_class.relam
WHERE indrelid = $qg
ORDER BY indisprimary DESC, indisunique DESC",$h)as$I){$zf=$I["relname"];$H[$zf]["type"]=($I["partial"]?"INDEX":($I["indisprimary"]?"PRIMARY":($I["indisunique"]?"UNIQUE":"INDEX")));$H[$zf]["columns"]=array();$H[$zf]["descs"]=array();$H[$zf]["algorithm"]=$I["amname"];$H[$zf]["partial"]=$I["partial"];$id=preg_split('~(?<=\)), (?=\()~',$I["indexpr"]);foreach(explode(" ",$I["indkey"])as$jd)$H[$zf]["columns"][]=($jd?$e[$jd]:array_shift($id));foreach(explode(" ",$I["indoption"])as$kd)$H[$zf]["descs"][]=(intval($kd)&1?'1':null);$H[$zf]["lengths"]=array();}return$H;}function
foreign_keys($R){$H=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = ".driver()->tableOid($R)."
AND contype = 'f'::char
ORDER BY conkey, conname")as$I){if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$I['definition'],$z)){$I['source']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$z[1])));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$z[2],$Td)){$I['ns']=idf_unescape($Td[2]);$I['table']=idf_unescape($Td[4]);}$I['target']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$z[3])));$I['on_delete']=(preg_match("~ON DELETE (".driver()->onActions.")~",$z[4],$Td)?$Td[1]:'NO ACTION');$I['on_update']=(preg_match("~ON UPDATE (".driver()->onActions.")~",$z[4],$Td)?$Td[1]:'NO ACTION');$H[$I['conname']]=$I;}}return$H;}function
view($_){return
array("select"=>trim(get_val("SELECT pg_get_viewdef(".driver()->tableOid($_).")")));}function
collations(){return
array();}function
information_schema($i){return
get_schema()=="information_schema";}function
error(){$H=h(connection()->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$H,$z))$H=$z[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($z[3]).'})(.*)~','\1<b>\2</b>',$z[2]).$z[4];return
nl_br($H);}function
create_database($i,$c){return
queries("CREATE DATABASE ".idf_escape($i).($c?" ENCODING ".idf_escape($c):""));}function
drop_databases($ub){connection()->close();return
apply_queries("DROP DATABASE",$ub,'Adminer\idf_escape');}function
rename_database($_,$c){connection()->close();return
queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($_));}function
auto_increment(){return"";}function
alter_table($R,$_,$m,$wc,$ab,$Sb,$c,$ta,$Xe){$b=array();$rf=array();if($R!=""&&$R!=$_)$rf[]="ALTER TABLE ".table($R)." RENAME TO ".table($_);$Pf="";foreach($m
as$l){$d=idf_escape($l[0]);$X=$l[1];if(!$X)$b[]="DROP $d";else{$ih=$X[5];unset($X[5]);if($l[0]==""){if(isset($X[6]))$X[1]=($X[1]==" bigint"?" big":($X[1]==" smallint"?" small":" "))."serial";$b[]=($R!=""?"ADD ":"  ").implode($X);if(isset($X[6]))$b[]=($R!=""?"ADD":" ")." PRIMARY KEY ($X[0])";}else{if($d!=$X[0])$rf[]="ALTER TABLE ".table($_)." RENAME $d TO $X[0]";$b[]="ALTER $d TYPE$X[1]";$Qf=$R."_".idf_unescape($X[0])."_seq";$b[]="ALTER $d ".($X[3]?"SET".preg_replace('~GENERATED ALWAYS(.*) STORED~','EXPRESSION\1',$X[3]):(isset($X[6])?"SET DEFAULT nextval(".q($Qf).")":"DROP DEFAULT"));if(isset($X[6]))$Pf="CREATE SEQUENCE IF NOT EXISTS ".idf_escape($Qf)." OWNED BY ".idf_escape($R).".$X[0]";$b[]="ALTER $d ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];}if($l[0]!=""||$ih!="")$rf[]="COMMENT ON COLUMN ".table($_).".$X[0] IS ".($ih!=""?substr($ih,9):"''");}}$b=array_merge($b,$wc);if($R==""){$P="";if($Xe){$Sa=(connection()->flavor=='cockroach');$P=" PARTITION BY $Xe[partition_by]($Xe[partition])";if($Xe["partition_by"]=='HASH'){$Ye=+$Xe["partitions"];for($r=0;$r<$Ye;$r++)$rf[]="CREATE TABLE ".idf_escape($_."_$r")." PARTITION OF ".idf_escape($_)." FOR VALUES WITH (MODULUS $Ye, REMAINDER $r)";}else{$kf="MINVALUE";foreach($Xe["partition_names"]as$r=>$X){$Y=$Xe["partition_values"][$r];$Ve=" VALUES ".($Xe["partition_by"]=='LIST'?"IN ($Y)":"FROM ($kf) TO ($Y)");if($Sa)$P
.=($r?",":" (")."\n  PARTITION ".(preg_match('~^DEFAULT$~i',$X)?$X:idf_escape($X))."$Ve";else$rf[]="CREATE TABLE ".idf_escape($_."_$X")." PARTITION OF ".idf_escape($_)." FOR$Ve";$kf=$Y;}$P
.=($Sa?"\n)":"");}}array_unshift($rf,"CREATE TABLE ".table($_)." (\n".implode(",\n",$b)."\n)$P");}elseif($b)array_unshift($rf,"ALTER TABLE ".table($R)."\n".implode(",\n",$b));if($Pf)array_unshift($rf,$Pf);if($ab!==null)$rf[]="COMMENT ON TABLE ".table($_)." IS ".q($ab);foreach($rf
as$F){if(!queries($F))return
false;}return
true;}function
alter_indexes($R,$b){$lb=array();$Gb=array();$rf=array();foreach($b
as$X){if($X[0]!="INDEX")$lb[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");elseif($X[2]=="DROP")$Gb[]=idf_escape($X[1]);else$rf[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($R."_"))." ON ".table($R).($X[3]?" USING $X[3]":"")." (".implode(", ",$X[2]).")".($X[4]?" WHERE $X[4]":"");}if($lb)array_unshift($rf,"ALTER TABLE ".table($R).implode(",",$lb));if($Gb)array_unshift($rf,"DROP INDEX ".implode(", ",$Gb));foreach($rf
as$F){if(!queries($F))return
false;}return
true;}function
truncate_tables($T){return
queries("TRUNCATE ".implode(", ",array_map('Adminer\table',$T)));}function
drop_views($nh){return
drop_tables($nh);}function
drop_tables($T){foreach($T
as$R){$P=table_status1($R);if(!queries("DROP ".strtoupper($P["Engine"])." ".table($R)))return
false;}return
true;}function
move_tables($T,$nh,$rg){foreach(array_merge($T,$nh)as$R){$P=table_status1($R);if(!queries("ALTER ".strtoupper($P["Engine"])." ".table($R)." SET SCHEMA ".idf_escape($rg)))return
false;}return
true;}function
trigger($_,$R){if($_=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");$e=array();$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($R)." AND trigger_name = ".q($_);foreach(get_rows("SELECT * FROM information_schema.triggered_update_columns $Z")as$I)$e[]=$I["event_object_column"];$H=array();foreach(get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement"
FROM information_schema.triggers'."
$Z
ORDER BY event_manipulation DESC")as$I){if($e&&$I["Event"]=="UPDATE")$I["Event"].=" OF";$I["Of"]=implode(", ",$e);if($H)$I["Event"].=" OR $H[Event]";$H=$I;}return$H;}function
triggers($R){$H=array();foreach(get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($R))as$I){$Lg=trigger($I["trigger_name"],$R);$H[$Lg["Trigger"]]=array($Lg["Timing"],$Lg["Event"]);}return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF"),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($_,$U){$J=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($_));$H=idx($J,0,array());$H["returns"]=array("type"=>$H["type_udt_name"]);$H["fields"]=get_rows('SELECT COALESCE(parameter_name, ordinal_position::text) AS field, data_type AS type, character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = '.q($_).'
ORDER BY ordinal_position');return$H;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()
ORDER BY SPECIFIC_NAME');}function
routine_languages(){return
get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");}function
routine_id($_,$I){$H=array();foreach($I["fields"]as$l){$Kd=$l["length"];$H[]=$l["type"].($Kd?"($Kd)":"");}return
idf_escape($_)."(".implode(", ",$H).")";}function
last_id($G){$I=(is_object($G)?$G->fetch_row():array());return($I?$I[0]:0);}function
explain($g,$F){return$g->query("EXPLAIN $F");}function
found_rows($S,$Z){if(preg_match("~ rows=([0-9]+)~",get_val("EXPLAIN SELECT * FROM ".idf_escape($S["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$yf))return$yf[1];}function
types(){return
get_key_vals("SELECT oid, typname
FROM pg_type
WHERE typnamespace = ".driver()->nsOid."
AND typtype IN ('b','d','e')
AND typelem = 0");}function
type_values($s){$Vb=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $s ORDER BY enumsortorder");return($Vb?"'".implode("', '",array_map('addslashes',$Vb))."'":"");}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){return
get_val("SELECT current_schema()");}function
set_schema($Hf,$h=null){if(!$h)$h=connection();$H=$h->query("SET search_path TO ".idf_escape($Hf));driver()->setUserTypes(types());return$H;}function
foreign_keys_sql($R){$H="";$P=table_status1($R);$sc=foreign_keys($R);ksort($sc);foreach($sc
as$rc=>$qc)$H
.="ALTER TABLE ONLY ".idf_escape($P['nspname']).".".idf_escape($P['Name'])." ADD CONSTRAINT ".idf_escape($rc)." $qc[definition] ".($qc['deferrable']?'DEFERRABLE':'NOT DEFERRABLE').";\n";return($H?"$H\n":$H);}function
create_sql($R,$ta,$ig){$Df=array();$Rf=array();$P=table_status1($R);if(is_view($P)){$mh=view($R);return
rtrim("CREATE VIEW ".idf_escape($R)." AS $mh[select]",";");}$m=fields($R);if(count($P)<2||empty($m))return
false;$H="CREATE TABLE ".idf_escape($P['nspname']).".".idf_escape($P['Name'])." (\n    ";foreach($m
as$l){$Ue=idf_escape($l['field']).' '.$l['full_type'].default_value($l).($l['null']?"":" NOT NULL");$Df[]=$Ue;if(preg_match('~nextval\(\'([^\']+)\'\)~',$l['default'],$Vd)){$Qf=$Vd[1];$cg=first(get_rows((min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q(idf_unescape($Qf)):"SELECT * FROM $Qf"),null,"-- "));$Rf[]=($ig=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $Qf;\n":"")."CREATE SEQUENCE $Qf INCREMENT $cg[increment_by] MINVALUE $cg[min_value] MAXVALUE $cg[max_value]".($ta&&$cg['last_value']?" START ".($cg["last_value"]+1):"")." CACHE $cg[cache_value];";}}if(!empty($Rf))$H=implode("\n\n",$Rf)."\n\n$H";$E="";foreach(indexes($R)as$gd=>$u){if($u['type']=='PRIMARY'){$E=$gd;$Df[]="CONSTRAINT ".idf_escape($gd)." PRIMARY KEY (".implode(', ',array_map('Adminer\idf_escape',$u['columns'])).")";}}foreach(driver()->checkConstraints($R)as$eb=>$gb)$Df[]="CONSTRAINT ".idf_escape($eb)." CHECK $gb";$H
.=implode(",\n    ",$Df)."\n)";$Ve=driver()->partitionsInfo($P['Name']);if($Ve)$H
.="\nPARTITION BY $Ve[partition_by]($Ve[partition])";$H
.="\nWITH (oids = ".($P['Oid']?'true':'false').");";if($P['Comment'])$H
.="\n\nCOMMENT ON TABLE ".idf_escape($P['nspname']).".".idf_escape($P['Name'])." IS ".q($P['Comment']).";";foreach($m
as$kc=>$l){if($l['comment'])$H
.="\n\nCOMMENT ON COLUMN ".idf_escape($P['nspname']).".".idf_escape($P['Name']).".".idf_escape($kc)." IS ".q($l['comment']).";";}foreach(get_rows("SELECT indexdef FROM pg_catalog.pg_indexes WHERE schemaname = current_schema() AND tablename = ".q($R).($E?" AND indexname != ".q($E):""),null,"-- ")as$I)$H
.="\n\n$I[indexdef];";return
rtrim($H,';');}function
truncate_sql($R){return"TRUNCATE ".table($R);}function
trigger_sql($R){$P=table_status1($R);$H="";foreach(triggers($R)as$Kg=>$Jg){$Lg=trigger($Kg,$P['Name']);$H
.="\nCREATE TRIGGER ".idf_escape($Lg['Trigger'])." $Lg[Timing] $Lg[Event] ON ".idf_escape($P["nspname"]).".".idf_escape($P['Name'])." $Lg[Type] $Lg[Statement];;\n";}return$H;}function
use_sql($tb,$ig=""){$_=idf_escape($tb);$H="";if(preg_match('~CREATE~',$ig)){if($ig=="DROP+CREATE")$H="DROP DATABASE IF EXISTS $_;\n";$H
.="CREATE DATABASE $_;\n";}return"$H\\connect $_";}function
show_variables(){return
get_rows("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
convert_field($l){}function
unconvert_field($l,$H){return$H;}function
support($jc){return
preg_match('~^(check|columns|comment|database|drop_col|dump|descidx|indexes|kill|partial_indexes|routine|scheme|sequence|sql|table|trigger|type|variables|view'.(min_version(9.3)?'|materializedview':'').(min_version(11)?'|procedure':'').(connection()->flavor=='cockroach'?'':'|processlist').')$~',$jc);}function
kill_process($X){return
queries("SELECT pg_terminate_backend(".number($X).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){return
get_val("SHOW max_connections");}}add_driver("oracle","Oracle (beta)");if(isset($_GET["oracle"])){define('Adminer\DRIVER',"oracle");if(extension_loaded("oci8")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="oci8";var$_current_db;private$link;function
_error($Xb,$k){if(ini_bool("html_errors"))$k=html_entity_decode(strip_tags($k));$k=preg_replace('~^[^:]*: ~','',$k);$this->error=$k;}function
attach($M,$V,$D){$this->link=@oci_new_connect($V,$D,$M,"AL32UTF8");if($this->link){$this->server_info=oci_server_version($this->link);return'';}$k=oci_error();return$k["message"];}function
quote($Q){return"'".str_replace("'","''",$Q)."'";}function
select_db($tb){$this->_current_db=$tb;return
true;}function
query($F,$Rg=false){$G=oci_parse($this->link,$F);$this->error="";if(!$G){$k=oci_error($this->link);$this->errno=$k["code"];$this->error=$k["message"];return
false;}set_error_handler(array($this,'_error'));$H=@oci_execute($G);restore_error_handler();if($H){if(oci_num_fields($G))return
new
Result($G);$this->affected_rows=oci_num_rows($G);oci_free_statement($G);}return$H;}function
timeout($je){return
oci_set_call_timeout($this->link,$je);}}class
Result{var$num_rows;private$result,$offset=1;function
__construct($G){$this->result=$G;}private
function
convert($I){foreach((array)$I
as$w=>$X){if(is_a($X,'OCILob')||is_a($X,'OCI-Lob'))$I[$w]=$X->load();}return$I;}function
fetch_assoc(){return$this->convert(oci_fetch_assoc($this->result));}function
fetch_row(){return$this->convert(oci_fetch_row($this->result));}function
fetch_field(){$d=$this->offset++;$H=new
\stdClass;$H->name=oci_field_name($this->result,$d);$H->type=oci_field_type($this->result,$d);$H->charsetnr=(preg_match("~raw|blob|bfile~",$H->type)?63:0);return$H;}function
__destruct(){oci_free_statement($this->result);}}}elseif(extension_loaded("pdo_oci")){class
Db
extends
PdoDb{var$extension="PDO_OCI";var$_current_db;function
attach($M,$V,$D){return$this->dsn("oci:dbname=//$M;charset=AL32UTF8",$V,$D);}function
select_db($tb){$this->_current_db=$tb;return
true;}}}class
Driver
extends
SqlDriver{static$extensions=array("OCI8","PDO_OCI");static$jush="oracle";var$insertFunctions=array("date"=>"current_date","timestamp"=>"current_timestamp",);var$editFunctions=array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",);var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("length","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");function
__construct(Db$g){parent::__construct($g);$this->types=array(lang(27)=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),lang(28)=>array("date"=>10,"timestamp"=>29,"interval year"=>12,"interval day"=>28),lang(29)=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),lang(30)=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),);}function
begin(){return
true;}function
insertUpdate($R,array$J,array$E){foreach($J
as$N){$Zg=array();$Z=array();foreach($N
as$w=>$X){$Zg[]="$w = $X";if(isset($E[idf_unescape($w)]))$Z[]="$w = $X";}if(!(($Z&&queries("UPDATE ".table($R)." SET ".implode(", ",$Zg)." WHERE ".implode(" AND ",$Z))&&$this->conn->affected_rows)||queries("INSERT INTO ".table($R)." (".implode(", ",array_keys($N)).") VALUES (".implode(", ",$N).")")))return
false;}return
true;}function
hasCStyleEscapes(){return
true;}}function
idf_escape($t){return'"'.str_replace('"','""',$t).'"';}function
table($t){return
idf_escape($t);}function
get_databases($uc){return
get_vals("SELECT DISTINCT tablespace_name FROM (
SELECT tablespace_name FROM user_tablespaces
UNION SELECT tablespace_name FROM all_tables WHERE tablespace_name IS NOT NULL
)
ORDER BY 1");}function
limit($F,$Z,$x,$we=0,$L=" "){return($we?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $F$Z) t WHERE rownum <= ".($x+$we).") WHERE rnum > $we":($x?" * FROM (SELECT $F$Z) WHERE rownum <= ".($x+$we):" $F$Z"));}function
limit1($R,$F,$Z,$L="\n"){return" $F$Z";}function
db_collation($i,$Wa){return
get_val("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
logged_user(){return
get_val("SELECT USER FROM DUAL");}function
get_current_db(){$i=connection()->_current_db?:DB;unset(connection()->_current_db);return$i;}function
where_owner($jf,$Pe="owner"){if(!$_GET["ns"])return'';return"$jf$Pe = sys_context('USERENV', 'CURRENT_SCHEMA')";}function
views_table($e){$Pe=where_owner('');return"(SELECT $e FROM all_views WHERE ".($Pe?:"rownum < 0").")";}function
tables_list(){$mh=views_table("view_name");$Pe=where_owner(" AND ");return
get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."$Pe
UNION SELECT view_name, 'view' FROM $mh
ORDER BY 1");}function
count_tables($ub){$H=array();foreach($ub
as$i)$H[$i]=get_val("SELECT COUNT(*) FROM all_tables WHERE tablespace_name = ".q($i));return$H;}function
table_status($_=""){$H=array();$Jf=q($_);$i=get_current_db();$mh=views_table("view_name");$Pe=where_owner(" AND ");foreach(get_rows('SELECT table_name "Name", \'table\' "Engine", avg_row_len * num_rows "Data_length", num_rows "Rows" FROM all_tables WHERE tablespace_name = '.q($i).$Pe.($_!=""?" AND table_name = $Jf":"")."
UNION SELECT view_name, 'view', 0, 0 FROM $mh".($_!=""?" WHERE view_name = $Jf":"")."
ORDER BY 1")as$I)$H[$I["Name"]]=$I;return$H;}function
is_view($S){return$S["Engine"]=="view";}function
fk_support($S){return
true;}function
fields($R){$H=array();$Pe=where_owner(" AND ");foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($R)."$Pe ORDER BY column_id")as$I){$U=$I["DATA_TYPE"];$Kd="$I[DATA_PRECISION],$I[DATA_SCALE]";if($Kd==",")$Kd=$I["CHAR_COL_DECL_LENGTH"];$H[$I["COLUMN_NAME"]]=array("field"=>$I["COLUMN_NAME"],"full_type"=>$U.($Kd?"($Kd)":""),"type"=>strtolower($U),"length"=>$Kd,"default"=>$I["DATA_DEFAULT"],"null"=>($I["NULLABLE"]=="Y"),"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1),);}return$H;}function
indexes($R,$h=null){$H=array();$Pe=where_owner(" AND ","aic.table_owner");foreach(get_rows("SELECT aic.*, ac.constraint_type, atc.data_default
FROM all_ind_columns aic
LEFT JOIN all_constraints ac ON aic.index_name = ac.constraint_name AND aic.table_name = ac.table_name AND aic.index_owner = ac.owner
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($R)."$Pe
ORDER BY ac.constraint_type, aic.column_position",$h)as$I){$gd=$I["INDEX_NAME"];$Ya=$I["DATA_DEFAULT"];$Ya=($Ya?trim($Ya,'"'):$I["COLUMN_NAME"]);$H[$gd]["type"]=($I["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($I["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));$H[$gd]["columns"][]=$Ya;$H[$gd]["lengths"][]=($I["CHAR_LENGTH"]&&$I["CHAR_LENGTH"]!=$I["COLUMN_LENGTH"]?$I["CHAR_LENGTH"]:null);$H[$gd]["descs"][]=($I["DESCEND"]&&$I["DESCEND"]=="DESC"?'1':null);}return$H;}function
view($_){$mh=views_table("view_name, text");$J=get_rows('SELECT text "select" FROM '.$mh.' WHERE view_name = '.q($_));return
reset($J);}function
collations(){return
array();}function
information_schema($i){return
get_schema()=="INFORMATION_SCHEMA";}function
error(){return
h(connection()->error);}function
explain($g,$F){$g->query("EXPLAIN PLAN FOR $F");return$g->query("SELECT * FROM plan_table");}function
found_rows($S,$Z){}function
auto_increment(){return"";}function
alter_table($R,$_,$m,$wc,$ab,$Sb,$c,$ta,$Xe){$b=$Gb=array();$Le=($R?fields($R):array());foreach($m
as$l){$X=$l[1];if($X&&$l[0]!=""&&idf_escape($l[0])!=$X[0])queries("ALTER TABLE ".table($R)." RENAME COLUMN ".idf_escape($l[0])." TO $X[0]");$Ke=$Le[$l[0]];if($X&&$Ke){$ye=process_field($Ke,$Ke);if($X[2]==$ye[2])$X[2]="";}if($X)$b[]=($R!=""?($l[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($R!=""?")":"");else$Gb[]=idf_escape($l[0]);}if($R=="")return
queries("CREATE TABLE ".table($_)." (\n".implode(",\n",$b)."\n)");return(!$b||queries("ALTER TABLE ".table($R)."\n".implode("\n",$b)))&&(!$Gb||queries("ALTER TABLE ".table($R)." DROP (".implode(", ",$Gb).")"))&&($R==$_||queries("ALTER TABLE ".table($R)." RENAME TO ".table($_)));}function
alter_indexes($R,$b){$Gb=array();$rf=array();foreach($b
as$X){if($X[0]!="INDEX"){$X[2]=preg_replace('~ DESC$~','',$X[2]);$lb=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");array_unshift($rf,"ALTER TABLE ".table($R).$lb);}elseif($X[2]=="DROP")$Gb[]=idf_escape($X[1]);else$rf[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($R."_"))." ON ".table($R)." (".implode(", ",$X[2]).")";}if($Gb)array_unshift($rf,"DROP INDEX ".implode(", ",$Gb));foreach($rf
as$F){if(!queries($F))return
false;}return
true;}function
foreign_keys($R){$H=array();$F="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($R);foreach(get_rows($F)as$I)$H[$I['NAME']]=array("db"=>$I['DEST_DB'],"table"=>$I['DEST_TABLE'],"source"=>array($I['SRC_COLUMN']),"target"=>array($I['DEST_COLUMN']),"on_delete"=>$I['ON_DELETE'],"on_update"=>null,);return$H;}function
truncate_tables($T){return
apply_queries("TRUNCATE TABLE",$T);}function
drop_views($nh){return
apply_queries("DROP VIEW",$nh);}function
drop_tables($T){return
apply_queries("DROP TABLE",$T);}function
last_id($G){return
0;}function
schemas(){$H=get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX')) ORDER BY 1");return($H?:get_vals("SELECT DISTINCT owner FROM all_tables WHERE tablespace_name = ".q(DB)." ORDER BY 1"));}function
get_schema(){return
get_val("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");}function
set_schema($If,$h=null){if(!$h)$h=connection();return$h->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($If));}function
show_variables(){return
get_rows('SELECT name, display_value FROM v$parameter');}function
show_status(){$H=array();$J=get_rows('SELECT * FROM v$instance');foreach(reset($J)as$w=>$X)$H[]=array($w,$X);return$H;}function
process_list(){return
get_rows('SELECT
	sess.process AS "process",
	sess.username AS "user",
	sess.schemaname AS "schema",
	sess.status AS "status",
	sess.wait_class AS "wait_class",
	sess.seconds_in_wait AS "seconds_in_wait",
	sql.sql_text AS "sql_text",
	sess.machine AS "machine",
	sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
convert_field($l){}function
unconvert_field($l,$H){return$H;}function
support($jc){return
preg_match('~^(columns|database|drop_col|indexes|descidx|processlist|scheme|sql|status|table|variables|view)$~',$jc);}}add_driver("mssql","MS SQL");if(isset($_GET["mssql"])){define('Adminer\DRIVER',"mssql");if(extension_loaded("sqlsrv")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="sqlsrv";private$link,$result;private
function
get_error(){$this->error="";foreach(sqlsrv_errors()as$k){$this->errno=$k["code"];$this->error
.="$k[message]\n";}$this->error=rtrim($this->error);}function
attach($M,$V,$D){$fb=array("UID"=>$V,"PWD"=>$D,"CharacterSet"=>"UTF-8");$O=adminer()->connectSsl();if(isset($O["Encrypt"]))$fb["Encrypt"]=$O["Encrypt"];if(isset($O["TrustServerCertificate"]))$fb["TrustServerCertificate"]=$O["TrustServerCertificate"];$i=adminer()->database();if($i!="")$fb["Database"]=$i;list($Wc,$ff)=host_port($M);$this->link=@sqlsrv_connect($Wc.($ff?",$ff":""),$fb);if($this->link){$ld=sqlsrv_server_info($this->link);$this->server_info=$ld['SQLServerVersion'];}else$this->get_error();return($this->link?'':$this->error);}function
quote($Q){$Sg=strlen($Q)!=strlen(utf8_decode($Q));return($Sg?"N":"")."'".str_replace("'","''",$Q)."'";}function
select_db($tb){return$this->query(use_sql($tb));}function
query($F,$Rg=false){$G=sqlsrv_query($this->link,$F);$this->error="";if(!$G){$this->get_error();return
false;}return$this->store_result($G);}function
multi_query($F){$this->result=sqlsrv_query($this->link,$F);$this->error="";if(!$this->result){$this->get_error();return
false;}return
true;}function
store_result($G=null){if(!$G)$G=$this->result;if(!$G)return
false;if(sqlsrv_field_metadata($G))return
new
Result($G);$this->affected_rows=sqlsrv_rows_affected($G);return
true;}function
next_result(){return$this->result?!!sqlsrv_next_result($this->result):false;}}class
Result{var$num_rows;private$result,$offset=0,$fields;function
__construct($G){$this->result=$G;}private
function
convert($I){foreach((array)$I
as$w=>$X){if(is_a($X,'DateTime'))$I[$w]=$X->format("Y-m-d H:i:s");}return$I;}function
fetch_assoc(){return$this->convert(sqlsrv_fetch_array($this->result,SQLSRV_FETCH_ASSOC));}function
fetch_row(){return$this->convert(sqlsrv_fetch_array($this->result,SQLSRV_FETCH_NUMERIC));}function
fetch_field(){if(!$this->fields)$this->fields=sqlsrv_field_metadata($this->result);$l=$this->fields[$this->offset++];$H=new
\stdClass;$H->name=$l["Name"];$H->type=($l["Type"]==1?254:15);$H->charsetnr=0;return$H;}function
seek($we){for($r=0;$r<$we;$r++)sqlsrv_fetch($this->result);}function
__destruct(){sqlsrv_free_stmt($this->result);}}function
last_id($G){return
get_val("SELECT SCOPE_IDENTITY()");}function
explain($g,$F){$g->query("SET SHOWPLAN_ALL ON");$H=$g->query($F);$g->query("SET SHOWPLAN_ALL OFF");return$H;}}else{abstract
class
MssqlDb
extends
PdoDb{function
select_db($tb){return$this->query(use_sql($tb));}function
lastInsertId(){return$this->pdo->lastInsertId();}}function
last_id($G){return
connection()->lastInsertId();}function
explain($g,$F){}if(extension_loaded("pdo_sqlsrv")){class
Db
extends
MssqlDb{var$extension="PDO_SQLSRV";function
attach($M,$V,$D){list($Wc,$ff)=host_port($M);return$this->dsn("sqlsrv:Server=$Wc".($ff?",$ff":""),$V,$D);}}}elseif(extension_loaded("pdo_dblib")){class
Db
extends
MssqlDb{var$extension="PDO_DBLIB";function
attach($M,$V,$D){list($Wc,$ff)=host_port($M);return$this->dsn("dblib:charset=utf8;host=$Wc".($ff?(is_numeric($ff)?";port=":";unix_socket=").$ff:""),$V,$D);}}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLSRV","PDO_SQLSRV","PDO_DBLIB");static$jush="mssql";var$insertFunctions=array("date|time"=>"getdate");var$editFunctions=array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",);var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");var$functions=array("len","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$generated=array("PERSISTED","VIRTUAL");var$onActions="NO ACTION|CASCADE|SET NULL|SET DEFAULT";static
function
connect($M,$V,$D){if($M=="")$M="localhost:1433";return
parent::connect($M,$V,$D);}function
__construct(Db$g){parent::__construct($g);$this->types=array(lang(27)=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20),lang(28)=>array("date"=>10,"smalldatetime"=>19,"datetime"=>19,"datetime2"=>19,"time"=>8,"datetimeoffset"=>10),lang(29)=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823),lang(30)=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),);}function
insertUpdate($R,array$J,array$E){$m=fields($R);$Zg=array();$Z=array();$N=reset($J);$e="c".implode(", c",range(1,count($N)));$Ia=0;$pd=array();foreach($N
as$w=>$X){$Ia++;$_=idf_unescape($w);if(!$m[$_]["auto_increment"])$pd[$w]="c$Ia";if(isset($E[$_]))$Z[]="$w = c$Ia";else$Zg[]="$w = c$Ia";}$jh=array();foreach($J
as$N)$jh[]="(".implode(", ",$N).")";if($Z){$bd=queries("SET IDENTITY_INSERT ".table($R)." ON");$H=queries("MERGE ".table($R)." USING (VALUES\n\t".implode(",\n\t",$jh)."\n) AS source ($e) ON ".implode(" AND ",$Z).($Zg?"\nWHEN MATCHED THEN UPDATE SET ".implode(", ",$Zg):"")."\nWHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($bd?$N:$pd)).") VALUES (".($bd?$e:implode(", ",$pd)).");");if($bd)queries("SET IDENTITY_INSERT ".table($R)." OFF");}else$H=queries("INSERT INTO ".table($R)." (".implode(", ",array_keys($N)).") VALUES\n".implode(",\n",$jh));return$H;}function
begin(){return
queries("BEGIN TRANSACTION");}function
tableHelp($_,$yd=false){$Nd=array("sys"=>"catalog-views/sys-","INFORMATION_SCHEMA"=>"information-schema-views/",);$y=$Nd[get_schema()];if($y)return"relational-databases/system-$y".preg_replace('~_~','-',strtolower($_))."-transact-sql";}}function
idf_escape($t){return"[".str_replace("]","]]",$t)."]";}function
table($t){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($t);}function
get_databases($uc){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($F,$Z,$x,$we=0,$L=" "){return($x?" TOP (".($x+$we).")":"")." $F$Z";}function
limit1($R,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($i,$Wa){return
get_val("SELECT collation_name FROM sys.databases WHERE name = ".q($i));}function
logged_user(){return
get_val("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables($ub){$H=array();foreach($ub
as$i){connection()->select_db($i);$H[$i]=get_val("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$H;}function
table_status($_=""){$H=array();foreach(get_rows("SELECT ao.name AS Name, ao.type_desc AS Engine, (SELECT value FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment
FROM sys.all_objects AS ao
WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($_!=""?"AND name = ".q($_):"ORDER BY name"))as$I)$H[$I["Name"]]=$I;return$H;}function
is_view($S){return$S["Engine"]=="VIEW";}function
fk_support($S){return
true;}function
fields($R){$bb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($R).", 'column', NULL)");$H=array();$og=get_val("SELECT object_id FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') AND name = ".q($R));foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name, t.name type, d.definition [default], d.name default_constraint, i.is_primary_key
FROM sys.all_columns c
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.object_id
LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
WHERE c.object_id = ".q($og))as$I){$U=$I["type"];$Kd=(preg_match("~char|binary~",$U)?intval($I["max_length"])/($U[0]=='n'?2:1):($U=="decimal"?"$I[precision],$I[scale]":""));$H[$I["name"]]=array("field"=>$I["name"],"full_type"=>$U.($Kd?"($Kd)":""),"type"=>$U,"length"=>$Kd,"default"=>(preg_match("~^\('(.*)'\)$~",$I["default"],$z)?str_replace("''","'",$z[1]):$I["default"]),"default_constraint"=>$I["default_constraint"],"null"=>$I["is_nullable"],"auto_increment"=>$I["is_identity"],"collation"=>$I["collation_name"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1),"primary"=>$I["is_primary_key"],"comment"=>$bb[$I["name"]],);}foreach(get_rows("SELECT * FROM sys.computed_columns WHERE object_id = ".q($og))as$I){$H[$I["name"]]["generated"]=($I["is_persisted"]?"PERSISTED":"VIRTUAL");$H[$I["name"]]["default"]=$I["definition"];}return$H;}function
indexes($R,$h=null){$H=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($R),$h)as$I){$_=$I["name"];$H[$_]["type"]=($I["is_primary_key"]?"PRIMARY":($I["is_unique"]?"UNIQUE":"INDEX"));$H[$_]["lengths"]=array();$H[$_]["columns"][$I["key_ordinal"]]=$I["column_name"];$H[$_]["descs"][$I["key_ordinal"]]=($I["is_descending_key"]?'1':null);}return$H;}function
view($_){return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',get_val("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($_))));}function
collations(){$H=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$c)$H[preg_replace('~_.*~','',$c)][]=$c;return$H;}function
information_schema($i){return
get_schema()=="INFORMATION_SCHEMA";}function
error(){return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',connection()->error)));}function
create_database($i,$c){return
queries("CREATE DATABASE ".idf_escape($i).(preg_match('~^[a-z0-9_]+$~i',$c)?" COLLATE $c":""));}function
drop_databases($ub){return
queries("DROP DATABASE ".implode(", ",array_map('Adminer\idf_escape',$ub)));}function
rename_database($_,$c){if(preg_match('~^[a-z0-9_]+$~i',$c))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $c");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($_));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($R,$_,$m,$wc,$ab,$Sb,$c,$ta,$Xe){$b=array();$bb=array();$Le=fields($R);foreach($m
as$l){$d=idf_escape($l[0]);$X=$l[1];if(!$X)$b["DROP"][]=" COLUMN $d";else{$X[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$X[1]);$bb[$l[0]]=$X[5];unset($X[5]);if(preg_match('~ AS ~',$X[3]))unset($X[1],$X[2]);if($l[0]=="")$b["ADD"][]="\n  ".implode("",$X).($R==""?substr($wc[$X[0]],16+strlen($X[0])):"");else{$j=$X[3];unset($X[3]);unset($X[6]);if($d!=$X[0])queries("EXEC sp_rename ".q(table($R).".$d").", ".q(idf_unescape($X[0])).", 'COLUMN'");$b["ALTER COLUMN ".implode("",$X)][]="";$Ke=$Le[$l[0]];if(default_value($Ke)!=$j){if($Ke["default"]!==null)$b["DROP"][]=" ".idf_escape($Ke["default_constraint"]);if($j)$b["ADD"][]="\n $j FOR $d";}}}}if($R=="")return
queries("CREATE TABLE ".table($_)." (".implode(",",(array)$b["ADD"])."\n)");if($R!=$_)queries("EXEC sp_rename ".q(table($R)).", ".q($_));if($wc)$b[""]=$wc;foreach($b
as$w=>$X){if(!queries("ALTER TABLE ".table($_)." $w".implode(",",$X)))return
false;}foreach($bb
as$w=>$X){$ab=substr($X,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($_).", @level2type = N'Column', @level2name = ".q($w));queries("EXEC sp_addextendedproperty
@name = N'MS_Description',
@value = $ab,
@level0type = N'Schema',
@level0name = ".q(get_schema()).",
@level1type = N'Table',
@level1name = ".q($_).",
@level2type = N'Column',
@level2name = ".q($w));}return
true;}function
alter_indexes($R,$b){$u=array();$Gb=array();foreach($b
as$X){if($X[2]=="DROP"){if($X[0]=="PRIMARY")$Gb[]=idf_escape($X[1]);else$u[]=idf_escape($X[1])." ON ".table($R);}elseif(!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($R."_"))." ON ".table($R):"ALTER TABLE ".table($R)." ADD PRIMARY KEY")." (".implode(", ",$X[2]).")"))return
false;}return(!$u||queries("DROP INDEX ".implode(", ",$u)))&&(!$Gb||queries("ALTER TABLE ".table($R)." DROP ".implode(", ",$Gb)));}function
found_rows($S,$Z){}function
foreign_keys($R){$H=array();$_e=array("CASCADE","NO ACTION","SET NULL","SET DEFAULT");foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($R).", @fktable_owner = ".q(get_schema()))as$I){$o=&$H[$I["FK_NAME"]];$o["db"]=$I["PKTABLE_QUALIFIER"];$o["ns"]=$I["PKTABLE_OWNER"];$o["table"]=$I["PKTABLE_NAME"];$o["on_update"]=$_e[$I["UPDATE_RULE"]];$o["on_delete"]=$_e[$I["DELETE_RULE"]];$o["source"][]=$I["FKCOLUMN_NAME"];$o["target"][]=$I["PKCOLUMN_NAME"];}return$H;}function
truncate_tables($T){return
apply_queries("TRUNCATE TABLE",$T);}function
drop_views($nh){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$nh)));}function
drop_tables($T){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$T)));}function
move_tables($T,$nh,$rg){return
apply_queries("ALTER SCHEMA ".idf_escape($rg)." TRANSFER",array_merge($T,$nh));}function
trigger($_,$R){if($_=="")return
array();$J=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($_));$H=reset($J);if($H)$H["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$H["text"]);return$H;}function
triggers($R){$H=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE' WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($R))as$I)$H[$I["name"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){if($_GET["ns"]!="")return$_GET["ns"];return
get_val("SELECT SCHEMA_NAME()");}function
set_schema($Hf){$_GET["ns"]=$Hf;return
true;}function
create_sql($R,$ta,$ig){if(is_view(table_status1($R))){$mh=view($R);return"CREATE VIEW ".table($R)." AS $mh[select]";}$m=array();$E=false;foreach(fields($R)as$_=>$l){$X=process_field($l,$l);if($X[6])$E=true;$m[]=implode("",$X);}foreach(indexes($R)as$_=>$u){if(!$E||$u["type"]!="PRIMARY"){$e=array();foreach($u["columns"]as$w=>$X)$e[]=idf_escape($X).($u["descs"][$w]?" DESC":"");$_=idf_escape($_);$m[]=($u["type"]=="INDEX"?"INDEX $_":"CONSTRAINT $_ ".($u["type"]=="UNIQUE"?"UNIQUE":"PRIMARY KEY"))." (".implode(", ",$e).")";}}foreach(driver()->checkConstraints($R)as$_=>$Ma)$m[]="CONSTRAINT ".idf_escape($_)." CHECK ($Ma)";return"CREATE TABLE ".table($R)." (\n\t".implode(",\n\t",$m)."\n)";}function
foreign_keys_sql($R){$m=array();foreach(foreign_keys($R)as$wc)$m[]=ltrim(format_foreign_key($wc));return($m?"ALTER TABLE ".table($R)." ADD\n\t".implode(",\n\t",$m).";\n\n":"");}function
truncate_sql($R){return"TRUNCATE TABLE ".table($R);}function
use_sql($tb,$ig=""){return"USE ".idf_escape($tb);}function
trigger_sql($R){$H="";foreach(triggers($R)as$_=>$Lg)$H
.=create_trigger(" ON ".table($R),trigger($_,$R)).";";return$H;}function
convert_field($l){}function
unconvert_field($l,$H){return$H;}function
support($jc){return
preg_match('~^(check|comment|columns|database|drop_col|dump|indexes|descidx|scheme|sql|table|trigger|view|view_trigger)$~',$jc);}}class
Adminer{static$instance;var$error='';private$values=array();function
name(){return"<a href='https://www.adminer.org/editor/'".target_blank()." id='h1'><img src='".h(preg_replace("~\\?.*~","",ME)."?file=logo.png&version=5.4.1")."' width='24' height='24' alt='' id='logo'>".lang(33)."</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($lb=false){return
password_file($lb);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($M){}function
database(){if(connection()){$ub=adminer()->databases(false);return(!$ub?get_val("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1)"):$ub[(information_schema($ub[0])?1:0)]);}}function
operators(){return
array("<=",">=");}function
schemas(){return
schemas();}function
databases($uc=true){return
get_databases($uc);}function
pluginsLinks(){}function
queryTimeout(){return
5;}function
afterConnect(){}function
headers(){}function
csp($ob){return$ob;}function
head($rb=null){return
true;}function
bodyClass(){echo" editor";}function
css(){$H=array();foreach(array("","-dark")as$ie){$n="adminer$ie.css";if(file_exists($n)){$mc=file_get_contents($n);$H["$n?v=".crc32($mc)]=($ie?"dark":(preg_match('~prefers-color-scheme:\s*dark~',$mc)?'':'light'));}}return$H;}function
loginForm(){echo"<table class='layout'>\n",adminer()->loginFormField('username','<tr><th>'.lang(34).'<td>',input_hidden("auth[driver]","server").'<input name="auth[username]" autofocus value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'),adminer()->loginFormField('password','<tr><th>'.lang(35).'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'),"</table>\n","<p><input type='submit' value='".lang(36)."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],lang(37))."\n";}function
loginFormField($_,$Sc,$Y){return$Sc.$Y."\n";}function
login($Pd,$D){return
true;}function
tableName($ng){return
h(isset($ng["Engine"])?($ng["Comment"]!=""?$ng["Comment"]:$ng["Name"]):"");}function
fieldName($l,$He=0){return
h(preg_replace('~\s+\[.*\]$~','',($l["comment"]!=""?$l["comment"]:$l["field"])));}function
selectLinks($ng,$N=""){$a=$ng["Name"];if($N!==null)echo'<p class="tabs"><a href="'.h(ME.'edit='.urlencode($a).$N).'">'.lang(38)."</a>\n";}function
foreignKeys($R){return
foreign_keys($R);}function
backwardKeys($R,$mg){$H=array();foreach(get_rows("SELECT TABLE_NAME, CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = ".q(adminer()->database())."
AND REFERENCED_TABLE_SCHEMA = ".q(adminer()->database())."
AND REFERENCED_TABLE_NAME = ".q($R)."
ORDER BY ORDINAL_POSITION",null,"")as$I)$H[$I["TABLE_NAME"]]["keys"][$I["CONSTRAINT_NAME"]][$I["COLUMN_NAME"]]=$I["REFERENCED_COLUMN_NAME"];foreach($H
as$w=>$X){$_=adminer()->tableName(table_status1($w,true));if($_!=""){$Jf=preg_quote($mg);$L="(:|\\s*-)?\\s+";$H[$w]["name"]=(preg_match("(^$Jf$L(.+)|^(.+?)$L$Jf\$)iu",$_,$z)?$z[2].$z[3]:$_);}else
unset($H[$w]);}return$H;}function
backwardKeysPrint($ya,$I){foreach($ya
as$R=>$xa){foreach($xa["keys"]as$Xa){$y=ME.'select='.urlencode($R);$r=0;foreach($Xa
as$d=>$X)$y
.=where_link($r++,$d,$I[$X]);echo"<a href='".h($y)."'>".h($xa["name"])."</a>";$y=ME.'edit='.urlencode($R);foreach($Xa
as$d=>$X)$y
.="&set".urlencode("[".bracket_escape($d)."]")."=".urlencode($I[$X]);echo"<a href='".h($y)."' title='".lang(38)."'>+</a> ";}}}function
selectQuery($F,$gg,$hc=false){return"<!--\n".str_replace("--","--><!-- ",$F)."\n(".format_time($gg).")\n-->\n";}function
rowDescription($R){foreach(fields($R)as$l){if(preg_match("~varchar|character varying~",$l["type"]))return
idf_escape($l["field"]);}return"";}function
rowDescriptions($J,$yc){$H=$J;foreach($J[0]as$w=>$X){if(list($R,$s,$_)=$this->_foreignColumn($yc,$w)){$cd=array();foreach($J
as$I)$cd[$I[$w]]=q($I[$w]);$_b=$this->values[$R];if(!$_b)$_b=get_key_vals("SELECT $s, $_ FROM ".table($R)." WHERE $s IN (".implode(", ",$cd).")");foreach($J
as$ne=>$I){if(isset($I[$w]))$H[$ne][$w]=(string)$_b[$I[$w]];}}}return$H;}function
selectLink($X,$l){}function
selectVal($X,$y,$l,$Me){$H="$X";$y=h($y);if(is_blob($l)&&!is_utf8($X)){$H=lang(39,strlen($Me));if(preg_match("~^(GIF|\xFF\xD8\xFF|\x89PNG\x0D\x0A\x1A\x0A)~",$Me))$H="<img src='$y' alt='$H'>";}if(like_bool($l)&&$H!="")$H=(preg_match('~^(1|t|true|y|yes|on)$~i',$X)?lang(40):lang(41));if($y)$H="<a href='$y'".(is_url($y)?target_blank():"").">$H</a>";if(preg_match('~date~',$l["type"]))$H="<div class='datetime'>$H</div>";return$H;}function
editVal($X,$l){if(preg_match('~date|timestamp~',$l["type"])&&$X!==null)return
preg_replace('~^(\d{2}(\d+))-(0?(\d+))-(0?(\d+))~',lang(42),$X);return$X;}function
config(){return
array();}function
selectColumnsPrint($K,$e){}function
selectSearchPrint($Z,$e,$v){$Z=(array)$_GET["where"];echo'<fieldset id="fieldset-search"><legend>'.lang(43)."</legend><div>\n";$Bd=array();foreach($Z
as$w=>$X)$Bd[$X["col"]]=$w;$r=0;$m=fields($_GET["select"]);foreach($e
as$_=>$zb){$l=$m[$_];if($l["type"]=="enum"||like_bool($l)){$w=$Bd[$_];$r--;echo"<div>".h($zb).":".input_hidden("where[$r][col]",$_);$X=idx($Z[$w],"val");echo(like_bool($l)?"<select name='where[$r][val]'>".optionlist(array(""=>"",lang(41),lang(40)),$X,true)."</select>":enum_input("checkbox"," name='where[$r][val][]'",$l,(array)$X,lang(44))),"</div>\n";unset($e[$_]);}elseif(is_array($B=$this->foreignKeyOptions($_GET["select"],$_))){if($m[$_]["null"])$B[0]='('.lang(44).')';$w=$Bd[$_];$r--;echo"<div>".h($zb).input_hidden("where[$r][col]",$_).input_hidden("where[$r][op]","=").": <select name='where[$r][val]'>".optionlist($B,idx($Z[$w],"val"),true)."</select></div>\n";unset($e[$_]);}}$r=0;foreach($Z
as$X){if(($X["col"]==""||$e[$X["col"]])&&"$X[col]$X[val]"!=""){echo"<div><select name='where[$r][col]'><option value=''>(".lang(45).")".optionlist($e,$X["col"],true)."</select>",html_select("where[$r][op]",array(-1=>"")+adminer()->operators(),$X["op"]),"<input type='search' name='where[$r][val]' value='".h($X["val"])."'>".script("mixin(qsl('input'), {onkeydown: selectSearchKeydown, onsearch: selectSearchSearch});","")."</div>\n";$r++;}}echo"<div><select name='where[$r][col]'><option value=''>(".lang(45).")".optionlist($e,null,true)."</select>",script("qsl('select').onchange = selectAddRow;",""),html_select("where[$r][op]",array(-1=>"")+adminer()->operators()),"<input type='search' name='where[$r][val]'></div>",script("mixin(qsl('input'), {onchange: function () { this.parentNode.firstChild.onchange(); }, onsearch: selectSearchSearch});"),"</div></fieldset>\n";}function
selectOrderPrint($He,$e,$v){$Je=array();foreach($v
as$w=>$u){$He=array();foreach($u["columns"]as$X)$He[]=$e[$X];if(count(array_filter($He,'strlen'))>1&&$w!="PRIMARY")$Je[$w]=implode(", ",$He);}if($Je)echo'<fieldset><legend>'.lang(46)."</legend><div>","<select name='index_order'>".optionlist(array(""=>"")+$Je,(idx($_GET["order"],0)!=""?"":$_GET["index_order"]),true)."</select>","</div></fieldset>\n";if($_GET["order"])echo"<div style='display: none;'>".hidden_fields(array("order"=>array(1=>reset($_GET["order"])),"desc"=>($_GET["desc"]?array(1=>1):array()),))."</div>\n";}function
selectLimitPrint($x){echo"<fieldset><legend>".lang(47)."</legend><div>",html_select("limit",array("",50,100),$x),"</div></fieldset>\n";}function
selectLengthPrint($vg){}function
selectActionPrint($v){echo"<fieldset><legend>".lang(48)."</legend><div>","<input type='submit' value='".lang(49)."'>","</div></fieldset>\n";}function
selectCommandPrint(){return
true;}function
selectImportPrint(){return
true;}function
selectEmailPrint($Pb,$e){}function
selectColumnsProcess($e,$v){return
array(array(),array());}function
selectSearchProcess($m,$v){$H=array();foreach((array)$_GET["where"]as$w=>$Z){$Va=$Z["col"];$Ce=$Z["op"];$X=$Z["val"];if(($w>=0&&$Va!="")||$X!=""){$cb=array();foreach(($Va!=""?array($Va=>$m[$Va]):$m)as$_=>$l){if($Va!=""||is_numeric($X)||!preg_match(number_type(),$l["type"])){$_=idf_escape($_);if($Va!=""&&$l["type"]=="enum"){$ed=array();foreach($X
as$hh){if(preg_match('~val-~',$hh))$ed[]=q(substr($hh,4));}$cb[]=(in_array("null",$X)?"$_ IS NULL OR ":"").($ed?"$_ IN (".implode(", ",$ed).")":"0");}else{$wg=preg_match('~char|text|enum|set~',$l["type"]);$Y=adminer()->processInput($l,(!$Ce&&$wg&&preg_match('~^[^%]+$~',$X)?"%$X%":$X));$cb[]=driver()->convertSearch($_,$Z,$l).($Y=="NULL"?" IS".($Ce==">="?" NOT":"")." $Y":(in_array($Ce,adminer()->operators())||$Ce=="="?" $Ce $Y":($wg?" LIKE $Y":" IN (".($Y[0]=="'"?str_replace(",","', '",$Y):$Y).")")));if($w<0&&$X=="0")$cb[]="$_ IS NULL";}}}$H[]=($cb?"(".implode(" OR ",$cb).")":"1 = 0");}}return$H;}function
selectOrderProcess($m,$v){$hd=$_GET["index_order"];if($hd!="")unset($_GET["order"][1]);if($_GET["order"])return
array(idf_escape(reset($_GET["order"])).($_GET["desc"]?" DESC":""));foreach(($hd!=""?array($v[$hd]):$v)as$u){if($hd!=""||$u["type"]=="INDEX"){$Mc=array_filter($u["descs"]);$zb=false;foreach($u["columns"]as$X){if(preg_match('~date|timestamp~',$m[$X]["type"])){$zb=true;break;}}$H=array();foreach($u["columns"]as$w=>$X)$H[]=idf_escape($X).(($Mc?$u["descs"][$w]:$zb)?" DESC":"");return$H;}}return
array();}function
selectLimitProcess(){return(isset($_GET["limit"])?intval($_GET["limit"]):50);}function
selectLengthProcess(){return"100";}function
selectEmailProcess($Z,$yc){return
false;}function
selectQueryBuild($K,$Z,$Gc,$He,$x,$C){return"";}function
messageQuery($F,$xg,$hc=false){return" <span class='time'>".@date("H:i:s")."</span><!--\n".str_replace("--","--><!-- ",$F)."\n".($xg?"($xg)\n":"")."-->";}function
editRowPrint($R,$m,$I,$Zg){}function
editFunctions($l){$H=array();if($l["null"]&&preg_match('~blob~',$l["type"]))$H["NULL"]=lang(44);$H[""]=($l["null"]||$l["auto_increment"]||like_bool($l)?"":"*");if(preg_match('~date|time~',$l["type"]))$H["now"]=lang(50);if(preg_match('~_(md5|sha1)$~i',$l["field"],$z))$H[]=strtolower($z[1]);return$H;}function
editInput($R,$l,$ra,$Y){if($l["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$ra value='orig' checked><i>".lang(10)."</i></label> ":"").enum_input("radio",$ra,$l,$Y,lang(44));$B=$this->foreignKeyOptions($R,$l["field"],$Y);if($B!==null)return(is_array($B)?"<select$ra>".optionlist($B,$Y,true)."</select>":"<input value='".h($Y)."'$ra class='hidden'>"."<input value='".h($B)."' class='jsonly'>"."<div></div>".script("qsl('input').oninput = partial(whisper, '".ME."script=complete&source=".urlencode($R)."&field=".urlencode($l["field"])."&value='); qsl('div').onclick = whisperClick;",""));if(like_bool($l))return'<input type="checkbox" value="1"'.(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?' checked':'')."$ra>";$Uc="";if(preg_match('~time~',$l["type"]))$Uc=lang(51);if(preg_match('~date|timestamp~',$l["type"]))$Uc=lang(52).($Uc?" [$Uc]":"");if($Uc)return"<input value='".h($Y)."'$ra> ($Uc)";if(preg_match('~_(md5|sha1)$~i',$l["field"]))return"<input type='password' value='".h($Y)."'$ra>";return'';}function
editHint($R,$l,$Y){return(preg_match('~\s+(\[.*\])$~',($l["comment"]!=""?$l["comment"]:$l["field"]),$z)?h(" $z[1]"):'');}function
processInput($l,$Y,$q=""){if($q=="now")return"$q()";$H=$Y;if(preg_match('~date|timestamp~',$l["type"])&&preg_match('(^'.str_replace('\$1','(?P<p1>\d*)',preg_replace('~(\\\\\\$([2-6]))~','(?P<p\2>\d{1,2})',preg_quote(lang(42)))).'(.*))',$Y,$z))$H=($z["p1"]!=""?$z["p1"]:($z["p2"]!=""?($z["p2"]<70?20:19).$z["p2"]:gmdate("Y")))."-$z[p3]$z[p4]-$z[p5]$z[p6]".end($z);$H=q($H);if($Y==""&&like_bool($l))$H="'0'";elseif($Y==""&&($l["null"]||!preg_match('~char|text~',$l["type"])))$H="NULL";elseif(preg_match('~^(md5|sha1)$~',$q))$H="$q($H)";return
unconvert_field($l,$H);}function
dumpOutput(){return
array();}function
dumpFormat(){return
array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpDatabase($i){}function
dumpTable($R,$ig,$yd=0){echo"\xef\xbb\xbf";}function
dumpData($R,$ig,$F){$G=connection()->query($F,1);if($G){while($I=$G->fetch_assoc()){if($ig=="table"){dump_csv(array_keys($I));$ig="INSERT";}dump_csv($I);}}}function
dumpFilename($ad){return
friendly_url($ad);}function
dumpHeaders($ad,$le=false){$dc="csv";header("Content-Type: text/csv; charset=utf-8");return$dc;}function
dumpFooter(){}function
importServerPath(){}function
homepage(){return
true;}function
navigation($he){echo"<h1>".adminer()->name()." <span class='version'>".VERSION;$qe=$_COOKIE["adminer_version"];echo" <a href='https://www.adminer.org/editor/#download'".target_blank()." id='version'>".(version_compare(VERSION,$qe)<0?h($qe):"")."</a>","</span></h1>\n";switch_lang();if($he=="auth"){$pc=true;foreach((array)$_SESSION["pwds"]as$kh=>$Tf){foreach($Tf[""]as$V=>$D){if($D!==null){if($pc){echo"<ul id='logins'>",script("mixin(qs('#logins'), {onmouseover: menuOver, onmouseout: menuOut});");$pc=false;}echo"<li><a href='".h(auth_url($kh,"",$V))."'>".($V!=""?h($V):"<i>".lang(44)."</i>")."</a>\n";}}}}else{adminer()->databasesPrint($he);if($he!="db"&&$he!="ns"){$S=table_status('',true);if(!$S)echo"<p class='message'>".lang(11)."\n";else
adminer()->tablesPrint($S);}}}function
syntaxHighlighting($T){}function
databasesPrint($he){}function
tablesPrint($T){echo"<ul id='tables'>",script("mixin(qs('#tables'), {onmouseover: menuOver, onmouseout: menuOut});");foreach($T
as$I){echo'<li>';$_=adminer()->tableName($I);if($_!="")echo"<a href='".h(ME).'select='.urlencode($I["Name"])."'".bold($_GET["select"]==$I["Name"]||$_GET["edit"]==$I["Name"],"select")." title='".lang(53)."'>$_</a>\n";}echo"</ul>\n";}function
_foreignColumn($yc,$d){foreach((array)$yc[$d]as$xc){if(count($xc["source"])==1){$_=adminer()->rowDescription($xc["table"]);if($_!=""){$s=idf_escape($xc["target"][0]);return
array($xc["table"],$s,$_);}}}}private
function
foreignKeyOptions($R,$d,$Y=null){if(list($rg,$s,$_)=$this->_foreignColumn(column_foreign_keys($R),$d)){$H=&$this->values[$rg];if($H===null){$S=table_status1($rg);$H=($S["Rows"]>1000?"":array(""=>"")+get_key_vals("SELECT $s, $_ FROM ".table($rg)." ORDER BY 2"));}if(!$H&&$Y!==null)return
get_val("SELECT $_ FROM ".table($rg)." WHERE $s = ".q($Y));return$H;}}}class
Plugins{private
static$append=array('dumpFormat'=>true,'dumpOutput'=>true,'editRowPrint'=>true,'editFunctions'=>true,'config'=>true);var$plugins;var$error='';private$hooks=array();function
__construct($ef){if($ef===null){$ef=array();$Aa="adminer-plugins";if(is_dir($Aa)){foreach(glob("$Aa/*.php")as$n)$fd=include_once"./$n";}$Tc=" href='https://www.adminer.org/plugins/#use'".target_blank();if(file_exists("$Aa.php")){$fd=include_once"./$Aa.php";if(is_array($fd)){foreach($fd
as$df)$ef[get_class($df)]=$df;}else$this->error
.=lang(54,"<b>$Aa.php</b>",$Tc)."<br>";}foreach(get_declared_classes()as$Ra){if(!$ef[$Ra]&&preg_match('~^Adminer\w~i',$Ra)){$xf=new
\ReflectionClass($Ra);$hb=$xf->getConstructor();if($hb&&$hb->getNumberOfRequiredParameters())$this->error
.=lang(55,$Tc,"<b>$Ra</b>","<b>$Aa.php</b>")."<br>";else$ef[$Ra]=new$Ra;}}}$this->plugins=$ef;$ga=new
Adminer;$ef[]=$ga;$xf=new
\ReflectionObject($ga);foreach($xf->getMethods()as$ge){foreach($ef
as$df){$_=$ge->getName();if(method_exists($df,$_))$this->hooks[$_][]=$df;}}}function
__call($_,array$Te){$ma=array();foreach($Te
as$w=>$X)$ma[]=&$Te[$w];$H=null;foreach($this->hooks[$_]as$df){$Y=call_user_func_array(array($df,$_),$ma);if($Y!==null){if(!self::$append[$_])return$Y;$H=$Y+(array)$H;}}return$H;}}abstract
class
Plugin{protected$translations=array();function
description(){return$this->lang('');}function
screenshot(){return"";}protected
function
lang($t,$A=null){$ma=func_get_args();$ma[0]=idx($this->translations[LANG],$t)?:$t;return
call_user_func_array('Adminer\lang_format',$ma);}}Adminer::$instance=(function_exists('adminer_object')?adminer_object():(is_dir("adminer-plugins")||file_exists("adminer-plugins.php")?new
Plugins(null):new
Adminer));SqlDriver::$drivers=array("server"=>"MySQL / MariaDB")+SqlDriver::$drivers;if(!defined('Adminer\DRIVER')){define('Adminer\DRIVER',"server");if(extension_loaded("mysqli")&&$_GET["ext"]!="pdo"){class
Db
extends
\MySQLi{static$instance;var$extension="MySQLi",$flavor='';function
__construct(){parent::init();}function
attach($M,$V,$D){mysqli_report(MYSQLI_REPORT_OFF);list($Wc,$ff)=host_port($M);$O=adminer()->connectSsl();if($O)$this->ssl_set($O['key'],$O['cert'],$O['ca'],'','');$H=@$this->real_connect(($M!=""?$Wc:ini_get("mysqli.default_host")),($M.$V!=""?$V:ini_get("mysqli.default_user")),($M.$V.$D!=""?$D:ini_get("mysqli.default_pw")),null,(is_numeric($ff)?intval($ff):ini_get("mysqli.default_port")),(is_numeric($ff)?null:$ff),($O?($O['verify']!==false?2048:64):0));$this->options(MYSQLI_OPT_LOCAL_INFILE,0);return($H?'':$this->error);}function
set_charset($La){if(parent::set_charset($La))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $La");}function
next_result(){return
self::more_results()&&parent::next_result();}function
quote($Q){return"'".$this->escape_string($Q)."'";}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Db
extends
SqlDb{private$link;function
attach($M,$V,$D){if(ini_bool("mysql.allow_local_infile"))return
lang(56,"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");$this->link=@mysql_connect(($M!=""?$M:ini_get("mysql.default_host")),($M.$V!=""?$V:ini_get("mysql.default_user")),($M.$V.$D!=""?$D:ini_get("mysql.default_password")),true,131072);if(!$this->link)return
mysql_error();$this->server_info=mysql_get_server_info($this->link);return'';}function
set_charset($La){if(function_exists('mysql_set_charset')){if(mysql_set_charset($La,$this->link))return
true;mysql_set_charset('utf8',$this->link);}return$this->query("SET NAMES $La");}function
quote($Q){return"'".mysql_real_escape_string($Q,$this->link)."'";}function
select_db($tb){return
mysql_select_db($tb,$this->link);}function
query($F,$Rg=false){$G=@($Rg?mysql_unbuffered_query($F,$this->link):mysql_query($F,$this->link));$this->error="";if(!$G){$this->errno=mysql_errno($this->link);$this->error=mysql_error($this->link);return
false;}if($G===true){$this->affected_rows=mysql_affected_rows($this->link);$this->info=mysql_info($this->link);return
true;}return
new
Result($G);}}class
Result{var$num_rows;private$result;private$offset=0;function
__construct($G){$this->result=$G;$this->num_rows=mysql_num_rows($G);}function
fetch_assoc(){return
mysql_fetch_assoc($this->result);}function
fetch_row(){return
mysql_fetch_row($this->result);}function
fetch_field(){$H=mysql_fetch_field($this->result,$this->offset++);$H->orgtable=$H->table;$H->charsetnr=($H->blob?63:0);return$H;}function
__destruct(){mysql_free_result($this->result);}}}elseif(extension_loaded("pdo_mysql")){class
Db
extends
PdoDb{var$extension="PDO_MySQL";function
attach($M,$V,$D){$B=array(\PDO::MYSQL_ATTR_LOCAL_INFILE=>false);$O=adminer()->connectSsl();if($O){if($O['key'])$B[\PDO::MYSQL_ATTR_SSL_KEY]=$O['key'];if($O['cert'])$B[\PDO::MYSQL_ATTR_SSL_CERT]=$O['cert'];if($O['ca'])$B[\PDO::MYSQL_ATTR_SSL_CA]=$O['ca'];if(isset($O['verify']))$B[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT]=$O['verify'];}list($Wc,$ff)=host_port($M);return$this->dsn("mysql:charset=utf8;host=$Wc".($ff?(is_numeric($ff)?";port=":";unix_socket=").$ff:""),$V,$D,$B);}function
set_charset($La){return$this->query("SET NAMES $La");}function
select_db($tb){return$this->query("USE ".idf_escape($tb));}function
query($F,$Rg=false){$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,!$Rg);return
parent::query($F,$Rg);}}}class
Driver
extends
SqlDriver{static$extensions=array("MySQLi","MySQL","PDO_MySQL");static$jush="sql";var$unsigned=array("unsigned","zerofill","unsigned zerofill");var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");var$functions=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");static
function
connect($M,$V,$D){$g=parent::connect($M,$V,$D);if(is_string($g)){if(function_exists('iconv')&&!is_utf8($g)&&strlen($Gf=iconv("windows-1250","utf-8",$g))>strlen($g))$g=$Gf;return$g;}$g->set_charset(charset($g));$g->query("SET sql_quote_show_create = 1, autocommit = 1");$g->flavor=(preg_match('~MariaDB~',$g->server_info)?'maria':'mysql');add_driver(DRIVER,($g->flavor=='maria'?"MariaDB":"MySQL"));return$g;}function
__construct(Db$g){parent::__construct($g);$this->types=array(lang(27)=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),lang(28)=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),lang(29)=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),lang(57)=>array("enum"=>65535,"set"=>64),lang(30)=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),lang(32)=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),);$this->insertFunctions=array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",);if(min_version('5.7.8',10.2,$g))$this->types[lang(29)]["json"]=4294967295;if(min_version('',10.7,$g)){$this->types[lang(29)]["uuid"]=128;$this->insertFunctions['uuid']='uuid';}if(min_version(9,'',$g)){$this->types[lang(27)]["vector"]=16383;$this->insertFunctions['vector']='string_to_vector';}if(min_version(5.1,'',$g))$this->partitionBy=array("HASH","LINEAR HASH","KEY","LINEAR KEY","RANGE","LIST");if(min_version(5.7,10.2,$g))$this->generated=array("STORED","VIRTUAL");}function
unconvertFunction(array$l){return(preg_match("~binary~",$l["type"])?"<code class='jush-sql'>UNHEX</code>":($l["type"]=="bit"?doc_link(array('sql'=>'bit-value-literals.html'),"<code>b''</code>"):(preg_match("~geometry|point|linestring|polygon~",$l["type"])?"<code class='jush-sql'>GeomFromText</code>":"")));}function
insert($R,array$N){return($N?parent::insert($R,$N):queries("INSERT INTO ".table($R)." ()\nVALUES ()"));}function
insertUpdate($R,array$J,array$E){$e=array_keys(reset($J));$jf="INSERT INTO ".table($R)." (".implode(", ",$e).") VALUES\n";$jh=array();foreach($e
as$w)$jh[$w]="$w = VALUES($w)";$kg="\nON DUPLICATE KEY UPDATE ".implode(", ",$jh);$jh=array();$Kd=0;foreach($J
as$N){$Y="(".implode(", ",$N).")";if($jh&&(strlen($jf)+$Kd+strlen($Y)+strlen($kg)>1e6)){if(!queries($jf.implode(",\n",$jh).$kg))return
false;$jh=array();$Kd=0;}$jh[]=$Y;$Kd+=strlen($Y)+2;}return
queries($jf.implode(",\n",$jh).$kg);}function
slowQuery($F,$yg){if(min_version('5.7.8','10.1.2')){if($this->conn->flavor=='maria')return"SET STATEMENT max_statement_time=$yg FOR $F";elseif(preg_match('~^(SELECT\b)(.+)~is',$F,$z))return"$z[1] /*+ MAX_EXECUTION_TIME(".($yg*1000).") */ $z[2]";}}function
convertSearch($t,array$X,array$l){return(preg_match('~char|text|enum|set~',$l["type"])&&!preg_match("~^utf8~",$l["collation"])&&preg_match('~[\x80-\xFF]~',$X['val'])?"CONVERT($t USING ".charset($this->conn).")":$t);}function
warnings(){$G=$this->conn->query("SHOW WARNINGS");if($G&&$G->num_rows){ob_start();print_select_result($G);return
ob_get_clean();}}function
tableHelp($_,$yd=false){$Rd=($this->conn->flavor=='maria');if(information_schema(DB))return
strtolower("information-schema-".($Rd?"$_-table/":str_replace("_","-",$_)."-table.html"));if(DB=="mysql")return($Rd?"mysql$_-table/":"system-schema.html");}function
partitionsInfo($R){$Bc="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($R);$G=$this->conn->query("SELECT PARTITION_METHOD, PARTITION_EXPRESSION, PARTITION_ORDINAL_POSITION $Bc ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");$H=array();list($H["partition_by"],$H["partition"],$H["partitions"])=$G->fetch_row();$Ye=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $Bc AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$H["partition_names"]=array_keys($Ye);$H["partition_values"]=array_values($Ye);return$H;}function
hasCStyleEscapes(){static$Ja;if($Ja===null){$eg=get_val("SHOW VARIABLES LIKE 'sql_mode'",1,$this->conn);$Ja=(strpos($eg,'NO_BACKSLASH_ESCAPES')===false);}return$Ja;}function
engines(){$H=array();foreach(get_rows("SHOW ENGINES")as$I){if(preg_match("~YES|DEFAULT~",$I["Support"]))$H[]=$I["Engine"];}return$H;}function
indexAlgorithms(array$ng){return(preg_match('~^(MEMORY|NDB)$~',$ng["Engine"])?array("HASH","BTREE"):array());}}function
idf_escape($t){return"`".str_replace("`","``",$t)."`";}function
table($t){return
idf_escape($t);}function
get_databases($uc){$H=get_session("dbs");if($H===null){$F="SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME";$H=($uc?slow_query($F):get_vals($F));restart_session();set_session("dbs",$H);stop_session();}return$H;}function
limit($F,$Z,$x,$we=0,$L=" "){return" $F$Z".($x?$L."LIMIT $x".($we?" OFFSET $we":""):"");}function
limit1($R,$F,$Z,$L="\n"){return
limit($F,$Z,1,0,$L);}function
db_collation($i,array$Wa){$H=null;$lb=get_val("SHOW CREATE DATABASE ".idf_escape($i),1);if(preg_match('~ COLLATE ([^ ]+)~',$lb,$z))$H=$z[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$lb,$z))$H=$Wa[$z[1]][-1];return$H;}function
logged_user(){return
get_val("SELECT USER()");}function
tables_list(){return
get_key_vals("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME");}function
count_tables(array$ub){$H=array();foreach($ub
as$i)$H[$i]=count(get_vals("SHOW TABLES IN ".idf_escape($i)));return$H;}function
table_status($_="",$ic=false){$H=array();foreach(get_rows($ic?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($_!=""?"AND TABLE_NAME = ".q($_):"ORDER BY Name"):"SHOW TABLE STATUS".($_!=""?" LIKE ".q(addcslashes($_,"%_\\")):""))as$I){if($I["Engine"]=="InnoDB")$I["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$I["Comment"]);if(!isset($I["Engine"]))$I["Comment"]="";if($_!="")$I["Name"]=$_;$H[$I["Name"]]=$I;}return$H;}function
is_view(array$S){return$S["Engine"]===null;}function
fk_support(array$S){return
preg_match('~InnoDB|IBMDB2I'.(min_version(5.6)?'|NDB':'').'~i',$S["Engine"]);}function
fields($R){$Rd=(connection()->flavor=='maria');$H=array();foreach(get_rows("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($R)." ORDER BY ORDINAL_POSITION")as$I){$l=$I["COLUMN_NAME"];$U=$I["COLUMN_TYPE"];$Fc=$I["GENERATION_EXPRESSION"];$gc=$I["EXTRA"];preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$gc,$Ec);preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$U,$Ud);$j=$I["COLUMN_DEFAULT"];if($j!=""){$xd=preg_match('~text|json~',$Ud[1]);if(!$Rd&&$xd)$j=preg_replace("~^(_\w+)?('.*')$~",'\2',stripslashes($j));if($Rd||$xd){$j=($j=="NULL"?null:preg_replace_callback("~^'(.*)'$~",function($z){return
stripslashes(str_replace("''","'",$z[1]));},$j));}if(!$Rd&&preg_match('~binary~',$Ud[1])&&preg_match('~^0x(\w*)$~',$j,$z))$j=pack("H*",$z[1]);}$H[$l]=array("field"=>$l,"full_type"=>$U,"type"=>$Ud[1],"length"=>$Ud[2],"unsigned"=>ltrim($Ud[3].$Ud[4]),"default"=>($Ec?($Rd?$Fc:stripslashes($Fc)):$j),"null"=>($I["IS_NULLABLE"]=="YES"),"auto_increment"=>($gc=="auto_increment"),"on_update"=>(preg_match('~\bon update (\w+)~i',$gc,$z)?$z[1]:""),"collation"=>$I["COLLATION_NAME"],"privileges"=>array_flip(explode(",","$I[PRIVILEGES],where,order")),"comment"=>$I["COLUMN_COMMENT"],"primary"=>($I["COLUMN_KEY"]=="PRI"),"generated"=>($Ec[1]=="PERSISTENT"?"STORED":$Ec[1]),);}return$H;}function
indexes($R,$h=null){$H=array();foreach(get_rows("SHOW INDEX FROM ".table($R),$h)as$I){$_=$I["Key_name"];$H[$_]["type"]=($_=="PRIMARY"?"PRIMARY":($I["Index_type"]=="FULLTEXT"?"FULLTEXT":($I["Non_unique"]?($I["Index_type"]=="SPATIAL"?"SPATIAL":"INDEX"):"UNIQUE")));$H[$_]["columns"][]=$I["Column_name"];$H[$_]["lengths"][]=($I["Index_type"]=="SPATIAL"?null:$I["Sub_part"]);$H[$_]["descs"][]=null;$H[$_]["algorithm"]=$I["Index_type"];}return$H;}function
foreign_keys($R){static$af='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$H=array();$mb=get_val("SHOW CREATE TABLE ".table($R),1);if($mb){preg_match_all("~CONSTRAINT ($af) FOREIGN KEY ?\\(((?:$af,? ?)+)\\) REFERENCES ($af)(?:\\.($af))? \\(((?:$af,? ?)+)\\)(?: ON DELETE (".driver()->onActions."))?(?: ON UPDATE (".driver()->onActions."))?~",$mb,$Vd,PREG_SET_ORDER);foreach($Vd
as$z){preg_match_all("~$af~",$z[2],$ag);preg_match_all("~$af~",$z[5],$rg);$H[idf_unescape($z[1])]=array("db"=>idf_unescape($z[4]!=""?$z[3]:$z[4]),"table"=>idf_unescape($z[4]!=""?$z[4]:$z[3]),"source"=>array_map('Adminer\idf_unescape',$ag[0]),"target"=>array_map('Adminer\idf_unescape',$rg[0]),"on_delete"=>($z[6]?:"RESTRICT"),"on_update"=>($z[7]?:"RESTRICT"),);}}return$H;}function
view($_){return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',get_val("SHOW CREATE VIEW ".table($_),1)));}function
collations(){$H=array();foreach(get_rows("SHOW COLLATION")as$I){if($I["Default"])$H[$I["Charset"]][-1]=$I["Collation"];else$H[$I["Charset"]][]=$I["Collation"];}ksort($H);foreach($H
as$w=>$X)sort($H[$w]);return$H;}function
information_schema($i){return($i=="information_schema")||(min_version(5.5)&&$i=="performance_schema");}function
error(){return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",connection()->error));}function
create_database($i,$c){return
queries("CREATE DATABASE ".idf_escape($i).($c?" COLLATE ".q($c):""));}function
drop_databases(array$ub){$H=apply_queries("DROP DATABASE",$ub,'Adminer\idf_escape');restart_session();set_session("dbs",null);return$H;}function
rename_database($_,$c){$H=false;if(create_database($_,$c)){$T=array();$nh=array();foreach(tables_list()as$R=>$U){if($U=='VIEW')$nh[]=$R;else$T[]=$R;}$H=(!$T&&!$nh)||move_tables($T,$nh,$_);drop_databases($H?array(DB):array());}return$H;}function
auto_increment(){$ua=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$u){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$u["columns"],true)){$ua="";break;}if($u["type"]=="PRIMARY")$ua=" UNIQUE";}}return" AUTO_INCREMENT$ua";}function
alter_table($R,$_,array$m,array$wc,$ab,$Sb,$c,$ta,$Xe){$b=array();foreach($m
as$l){if($l[1]){$j=$l[1][3];if(preg_match('~ GENERATED~',$j)){$l[1][3]=(connection()->flavor=='maria'?"":$l[1][2]);$l[1][2]=$j;}$b[]=($R!=""?($l[0]!=""?"CHANGE ".idf_escape($l[0]):"ADD"):" ")." ".implode($l[1]).($R!=""?$l[2]:"");}else$b[]="DROP ".idf_escape($l[0]);}$b=array_merge($b,$wc);$P=($ab!==null?" COMMENT=".q($ab):"").($Sb?" ENGINE=".q($Sb):"").($c?" COLLATE ".q($c):"").($ta!=""?" AUTO_INCREMENT=$ta":"");if($Xe){$Ye=array();if($Xe["partition_by"]=='RANGE'||$Xe["partition_by"]=='LIST'){foreach($Xe["partition_names"]as$w=>$X){$Y=$Xe["partition_values"][$w];$Ye[]="\n  PARTITION ".idf_escape($X)." VALUES ".($Xe["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$P
.="\nPARTITION BY $Xe[partition_by]($Xe[partition])";if($Ye)$P
.=" (".implode(",",$Ye)."\n)";elseif($Xe["partitions"])$P
.=" PARTITIONS ".(+$Xe["partitions"]);}elseif($Xe===null)$P
.="\nREMOVE PARTITIONING";if($R=="")return
queries("CREATE TABLE ".table($_)." (\n".implode(",\n",$b)."\n)$P");if($R!=$_)$b[]="RENAME TO ".table($_);if($P)$b[]=ltrim($P);return($b?queries("ALTER TABLE ".table($R)."\n".implode(",\n",$b)):true);}function
alter_indexes($R,$b){$Ka=array();foreach($b
as$X)$Ka[]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ",$X[2]).")");return
queries("ALTER TABLE ".table($R).implode(",",$Ka));}function
truncate_tables(array$T){return
apply_queries("TRUNCATE TABLE",$T);}function
drop_views(array$nh){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$nh)));}function
drop_tables(array$T){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$T)));}function
move_tables(array$T,array$nh,$rg){$_f=array();foreach($T
as$R)$_f[]=table($R)." TO ".idf_escape($rg).".".table($R);if(!$_f||queries("RENAME TABLE ".implode(", ",$_f))){$xb=array();foreach($nh
as$R)$xb[table($R)]=view($R);connection()->select_db($rg);$i=idf_escape(DB);foreach($xb
as$_=>$mh){if(!queries("CREATE VIEW $_ AS ".str_replace(" $i."," ",$mh["select"]))||!queries("DROP VIEW $i.$_"))return
false;}return
true;}return
false;}function
copy_tables(array$T,array$nh,$rg){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($T
as$R){$_=($rg==DB?table("copy_$R"):idf_escape($rg).".".table($R));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $_"))||!queries("CREATE TABLE $_ LIKE ".table($R))||!queries("INSERT INTO $_ SELECT * FROM ".table($R)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")))as$I){$Lg=$I["Trigger"];if(!queries("CREATE TRIGGER ".($rg==DB?idf_escape("copy_$Lg"):idf_escape($rg).".".idf_escape($Lg))." $I[Timing] $I[Event] ON $_ FOR EACH ROW\n$I[Statement];"))return
false;}}foreach($nh
as$R){$_=($rg==DB?table("copy_$R"):idf_escape($rg).".".table($R));$mh=view($R);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $_"))||!queries("CREATE VIEW $_ AS $mh[select]"))return
false;}return
true;}function
trigger($_,$R){if($_=="")return
array();$J=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($_));return
reset($J);}function
triggers($R){$H=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")))as$I)$H[$I["Trigger"]]=array($I["Timing"],$I["Event"]);return$H;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
routine($_,$U){$ka=array("bool","boolean","integer","double precision","real","dec","numeric","fixed","national char","national varchar");$bg="(?:\\s|/\\*[\s\S]*?\\*/|(?:#|-- )[^\n]*\n?|--\r?\n)";$Tb=driver()->enumLength;$Pg="((".implode("|",array_merge(array_keys(driver()->types()),$ka)).")\\b(?:\\s*\\(((?:[^'\")]|$Tb)++)\\))?"."\\s*(zerofill\\s*)?(unsigned(?:\\s+zerofill)?)?)(?:\\s*(?:CHARSET|CHARACTER\\s+SET)\\s*['\"]?([^'\"\\s,]+)['\"]?)?(?:\\s*COLLATE\\s*['\"]?[^'\"\\s,]+['\"]?)?";$af="$bg*(".($U=="FUNCTION"?"":driver()->inout).")?\\s*(?:`((?:[^`]|``)*)`\\s*|\\b(\\S+)\\s+)$Pg";$lb=get_val("SHOW CREATE $U ".idf_escape($_),2);preg_match("~\\(((?:$af\\s*,?)*)\\)\\s*".($U=="FUNCTION"?"RETURNS\\s+$Pg\\s+":"")."(.*)~is",$lb,$z);$m=array();preg_match_all("~$af\\s*,?~is",$z[1],$Vd,PREG_SET_ORDER);foreach($Vd
as$Se)$m[]=array("field"=>str_replace("``","`",$Se[2]).$Se[3],"type"=>strtolower($Se[5]),"length"=>preg_replace_callback("~$Tb~s",'Adminer\normalize_enum',$Se[6]),"unsigned"=>strtolower(preg_replace('~\s+~',' ',trim("$Se[8] $Se[7]"))),"null"=>true,"full_type"=>$Se[4],"inout"=>strtoupper($Se[1]),"collation"=>strtolower($Se[9]),);return
array("fields"=>$m,"comment"=>get_val("SELECT ROUTINE_COMMENT FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_NAME = ".q($_)),)+($U!="FUNCTION"?array("definition"=>$z[11]):array("returns"=>array("type"=>$z[12],"length"=>$z[13],"unsigned"=>$z[15],"collation"=>$z[16]),"definition"=>$z[17],"language"=>"SQL",));}function
routines(){return
get_rows("SELECT SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()");}function
routine_languages(){return
array();}function
routine_id($_,array$I){return
idf_escape($_);}function
last_id($G){return
get_val("SELECT LAST_INSERT_ID()");}function
explain(Db$g,$F){return$g->query("EXPLAIN ".(min_version(5.1)&&!min_version(5.7)?"PARTITIONS ":"").$F);}function
found_rows(array$S,array$Z){return($Z||$S["Engine"]!="InnoDB"?null:$S["Rows"]);}function
create_sql($R,$ta,$ig){$H=get_val("SHOW CREATE TABLE ".table($R),1);if(!$ta)$H=preg_replace('~ AUTO_INCREMENT=\d+~','',$H);return$H;}function
truncate_sql($R){return"TRUNCATE ".table($R);}function
use_sql($tb,$ig=""){$_=idf_escape($tb);$H="";if(preg_match('~CREATE~',$ig)&&($lb=get_val("SHOW CREATE DATABASE $_",1))){set_utf8mb4($lb);if($ig=="DROP+CREATE")$H="DROP DATABASE IF EXISTS $_;\n";$H
.="$lb;\n";}return$H."USE $_";}function
trigger_sql($R){$H="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")),null,"-- ")as$I)$H
.="\nCREATE TRIGGER ".idf_escape($I["Trigger"])." $I[Timing] $I[Event] ON ".table($I["Table"])." FOR EACH ROW\n$I[Statement];;\n";return$H;}function
show_variables(){return
get_rows("SHOW VARIABLES");}function
show_status(){return
get_rows("SHOW STATUS");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
convert_field(array$l){if(preg_match("~binary~",$l["type"]))return"HEX(".idf_escape($l["field"]).")";if($l["type"]=="bit")return"BIN(".idf_escape($l["field"])." + 0)";if(preg_match("~geometry|point|linestring|polygon~",$l["type"]))return(min_version(8)?"ST_":"")."AsWKT(".idf_escape($l["field"]).")";}function
unconvert_field(array$l,$H){if(preg_match("~binary~",$l["type"]))$H="UNHEX($H)";if($l["type"]=="bit")$H="CONVERT(b$H, UNSIGNED)";if(preg_match("~geometry|point|linestring|polygon~",$l["type"])){$jf=(min_version(8)?"ST_":"");$H=$jf."GeomFromText($H, $jf"."SRID($l[field]))";}return$H;}function
support($jc){return
preg_match('~^(comment|columns|copy|database|drop_col|dump|indexes|kill|privileges|move_col|procedure|processlist|routine|sql|status|table|trigger|variables|view'.(min_version(5.1)?'|event':'').(min_version(8)?'|descidx':'').(min_version('8.0.16','10.2.1')?'|check':'').')$~',$jc);}function
kill_process($s){return
queries("KILL ".number($s));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){return
get_val("SELECT @@max_connections");}function
types(){return
array();}function
type_values($s){return"";}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($Hf,$h=null){return
true;}}define('Adminer\JUSH',Driver::$jush);define('Adminer\SERVER',"".$_GET[DRIVER]);define('Adminer\DB',"$_GET[db]");define('Adminer\ME',preg_replace('~\?.*~','',relative_uri()).'?'.(sid()?SID.'&':'').(SERVER!==null?DRIVER."=".urlencode(SERVER).'&':'').($_GET["ext"]?"ext=".urlencode($_GET["ext"]).'&':'').(isset($_GET["username"])?"username=".urlencode($_GET["username"]).'&':'').(DB!=""?'db='.urlencode(DB).'&'.(isset($_GET["ns"])?"ns=".urlencode($_GET["ns"])."&":""):''));function
page_header($_g,$k="",$Ga=array(),$Ag=""){page_headers();if(is_ajax()&&$k){page_messages($k);exit;}if(!ob_get_level())ob_start('ob_gzhandler',4096);$Bg=$_g.($Ag!=""?": $Ag":"");$Cg=strip_tags($Bg.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".adminer()->name());echo'<!DOCTYPE html>
<html lang="',LANG,'" dir="',lang(58),'">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>',$Cg,'</title>
<link rel="stylesheet" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=5.4.1"),'">
';$pb=adminer()->css();if(is_int(key($pb)))$pb=array_fill_keys($pb,'light');$Oc=in_array('light',$pb)||in_array('',$pb);$Lc=in_array('dark',$pb)||in_array('',$pb);$rb=($Oc?($Lc?null:false):($Lc?:null));$de=" media='(prefers-color-scheme: dark)'";if($rb!==false)echo"<link rel='stylesheet'".($rb?"":$de)." href='".h(preg_replace("~\\?.*~","",ME)."?file=dark.css&version=5.4.1")."'>\n";echo"<meta name='color-scheme' content='".($rb===null?"light dark":($rb?"dark":"light"))."'>\n",script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=5.4.1");if(adminer()->head($rb))echo"<link rel='icon' href='data:image/gif;base64,R0lGODlhEAAQAJEAAAQCBPz+/PwCBAROZCH5BAEAAAAALAAAAAAQABAAAAI2hI+pGO1rmghihiUdvUBnZ3XBQA7f05mOak1RWXrNq5nQWHMKvuoJ37BhVEEfYxQzHjWQ5qIAADs='>\n","<link rel='apple-touch-icon' href='".h(preg_replace("~\\?.*~","",ME)."?file=logo.png&version=5.4.1")."'>\n";foreach($pb
as$dh=>$ie){$ra=($ie=='dark'&&!$rb?$de:($ie=='light'&&$Lc?" media='(prefers-color-scheme: light)'":""));echo"<link rel='stylesheet'$ra href='".h($dh)."'>\n";}echo"\n<body class='".lang(58)." nojs";adminer()->bodyClass();echo"'>\n";$n=get_temp_dir()."/adminer.version";if(!$_COOKIE["adminer_version"]&&function_exists('openssl_verify')&&file_exists($n)&&filemtime($n)+86400>time()){$lh=unserialize(file_get_contents($n));$pf="-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwqWOVuF5uw7/+Z70djoK
RlHIZFZPO0uYRezq90+7Amk+FDNd7KkL5eDve+vHRJBLAszF/7XKXe11xwliIsFs
DFWQlsABVZB3oisKCBEuI71J4kPH8dKGEWR9jDHFw3cWmoH3PmqImX6FISWbG3B8
h7FIx3jEaw5ckVPVTeo5JRm/1DZzJxjyDenXvBQ/6o9DgZKeNDgxwKzH+sw9/YCO
jHnq1cFpOIISzARlrHMa/43YfeNRAm/tsBXjSxembBPo7aQZLAWHmaj5+K19H10B
nCpz9Y++cipkVEiKRGih4ZEvjoFysEOdRLj6WiD/uUNky4xGeA6LaJqh5XpkFkcQ
fQIDAQAB
-----END PUBLIC KEY-----
";if(openssl_verify($lh["version"],base64_decode($lh["signature"]),$pf)==1)$_COOKIE["adminer_version"]=$lh["version"];}echo
script("mixin(document.body, {onkeydown: bodyKeydown, onclick: bodyClick".(isset($_COOKIE["adminer_version"])?"":", onload: partial(verifyVersion, '".VERSION."', '".js_escape(ME)."', '".get_token()."')")."});
document.body.classList.replace('nojs', 'js');
const offlineMessage = '".js_escape(lang(59))."';
const thousandsSeparator = '".js_escape(lang(4))."';"),"<div id='help' class='jush-".JUSH." jsonly hidden'></div>\n",script("mixin(qs('#help'), {onmouseover: () => { helpOpen = 1; }, onmouseout: helpMouseout});"),"<div id='content'>\n","<span id='menuopen' class='jsonly'>".icon("move","","menu","")."</span>".script("qs('#menuopen').onclick = event => { qs('#foot').classList.toggle('foot'); event.stopPropagation(); }");if($Ga!==null){$y=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($y?:".").'">'.get_driver(DRIVER).'</a> Â» ';$y=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$M=adminer()->serverName(SERVER);$M=($M!=""?$M:lang(60));if($Ga===false)echo"$M\n";else{echo"<a href='".h($y)."' accesskey='1' title='Alt+Shift+1'>$M</a> Â» ";if($_GET["ns"]!=""||(DB!=""&&is_array($Ga)))echo'<a href="'.h($y."&db=".urlencode(DB).(support("scheme")?"&ns=":"")).'">'.h(DB).'</a> Â» ';if(is_array($Ga)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> Â» ';foreach($Ga
as$w=>$X){$zb=(is_array($X)?$X[1]:h($X));if($zb!="")echo"<a href='".h(ME."$w=").urlencode(is_array($X)?$X[0]:$X)."'>$zb</a> Â» ";}}echo"$_g\n";}}echo"<h2>$Bg</h2>\n","<div id='ajaxstatus' class='jsonly hidden'></div>\n";restart_session();page_messages($k);$ub=&get_session("dbs");if(DB!=""&&$ub&&!in_array(DB,$ub,true))$ub=null;stop_session();define('Adminer\PAGE_HEADER',1);}function
page_headers(){header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach(adminer()->csp(csp())as$ob){$Qc=array();foreach($ob
as$w=>$X)$Qc[]="$w $X";header("Content-Security-Policy: ".implode("; ",$Qc));}adminer()->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self'","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
get_nonce(){static$se;if(!$se)$se=base64_encode(rand_string());return$se;}function
page_messages($k){$ch=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$fe=idx($_SESSION["messages"],$ch);if($fe){echo"<div class='message'>".implode("</div>\n<div class='message'>",$fe)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$ch]);}if($k)echo"<div class='error'>$k</div>\n";if(adminer()->error)echo"<div class='error'>".adminer()->error."</div>\n";}function
page_footer($he=""){echo"</div>\n\n<div id='foot' class='foot'>\n<div id='menu'>\n";adminer()->navigation($he);echo"</div>\n";if($he!="auth")echo'<form action="" method="post">
<p class="logout">
<span>',h($_GET["username"])."\n",'</span>
<input type="submit" name="logout" value="',lang(61),'" id="logout">
',input_token(),'</form>
';echo"</div>\n\n",script("setupSubmitHighlight(document);");}function
int32($ne){while($ne>=2147483648)$ne-=4294967296;while($ne<=-2147483649)$ne+=4294967296;return(int)$ne;}function
long2str(array$W,$ph){$Gf='';foreach($W
as$X)$Gf
.=pack('V',$X);if($ph)return
substr($Gf,0,end($W));return$Gf;}function
str2long($Gf,$ph){$W=array_values(unpack('V*',str_pad($Gf,4*ceil(strlen($Gf)/4),"\0")));if($ph)$W[]=strlen($Gf);return$W;}function
xxtea_mx($uh,$th,$lg,$_d){return
int32((($uh>>5&0x7FFFFFF)^$th<<2)+(($th>>3&0x1FFFFFFF)^$uh<<4))^int32(($lg^$th)+($_d^$uh));}function
encrypt_string($hg,$w){if($hg=="")return"";$w=array_values(unpack("V*",pack("H*",md5($w))));$W=str2long($hg,true);$ne=count($W)-1;$uh=$W[$ne];$th=$W[0];$qf=floor(6+52/($ne+1));$lg=0;while($qf-->0){$lg=int32($lg+0x9E3779B9);$Kb=$lg>>2&3;for($Qe=0;$Qe<$ne;$Qe++){$th=$W[$Qe+1];$me=xxtea_mx($uh,$th,$lg,$w[$Qe&3^$Kb]);$uh=int32($W[$Qe]+$me);$W[$Qe]=$uh;}$th=$W[0];$me=xxtea_mx($uh,$th,$lg,$w[$Qe&3^$Kb]);$uh=int32($W[$ne]+$me);$W[$ne]=$uh;}return
long2str($W,false);}function
decrypt_string($hg,$w){if($hg=="")return"";if(!$w)return
false;$w=array_values(unpack("V*",pack("H*",md5($w))));$W=str2long($hg,false);$ne=count($W)-1;$uh=$W[$ne];$th=$W[0];$qf=floor(6+52/($ne+1));$lg=int32($qf*0x9E3779B9);while($lg){$Kb=$lg>>2&3;for($Qe=$ne;$Qe>0;$Qe--){$uh=$W[$Qe-1];$me=xxtea_mx($uh,$th,$lg,$w[$Qe&3^$Kb]);$th=int32($W[$Qe]-$me);$W[$Qe]=$th;}$uh=$W[$ne];$me=xxtea_mx($uh,$th,$lg,$w[$Qe&3^$Kb]);$th=int32($W[0]-$me);$W[0]=$th;$lg=int32($lg-0x9E3779B9);}return
long2str($W,true);}$cf=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($w)=explode(":",$X);$cf[$w]=$X;}}function
add_invalid_login(){$_a=get_temp_dir()."/adminer.invalid";foreach(glob("$_a*")?:array($_a)as$n){$p=file_open_lock($n);if($p)break;}if(!$p)$p=file_open_lock("$_a-".rand_string());if(!$p)return;$td=unserialize(stream_get_contents($p));$xg=time();if($td){foreach($td
as$ud=>$X){if($X[0]<$xg)unset($td[$ud]);}}$sd=&$td[adminer()->bruteForceKey()];if(!$sd)$sd=array($xg+30*60,0);$sd[1]++;file_write_unlock($p,serialize($td));}function
check_invalid_login(array&$cf){$td=array();foreach(glob(get_temp_dir()."/adminer.invalid*")as$n){$p=file_open_lock($n);if($p){$td=unserialize(stream_get_contents($p));file_unlock($p);break;}}$sd=idx($td,adminer()->bruteForceKey(),array());$re=($sd[1]>29?$sd[0]-time():0);if($re>0)auth_error(lang(62,ceil($re/60)),$cf);}$sa=$_POST["auth"];if($sa){session_regenerate_id();$kh=$sa["driver"];$M=$sa["server"];$V=$sa["username"];$D=(string)$sa["password"];$i=$sa["db"];set_password($kh,$M,$V,$D);$_SESSION["db"][$kh][$M][$V][$i]=true;if($sa["permanent"]){$w=implode("-",array_map('base64_encode',array($kh,$M,$V,$i)));$nf=adminer()->permanentLogin(true);$cf[$w]="$w:".base64_encode($nf?encrypt_string($D,$nf):"");cookie("adminer_permanent",implode(" ",$cf));}if(count($_POST)==1||DRIVER!=$kh||SERVER!=$M||$_GET["username"]!==$V||DB!=$i)redirect(auth_url($kh,$M,$V,$i));}elseif($_POST["logout"]&&(!$_SESSION["token"]||verify_token())){foreach(array("pwds","db","dbs","queries")as$w)set_session($w,null);unset_permanent($cf);redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),lang(63).' '.lang(64));}elseif($cf&&!$_SESSION["pwds"]){session_regenerate_id();$nf=adminer()->permanentLogin();foreach($cf
as$w=>$X){list(,$Qa)=explode(":",$X);list($kh,$M,$V,$i)=array_map('base64_decode',explode("-",$w));set_password($kh,$M,$V,decrypt_string(base64_decode($Qa),$nf));$_SESSION["db"][$kh][$M][$V][$i]=true;}}function
unset_permanent(array&$cf){foreach($cf
as$w=>$X){list($kh,$M,$V,$i)=array_map('base64_decode',explode("-",$w));if($kh==DRIVER&&$M==SERVER&&$V==$_GET["username"]&&$i==DB)unset($cf[$w]);}cookie("adminer_permanent",implode(" ",$cf));}function
auth_error($k,array&$cf){$Uf=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$Uf]||$_GET[$Uf])&&!$_SESSION["token"])$k=lang(65);else{restart_session();add_invalid_login();$D=get_password();if($D!==null){if($D===false)$k
.=($k?'<br>':'').lang(66,target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);}unset_permanent($cf);}}if(!$_COOKIE[$Uf]&&$_GET[$Uf]&&ini_bool("session.use_only_cookies"))$k=lang(67);$Te=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?:rand_string()),$Te["lifetime"]);if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);page_header(lang(36),$k,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".lang(68)."\n";echo"</div>\n";adminer()->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists('Adminer\Db')){unset($_SESSION["pwds"][DRIVER]);unset_permanent($cf);page_header(lang(69),lang(70,implode(", ",Driver::$extensions)),false);page_footer("auth");exit;}$g='';if(isset($_GET["username"])&&is_string(get_password())){list(,$ff)=host_port(SERVER);if(preg_match('~^\s*([-+]?\d+)~',$ff,$z)&&($z[1]<1024||$z[1]>65535))auth_error(lang(71),$cf);check_invalid_login($cf);$nb=adminer()->credentials();$g=Driver::connect($nb[0],$nb[1],$nb[2]);if(is_object($g)){Db::$instance=$g;Driver::$instance=new
Driver($g);if($g->flavor)save_settings(array("vendor-".DRIVER."-".SERVER=>get_driver(DRIVER)));}}$Pd=null;if(!is_object($g)||($Pd=adminer()->login($_GET["username"],get_password()))!==true){$k=(is_string($g)?nl_br(h($g)):(is_string($Pd)?$Pd:lang(72))).(preg_match('~^ | $~',get_password())?'<br>'.lang(73):'');auth_error($k,$cf);}if($_POST["logout"]&&$_SESSION["token"]&&!verify_token()){page_header(lang(61),lang(74));page_footer("db");exit;}if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);stop_session(true);if($sa&&$_POST["token"])$_POST["token"]=get_token();$k='';if($_POST){if(!verify_token()){$md="max_input_vars";$be=ini_get($md);if(extension_loaded("suhosin")){foreach(array("suhosin.request.max_vars","suhosin.post.max_vars")as$w){$X=ini_get($w);if($X&&(!$be||$X<$be)){$md=$w;$be=$X;}}}$k=(!$_POST["token"]&&$be?lang(75,"'$md'"):lang(74).' '.lang(76));}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){$k=lang(77,"'post_max_size'");if(isset($_GET["sql"]))$k
.=' '.lang(78);}function
doc_link(array$Ze,$tg=""){return"";}function
email_header($Qc){return"=?UTF-8?B?".base64_encode($Qc)."?=";}function
send_mail($Ob,$jg,$ee,$Bc="",array$nc=array()){$Wb=PHP_EOL;$ee=str_replace("\n",$Wb,wordwrap(str_replace("\r","","$ee\n")));$Fa=uniqid("boundary");$qa="";foreach((array)$nc["error"]as$w=>$X){if(!$X)$qa
.="--$Fa$Wb"."Content-Type: ".str_replace("\n","",$nc["type"][$w]).$Wb."Content-Disposition: attachment; filename=\"".preg_replace('~["\n]~','',$nc["name"][$w])."\"$Wb"."Content-Transfer-Encoding: base64$Wb$Wb".chunk_split(base64_encode(file_get_contents($nc["tmp_name"][$w])),76,$Wb).$Wb;}$Ba="";$Rc="Content-Type: text/plain; charset=utf-8$Wb"."Content-Transfer-Encoding: 8bit";if($qa){$qa
.="--$Fa--$Wb";$Ba="--$Fa$Wb$Rc$Wb$Wb";$Rc="Content-Type: multipart/mixed; boundary=\"$Fa\"";}$Rc
.=$Wb."MIME-Version: 1.0$Wb"."X-Mailer: Adminer Editor".($Bc?$Wb."From: ".str_replace("\n","",$Bc):"");return
mail($Ob,email_header($jg),$Ba.$ee.$qa,$Rc);}function
like_bool(array$l){return
preg_match("~bool|(tinyint|bit)\\(1\\)~",$l["full_type"]);}connection()->select_db(adminer()->database());adminer()->afterConnect();add_driver(DRIVER,lang(36));if(isset($_GET["select"])&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["download"])){$a=$_GET["download"];$m=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$K=array(idf_escape($_GET["field"]));$G=driver()->select($a,$K,array(where($_GET,$m)),$K);$I=($G?$G->fetch_row():array());echo
driver()->value($I[0],$m[$_GET["field"]]);exit;}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$m=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$m):""):where($_GET,$m));$Zg=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($m
as$_=>$l){if(!isset($l["privileges"][$Zg?"update":"insert"])||adminer()->fieldName($l)==""||$l["generated"])unset($m[$_]);}if($_POST&&!$k&&!isset($_GET["select"])){$Od=$_POST["referer"];if($_POST["insert"])$Od=($Zg?null:$_SERVER["REQUEST_URI"]);elseif(!preg_match('~^.+&select=.+$~',$Od))$Od=ME."select=".urlencode($a);$v=indexes($a);$Ug=unique_array($_GET["where"],$v);$tf="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($Od,lang(79),driver()->delete($a,$tf,$Ug?0:1));else{$N=array();foreach($m
as$_=>$l){$X=process_input($l);if($X!==false&&$X!==null)$N[idf_escape($_)]=$X;}if($Zg){if(!$N)redirect($Od);queries_redirect($Od,lang(80),driver()->update($a,$N,$tf,$Ug?0:1));if(is_ajax()){page_headers();page_messages($k);exit;}}else{$G=driver()->insert($a,$N);$Id=($G?last_id($G):0);queries_redirect($Od,lang(81,($Id?" $Id":"")),$G);}}}$I=null;if($_POST["save"])$I=(array)$_POST["fields"];elseif($Z){$K=array();foreach($m
as$_=>$l){if(isset($l["privileges"]["select"])){$oa=($_POST["clone"]&&$l["auto_increment"]?"''":convert_field($l));$K[]=($oa?"$oa AS ":"").idf_escape($_);}}$I=array();if(!support("table"))$K=array("*");if($K){$G=driver()->select($a,$K,array($Z),$K,array(),(isset($_GET["select"])?2:1));if(!$G)$k=error();else{$I=$G->fetch_assoc();if(!$I)$I=false;}if(isset($_GET["select"])&&(!$I||$G->fetch_assoc()))$I=null;}}if(!support("table")&&!$m){if(!$Z){$G=driver()->select($a,array("*"),array(),array("*"));$I=($G?$G->fetch_assoc():false);if(!$I)$I=array(driver()->primary=>"");}if($I){foreach($I
as$w=>$X){if(!$Z)$I[$w]=null;$m[$w]=array("field"=>$w,"null"=>($w!=driver()->primary),"auto_increment"=>($w==driver()->primary));}}}edit_form($a,$m,$I,$Zg,$k);}elseif(isset($_GET["select"])){$a=$_GET["select"];$S=table_status1($a);$v=indexes($a);$m=fields($a);$zc=column_foreign_keys($a);$xe=$S["Oid"];$ha=get_settings("adminer_import");$Ef=array();$e=array();$Kf=array();$Ie=array();$vg="";foreach($m
as$w=>$l){$_=adminer()->fieldName($l);$oe=html_entity_decode(strip_tags($_),ENT_QUOTES);if(isset($l["privileges"]["select"])&&$_!=""){$e[$w]=$oe;if(is_shortable($l))$vg=adminer()->selectLengthProcess();}if(isset($l["privileges"]["where"])&&$_!="")$Kf[$w]=$oe;if(isset($l["privileges"]["order"])&&$_!="")$Ie[$w]=$oe;$Ef+=$l["privileges"];}list($K,$Gc)=adminer()->selectColumnsProcess($e,$v);$K=array_unique($K);$Gc=array_unique($Gc);$vd=count($Gc)<count($K);$Z=adminer()->selectSearchProcess($m,$v);$He=adminer()->selectOrderProcess($m,$v);$x=adminer()->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Vg=>$I){$oa=convert_field($m[key($I)]);$K=array($oa?:idf_escape(key($I)));$Z[]=where_check($Vg,$m);$H=driver()->select($a,$K,$Z,$K);if($H)echo
first($H->fetch_row());}exit;}$E=$Xg=array();foreach($v
as$u){if($u["type"]=="PRIMARY"){$E=array_flip($u["columns"]);$Xg=($K?$E:array());foreach($Xg
as$w=>$X){if(in_array(idf_escape($w),$K))unset($Xg[$w]);}break;}}if($xe&&!$E){$E=$Xg=array($xe=>0);$v[]=array("type"=>"PRIMARY","columns"=>array($xe));}if($_POST&&!$k){$rh=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$Pa=array();foreach($_POST["check"]as$Ma)$Pa[]=where_check($Ma,$m);$rh[]="((".implode(") OR (",$Pa)."))";}$rh=($rh?"\nWHERE ".implode(" AND ",$rh):"");if($_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers($a);adminer()->dumpTable($a,"");$Bc=($K?implode(", ",$K):"*").convert_fields($e,$m,$K)."\nFROM ".table($a);$Ic=($Gc&&$vd?"\nGROUP BY ".implode(", ",$Gc):"").($He?"\nORDER BY ".implode(", ",$He):"");$F="SELECT $Bc$rh$Ic";if(is_array($_POST["check"])&&!$E){$Tg=array();foreach($_POST["check"]as$X)$Tg[]="(SELECT".limit($Bc,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$m).$Ic,1).")";$F=implode(" UNION ALL ",$Tg);}adminer()->dumpData($a,"table",$F);adminer()->dumpFooter();exit;}if(!adminer()->selectEmailProcess($Z,$zc)){if($_POST["save"]||$_POST["delete"]){$G=true;$ia=0;$N=array();if(!$_POST["delete"]){foreach($_POST["fields"]as$_=>$X){$X=process_input($m[$_]);if($X!==null&&($_POST["clone"]||$X!==false))$N[idf_escape($_)]=($X!==false?$X:idf_escape($_));}}if($_POST["delete"]||$N){$F=($_POST["clone"]?"INTO ".table($a)." (".implode(", ",array_keys($N)).")\nSELECT ".implode(", ",$N)."\nFROM ".table($a):"");if($_POST["all"]||($E&&is_array($_POST["check"]))||$vd){$G=($_POST["delete"]?driver()->delete($a,$rh):($_POST["clone"]?queries("INSERT $F$rh".driver()->insertReturning($a)):driver()->update($a,$N,$rh)));$ia=connection()->affected_rows;if(is_object($G))$ia+=$G->num_rows;}else{foreach((array)$_POST["check"]as$X){$qh="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$m);$G=($_POST["delete"]?driver()->delete($a,$qh,1):($_POST["clone"]?queries("INSERT".limit1($a,$F,$qh)):driver()->update($a,$N,$qh,1)));if(!$G)break;$ia+=connection()->affected_rows;}}}$ee=lang(82,$ia);if($_POST["clone"]&&$G&&$ia==1){$Id=last_id($G);if($Id)$ee=lang(81," $Id");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page":""),$ee,$G);if(!$_POST["delete"]){$hf=(array)$_POST["fields"];edit_form($a,array_intersect_key($m,$hf),$hf,!$_POST["clone"],$k);page_footer();exit;}}elseif(!$_POST["import"]){if(!$_POST["val"])$k=lang(83);else{$G=true;$ia=0;foreach($_POST["val"]as$Vg=>$I){$N=array();foreach($I
as$w=>$X){$w=bracket_escape($w,true);$N[idf_escape($w)]=(preg_match('~char|text~',$m[$w]["type"])||$X!=""?adminer()->processInput($m[$w],$X):"NULL");}$G=driver()->update($a,$N," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($Vg,$m),($vd||$E?0:1)," ");if(!$G)break;$ia+=connection()->affected_rows;}queries_redirect(remove_from_uri(),lang(82,$ia),$G);}}elseif(!is_string($mc=get_file("csv_file",true)))$k=upload_error($mc);elseif(!preg_match('~~u',$mc))$k=lang(84);else{save_settings(array("output"=>$ha["output"],"format"=>$_POST["separator"]),"adminer_import");$G=true;$Xa=array_keys($m);preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$mc,$Vd);$ia=count($Vd[0]);driver()->begin();$L=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$J=array();foreach($Vd[0]as$w=>$X){preg_match_all("~((?>\"[^\"]*\")+|[^$L]*)$L~",$X.$L,$Wd);if(!$w&&!array_diff($Wd[1],$Xa)){$Xa=$Wd[1];$ia--;}else{$N=array();foreach($Wd[1]as$r=>$Va)$N[idf_escape($Xa[$r])]=($Va==""&&$m[$Xa[$r]]["null"]?"NULL":q(preg_match('~^".*"$~s',$Va)?str_replace('""','"',substr($Va,1,-1)):$Va));$J[]=$N;}}$G=(!$J||driver()->insertUpdate($a,$J,$E));if($G)driver()->commit();queries_redirect(remove_from_uri("page"),lang(85,$ia),$G);driver()->rollback();}}}$pg=adminer()->tableName($S);if(is_ajax()){page_headers();ob_start();}else
page_header(lang(49).": $pg",$k);$N=null;if(isset($Ef["insert"])||!support("table")){$Te=array();foreach((array)$_GET["where"]as$X){if(isset($zc[$X["col"]])&&count($zc[$X["col"]])==1&&($X["op"]=="="||(!$X["op"]&&(is_array($X["val"])||!preg_match('~[_%]~',$X["val"])))))$Te["set"."[".bracket_escape($X["col"])."]"]=$X["val"];}$N=$Te?"&".http_build_query($Te):"";}adminer()->selectLinks($S,$N);if(!$e&&support("table"))echo"<p class='error'>".lang(86).($m?".":": ".error())."\n";else{echo"<form action='' id='form'>\n","<div style='display: none;'>";hidden_fields_get();echo(DB!=""?input_hidden("db",DB).(isset($_GET["ns"])?input_hidden("ns",$_GET["ns"]):""):""),input_hidden("select",$a),"</div>\n";adminer()->selectColumnsPrint($K,$e);adminer()->selectSearchPrint($Z,$Kf,$v);adminer()->selectOrderPrint($He,$Ie,$v);adminer()->selectLimitPrint($x);adminer()->selectLengthPrint($vg);adminer()->selectActionPrint($v);echo"</form>\n";$C=$_GET["page"];$Ac=null;if($C=="last"){$Ac=get_val(count_rows($a,$Z,$vd,$Gc));$C=floor(max(0,intval($Ac)-1)/$x);}$Lf=$K;$Hc=$Gc;if(!$Lf){$Lf[]="*";$jb=convert_fields($e,$m,$K);if($jb)$Lf[]=substr($jb,2);}foreach($K
as$w=>$X){$l=$m[idf_unescape($X)];if($l&&($oa=convert_field($l)))$Lf[$w]="$oa AS $X";}if(!$vd&&$Xg){foreach($Xg
as$w=>$X){$Lf[]=idf_escape($w);if($Hc)$Hc[]=idf_escape($w);}}$G=driver()->select($a,$Lf,$Z,$Hc,$He,$x,$C,true);if(!$G)echo"<p class='error'>".error()."\n";else{if(JUSH=="mssql"&&$C)$G->seek($x*$C);$Qb=array();echo"<form action='' method='post' enctype='multipart/form-data'>\n";$J=array();while($I=$G->fetch_assoc()){if($C&&JUSH=="oracle")unset($I["RNUM"]);$J[]=$I;}if($_GET["page"]!="last"&&$x&&$Gc&&$vd&&JUSH=="sql")$Ac=get_val(" SELECT FOUND_ROWS()");if(!$J)echo"<p class='message'>".lang(14)."\n";else{$za=adminer()->backwardKeys($a,$pg);echo"<div class='scrollable'>","<table id='table' class='nowrap checkable odds'>",script("mixin(qs('#table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true), onkeydown: editingKeydown});"),"<thead><tr>".(!$Gc&&$K?"":"<td><input type='checkbox' id='all-page' class='jsonly'>".script("qs('#all-page').onclick = partial(formCheck, /check/);","")." <a href='".h($_GET["modify"]?remove_from_uri("modify"):$_SERVER["REQUEST_URI"]."&modify=1")."'>".lang(87)."</a>");$pe=array();$Dc=array();reset($K);$vf=1;foreach($J[0]as$w=>$X){if(!isset($Xg[$w])){$X=idx($_GET["columns"],key($K))?:array();$l=$m[$K?($X?$X["col"]:current($K)):$w];$_=($l?adminer()->fieldName($l,$vf):($X["fun"]?"*":h($w)));if($_!=""){$vf++;$pe[$w]=$_;$d=idf_escape($w);$Xc=remove_from_uri('(order|desc)[^=]*|page').'&order%5B0%5D='.urlencode($w);$zb="&desc%5B0%5D=1";echo"<th id='th[".h(bracket_escape($w))."]'>".script("mixin(qsl('th'), {onmouseover: partial(columnMouse), onmouseout: partial(columnMouse, ' hidden')});","");$Cc=apply_sql_function($X["fun"],$_);$Zf=isset($l["privileges"]["order"])||$Cc;echo($Zf?"<a href='".h($Xc.($He[0]==$d||$He[0]==$w?$zb:''))."'>$Cc</a>":$Cc),"<span class='column hidden'>";if($Zf)echo"<a href='".h($Xc.$zb)."' title='".lang(88)."' class='text'> â†“</a>";if(!$X["fun"]&&isset($l["privileges"]["where"]))echo'<a href="#fieldset-search" title="'.lang(43).'" class="text jsonly"> =</a>',script("qsl('a').onclick = partial(selectSearch, '".js_escape($w)."');");echo"</span>";}$Dc[$w]=$X["fun"];next($K);}}$Ld=array();if($_GET["modify"]){foreach($J
as$I){foreach($I
as$w=>$X)$Ld[$w]=max($Ld[$w],min(40,strlen(utf8_decode($X))));}}echo($za?"<th>".lang(89):"")."</thead>\n";if(is_ajax())ob_end_clean();foreach(adminer()->rowDescriptions($J,$zc)as$ne=>$I){$Ug=unique_array($J[$ne],$v);if(!$Ug){$Ug=array();reset($K);foreach($J[$ne]as$w=>$X){if(!preg_match('~^(COUNT|AVG|GROUP_CONCAT|MAX|MIN|SUM)\(~',current($K)))$Ug[$w]=$X;next($K);}}$Vg="";foreach($Ug
as$w=>$X){$l=(array)$m[$w];if((JUSH=="sql"||JUSH=="pgsql")&&preg_match('~char|text|enum|set~',$l["type"])&&strlen($X)>64){$w=(strpos($w,'(')?$w:idf_escape($w));$w="MD5(".(JUSH!='sql'||preg_match("~^utf8~",$l["collation"])?$w:"CONVERT($w USING ".charset(connection()).")").")";$X=md5($X);}$Vg
.="&".($X!==null?urlencode("where[".bracket_escape($w)."]")."=".urlencode($X===false?"f":$X):"null%5B%5D=".urlencode($w));}echo"<tr>".(!$Gc&&$K?"":"<td>".checkbox("check[]",substr($Vg,1),in_array(substr($Vg,1),(array)$_POST["check"])).($vd||information_schema(DB)?"":" <a href='".h(ME."edit=".urlencode($a).$Vg)."' class='edit'>".lang(90)."</a>"));reset($K);foreach($I
as$w=>$X){if(isset($pe[$w])){$d=current($K);$l=(array)$m[$w];$X=driver()->value($X,$l);if($X!=""&&(!isset($Qb[$w])||$Qb[$w]!=""))$Qb[$w]=(is_mail($X)?$pe[$w]:"");$y="";if(is_blob($l)&&$X!="")$y=ME.'download='.urlencode($a).'&field='.urlencode($w).$Vg;if(!$y&&$X!==null){foreach((array)$zc[$w]as$o){if(count($zc[$w])==1||end($o["source"])==$w){$y="";foreach($o["source"]as$r=>$ag)$y
.=where_link($r,$o["target"][$r],$J[$ne][$ag]);$y=($o["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.urlencode($o["db"]),ME):ME).'select='.urlencode($o["table"]).$y;if($o["ns"])$y=preg_replace('~([?&]ns=)[^&]+~','\1'.urlencode($o["ns"]),$y);if(count($o["source"])==1)break;}}}if($d=="COUNT(*)"){$y=ME."select=".urlencode($a);$r=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Ug))$y
.=where_link($r++,$W["col"],$W["val"],$W["op"]);}foreach($Ug
as$_d=>$W)$y
.=where_link($r++,$_d,$W);}$Yc=select_value($X,$y,$l,$vg);$s=h("val[$Vg][".bracket_escape($w)."]");$if=idx(idx($_POST["val"],$Vg),bracket_escape($w));$Mb=!is_array($I[$w])&&is_utf8($Yc)&&$J[$ne][$w]==$I[$w]&&!$Dc[$w]&&!$l["generated"];$U=(preg_match('~^(AVG|MIN|MAX)\((.+)\)~',$d,$z)?$m[idf_unescape($z[2])]["type"]:$l["type"]);$tg=preg_match('~text|json|lob~',$U);$wd=preg_match(number_type(),$U)||preg_match('~^(CHAR_LENGTH|ROUND|FLOOR|CEIL|TIME_TO_SEC|COUNT|SUM)\(~',$d);echo"<td id='$s'".($wd&&($X===null||is_numeric(strip_tags($Yc))||$U=="money")?" class='number'":"");if(($_GET["modify"]&&$Mb&&$X!==null)||$if!==null){$Kc=h($if!==null?$if:$I[$w]);echo">".($tg?"<textarea name='$s' cols='30' rows='".(substr_count($I[$w],"\n")+1)."'>$Kc</textarea>":"<input name='$s' value='$Kc' size='$Ld[$w]'>");}else{$Qd=strpos($Yc,"<i>â€¦</i>");echo" data-text='".($Qd?2:($tg?1:0))."'".($Mb?"":" data-warning='".h(lang(91))."'").">$Yc";}}next($K);}if($za)echo"<td>";adminer()->backwardKeysPrint($za,$J[$ne]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($J||$C){$bc=true;if($_GET["page"]!="last"){if(!$x||(count($J)<$x&&($J||!$C)))$Ac=($C?$C*$x:0)+count($J);elseif(JUSH!="sql"||!$vd){$Ac=($vd?false:found_rows($S,$Z));if(intval($Ac)<max(1e4,2*($C+1)*$x))$Ac=first(slow_query(count_rows($a,$Z,$vd,$Gc)));else$bc=false;}}$Re=($x&&($Ac===false||$Ac>$x||$C));if($Re)echo(($Ac===false?count($J)+1:$Ac-$C*$x)>$x?'<p><a href="'.h(remove_from_uri("page")."&page=".($C+1)).'" class="loadmore">'.lang(92).'</a>'.script("qsl('a').onclick = partial(selectLoadMore, $x, '".lang(93)."â€¦');",""):''),"\n";echo"<div class='footer'><div>\n";if($Re){$Zd=($Ac===false?$C+(count($J)>=$x?2:1):floor(($Ac-1)/$x));echo"<fieldset>";if(JUSH!="simpledb"){echo"<legend><a href='".h(remove_from_uri("page"))."'>".lang(94)."</a></legend>",script("qsl('a').onclick = function () { pageClick(this.href, +prompt('".lang(94)."', '".($C+1)."')); return false; };"),pagination(0,$C).($C>5?" â€¦":"");for($r=max(1,$C-4);$r<min($Zd,$C+5);$r++)echo
pagination($r,$C);if($Zd>0)echo($C+5<$Zd?" â€¦":""),($bc&&$Ac!==false?pagination($Zd,$C):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$Zd'>".lang(95)."</a>");}else
echo"<legend>".lang(94)."</legend>",pagination(0,$C).($C>1?" â€¦":""),($C?pagination($C,$C):""),($Zd>$C?pagination($C+1,$C).($Zd>$C+1?" â€¦":""):"");echo"</fieldset>\n";}echo"<fieldset>","<legend>".lang(96)."</legend>";$Db=($bc?"":"~ ").$Ac;$Be="const checked = formChecked(this, /check/); selectCount('selected', this.checked ? '$Db' : checked); selectCount('selected2', this.checked || !checked ? '$Db' : checked);";echo
checkbox("all",1,0,($Ac!==false?($bc?"":"~ ").lang(97,$Ac):""),$Be)."\n","</fieldset>\n";if(adminer()->selectCommandPrint())echo'<fieldset',($_GET["modify"]?'':' class="jsonly"'),'><legend>',lang(87),'</legend><div>
<input type="submit" value="',lang(16),'"',($_GET["modify"]?'':' title="'.lang(83).'"'),'>
</div></fieldset>
<fieldset><legend>',lang(98),' <span id="selected"></span></legend><div>
<input type="submit" name="edit" value="',lang(12),'">
<input type="submit" name="clone" value="',lang(99),'">
<input type="submit" name="delete" value="',lang(20),'">',confirm(),'</div></fieldset>
';$_c=adminer()->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]){unset($_c['sql']);break;}}if($_c){print_fieldset("export",lang(100)." <span id='selected2'></span>");$Oe=adminer()->dumpOutput();echo($Oe?html_select("output",$Oe,$ha["output"])." ":""),html_select("format",$_c,$ha["format"])," <input type='submit' name='export' value='".lang(100)."'>\n","</div></fieldset>\n";}adminer()->selectEmailPrint(array_filter($Qb,'strlen'),$e);echo"</div></div>\n";}if(adminer()->selectImportPrint())echo"<p>","<a href='#import'>".lang(101)."</a>",script("qsl('a').onclick = partial(toggle, 'import');",""),"<span id='import'".($_POST["import"]?"":" class='hidden'").">: ",file_input("<input type='file' name='csv_file'> ".html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ha["format"])." <input type='submit' name='import' value='".lang(101)."'>"),"</span>";echo
input_token(),"</form>\n",(!$Gc&&$K?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["script"])){if($_GET["script"]=="kill")connection()->query("KILL ".number($_POST["kill"]));elseif(list($R,$s,$_)=adminer()->_foreignColumn(column_foreign_keys($_GET["source"]),$_GET["field"])){$x=11;$G=connection()->query("SELECT $s, $_ FROM ".table($R)." WHERE ".(preg_match('~^[0-9]+$~',$_GET["value"])?"$s = $_GET[value] OR ":"")."$_ LIKE ".q("$_GET[value]%")." ORDER BY 2 LIMIT $x");for($r=1;($I=$G->fetch_row())&&$r<$x;$r++)echo"<a href='".h(ME."edit=".urlencode($R)."&where".urlencode("[".bracket_escape(idf_unescape($s))."]")."=".urlencode($I[0]))."'>".h($I[1])."</a><br>\n";if($I)echo"...\n";}exit;}else{page_header(lang(60),"",false);if(adminer()->homepage()){echo"<form action='' method='post'>\n","<p>".lang(102).": <input type='search' name='query' value='".h($_POST["query"])."'> <input type='submit' value='".lang(43)."'>\n";if($_POST["query"]!="")search_tables();echo"<div class='scrollable'>\n","<table class='nowrap checkable odds'>\n",script("mixin(qsl('table'), {onclick: tableClick, ondblclick: partialArg(tableClick, true)});"),'<thead><tr class="wrap">','<td><input id="check-all" type="checkbox" class="jsonly">'.script("qs('#check-all').onclick = partial(formCheck, /^tables\[/);",""),'<th>'.lang(103),'<td>'.lang(104),"</thead>\n";foreach(table_status()as$R=>$I){$_=adminer()->tableName($I);if($_!=""){echo'<tr><td>'.checkbox("tables[]",$R,in_array($R,(array)$_POST["tables"],true)),"<th><a href='".h(ME).'select='.urlencode($R)."'>$_</a>";$X=format_number($I["Rows"]);echo"<td align='right'><a href='".h(ME."edit=").urlencode($R)."'>".($I["Engine"]=="InnoDB"&&$X?"~ $X":$X)."</a>";}}echo"</table>\n","</div>\n","</form>\n",script("tableCheck();");adminer()->pluginsLinks();}}page_footer();