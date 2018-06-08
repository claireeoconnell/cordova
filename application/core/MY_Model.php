<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

  public $version;
  public $versionId;

  /**
   * @ignore
   */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
    $this->load->config('variation_database');

    // Initialize database tables (if they don't exist)
    if ( ! $this->db->table_exists('versions')) {
      $this->load->library('migration');
      if ( ! $this->migration->latest())
      {
        show_error($this->migration->error_string());
      }
      $html = 'Database tables have been intialized.';
      $this->session->set_flashdata('success', $html);
      redirect($this->uri->uri_string()); // reload the page
    }

		// Database version number
		$this->version = $this->get_db_version_num();

    // Set the appropriate production tables (latest version)
    $this->set_vd_live_table();
    $this->set_vd_queue_table();
    $this->set_reviews_table();
    $this->set_variant_count_table();
	}

  /**
   * Get DB Version
   *
   * Returns the id  number for the latest database if
   * $config['vd_version'] is set to 0. Otherwise the specified
   * version will be used. This is necessary because the version
   * does not accurately reflect the version of database implemented
   *
   * For point releases, the name of a table will be like 'dvd_2_1',
   * not 'dvd_2.1'. Therefore, this function returns the underscored 
   * format (e.g. 'dvd_2_1') by default. To return the point format
   * (e.g. 'dvd_2.1'), specify the first parameter to be TRUE.
   *
   *This has been templated from Sean's original get_db_version function.
   *Instead of returning the version field, as Sean's function does, this
   *function instead returns the id. This id is more repesentative of the
   *database's version.
   *
   * @author Rob Marini
   * @access public
   * @param boolean $use_point
   *    Return a point (.) format instead of a underscore (_) format
   * @return int
   *    Correct version id of the variation database
   */

  public function get_db_version($use_point = FALSE) {
    $versionId = $this->config->item("vd_version");
    if($versionId === 0) {
      //Automatically determine latest versionId
      $tables = $this->config->item("tables");
      $query = $this->db
		    ->select('version')
		    ->limit(1)
		    ->order_by("updated","desc")
		    ->get($tables['versions']);
      
      $versionId = (string) $query->row()->version;
      if ( $use_point ) {
		// Use underscore instead of point
		$versionId = str_replace('_','.', $versionId);
	   }
	   
      return $versionId;
    }
    else {
      $html = 'WARNING: your database has not been configured to automatically use the latest version. Until this is hcanged, you may be useing an older version of the database.';
      $this->session->set_flashdata('warning', $html);
      if ( ! $use_point) {
	// Use underscore instead of point
	$versionId = str_replace('_','.', $versionId);
      }
      return $versionId;
    }
  }

  /**
   * Get DB Version Num
   *
   * Returns the version number for the latest database if
   * $config['vd_version'] is set to 0. Otherwise the specified
   * version will be used.
   *
   * For point releases, the name of a table will be like 'dvd_2_1',
   * not 'dvd_2.1'. Therefore, this function returns the underscored 
   * format (e.g. 'dvd_2_1') by default. To return the point format
   * (e.g. 'dvd_2.1'), specify the first parameter to be TRUE.
   *
   * @author Sean Ephraim
   * @access public
   * @param boolean $use_point
   *    Return a point (.) format instead of a underscore (_) format
   * @return int
   *    Correct version of the variation database
   */
  public function get_db_version_num($use_point = FALSE) {
    $version = $this->config->item("vd_version");
    if ($version === 0) {
      
      // Automatically determine latest version and versionId
      $tables = $this->config->item("tables");

      $query = $this->db
                    ->select_max('version') //id; //version
                    ->limit(1)
                    ->get($tables['versions']);
      $version = $query->row()->version; //id; //version;      
      
      if ( ! $use_point) {
        // Use underscore instead of point
        $version = str_replace('.', '_', $version);
      }
      
      return $version;
    }
    else {
      $html = 'WARNING: your database has not been configured to automatically use the latest version. Until this is changed, you may be using an older version of the database.';
      $this->session->set_flashdata('warning', $html);
      if ( ! $use_point) {
        // Use underscore instead of point
        $version = str_replace('.', '_', $version);
      }
      return $version;
    }
  }

  /**
   * Get All DB Version Info
   *
   * Returns the versioning info for the variation database.
   *
   * @author Sean Ephraim
   * @access public
   * @return object
   *    All DB version info
   */
  public function get_all_db_version_info() {
    $tables = $this->config->item("tables");
    $query = $this->db
                  ->get($tables['versions']);
    return $query->result();
  }

  /**
   * Set VD Live Table
   *
   * Sets the proper name of the variations table by determining the
   * latest version of the database.
   *
   * Read more about it in application/config/variation_database.php.
   *
   * @author Sean Ephraim
   * @access public
   * @return void
   */
  public function set_vd_live_table() {
    // Load table name from config file
    $tables = $this->config->item("tables");
    $vd_live = $tables['vd_live'];
    $vd_prefix = $this->config->item("vd_prefix");

    $default = 'variations'; // default table name

    if ($vd_live == $default || $vd_live == '') {
      // Update the table name
      $tables['vd_live'] = $default.'_'.$this->version;
      $this->config->set_item('tables', $tables);
    }
  }

  /**
   * Set VD Queue Table
   *
   * Sets the proper name of the variations queue table by determining the
   * latest version of the database.
   *
   * Read more about it in application/config/variation_database.php.
   *
   * @author Sean Ephraim
   * @access public
   * @return void
   */
  public function set_vd_queue_table() {
    // Load table name from config file
    $tables = $this->config->item("tables");
    $vd_queue = $tables['vd_queue'];
    $vd_prefix = $this->config->item("vd_prefix");

    $default = 'variations_queue'; // default table name

    if ($vd_queue == $default || $vd_queue == '') {
      // Update the table name
      $tables['vd_queue'] = $default.'_'.$this->version;
      $this->config->set_item('tables', $tables);
    }
  }

  /**
   * Set Reviews Table
   *
   * Sets the proper name for the variant reviews table by determining the
   * latest version of the database.
   *
   * Read more about it in application/config/variation_database.php.
   *
   * @author Sean Ephraim
   * @access public
   * @return void
   */
  public function set_reviews_table() {
    // Load table name from config file
    $tables = $this->config->item("tables");
    $table = $tables['reviews'];

    if ($table == 'reviews' || $table == '') {
      // Update the table name
      $tables['reviews'] = 'reviews_'.$this->version;
      $this->config->set_item('tables', $tables);
    }
  }

  /**
   * Set Variant Count Table
   *
   * Sets the proper name for the variant count table by determining the
   * latest version of the database.
   *
   * Read more about it in application/config/variation_database.php.
   *
   * @author Sean Ephraim
   * @access public
   * @return void
   */
  public function set_variant_count_table() {
    // Load table name from config file
    $tables = $this->config->item("tables");
    $table = $tables['variant_count'];

    if ($table == 'variant_count' || $table == '') {
      // Update the table name
      $tables['variant_count'] = 'variant_count_'.$this->version;
      $this->config->set_item('tables', $tables);
    }
  }

  /**
   * Get Last Update Date
   *
   * Returns the correct date for when the database was last updated.
   *
   * @author Sean Ephraim
   * @access public
   * @return void
   */
  public function get_last_update_date() {
    $tables = $this->config->item("tables");
    $version = $this->get_db_version_num(TRUE);
    $query = $this->db
                  ->get_where($tables['versions'], array('version' => $version), 1); //id; //version;
    return $query->row()->updated;
  }

  /**
   * Get New Version Name
   *
   * Returns the name of the next version of a table based on
   * the current name.
   *
   * For example:
   *    if current name = 'dvd_1'
   *    then next name  = 'dvd_2'
   *
   * The latest version number is computed automatically. However,
   * you can specify your own latest version using the second
   * parameter.
   *
   * @author Sean Ephraim
   * @access public
   * @param string $cur_name
   *    Current name of table
   * @param int
   *    $latest_version Latest version number
   * @return string
   *    Next version of a table name
   */
  public function get_new_version_name($cur_name, $latest_version = NULL) {
    if ($latest_version === NULL) {
      // Get latest version
      $tables = $this->config->item("tables");
      $query = $this->db
                    ->select_max('version')
                    ->limit(1)
                    ->get($tables['versions']);
      $latest_version = $query->row()->version;
    }
    // Convert versions such as 2.1 or 2_1 to just 2
    $latest_version = (int) str_replace('_', '.', $latest_version);
    // take the name apart
    $parts = explode('_', $cur_name);
    // remove the last part(s) [the number(s)]
    while (is_numeric(end($parts))) {
      array_pop($parts);
    }
    // increment latest version num. and put it back on
    array_push($parts, $latest_version+1);
    // put the name back together
    $next_name = join('_', $parts);
    return $next_name;
  }

  /**
   * Get IDs Within Range
   *
   * Returns an array of IDs within a range of rows
   * in the database table. This is good for pagination.
   *
   * For example, if you specify your range as
   *   get_ids_within_range($this->tables['vd_queue'], 0, 10)
   * You will get the variant IDs for the first 10 rows of the
   * queue table (rows 0-9).
   *
   * @author Sean Ephraim
   * @param string $table
   *    Database table name
   * @param int $start_pos
   *    Starting row
   * @param int $limit
   *    (optional) Number of rows total (from/including the starting row)
   * @param boolean $full_records
   *    (optional) If TRUE, returns full records instead of just the ID
   * @return array
   *    Array of unique IDs (default); or array of record objects
   */
  public function get_ids_within_range($table, $start_pos, $limit = 1, $full_records = FALSE) {
    $columns = ($full_records) ? '*' : 'id';
    $result = $this->db->select($columns)
                       ->get($table, $limit, $start_pos)
                       ->result();
    // Create a nice array from the result
    $records = array();
    foreach ($result as $row) {
      $item = ($full_records) ? $row : $row->id;
      array_push($records, $item);
    }
    return $records;
  }

  /**
   * Get All Current Values
   *
   * Get a list of all values currently stored in the
   * database for a specific field.
   *
   * @author Sean Ephraim
   * @access public
   * @param  string $field
   *    Field name
   * @param  string $table
   *    (optional) Table name
   * @return array
   *    Current values in the DB
   */
  public function get_all_current_values($field, $table = NULL) {
    if ($table === NULL) {
      $table = $this->tables['vd_live'];
    }
    $query = $this->db->distinct()
                      ->select($field)
                      ->get($table);
    $values = array();
    // Extract just the field values from the query
    foreach ($query->result_array() as $key => $value) {
      $values[] = $value[$field];
    }
    return $values;
  }

  /**
   * Copy Table
   *
   * Makes an exact copy of a table (structure and data).
   * To only copy the structure (and not the data), set the
   * 3rd parameter to FALSE.
   *
   * @author Sean Ephraim
   * @access public
   * @param  string   $source
   *    Name of the source table
   * @param  string   $target
   *    Name of the target (new) table
   * @param  boolean  $include_data
   *    (optional) Copy the data into the new table
   * @return boolean
   *    TRUE on success, else FALSE
   */
  public function copy_table($source, $target, $include_data = TRUE) {
    if ($this->db->table_exists($target)) {
      return FALSE;     
    }

    // Sanitize input
    $source = mysql_real_escape_string(stripslashes($source));
    $target = mysql_real_escape_string(stripslashes($target));
    // Copy table structure
    $this->db->query("CREATE TABLE $target LIKE $source");
    // Copy table data
    if ($include_data) {
      $this->db->query("INSERT INTO $target SELECT * FROM $source");
    }
    return TRUE;
  }

  /**
   * Empty Table
   *
   * Remove all data from a table, leaving structure intact.
   *
   * @author Sean Ephraim
   * @access public
   * @param string $table
   *    Name of the source table
   * @return void
   */
  public function empty_table($table) {
    $this->db->empty_table($table);
  }
}

/* End of file MY_Model.php */
/* Location: ./application/core/MY_Model.php */
