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
        
        if(Text::startsWith($fieldName,"date_")){
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
    
}
