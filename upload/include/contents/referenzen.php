<?php 
#   Copyright by: Manuel Staechele
#   Support: www.ilch.de


defined ('main') or die ( 'no direct access' );



$title = $allgAr['title'].' :: Referenzen';
$hmenu = 'Referenzen';
$design = new design($title , $hmenu);

function referenzen_find_kat($kat){   
	$katpfad = 'include/images/referenzen/';
	$katjpg = $katpfad.$kat.'.jpg';
	$katgif = $katpfad.$kat.'.gif';
	$katpng = $katpfad.$kat.'.png';
	
	if(file_exists($katjpg)){
		$pfadzumBild = $katjpg;
	}elseif(file_exists($katgif)){
		$pfadzumBild = $katgif;
	}elseif(file_exists($katpng)){
		$pfadzumBild = $katpng;
	}
	
	if(!empty($pfadzumBild)){
		$kategorie = '<img style="" src="'.$pfadzumBild.'" alt="'.$kat.'">';
	}else{
		$kategorie = '<b>'.$kat.'</b><br /><br />';
	}
	
	return($kategorie);
}

if(!is_numeric($menu->get(1))) {
    $design->header();
	$limit = $allgAr['Nlimit'] + 7;
	//$limit = 20;
    $page = ($menu->getA(1) == 'p' ? $menu->getE(1) : 1 );
    $MPL = db_make_sites($page, "WHERE referenzen_recht >= ".$_SESSION['authright'], $limit, '?referenzen', 'referenzen');
    $anfang = ($page - 1) * $limit;
    
    $tpl = new tpl('referenzen.htm');
    $abf = "SELECT
				a.referenzen_title as title,
				a.referenzen_id as id,
				DATE_FORMAT(a.referenzen_time,'%d. %m. %Y') as datum,
				DATE_FORMAT(a.referenzen_time,'%W') as dayofweek,
				a.referenzen_kat as kate,
				a.referenzen_text as text,
				a.referenzen_designer as designer,
				a.referenzen_slicer as slicer,
				a.referenzen_anpasser as anpasser,
				a.referenzen_code as code,
				a.referenzen_preis as preis,
				a.referenzen_anmerkung as anmerkung,
				a.referenzen_vprogramme as vprogramme,
				a.referenzen_zeit as zeit,
				a.referenzen_release as rel,
				a.referenzen_name as na,
				a.referenzen_nname as nna,
				a.referenzen_email as email,
				a.referenzen_icq as icq,
				a.referenzen_hp as hp,
				a.referenzen_screen as screen,
				a.referenzen_forum as forumlink,
				b.name as username
			FROM prefix_referenzen as a
			LEFT JOIN prefix_user as b ON a.user_id = b.id
			WHERE ".$_SESSION['authright']." <= a.referenzen_recht
			OR a.referenzen_recht = 0
			ORDER BY referenzen_time DESC
			LIMIT ".$anfang.",".$limit;
    
    $erg = db_query($abf);
	$nlimit = db_num_rows($erg);
    echo mysql_error();
	$i = 0;

	
    while($row = db_fetch_assoc($erg)){
		$i++;
		if($i == 1){
			$row['anfang'] = "<table width=\"100%\"><tr><td>";
		}else{
			$row['anfang'] = "";
		}
		if($i % 2 == 1){
			$row['bruch'] = "<td>";
		}else{
			$row['bruch'] = "</tr><tr><td>";
		}
	  
		if($row['forumlink'] == ""){
			$row['forumlink'] = "<td>kein Forumbeitrag vorhanden.</td>";
		}else{
			$row['forumlink'] = "<td><a href=\"".$row['forumlink']."\" target=\"_self\"><b>Forumbeitrag</b></a></td>";
		}
	  
		$k0m  = db_query("SELECT COUNT(ID) FROM `prefix_koms` WHERE uid = ".$row['id']." AND cat = 'referenzen'");
		$row['kom']  = db_result($k0m,0);
      
		$row['kate'] = referenzen_find_kat($row['kate']);
		$row['datum'] = $lang[$row['dayofweek']].' '.$row['datum'];
		if(strpos($row['text'], '[PREVIEWENDE]') !== FALSE){
			$a = explode('[PREVIEWENDE]' , $row['text']);
			$row['text'] = $a[0];
		}
		$row['text'] = bbcode($row['text']);
		if($i == $nlimit){
			if($i % 2 == 1){
				$row['bruch'] = "</td></tr></table>";
			}else{
				$row['bruch'] = "</td><td></td></tr></table>";
			}
		}
	  
		$tpl->set_ar_out($row,0);
	}
    $tpl->set_out('SITELINK', $MPL,1);
    unset($tpl);   
}else{
	
	$design->header();
	$nid = escape($menu->get(1), 'integer');
	$row = db_fetch_object(db_query("SELECT * FROM `prefix_referenzen` WHERE referenzen_id = '".$nid."'"));

	if(has_right(array($row->referenzen_recht))){
		$komsOK = true;
		if($allgAr['Ngkoms'] == 0){
			if(loggedin()){
				$komsOK = true;
			}else{
				$komsOK = false;
			}
		}
		if($allgAr['Nukoms'] == 0){
			$komsOK = false;
		}

    # kommentar add
		if((loggedin() OR chk_antispam('referenzenkom')) AND $komsOK AND !empty($_POST['name']) AND !empty($_POST['txt'])){
			$_POST['txt'] = escape($_POST['txt'], 'string');
			$_POST['name'] = escape($_POST['name'], 'string');
			db_query("INSERT INTO `prefix_koms` VALUES ('',".$nid.",'referenzen','".$_POST['name']."','".$_POST['txt']."')");
		} 
	# kommentar add
		
    # kommentar loeschen
		if($menu->getA(2) == 'd' AND is_numeric($menu->getE(2)) AND has_right(-7, 'referenzen')){
			$kommentar_id = escape($menu->getE(2),'integer');
			db_query("DELETE FROM prefix_koms WHERE uid = ".$nid." AND cat = 'referenzen' AND id = ".$kommentar_id);
		}
    # kommentar loeschen
	
		$kategorie = referenzen_find_kat($row->referenzen_kat);
		$textToShow = bbcode($row->referenzen_text);
		$textToShow = str_replace('[PREVIEWENDE]','',$textToShow);
		if(!empty($such)){
			$textToShow = markword($textToShow,$such);
		}
		
		$tpl = new tpl('referenzen.htm');
		$ar = array(
				'TEXT'  => $textToShow,
				'KATE'  => $kategorie,
				'NID' => $nid,
				'uname' => $_SESSION['authname'],
				'ANTISPAM' => (loggedin()?'':get_antispam ('referenzenkom', 0)),
				'NAME'  => $row->referenzen_title,
				'DESIGNER'  => $row-> referenzen_designer,
				'SILCER'  => $row-> referenzen_slicer,
				'ANPASSER'  => $row-> referenzen_anpasser,
				'CODE'  => $row-> referenzen_code,
				'PREIS'  => $row-> referenzen_preis,
				'ANMERKUNG'  => $row-> referenzen_anmerkung,
				'VPROGRAMME'  => $row-> referenzen_vprogramme,
				'ZEIT'  => $row-> referenzen_zeit,
				'RELEASE'  => $row-> referenzen_release,
				'NAME'  => $row-> referenzen_name,
				'NNAME'  => $row-> referenzen_nname,
				'EMAIL'  => $row-> referenzen_email,
				'ICQ'  => $row-> referenzen_icq,
				'HP'  => $row-> referenzen_hp,
				'SCREEN'  => $row-> referenzen_screen,
		);
		$tpl->set_ar_out($ar, 2);
		
		if($komsOK){
			$tpl->set_ar_out(array('NAME' => $row->referenzen_title, 'NID' => $nid), 3);
		}
		$erg1 = db_query("SELECT text, name, id FROM `prefix_koms` WHERE uid = ".$nid." AND cat = 'referenzen' ORDER BY id DESC");
		$ergAnz1 = db_num_rows($erg1);
		if($ergAnz1 == 0){
			echo '<b>'.$lang['nocomments'].'</b>';
		}else{
			$zahl = $ergAnz1;
			while($row1 = db_fetch_assoc($erg1)){
				$row1['text'] = bbcode(trim($row1['text']));
				if(has_right(-7, 'referenzen')){
					$row1['text'] .= '<a href="?referenzen-'.$nid.'-d'.$row1['id'].'"><img src="include/images/icons/del.gif" alt="l&ouml;schen" border="0" title="l&ouml;schen" /></a>';
				}
				$tpl->set_ar_out(array('NAME' => $row1['name'], 'TEXT' => $row1['text'], 'ZAHL' => $zahl), 4);
				$zahl--;
			}
		}
	}
	$tpl->out(5);
}

$design->footer();

?>
