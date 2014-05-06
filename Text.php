<?php class Text {
    /*
     * Clean For Sql
     * Devuelbe caracteres seguro para insersión en base de datos
     * Sacado de: https://code.google.com/p/clients-oriented-ftp/issues/detail?id=164
     */
    public static function cleanForSql($string) {
        return nl2br(mysql_real_escape_string(htmlentities($string, ENT_QUOTES)));
    }
    
    public static function cleanForWeb($string){
        return html_entity_decode($string);
    }
    
    public static function formatMoney($int) {
        $result = number_format($int);
        return $result;
    }
    public static function capitalize($text, $first = false) {
        if($first){
            $newText = ucfirst($text);
        } else { // All
            $newText = ucwords($text);
            //$newText = strtoupper($text);
        }
        
        return $newText;
    }
    public static function replace($string,$something = "_",$with = " ") {
        $newString = str_replace($something, $with, $string);
        return $newString;
    }
    public static function title($tableName,$separator = "_"){
        $normalSentence = Text::replace($tableName,$separator," ");
        $title = Text::capitalize($normalSentence);
        return $title;
    }
    public static function makeUrl($string) {
        $borrar = array("!", ",", ".", ":", "'", "=");
        $remplazar = array(
            " "=>"-",
            "&aacute;"=>"a",
            "&eacute;"=>"e",
            "&iacute;"=>"i",
            "&oacute;"=>"o",
            "&uacute;"=>"u",
            "&ntilde;"=>"n", 
        );
        $cleanStr = strtolower($string);
        foreach($remplazar as $remplazado => $por) {
            $cleanStr = str_replace($remplazado,$por,$cleanStr);
        }
        
        foreach($borrar as $borrado) {
            $cleanStr = str_replace($borrado,"",$cleanStr);
        }
        
        //$newString4 = str_replace ("", "", $newString3);
        return $cleanStr;
    }    
    public static function getHash($algoritmo, $data, $key) {
        $hash = hash_init($algoritmo, HASH_HMAC, $key);
        hash_update($hash, $data);
        
        return hash_final($hash);
    }
    
    public static function stripNum($num) {
	$new_num = $num;
        if ($num == "00") { $new_num = '0'; }
	if ($num == "01") { $new_num = str_replace ("0", "", $num); }
	if ($num == "02") { $new_num = str_replace ("0", "", $num); }
	if ($num == "03") { $new_num = str_replace ("0", "", $num); }
	if ($num == "04") { $new_num = str_replace ("0", "", $num); }
	if ($num == "05") { $new_num = str_replace ("0", "", $num); }
	if ($num == "06") { $new_num = str_replace ("0", "", $num); }
	if ($num == "07") { $new_num = str_replace ("0", "", $num); }
	if ($num == "08") { $new_num = str_replace ("0", "", $num); }
	if ($num == "09") { $new_num = str_replace ("0", "", $num); }
	return $new_num;
    }
    
    public static function cleanString($string){
        $new_string = str_replace ("&", "&amp;", $string);
        $new_string2 = str_replace ('"', "&quot;", $new_string);
        $new_string3 = str_replace (chr(151), "&#8212;", $new_string2); //em dash
        $new_string4 = str_replace (chr(150), "&#8211;", $new_string3); //en dash
        $new_string5 = str_replace (chr(145), "'", $new_string4); // Left single quote
        $new_string6 = str_replace (chr(146), "'", $new_string5); // Right single quote
        $new_string7 = str_replace ("ñ", "&ntilde;", $new_string6); // Right single quote
        $new_string8 = htmlentities($new_string7, ENT_NOQUOTES, 'UTF-8');
        if (substr($new_string8, -1, 1) == ' ') { $last_string = substr($new_string8, 0, -1); return $last_string;} 
        else { return $new_string8; }
    }
    
    /*
     * Starts With
     * returns true or false
     * Thanks: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php @Salman A
     */
    public static function startsWith($haystack, $needle) {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }
    
    /*
     * Ends With
     * returns true or false
     * Thanks: http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php @Salman A
     */
    public static function endsWith($haystack, $needle){
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }
    
    /*
     * Funcionize 
     * Makes a String like: book_types to bookTypes
     * And optionaly it can take suffix like: 
     *      functionizeFromTable("book_type_id", "_id"); 
     *      would get: bookType
     */
    public static function functionize($dirtyString,$removeSufix = false){
        $string = ($removeSufix) ? Text::substractSuffix($dirtyString,$removeSufix) : $dirtyString;
        $decentString = str_replace("_"," ",$string);
        $capitalizedString = ucwords($decentString);
        $nonSpacedString = str_replace(" ","",$capitalizedString);
        $firstLetterLoweredCase = lcfirst($nonSpacedString);
        $niceFunctionName = $firstLetterLoweredCase;
        return $niceFunctionName;
    }
    
    /*
     * Slugify
     * Makes a String like: Júan Carlos to juan-carlos
     * And optionaly it can take suffix like: 
     *      functionizeFromTable("book_type_id", "_id"); 
     *      would get: book-type
     */
    public static function slugify($dirtyString, $separator = "-", $removeSufix = false){
        // Cambiar caracteres especiales
        $special_cases = array( '&' => 'and');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $dirtyString);
        // Quitar caracteres raros
        $normalString = Text::sluggable($string);
        // De mayuscula a minuscula
        $loweredString = strtolower($normalString);
        // Si hay sufijo removerlo
        $goodString = ($removeSufix) ? Text::substractSuffix($loweredString,$removeSufix) : $loweredString;
        // Espacios a separador
        $slug = str_replace(" ",$separator,$goodString);
        
        return $slug;
    }
    
    public static function sluggable($p) {
        $ts = array("/[À-Å]/","/Æ/","/Ç/","/[È-Ë]/","/[Ì-Ï]/","/Ð/","/Ñ/","/[Ò-ÖØ]/","/×/","/[Ù-Ü]/","/[Ý-ß]/","/[à-å]/","/æ/","/ç/","/[è-ë]/","/[ì-ï]/","/ð/","/ñ/","/[ò-öø]/","/÷/","/[ù-ü]/","/[ý-ÿ]/");
        $tn = array("A","AE","C","E","I","D","N","O","X","U","Y","a","ae","c","e","i","d","n","o","x","u","y");
        return preg_replace($ts,$tn, $p);
    }
    
    /*
     * Substract Suffix
     * Devuelve string sin la última palabra coincidente del string
     */
    public static function substractSuffix($dirtyString, $word){
        $string = substr($dirtyString,0,strlen($dirtyString)-strlen($word));
        return $string;
    }
    
    /*
     * Substract Prefix
     * Devuelve string sin la primera palabra coincidente del string
     */
    public static function substractPrefix($dirtyString, $word){
        $string = substr($dirtyString,-0,strlen($word));
        return $string;
    }
    
    public static function insideParentesis($type){
        $pos = strpos($type, "("); // Busca la posición del primer parentesis
        $typeMax = substr($type, $pos-1);
        $almostMax = str_replace("(","",$typeMax);
        $max = str_replace(")","",$almostMax);
        return $max;
    }
    
    public static function convertEntities($text) {
        $t = htmlspecialchars_decode($text, ENT_NOQUOTES);
        $output = str_replace('\&quot;', '&quot;', $t);
        $output2 = str_replace("\&#039;", "&#039;", $output);
        $output3 = str_replace("\'", "'", $output2);
        $output4 = str_replace('\"', '"', $output3);
        return $output4;
    }
    
    // Remplaza los caracteres mayores que el $lenght en $text por ...
    public static function wrap($lenght, $text) { 
	if (strlen($text)>$lenght) {
		echo substr($text, -strlen($text), $lenght).'...'; 
	} else {
		echo $text;
	}
    }
    
    /* Tab
     * devuelbe n veces "     ";
     */
    public static function tab($number = false) {
        $tab = "     ";
        if($number) {
            for ($i = 1; $i <= $number; $i++) {
                $tab.= $tab;
            }
        }
        return $tab;
    }   
}