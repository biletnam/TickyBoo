<?php
   function findinside( $filestring) {
        $string = file_get_contents($filestring);
        preg_match_all('/define\(["\']([a-zA-Z0-9_]+)["\'],[ ]*(.*?)\);/si',  $string, $m); //.'/i'
        return array_combine( $m[1],$m[2]);
    }

    $en = findinside('includes/lang/site_en.inc');
    $du = findinside('includes/lang/site_nlz.php');
    ksort($en, SORT_LOCALE_STRING);
    echo "<table border=1>";
    foreach ($en as $key =>$value) {
      $keyx=(isset($du[$key]))?$key:"<b>$key</b>";
      echo "<tr><td>$keyx</td><td>".htmlentities($value)."</td><td>";
      echo(isset($du[$key]))?htmlentities($du[$key]):"&nbsp;","</td></tr>\n";
    }
    $diff= array_diff_key($du, $en);
    foreach ($diff as $key =>$value) {
      echo "<tr><td><b>$key</b></td><td>&nbsp;</td><td>".htmlentities($value)."</td></tr>\n";
    }
    echo "</table>";
?>