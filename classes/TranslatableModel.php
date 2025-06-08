<?php
/**
 * TranslatableModel Class
 * 
 * This class provides a model for working with translatable content
 * It handles retrieving and saving content with their translations
 * 
 * @version 1.0
 */

class TranslatableModel {
  protected $db;
  protected $table;
  protected $translationTable;
  protected $translatableFields;
  protected $primaryKey;
  
  /**
   * Constructor
   * 
   * @param PDO $db Database connection
   * @param string $table Main table name
   * @param array $translatableFields Fields that can be translated
   * @param string $primaryKey Primary key field name (default: 'ID')
   */
  public function __construct($db, $table, $translatableFields = [], $primaryKey = 'ID') {
    $this->db = $db;
    $this->table = $table;
    $this->translationTable = $table . '_translations';
    $this->translatableFields = $translatableFields;
    $this->primaryKey = $primaryKey;
  }
  
  /**
   * Get item by ID with translations
   * 
   * @param int $id Item ID
   * @param string $lang Language code
   * @return array|null Item data with translations or null if not found
   */
  public function getById($id, $lang = null) {
    if ($lang === null) {
      global $lang;
    }
    
    // Get main content
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$item) return null;
    
    // Get translations
    if ($lang !== DEFAULT_LANG) {
      $translations = $this->getTranslations($id, $lang);
      
      // Merge translations with main content
      foreach ($this->translatableFields as $field) {
        if (isset($translations[$field])) {
          $item[$field] = $translations[$field];
        }
      }
    }
    
