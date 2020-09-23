<?php
function filterInput($input){
    //Removes invalid/dangerous stuff from input - like slashes, and HTML chars
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

function whitespace($input){
    //Removes whiespaces
    if (!preg_match("/^[a-zA-Z-' ]*$/",$input)) return true;
    else return false;
}