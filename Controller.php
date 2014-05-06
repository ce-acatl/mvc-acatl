<? abstract class Controller {
    
    protected $view;

    public function __construct() {
        $this->view = new View(new Request);
    }    
    
    /* 
    * CONTROLLER FUNCTIONS
    *  En ésta clase van todas las funciones que se quieran tener en todos los controladores
    *  Se invocan en los controladores llamando: $this->myFunction();
    */ 
    
    abstract public function index();
    
    /* ENFORCED FUNCTIONS
    *  Sirve para poner funciones o variables que quieras tener en todos los controladores
    */ 
    public function enforcedFunctions($algo = false, $que = false){
        $this->view->flash = Session::getKey("flash"); // Flash data
        $this->view->user = (Session::getKey("loggedIn"))?Session::getKey("user"):false;
        $this->view->userPermission = $this->getPermission();
        if ($algo) $this->view->title = $algo." de ".$que; // Generic title constructor
    }
    
    /* GET PERMISSION
    *  traen en un arreglo los permisos del usuario o devuelbe false si no hay usuario
    */
    protected function getPermission(){
        if(Session::getKey("loggedIn")):
            $userPermissions = array();
            $userPermission = Session::getKey("level");
            if($userPermission[0]) { // tiene permiso para crear
                $userPermissions[] = "create";
            }
            if($userPermission[1]) { // tiene permiso para crear
                $userPermissions[] = "read";
            }
            if($userPermission[2]) { // tiene permiso para crear
                $userPermissions[] = "update";
            }
            if($userPermission[3]) { // tiene permiso para crear
                $userPermissions[] = "delete";
            }
            return $userPermissions;            
        else:
            return false;
        endif;
    }
    
    /* LOAD MODEL
    *  Carga el modelo, necesita el nombre del modelo, si el espacio esta vacio se cargan los modelos del folder de raiz de los modelos
    *  Ex:  $model = "something"
    *       $space = "admin"
    *       y un archivo llamado somethingModel.php en la carpeta correspondiente al espacio
    */ 
    protected function loadModel($model,$space = false){
        $modelName = $model."Model";
        $modelRoute = (!$space)?
                ROOT."private".DS."models".DS.$modelName.".php"
                : ROOT."private".DS."models".DS.$space.DS.$modelName.".php";
        
        if(is_readable($modelRoute)){
            require_once $modelRoute;
            $model = new $modelName;
            return $model;
        }
        else {
            throw new Exception("{$modelRoute} > Error de modelo");
        }
    }
    
    protected function error($message, $type = 404){
        include_once $type.'.html';
        echo '<br />';
        echo $message;
    }
    
    
    /* CLEAN ARRAY
    *  Convierte datos HTML y los caracteres especiales a un código más seguro.
    *  <> = &amp;gt;&amp;lt;
    */ 
    protected function cleanArray($dirty){
        $clean = array();
        foreach ($dirty as $key => $value){
            //$clean[$key] = htmlentities(utf8_decode(htmlspecialchars($value)));
            $clean[$key] = Text::cleanForSql($value);
        }
        return $clean;
    }
    
    /* GET LIBRARY
    *  Requiere del folder de librerias la class que se pida.
    */ 
    protected function getLibrary($library) {
        $routeLibrary = APP_PATH.$library.'.php';
        
        if(is_readable($routeLibrary)){
            require_once $routeLibrary;
        }
        else{
            throw new Exception('Error de libreria');
        }
    }
    
    /* GET TEXT
    *  Saca de los datos del request la clave que se pida, devuelbe texto limpio
    */ 
    protected function getText($clave) {
        if(isset($_REQUEST[$clave]) && !empty($_REQUEST[$clave])){
            $_REQUEST[$clave] = htmlentities(htmlspecialchars($_REQUEST[$clave]), ENT_NOQUOTES, 'UTF-8');
            return $_REQUEST[$clave];
        }
        
        return '';
    }
    
    /* GET INT
    *  Saca de los datos del request la clave que se pida, devuelbe int limpia o 0 si no es int
    */ 
    protected function getInt($clave) {
        if(isset($_REQUEST[$clave]) && !empty($_REQUEST[$clave])){
            $_REQUEST[$clave] = filter_input($_REQUEST[$clave], $clave, FILTER_VALIDATE_INT);
            return $_REQUEST[$clave];
        }
        
        return 0;
    }
    
    /* Redirect
    *  Redirige a una ruta con un espacio, si no se asignan, redirige a la URL del proyecto
    *  $route = "home/index" => $space = "directory"
    */ 
    protected function redirect($route = false, $space = false) {
        $url = (LANG_MODE) ? URL_LANG :URL;
        if($route){
            if($space){
                header("location:".$url.$space."/".$route);
            } else {
                header("location:".$url.$route);
            }
        } else{
            header('location:'.$url);
        }
    }
    
    /* Get Pass
     * Saca el password directamente encryptado del POST
    */
   protected function getPass($parametro = 'pass') { //por default esta como pass
        $encryptedPass = false;
        if(isset($_POST[$parametro]) && !empty($_POST[$parametro])){
            $encryptedPass = Tool::getHash('sha256', $_POST[$parametro], HASH_KEY);
        }
        return $encryptedPass;
   }
    
    /* Get SQL
    *  Saca de los datos del request la clave que se pida, devuelbe string listo para inserción en base de datos
    */     
    protected function getSql($clave) {
        if(isset($_REQUEST[$clave]) && !empty($_REQUEST[$clave])){
            $_REQUEST[$clave] = strip_tags($_REQUEST[$clave]);
            
            if(!get_magic_quotes_gpc()){
                $_REQUEST[$clave] = mysql_escape_string($_REQUEST[$clave]);
            }
            
            return trim($_REQUEST[$clave]);
        }
    }
    
    /* Get AlphaNum
    *  Saca de los datos del request la clave que se pida, devuelbe string alfanumérico y sin espacios
    */  
    protected function getAlphaNum($clave) {
        if(isset($_REQUEST[$clave]) && !empty($_REQUEST[$clave])){
            $_REQUEST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_REQUEST[$clave]);
            return trim($_REQUEST[$clave]);
        }        
    }
    
    /* Validate Email
    *  Si tiene formato de email regresa TRUE
    */  
    public function validateEmail($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        
        return true;
    }
    
}
