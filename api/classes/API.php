<?php


class API  
{ 
    /**
     * @var PDO
     * */
    protected $conn;


    public function __construct($conn, $id = '')
    {
        $this->conn = $conn;
        if (!empty($id)) {
            $this->id = $id;
        }
    }
    
    
    # BINDING ALL CLASSES FOR API INTERFACING
    
    public function login($data)
    {
        extract($data); 

        $query = 'SELECT id, name, remember_token as token, password FROM users WHERE phone = :phone';
        $query = $this->conn->prepare($query);
        $query->bindParam(':phone', $phone);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if(!$user || !password_verify($password, $user['password'])) {
            // Invalid credentials
            return false;
        }  else {
 
            return $user; 
        } 
    }
    
    public function getWarehousesWithBays($warehouseId) {
       
        $warehouseQuery = "SELECT * FROM warehouses WHERE warehouse_id = ?";
        $warehouseStmt = $pdo->prepare($warehouseQuery);
        $warehouseStmt->execute([$warehouseId]);
        $warehouses = $warehouseStmt->fetchAll(PDO::FETCH_ASSOC);
    
        $bayQuery = "SELECT * FROM bays WHERE warehouse_id = ?";
        $bayStmt = $pdo->prepare($bayQuery);
        $bayStmt->execute([$warehouseId]);
        $bays = $bayStmt->fetchAll(PDO::FETCH_ASSOC);
    
        $result = [];
        foreach ($warehouses as $warehouse) {
            $warehouse['bays'] = [];
            foreach ($bays as $bay) {
                if ($bay['warehouse_id'] === $warehouse['warehouse_id']) {
                    $warehouse['bays'][] = $bay;
                }
            }
            $result[] = $warehouse;
        }
    
        return $result;
    }

    
    public function get_bays_and_warehouse()
    {
        $query = "SELECT w.id as warehouse_id, 
                         w.name as warehouse,
                         b.id as warehouse_bay_id,
                         b.name as warehouse_bay_name
                         
                    FROM bays as b
                    
                  LEFT JOIN warehouses as w ON b.warehouse_id = w.id;
                 ";
        $query = $this->conn->prepare($query);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
  
    }
 
    
    public function get_warehouse()
    {
 
        $query = $this->conn->prepare('SELECT * FROM warehouses ');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
  
    }
 

    
    public function get_bays($wid)
    {
 
        $query = $this->conn->prepare('SELECT id,name,warehouse_id FROM warehouse_bays WHERE warehouse_id = :wid ');
        
        $query->bindParam(':wid', $wid);
         
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
  
    }
    
    public function get_owners()
    {
 
        $query = $this->conn->prepare('SELECT id, name FROM owners ');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
  
    }
    
    public function get_gardens($owner_id="")
    { 
        
       if(!empty($owner_id)){
        
            $statement = 'SELECT * FROM gardens WHERE gardens.owner_id =:owner';
            
            //'SELECT id, owner_id, name FROM gardens '
            $query = $this->conn->prepare($statement);
            
            $query->bindParam(':owner',$owner_id);
            
             $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        
        $statement = 'SELECT * FROM gardens';// RIGHT JOIN owners ON gardens.owner_id = owners.id ';
            
        //'SELECT id, owner_id, name FROM gardens '
        $query = $this->conn->prepare($statement);
        
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
        
  
    }
    

       
        
    
    
    public function get_grades()
    { 
        $query = $this->conn->prepare('SELECT id, name FROM grades ');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
  
    }
    
    
    
    public function get_packages()
    { 
        $query = $this->conn->prepare('SELECT id, name FROM packages');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
  
    }
    
    
    public function record_stock_entry($data){
     
        $query = 'INSERT INTO stocks  SET   user_id=:userid,
                                            warehouse_id=:warehouse,
                                            warehouse_bay_id=:bay,
                                            owner_id=:owner,
                                            garden_id=:garden,
                                            grade_id=:grade,
                                            package_id=:packageType,
                                            invoice=:invoice,
                                            qty=:packageNumber,
                                            year=:yearOfManufacture,
                                            remark=:remarks';
                                            
        // $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

        $query = $this->conn->prepare($query);
        
        
        $query->bindParam(':userid', $data['userid']);
        $query->bindParam(':warehouse', $data['warehouse']);
        $query->bindParam(':bay', $data['bay']);
        $query->bindParam(':owner', $data['owner']);
        $query->bindParam(':garden', $data['garden']);
        $query->bindParam(':grade', $data['grade']);
        $query->bindParam(':invoice', $data['invoice']);
        $query->bindParam(':packageType', $data['packageType']);
        $query->bindParam(':packageNumber', $data['packageNumber']);
        $query->bindParam(':yearOfManufacture', $data['yearOfManufacture']);
        $query->bindParam(':remarks', $data['remarks']);
        
        //print_r($query->errorInfo());
        return $query->execute();
           
        
    }
    
    public function update_stock_entry($data) {
 
        $query = 'UPDATE stocks SET warehouse_id=:warehouse,
                                    warehouse_bay_id=:bay,
                                    owner_id=:owner,
                                    garden_id=:garden,
                                    grade_id=:grade,
                                    package_id=:packageType,
                                    invoice=:invoice,
                                    qty=:packageNumber,
                                    year=:yearOfManufacture,
                                    remark=:remarks
                  WHERE id=:entryId';
    
        $query = $this->conn->prepare($query);
    
    
        $query->bindParam(':warehouse', $data['warehouse']);
        $query->bindParam(':bay', $data['bay']);
        $query->bindParam(':owner', $data['owner']);
        $query->bindParam(':garden', $data['garden']);
        $query->bindParam(':grade', $data['grade']);
        $query->bindParam(':invoice', $data['invoice']);
        $query->bindParam(':packageType', $data['packageType']);
        $query->bindParam(':packageNumber', $data['packageNumber']);
        $query->bindParam(':yearOfManufacture', $data['yearOfManufacture']);
        $query->bindParam(':remarks', $data['remarks']);
        $query->bindParam(':entryId', $data['update_entry']);
    
        return $query->execute();
    }
    
    
     
    public function get_entry_count($uid)
    {  
        $query = $this->conn->prepare(' SELECT count(DISTINCT garden_id) as gardens, count(id) as entries, sum(qty) as bags, created_at as timestamp 
                                            FROM stocks 
                                        WHERE user_id = :user_id 
                                            AND created_at >= DATE_FORMAT(NOW(), "%Y-%m-01")'
                                    );
        $query->bindParam(':user_id', $uid);
        $query->execute();
    
        return $query->fetch(PDO::FETCH_OBJ);
  
    }
    
    
     
    public function get_recent_entries($uid)
    {
        // $query = $this->conn->prepare('SELECT * FROM stocks WHERE user_id = :user_id AND created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)');
        
        
        $query = $this->conn->prepare(' SELECT s.*, g.name as garden_id 
                                           FROM stocks s 
                                        INNER JOIN gardens g ON s.garden_id = g.id 
                                           WHERE s.user_id = :user_id 
                                        AND s.created_at >= DATE_FORMAT(NOW(), "%Y-%m-01")'
                                    );
        $query->bindParam(':user_id', $uid);
        
        
        
        $query->bindParam(':user_id', $uid);
        $query->execute();
    
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_last_30days_entries($uid)
    {
        $query = $this->conn->prepare('SELECT * FROM stocks WHERE user_id = :user_id AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        $query->bindParam(':user_id', $uid);
        $query->execute();
    
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    
    

 
     
}










