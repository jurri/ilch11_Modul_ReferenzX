<?php 
#   Copyright by: Manuel Staechele
#   Support: www.ilch.de


defined ('main') or die ( 'no direct access' );
defined ('admin') or die ( 'only admin access' );

$design = new design ( 'Admins Area', 'Admins Area', 2 );
$design->header();

//-----------------------------------------------------------|

##
###
####
##### F u n k t i o n e n 

function getKats ( $akt ) {
  $katAR = array();
	$kats = '';
	$erg = db_query("SELECT DISTINCT referenzen_kat FROM `prefix_referenzen`");
	while ($row = db_fetch_object($erg)) {
		$katAr[] = $row->referenzen_kat;
	}
  $katAr[] = 'Allgemein';
	$katAr = array_unique($katAr);
	foreach($katAr as $a) {
	  if (trim($a) == trim($akt)) {
		  $sel = ' selected';
		} else {
		  $sel = '';
		}
	  $kats .= '<option'.$sel.'>'.$a.'</option>';
	} 
  return ($kats);
}

##### F u n k t i o n
####
###
##
#
##
###
####
##### A k t i o n e n

if ( !empty($_REQUEST['um']) ) {
  $um = $_REQUEST['um'];
  if ( $um == 'insert' ) {
	  
# insert
		$text  = escape($_POST['txt'], 'textarea');
		if ( $_POST['katLis'] == 'neu' ) {
		  $_POST['katLis'] = $_POST['kat'];
		}
		db_query("INSERT INTO `prefix_referenzen` (referenzen_title,user_id,referenzen_time,referenzen_recht,referenzen_kat,referenzen_text,referenzen_designer,referenzen_slicer,referenzen_anpasser,referenzen_code,referenzen_preis,referenzen_anmerkung,referenzen_vprogramme,referenzen_zeit,referenzen_release,referenzen_name,referenzen_nname,referenzen_email,referenzen_icq,referenzen_hp,referenzen_screen, referenzen_forum)
		VALUES ('".$_POST['titel']."',".$_SESSION['authid'].",NOW(),".escape($_POST['grecht'],'integer').",'".$_POST['katLis']."','".$text."','".$_POST['designer']."','".$_POST['slicer']."','".$_POST['anpasser']."','".$_POST['code']."','".$_POST['preis']."','".$_POST['anmerkung']."','".$_POST['vprogramme']."','".$_POST['zeit']."','".$_POST['release']."','".$_POST['name']."','".$_POST['nname']."','".$_POST['email']."','".$_POST['icq']."','".$_POST['hp']."','".$_POST['screen']."','".$_POST['forumlink']."')");
	  echo mysql_error();
# insert		
		
	} elseif ( $um == 'change' ) {

# edit
	  $text  = escape($_POST['txt'],'textarea');
		
		if ( $_POST['katLis'] == 'neu' ) {
		  $_POST['katLis'] = $_POST['kat'];
		}
		db_query('UPDATE `prefix_referenzen` SET
				referenzen_title = "'.$_POST['titel'].'",
				user_id  = '.$_SESSION['authid'].',
				referenzen_recht = '.$_POST['grecht'].',
				referenzen_kat   = "'.$_POST['katLis'].'",
				referenzen_designer  = "'.$_POST['designer'].'",
        referenzen_slicer  = "'.$_POST['slicer'].'",
        referenzen_anpasser  = "'.$_POST['anpasser'].'",
        referenzen_code  = "'.$_POST['code'].'",
        referenzen_preis  = "'.$_POST['preis'].'",
        referenzen_anmerkung  = "'.$_POST['anmerkung'].'",
        referenzen_vprogramme  = "'.$_POST['vprogramme'].'",
        referenzen_zeit  = "'.$_POST['zeit'].'",
        referenzen_release  = "'.$_POST['release'].'",
        referenzen_name  = "'.$_POST['name'].'",
        referenzen_nname  = "'.$_POST['nname'].'",
        referenzen_email  = "'.$_POST['email'].'",
       	referenzen_icq  = "'.$_POST['icq'].'",
        referenzen_hp  = "'.$_POST['hp'].'",
        referenzen_screen  = "'.$_POST['screen'].'",
		referenzen_forum  = "'.$_POST['forumlink'].'",
				referenzen_text  = "'.$text.'" WHERE referenzen_id = "'.$_POST['referenzenID'].'" LIMIT 1');
					  echo mysql_error();
	  $edit = $_POST['referenzenID'];
  }
}
# edit


# del
if ( $menu->get(1) == 'del' ) {
  db_query('DELETE FROM `prefix_referenzen` WHERE referenzen_id = '.$menu->get(2).' LIMIT 1');
}
#del


##### A k t i o n e n
####
###
##
#
##
###
####
##### h t m l   E i n g a b e n




if ( empty ($doNoIn) ) {
   
	$limit = 20;  // Limit 
  $page = ($menu->getA(1) == 'p' ? $menu->getE(1) : 1 );
  $MPL = db_make_sites ($page , '' , $limit , "?referenzen" , 'referenzen' );
  $anfang = ($page - 1) * $limit;
	if ( $menu->get(1) != 'edit' ) {
	  $FreferenzenID = '';
		$Faktion = 'insert';
		$Fueber  = '';
		$Fueber0  = '';
		    $Fueber1  = '';
    $Fueber2  = '';
    $Fueber3  = '';
    $Fueber4  = '';
    $Fueber5  = '';
    $Fueber6  = '';
    $Fueber7  = '';
    $Fueber8  = '';
    $Fueber9  = '';
    $Fueber10  = '';
    $Fueber11  = '';
    $Fueber12  = '';
    $Fueber13  = '';
    $Fueber14  = '';
    $Fueber15  = '';
		$Fstext  = '';
		$Ftxt    = '';
		$Fgrecht = '';
		$FkatLis = '';
		$Fsub    = 'Eintragen';
	} else {
	  $row = db_fetch_object(db_query("SELECT * FROM `prefix_referenzen` WHERE referenzen_id = ".$menu->get(2)));
	  $FreferenzenID = $row->referenzen_id;
		$Faktion = 'change';
		$Fueber  = $row->referenzen_title;
		$Ftxt    = stripslashes($row->referenzen_text);
		$Fueber0  = $row->referenzen_forum;
    $Fueber1  = $row->referenzen_designer;
    $Fueber2  = $row->referenzen_slicer;
    $Fueber3  = $row->referenzen_anpasser;
    $Fueber4  = $row->referenzen_code;
    $Fueber5  = $row->referenzen_preis;
    $Fueber6  = $row->referenzen_anmerkung;
    $Fueber7  = $row->referenzen_vprogramme;
    $Fueber8  = $row->referenzen_zeit;
    $Fueber9  = $row->referenzen_release;
    $Fueber10  = $row->referenzen_name;
    $Fueber11  = $row->referenzen_nname;
    $Fueber12  = $row->referenzen_email;
    $Fueber13  = $row->referenzen_icq;
    $Fueber14  = $row->referenzen_hp;
    $Fueber15  = $row->referenzen_screen;
		$Fgrecht = $row->referenzen_recht;
		$FkatLis = $row->referenzen_kat;
		$Fsub    = '&Auml;ndern';
	}
$tpl = new tpl ( 'referenzen', 1);

  $ar = array 
			  (
			    'referenzenID' => $FreferenzenID,
					'AKTION' => $Faktion,
					'MPL'    => $MPL,
					'UEBER'  => $Fueber,
					'txt'    => $Ftxt,
					'UEBER0'  => $Fueber0,
					'UEBER1'  => $Fueber1,
'UEBER2'  => $Fueber2,
'UEBER3'  => $Fueber3,
'UEBER4'  => $Fueber4,
'UEBER5'  => $Fueber5,
'UEBER6'  => $Fueber6,
'UEBER7'  => $Fueber7,
'UEBER8'  => $Fueber8,
'UEBER9'  => $Fueber9,
'UEBER10'  => $Fueber10,
'UEBER11'  => $Fueber11,
'UEBER12'  => $Fueber12,
'UEBER13'  => $Fueber13,
'UEBER14'  => $Fueber14,
'UEBER15'  => $Fueber15,
          'SMILIS' => getsmilies(),
					'grecht' => dbliste($Fgrecht,$tpl,'grecht',"SELECT id,name FROM prefix_grundrechte ORDER BY id DESC"),
					'KATS'   => getKats($FkatLis),
					'FSUB'   => $Fsub
							
	  );
		
		$tpl->set_ar_out($ar,0);
		
	
	# e d i t , d e l e t e
  $abf = 'SELECT *
 	        FROM `prefix_referenzen` 
					ORDER BY referenzen_time DESC 
					LIMIT '.$anfang.','.$limit;
					
  $erg = db_query($abf);
  $class = '';
	while ($row = db_fetch_object($erg) ) {
    $class = ($class == 'Cmite' ? 'Cnorm' : 'Cmite' );
		$tpl->set_ar_out( array ( 'ID' => $row->referenzen_id, 'class' => $class, 'TITEL' => $row->referenzen_title,    'DESIGNER'  => $row-> referenzen_designer,    'SILCER'  => $row-> referenzen_slicer,    'ANPASSER'  => $row-> referenzen_anpasser,    'CODE'  => $row-> referenzen_code,    'PREIS'  => $row-> referenzen_preis,    'ANMERKUNG'  => $row-> referenzen_anmerkung,    'VPROGRAMME'  => $row-> referenzen_vprogramme,    'ZEIT'  => $row-> referenzen_zeit,    'RELEASE'  => $row-> referenzen_release,    'NAME'  => $row-> referenzen_name,    'NNAME'  => $row-> referenzen_nname,    'EMAIL'  => $row-> referenzen_email,    'ICQ'  => $row-> referenzen_icq,    'HP'  => $row-> referenzen_hp,    'SCREEN'  => $row-> referenzen_screen) , 1 );
	}
  # e d i t , d e l e t e

  $tpl->set_ar_out( array ('MPL' => $MPL ) , 2 );

}

$design->footer();
?>
