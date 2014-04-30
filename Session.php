<? class Session {
    
    public static function init() {
        session_start();
    }
    
    public static function destroyKey($clave = FALSE) {
        if($clave){
            if(is_array($clave)){
                for($i = 0; $i < count($clave); $i++){
                    if(isset($_SESSION[$clave[$i]])){
                        unset($_SESSION[$clave[$i]]);
                    }
                }
            } else{
                if(isset($_SESSION[$clave])){
                    unset($_SESSION[$clave]);
                }
            }
        } else{
            session_destroy();
        }
    }
    
    public static function setKey($key, $value) {
        if(!empty($key))
            $_SESSION[$key] = $value;
    }
        
    
    public static function getKey($clave) {
        if(isset($_SESSION[$clave])) {
            return $_SESSION[$clave]; 
        } else {
            return false;      
        }
    }
    
    public static function access($level) {
        if(!Session::get('loggedIn')){
            header('location:'.URL.'error/access/5050');
            exit;
        }
        
        Session::tiempo();
        
        if(Session::getLevel($level) > Session::getLevel(Session::get('level'))){
            header('location:'.URL.'error/access/5050');
            exit;
        }
    }
    
    public static function accessView($level) {
        if(!Session::get('loggedIn')){
            return false;
        }
        
        if(Session::getLevel($level) > Session::getLevel(Session::get('level'))){
            return false;
        }
        return true;
    }
    
    public static function getLevel($level) {
        $role['admin'] = 3;
        $role['plus'] = 2;
        $role['user'] = 1;
        
        if(!array_key_exists($level, $role)){
            throw new Exception('Error de acceso');
        }
        else{
            return $role[$level];
        }
    }
    
    public static function accessEstricto(array $level, $noAdmin = false) {
        if(!Session::get('loggedIn')){
            header('location:'.URL.'error/access/5050');
            exit;
        }
        
        Session::tiempo();
        
        if($noAdmin == false){
            if(Session::get('level') == 'admin'){
                return;
            }
        }
        
        if(count($level)){
            if(in_array(Session::get('level'), $level)){
                return;
            }
        }
        
        header('location:'.URL.'error/access/5050');
    }
    
    public static function accessViewEstricto(array $level, $noAdmin = false) {
        if(!Session::get('loggedIn')){
            return false;
        }
        
        if($noAdmin == false){
            if(Session::get('level') == 'admin'){
                return true;
            }
        }
        
        if(count($level)){
            if(in_array(Session::get('level'), $level)){
                return true;
            }
        }
        
        return false;
    }
    
    public static function tiempo() {
        if(!Session::get('tiempo') || !defined('SESSION_TIME')){
            throw new Exception('No se ha definido el tiempo de sesion'); 
        }
        
        if(SESSION_TIME == 0){
            return;
        }
        
        if(time() - Session::get('tiempo') > (SESSION_TIME * 60)){
            Session::destroy();
            header('location:'.URL.'error/access/8080');
        }
        else{
            Session::set('tiempo', time());
        }
    }
}