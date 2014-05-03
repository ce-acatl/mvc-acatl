<? class Tool {

    public static function handleLogin() {
        @session_start();
        $logged = $_SESSION['loggedIn'];
        if ($logged == false) {
            session_destroy();
            header('location: ../login');
            exit;
        }
    }
    
    // For a multi-dimensional array
    public static function array2object($array) {
        $object = (object) $array;

        foreach ($object as $key) {
            if (is_array($key)) { $object = (object) $key; }
        }
        return $object;
    }
    
    // Remplaza los caracteres mayores que el $lenght en $text por ...
    public static function grapText($lenght, $text) { 
	if (strlen($text)>$lenght) {
		echo substr($text, -strlen($text), $lenght).'...'; 
	} else {
		echo $text;
	}
    }
    public function formatMoney($int) {
        $result = number_format($int);
        return $result;
    }
    public function capitalize($text) {
        $newText = strtoupper($text);
        return $newText;
    }
    public function capitalizeFirst($text) {
        $newText = ucfirst($text);
        return $newText;
    }
    public function replaceUnder($string) {
        $newString = str_replace("_"," ",$string);
        return $newString;
    }
    public function replaceSpace($string) {
        $newString = str_replace(" ","_",$string);
        return $newString;
    }
    public function makeUrl($string) {
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
    public function cleanText($text) {
        $t = htmlspecialchars_decode($text, ENT_NOQUOTES);
        $output = str_replace('\&quot;', '&quot;', $t);
        $output2 = str_replace("\&#039;", "&#039;", $output);
        $output3 = str_replace("\'", "'", $output2);
        $output4 = str_replace('\"', '"', $output3);
        return $output4;
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
    
    /* Create HTML
     * Funcion para construir etiquetas HTML [03-04-2014]
     * con un tag se puede construir, pero se puede incluir class, id, style (en formato css) y un arreglo de atributos
     * regresa un arreglo con las propiedades: head y tail para poder insertar contenido
    */
    
    public static function createHTML($tag,$id = false,$class = false,$style = false, $attrArray = false){
        $idSintax = ($id) ? "id='{$id}' " : "";
        $classSintax = ($class) ? "class='{$class}' " : "";
        $styleSintax = ($style) ? "style='{$style}' " : "";
        if($attrArray){
            $attrSintax = "";
            foreach ($attrArray as $attr => $value){
                $attrSintax .= "{$attr}='{$value}' ";
            }
        } else {
            $attrSintax = "";
        }
        $head = "<{$tag} {$idSintax}{$classSintax}{$styleSintax}{$attrSintax}>";
        $head = str_replace(" >", ">", $head);
        $tail = "</{$tag}>";
        $newHtml = array(
            'head' => $head,
            'tail' => $tail
        );
        
        return $newHtml;
    }
    
    public static function getMaxOfField($type){
        $pos = strpos($type, "("); // Busca la posición del primer parentesis
        $typeMax = substr($type, $pos-1);
        $almostMax = str_replace("(","",$typeMax);
        $max = str_replace(")","",$almostMax);
        return (int) $max;
    }
    
    /*
     * Get Validation Classes
     * Obtiene las classes necesarias para la validación del formulario, en conjunto del plugin validace.js 
     */
    public static function getValidationClasses($fieldName, $fieldTypeName,$necessary = false){
        $classes = array("validated");
        if($necessary) { $classes[] = "necessary"; }
        // int = number, varchar = text, max-chars, validated, email = email, double, date_ = datepicker, text = textarea
        switch ($fieldTypeName) {
            case "int": $classes[] = "number"; break;
            case "varchar": $classes[] = "alphanumeric"; break;
            case "text": $classes[] = "onlyText"; break;
            default: break;
        }
        
        if(Tool::startsWith($fieldName,"date_")){
            $classes[] = "datepicker";            
        } else {
            if ($fieldName =="date_of_creation") {
                
            }
        }
        if(in_array($fieldTypeName,array("varchar","int"))){
            $classes[] = "max";
        }
        $readableClasses = "";
        $howManyClasses = count($classes);
        if($howManyClasses){
            for($i = 0;$i<=$howManyClasses-1;$i++){                
                $readableClasses .= ($howManyClasses<>$i) ? $classes[$i]." ": $classes[$i];
            }
        }
        
        return $readableClasses;
    }
    
    /*
     * Funcionize From Table
     * Makes a String like: book_types to bookTypes
     * And optionaly it can take suffix like: 
     *      functionizeFromTable("book_type_id", "_id"); 
     *      would get: bookType
     */
    public static function functionize($dirtyString,$removeSufix = false){
        $string = ($removeSufix) ? substr($dirtyString,0,strlen($dirtyString)-strlen($removeSufix)) : $dirtyString;
        $decentString = str_replace("_"," ",$string);
        $capitalizedString = ucwords($decentString);
        $nonSpacedString = str_replace(" ","",$capitalizedString);
        $firstLetterLoweredCase = lcfirst($nonSpacedString);
        $niceFunctionName = $firstLetterLoweredCase;
        return $niceFunctionName;
    }
    
}
