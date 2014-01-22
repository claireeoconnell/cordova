<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages_model extends MY_Model {
	/**
	 * Holds an array of tables used.
	 *
	 * @var array
	 */
  public $tables = array();

	public function __construct() {
		parent::__construct();
    $this->load->config('variation_database');

		//initialize db tables data
		$this->tables = $this->config->item('tables');
	}

  /**
   * Get All Version Info
   *
   * Returns all information for all versions of the database.
   *
   * @author Sean Ephraim
   * @access public
   * @return object All versioning info
   */
  public function get_all_version_info() {
    $query = $this->db
                  ->get($this->tables['versions']);
    return $query->result();
  }

  /**
   * Get Genes
   *
   * Get a list of all genes in the variation database.
   * A first letter may be provided to only get the
   * genes that start with that letter.
   *
   * @author Sean Ephraim
   * @access public
   * @param char First letter of the gene
   * @return array Gene names
   */
  public function get_genes($f_letter = NULL) {
    $vd_live = $this->tables['vd_live'];
    $vd_queue = $this->tables['vd_queue'];
    // Only get genes of a certain letter
    if ($f_letter) {
      $this->db->like('gene', $f_letter, 'after');
    }

    $query = $this->db->distinct()
                      ->select('gene')
                      ->get($this->tables['vd_live']);

    // Build array of gene names from result
    $genes = array();
    foreach ($query->result() as $row) {
      if ( ! empty($row->gene)) {
        $genes[] = $row->gene;
      }
    }

    // Include genes in the queue as well
    if ($f_letter) {
      $this->db->like('gene', $f_letter, 'after');
    }
    $query = $this->db->distinct()
                      ->select('gene')
                      ->get($this->tables['vd_queue']);

    foreach ($query->result() as $row) {
      if ( ! empty($row->gene)) {
        $genes[] = $row->gene;
      }
    }

    $genes = array_unique($genes);
    sort($genes);
    return $genes;
  }
}

/* End of file genes_model.php */
/* Location: ./application/models/genes_model.php */