<?php
/*
 * Factux le facturier libre
 * Replacement date.php for PHP 8 compatibility
 */

function get_month_names($lang) {
    if (strpos($lang, 'fr') !== false) return [1=>'Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    if (strpos($lang, 'nl') !== false) return [1=>'Januari','Februari','Maart','April','Mei','Juni','Juli','Augustus','September','Oktober','November','December'];
    return [1=>'January','February','March','April','May','June','July','August','September','October','November','December'];
}

function get_day_names($lang) {
    if (strpos($lang, 'fr') !== false) return ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
    if (strpos($lang, 'nl') !== false) return ['Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag'];
    return ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
}

function calendrier_local_mois ()
{
  global $code_langue;
  return get_month_names($code_langue);
}

function calendrier_local_mois2 ()
{
  global $code_langue;
  $m = get_month_names($code_langue);
  return [12 => $m[12]];
}

function calendrier_local_mois3 ()
{
  global $code_langue;
  $m = get_month_names($code_langue);
  unset($m[12]);
  return $m;
}

function calendrier_local_jour ()
{
  global $code_langue;
  $d = get_day_names($code_langue);
  // Return first 6 days (Sun-Fri)
  return array_slice($d, 0, 6);
}

function calendrier_local_jour2 ()
{
  global $code_langue;
  $d = get_day_names($code_langue);
  // Return last day (Sat)
  return array_slice($d, 6, 1);
}
?>