    return $item;
  }
  
  /**
   * Get multiple items with their translations
   * 
   * @param string $where WHERE clause
   * @param array $params Query parameters
   * @param string $orderBy ORDER BY clause
   * @param int $limit LIMIT clause
   * @param string $lang Language code
   * @return array List of items with translations
   */
  public function getItems($where = "", $params = [], $orderBy = "", $limit = 0, $lang = null) {
    if ($lang === null) {
      global $lang;
    }
    
    // Build query
    $sql = "SELECT * FROM {$this->table}";
    if (!empty($where)) {
      $sql .= " WHERE " . $where;
    }
    if (!empty($orderBy)) {
      $sql .= " ORDER BY " . $orderBy;
    }
    if ($limit > 0) {
      $sql .= " LIMIT " . (int)$limit;
    }
    
    // Execute query
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($items) || $lang === DEFAULT_LANG) {
      return $items;
    }
    
    // Get all IDs
    $ids = array_column($items, $this->primaryKey);
    
    // Get translations for all items at once
    $translationsForAll = $this->getTranslationsForMultipleItems($ids, $lang);
    
    // Apply translations to items
    foreach ($items as &$item) {
      $id = $item[$this->primaryKey];
      if (isset($translationsForAll[$id])) {
        foreach ($this->translatableFields as $field) {
          if (isset($translationsForAll[$id][$field])) {
            $item[$field] = $translationsForAll[$id][$field];
          }
        }
      }
    }
    
    return $items;
  }
  
  /**
   * Get translations for a single item
   * 
   * @param int $id Item ID
   * @param string $lang Language code
   * @return array Translations as field => translated_content
   */
  protected function getTranslations($id, $lang) {
    $sql = "SELECT field_name, translated_content FROM {$this->translationTable} 
            WHERE {$this->table}_id = ? AND language_code = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id, $lang]);
    $result = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    return $result ?: [];
  }
  
  /**
   * Get translations for multiple items in one query
   * 
   * @param array $ids Item IDs
   * @param string $lang Language code
   * @return array Translations as ID => field => translated_content
   */
  protected function getTranslationsForMultipleItems($ids, $lang) {
    if (empty($ids)) {
      return [];
    }
    
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT {$this->table}_id, field_name, translated_content 
            FROM {$this->translationTable} 
            WHERE {$this->table}_id IN ($placeholders) AND language_code = ?";
    
    $params = array_merge($ids, [$lang]);
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $translations = [];
    foreach ($result as $row) {
      $itemId = $row["{$this->table}_id"];
      $field = $row["field_name"];
      $value = $row["translated_content"];
      
      if (!isset($translations[$itemId])) {
        $translations[$itemId] = [];
      }
      
      $translations[$itemId][$field] = $value;
    }
    
    return $translations;
  }
  
  /**
   * Save item with translations
   * 
   * @param array $data Main item data
   * @param array $translations Translations as lang => field => value
   * @return int|bool Item ID on success, false on failure
   */
  public function save($data, $translations = []) {
    // Begin transaction
    $this->db->beginTransaction();
    
    try {
      // Extract translatable fields from the data
      $mainData = [];
      foreach ($data as $key => $value) {
        if (!in_array($key, $this->translatableFields) || $key === $this->primaryKey) {
          $mainData[$key] = $value;
        }
      }
      
      // Add or update main record
      if (isset($data[$this->primaryKey])) {
        // Update
        $id = $data[$this->primaryKey];
        $fields = [];
        $values = [];
        
        foreach ($mainData as $field => $value) {
          if ($field !== $this->primaryKey) {
            $fields[] = "$field = ?";
            $values[] = $value;
          }
        }
        
        if (!empty($fields)) {
          $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE {$this->primaryKey} = ?";
          $values[] = $id;
          
          $stmt = $this->db->prepare($sql);
          $stmt->execute($values);
        }
      } else {
        // Insert
        $fields = array_keys($mainData);
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($mainData));
        
        $id = $this->db->lastInsertId();
      }
      
      // Save translations
      if (!empty($translations) && !empty($id)) {
        foreach ($translations as $lang => $fields) {
          foreach ($fields as $field => $value) {
            if (in_array($field, $this->translatableFields)) {
              $this->saveTranslation($id, $lang, $field, $value);
            }
          }
        }
      }
      
      // Commit transaction
      $this->db->commit();
      return $id;
    } catch (Exception $e) {
      // Rollback transaction on error
      $this->db->rollBack();
      error_log("Error saving translatable content: " . $e->getMessage());
      return false;
    }
  }
  
  /**
   * Save translation for a field
   * 
   * @param int $id Item ID
   * @param string $lang Language code
   * @param string $field Field name
   * @param string $value Translated value
   */
  protected function saveTranslation($id, $lang, $field, $value) {
    // Check if translation exists
    $sql = "SELECT id FROM {$this->translationTable} 
            WHERE {$this->table}_id = ? AND language_code = ? AND field_name = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id, $lang, $field]);
    $exists = $stmt->fetchColumn();
    
    if ($exists) {
      // Update existing translation
      $sql = "UPDATE {$this->translationTable} SET translated_content = ? 
              WHERE {$this->table}_id = ? AND language_code = ? AND field_name = ?";
      $this->db->prepare($sql)->execute([$value, $id, $lang, $field]);
    } else {
      // Insert new translation
      $sql = "INSERT INTO {$this->translationTable} 
              ({$this->table}_id, language_code, field_name, translated_content) 
              VALUES (?, ?, ?, ?)";
      $this->db->prepare($sql)->execute([$id, $lang, $field, $value]);
    }
  }
  
  /**
   * Delete item and its translations
   * 
   * @param int $id Item ID
   * @return bool True on success, false on failure
   */
  public function delete($id) {
    $this->db->beginTransaction();
    
    try {
      // Delete translations first
      $sql = "DELETE FROM {$this->translationTable} WHERE {$this->table}_id = ?";
      $this->db->prepare($sql)->execute([$id]);
      
      // Delete main record
      $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
      $this->db->prepare($sql)->execute([$id]);
      
      $this->db->commit();
      return true;
    } catch (Exception $e) {
      $this->db->rollBack();
      error_log("Error deleting translatable content: " . $e->getMessage());
      return false;
    }
  }
}
?> 