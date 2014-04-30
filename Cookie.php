<? class Cookies {
    
    /* MAKE COOKIES
    *  Crea cookies.
    *  ex: $cookies = array("email" => "email@gmail.com", "password" => "passwordEncryptado");
    */ 
    public static function makeCookies($cookies = array(), $howManyDays = 28){
        foreach($cookies as $cookie){
            foreach ($cookie as $param => $data){
                if(!setcookie(APP_NAME."_".$param, $data, time()+60*60*24*$howManyDays)) return false;
            }            
        }
        return true;
    }
    
    /* DELETE COOKIES
    *  Borra cookies.
    *  ex: $cookies = array("email","password");
    */ 
    public static function deleteCookies($cookies = array()){
        foreach($cookies as $cookieParam){
            setcookie(APP_NAME."_".$cookieParam, "", time()-3600);
        }        
    }
}