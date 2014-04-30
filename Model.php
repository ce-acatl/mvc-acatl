<? class Model {
    protected $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /* LOAD MODEL
    *  Carga el modelo, necesita el nombre del modelo, si el espacio esta vacio se cargan los modelos del folder default
    *  Ex:  $model = "something"
    *       $space = "admin"
    *       y un archivo llamado somethingModel.php en la carpeta correspondiente al espacio
    */ 
    public function loadModel($model,$space = false){
        $modelName = $model."Model";
        if(!$space){
           $space = "default"; 
        }  
        $modelRoute = ROOT."private".DS."models".DS.$space.DS.$modelName.".php";
        
        if(is_readable($modelRoute)){
            require_once $modelRoute;
            $model = new $modelName;
            return $model;
        }
        else {
            throw new Exception("{$modelRoute} > Error de modelo");
        }
    }
    
    public function getParam($id, $param) {
        $sql = "SELECT {$param} FROM {$this->table} WHERE id = {$id}";
        $sth = $this->db->select($sql);
        $data = $sth->fetch(2);
        return $data[$param];
    }
    
    public function getEmpty(){
        $sql = "SHOW COLUMNS FROM {$this->table}";
        $sth = $this->db->select($sql);
        $columns = $sth->fetchAll(2);
        $empty = array();
        foreach ($columns as $column){
            $empty[$column['Field']] = '';
        }
    }
    
    public function isValid($something){
        $necesaryFields = $this->necesaryFields();
        $return = array();
        $return["isValid"] = false;
        if($this->checkIfValid($necesaryFields, $something)){
            $return["isValid"] = true;
        } 
        return $return;
    }
    
    /* Exists
     * Necesita un arreglo
     * -> array($campo,$valor), busca un parametro en la tabla
     * -> array(array($string,$string2),array($string3,$string4)), busca varios parametros
    */
    public function exists($somethings){
        $howManyThings = count($somethings);
        $sql = "SELECT * FROM {$this->table} WHERE ";
        if($howManyThings>1){
            $i = 1;
            foreach($somethings as $something){
                $campo = $something[0];
                $valor = $something[1];
                $sql .= 
                    ($howManyThings==$i)? // Es la última?
                        "{$campo} = '{$valor}'":
                        "{$campo} = '{$valor}' AND ";
                $i++;
            }
        } else {
            $campo = $somethings[0];
            $valor = $somethings[1];
            $sql .= "{$campo} = '{$valor}'";
        }
        $sth = $this->db->select($sql);
        $data = $sth->fetchAll(2);
        
        if(!$data){
            return false;
        }
        return $data;
    }

     public function getAll($user = false) {
        $sql = ($user)?
            "SELECT * FROM {$this->table} WHERE user_id = {$user}":
            "SELECT * FROM {$this->table}";
        $sth = $this->db->select($sql);
        $data = $sth->fetchAll(2);
        return $data;
    }
    
    public function getLast($howMany=1) {
        $sql = "SELECT * FROM {$this->table} ORDER id desc LIMIT {$howMany}";
        $sth = $this->db->select($sql);
        $data = $sth->fetchAll(2);
        return $data;
    }
    
    public function getOne($id){
        $sql = "SELECT * FROM {$this->table} WHERE id = {$id}";
        $sth = $this->db->select($sql);
        $data = $sth->fetch(2);
        return $data;
    }

     public function saveOne($something, $register = false) {
        if($register) {
            $something["date_created"] = time();
        }
        
        $id = $this->db->insert($this->table, $something);        
        return $id;
    }

    public function saveImage($id,$fileName){
        $image = array("image" => $fileName);
        return $this->db->update($this->table, $image, "id = '{$id}'");
    }

    public function uploadFile($file, $id){
        $fileTypes = array("image/gif","image/jpeg","image/png","image/pjpeg");
        $fileMaxSize = 3000000;
        if ($file['size'] > 0) {
            if (in_array($file["type"],$fileTypes)) {
                if($file["size"] < $fileMaxSize){
                    $fileName = $file['name'];
                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                    $imageName = $id.".".$ext;
                    $imagePath = UPLOADSPATH.$imageName;
                    if(is_dir(UPLOADSPATH)){
                        if(move_uploaded_file($file["tmp_name"], $imagePath)){
                            $this->saveImage($id,$imageName);
                            return true;
                        } else {
                            $errorLog = 'Could not save file';
                            return $errorLog;
                        }
                    } else {
                        $errorLog = 'Directory doesnt exist :'.UPLOADSPATH;
                        return $errorLog;
                    }
                } else {
                    $errorLog = 'File is too big';
                    return $errorLog;
                }
            } else {
                $errorLog = 'Invalid type of file';
                return $errorLog;
            }
        }
    }
    
    public function edit($id, $news){
        return $this->db->update($this->table, $news, "id = '{$id}'");
    }

    public function deleteImage($id){
        $this->saveImage($id,"");
        return true;
    }

     public function getMyCount() {
        $data = $this->getAll();
        return count($data);
    }
}