<? class Request {

    private $controller;
    private $space;
    private $method;
    private $args;
    private $lang;
    
    /* Class Request
     * Se corre automáticamente cuando corre la aplicación, para dividir la URL en:
     * 1. Lenguaje < default: es
     * 2. Espacio < solo lo toma en cuenta si viene la string S asi: url.com/es/home/S/directory
     * 3. Controlador
     * 4. Metodo < si el tercer parametro de la url no es una S, se toma en cuenta como Metodo
     * 5. n Variables divididas todas con SLASH [/]
     */

    public function __construct() {
        if (!isset($_GET["url"])) {
            if (LANG_MODE) {
                header("Location: ".URL.ANCLE.DEFAULT_LANG."/");
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

}