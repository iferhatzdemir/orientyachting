<?php
class VT {
    private $db;
    private $last_error;

    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4",
                DBUSER,
                DBPASS,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $e) {
            $this->last_error = $e->getMessage();
            if(DEBUG) {
                error_log("Database connection error: " . $e->getMessage());
            }
            throw new Exception("Database connection failed.");
        }
    }

    public function VeriGetir($tablo, $where = "", $whereArray = array(), $orderBy = "", $limit = "") {
        try {
            $sql = "SELECT * FROM " . $tablo;
            
            if(!empty($where)) {
                $sql .= " " . $where;
            }
            
            if(!empty($orderBy)) {
                $sql .= " " . $orderBy;
            }
            
            if(!empty($limit)) {
                $sql .= " LIMIT " . $limit;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($whereArray);
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            $this->last_error = $e->getMessage();
            if(DEBUG) {
                error_log("Database query error in VeriGetir: " . $e->getMessage());
                error_log("SQL: " . $sql);
                error_log("Parameters: " . print_r($whereArray, true));
            }
            return false;
        }
    }

    public function SorguCalistir($sql, $params = array()) {
        try {
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute($params);
            
            if($result) {
                // For INSERT queries, return the last insert ID
                if(stripos($sql, 'INSERT') === 0) {
                    return $this->db->lastInsertId();
                }
                return true;
            }
            return false;
        } catch(PDOException $e) {
            $this->last_error = $e->getMessage();
            if(DEBUG) {
                error_log("Database query error in SorguCalistir: " . $e->getMessage());
                error_log("SQL: " . $sql);
                error_log("Parameters: " . print_r($params, true));
            }
            return false;
        }
    }

    public function getLastError() {
        return $this->last_error;
    }

    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    public function __destruct() {
        $this->db = null;
    }
}
?> 