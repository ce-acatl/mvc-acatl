<?php class View {
    
    public $lang;
    public $jsRoute;
    public $cssRoute;
    public $error;
    public $message;
    
    public function __construct(Request $r) {
        $this->lang = $r->getLang();
        $this->error = false;
        $this->message = array(
            "msg" => false, // verdadero para que aparezca en el sitio
            "iconClass" => "icon-ok-sign",
            "type" => "success"
        );
    }
    
    public function render($view, $skin = false) {        
        $view = explode("/", $view);
        
        if(!$skin){
            $routeView = VIEWSPATH.$view[0].DS.$view[1].".php";
            $this->jsRoute = SKINSROUTE."js/";
            $this->cssRoute = SKINSROUTE."css/";
            $this->imgRoute = SKINSROUTE."images/";
            $skinPath = PUBLICPATH."layout".DS;
        } else {
            $routeView = VIEWSPATH.$skin.DS.$view[0].DS.$view[1].".php";
            $this->jsRoute = SKINSROUTE.$skin."/js/";
            $this->cssRoute = SKINSROUTE.$skin."/css/";
            $this->imgRoute = SKINSROUTE.$skin."/images/";
            $skinPath = SKINSPATH.$skin.DS."layout".DS;
        }
        if(is_readable($routeView) && is_readable($skinPath)){
            include_once $skinPath."head.php";
            include_once $routeView;
            include_once $skinPath."foot.php";
        } else {
            throw new Exception("> No existe vista en RENDER {$routeView} o {$skinPath}"); 
        }
    }
    
    /* RENDER PARTIAL
    *  Renderea especificamente una vista con datos sin template
    *  Ex:
    *  $view = "S/space/home/_header" => Invocando al archivo _home.php dentro de la carpeta home de vistas
    *  Si la primera variable no es S, se toma el espacio default;
    */  
    public function renderPartial($view, $dataArray = false){
        $views = explode("/", $view);
        if($view[0]=="S"){
            array_shift($views);
            $space = array_shift($views);
        } else {
            $space = false;
        }
        $folder = array_shift($views);
        $viewFile = array_shift($views).".php";
        
        $routeView = (!$space)?
            VIEWSPATH.$folder.DS.$viewFile
            :VIEWSPATH.$space.DS.$folder.DS.$viewFile;
        
        if(is_readable($routeView)){
            if($dataArray){
                echo $this->includeContents($routeView, $dataArray);
            } else {
                include_once $routeView;
            }
        } else {
            throw new Exception("> No existe vista en RENDER PARTIAL {$routeView}"); 
        }
    }
    
    /* T > TRANSLATE
    *  Traduce un string
    *  Si no haya el string en el arreglo del idioma adecuado, muestra el string sin cambios
    */ 
    public function t($defaultString){
        if($this->lang == DEFAULT_LANG){
            return $defaultString;
        } else {
            $routeLang = ROOT."public".DS."translations".DS.$this->lang.".php";
            if(is_readable($routeLang)){
                $langArray = include $routeLang;
                if(array_key_exists($defaultString, $langArray)){
                    return $langArray[$defaultString];
                } else {
                    return $defaultString;
                }
            } else {
                throw new Exception("> No existe archivo de traduccion o no es legible en T {$routeLang}"); 
            }
        }        
    }
    
    public function dirtyArray($clean){
        $dirty = array();
        foreach ($clean as $key => $value):
            $dirty[$key] = utf8_encode(html_entity_decode($value));
        endforeach;
        return $dirty;
    }
    
    private function includeContents($filename, $variablesToMakeLocal) {
        extract($variablesToMakeLocal);
        if (is_file($filename)) {
            ob_start();
            require_once $filename;
            return ob_get_clean();
        } else {
            return false;
        }
    }
}