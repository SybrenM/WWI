<?php
//De volgende functie is letterlijk de LIKE functie uit SQL, maar dan PHP
function like_match($pattern, $subject) {
$pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
return (bool) preg_match("/^{$pattern}$/i", $subject);
}
function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
function cleanemail($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Z-a-z0-9@.\-]/', '', $string); // Verwijder alle caracters behalve de @ en .
}
?>
