<?php
//De volgende functie is letterlijk de LIKE functie uit SQL, maar dan PHP
function like_match($pattern, $subject) {
$pattern = str_replace('%', '.*', preg_quote($pattern, '/'));
return (bool) preg_match("/^{$pattern}$/i", $subject);
}

?>
