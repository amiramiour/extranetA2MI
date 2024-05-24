<?php

/******************************************************************/
/* Réduction du nombre de caractères à afficher dans les tableaux */
/* en indiquant seulmeent le début du texte, puis ...             */
/******************************************************************/

function reduireTexte ($string, $length)
{
    if (strlen($string) > $length)
        $string = substr($string,0,strpos($string," ",$length)) . " …";
    return $string;
}
		
/******************************************************************/
/* Réduction du nombre de caractères à afficher dans les tableaux */
/* en indiquant le début et la fin du texte séparées par des ...  */
/******************************************************************/

function raccourcirTexte($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
{
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return _functions . phpsubstr($string, 0, $length) . $etc;
        } else {
            return _functions . phpsubstr($string, 0, $length / 2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}		

/*****************************************************************************************/
/* Récupération du nom de la page sans le .php pour activer la surbrillance dans le menu */
/*****************************************************************************************/

$page = $_SERVER['PHP_SELF'];
$element = strrchr($page,'/');
$from = '/';
$to = ".";

function getStringBetween($element,$from,$to)
{
    $sub = substr($element, strpos($element,$from)+strlen($from),strlen($element));
    return substr($sub,0,strpos($sub,$to));
}

$result = getStringBetween($element,$from,$to);

/*****************************************************************************************/
/*                          Affichage de la date inscription                             */
/*****************************************************************************************/

function mepd($date)//affichage basique
{
    if(intval($date) == 0) return $date;

    $tampon    = time();
    $diff      = $tampon - $date;
    $dateDay   = date('d', $date);
    $tamponDay = date('d', $tampon);
    $diffDay   = $tamponDay - $dateDay;

    return date('Y/m/d', $date);
}




// affichage d'un numéro de telephone avec un point comme séparateur : 01.02.03.04.05
//$tel = wordwrap($tel, 2, '.', 1);

// un espace comme séparateurs de milliers, et pas de chiffre après la virgule, dans ce cas :
//$result = number_format("1236598", 0, ',', '.');

/* L'opérateur de coalescence nulle a une faible priorité. Cela signifie que si vous le mélangez avec d'autres
opérateurs (tels que la concaténation de chaînes ou les opérateurs arithmétiques), des parenthèses seront probablement nécessaires.
print 'Mr. ' . $name ?? 'Anonymous';
print 'Mr. ' . ($name ?? 'Anonymous');
*/