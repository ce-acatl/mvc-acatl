<? class Request {

    private $controller;
    private $space;
    private $method;
    private $args;
    private $lang;
    public $url;
    public $host;
    public $afterPath;
    
    /* Class Request
     * Se corre automáticamente cuando corre la aplicación, para dividir la URL en:
     * 1. Lenguaje < default: es
     * 2. Espacio < solo lo toma en cuenta si viene la string S asi: url.com/es/home/S/directory
     * 3. Controlador
     * 4. Metodo < si el tercer parametro de la url no es una S, se toma en cuenta como Metodo
     * 5. n Variables divididas todas con SLASH [/]
     */

    public function __construct() {
        $this->host = $_SERVER["HTTP_HOST"]; // gets host
        $this->afterPath = $_SERVER["REQUEST_URI"]; // gets path after url
        
        if (!isset($_GET["url"])) {
            if (LANG_MODE) {
                //URL.ANCLE.DEFAULT_LANG
            header("Location: {$this->url}/");
            }
        } else {
            $url0 = filter_input(INPUT_GET, "url"); // Se limpia la url
            $url00 = explode("/", $url0);
            $url = array_filter($url00);
            
            $this->lang = ($url[0] == 'es' || $url[0] == 'en') ? strtolower(array_shift($url)) : $this->lang = DEFAULT_LANG;
            if(isset($url[0]) && $url[0] == "S"){
                array_shift($url);
                $this->space = array_shift($url);
            } else {
                $this->space = false;
            }
            
            $this->controller = strtolower(array_shift($url));
            $this->method = strtolower(array_shift($url));
            $this->args = $url;
              
        }

        if (!$this->controller) $this->controller = DEFAULT_CONTROLLER;

        if (!$this->method) $this->method = 'index';

        if (!isset($this->args)) $this->args = array();
    }

    public function getController() {
        return $this->controller;
    }
    
    public function getSpace() {
        return $this->space;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getArgs() {
        return $this->args;
    }

    public function getLang() {
        return $this->lang;
    }
    
    /*
     * Get Url
     * Obtiene la url, por default: http://www.thissite.com
     * primer parametro full, para obtener tambien los metodos y parametros completos de la url
     * segundo parametro secure, para 
     */
    public static function getUrl($full = false, $secure = false){
        $this->url = ($full) ? $this->host.$this->afterPath : $this->host;
        return ($secure) ? "https://".$this->url : "http://".$this->url;        
    }
    
    /* IS AJAX
    *  Devuelve FALSE si no es un Request via ajax o TRUE si es.
    */ 
    public static function isAjax(){
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return 1;
        } else {
            return 0;
        }
    }

}