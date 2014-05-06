<?php class File {
    
    /*
     * Create Directory If Not Exists
     * Crea un directorio si no existe, regresa boleano
     */
    public static function createDirectoryIfNotExist($path){
        $return = 0;
        if (!is_dir($path)){ 
            mkdir($path); 
            $return = 1;
        } 
        return $return;
    }
    
    public static function insertDataInFile($content,$path,$fileName,$ext){
        $filePath = $path.$fileName.".".$ext;
        $fileOpened = fopen($filePath, 'w') or die("can't open file");        
        fwrite($fileOpened, $content);
        fclose($fileOpened);
        return $filePath;
    }
}