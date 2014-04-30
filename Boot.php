<? class Boot {
    
    public static function run(Request $r) {
        $controllerName = $r->getController().'Controller';
        $space = $r->getSpace();
        $controllerPath = (!$space)?
                ROOT."private".DS."controllers".DS.$controllerName.'.php'
                : ROOT."private".DS."controllers".DS.$space.DS.$controllerName.'.php';
        $method = $r->getMethod();
        $args = $r->getArgs();

        if(is_readable($controllerPath)){
            require_once $controllerPath;
            $controller = new $controllerName;
            
            $method = (is_callable(array($controller, $method))) ? $r->getMethod() : "index";

            if(isset($args) && !empty($args)){
                call_user_func_array(array($controller, $method), $args);
            } else{
                call_user_func(array($controller, $method));
            }
        } else {
            throw new Exception("Metodo {$method} no encontrado en {$controllerPath}");
        }
    }
}