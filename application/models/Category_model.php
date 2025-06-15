<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_apostolic_categories() {
        return $this->db->get('apostolic_category')->result();
    }

    public function get_departments() {
        $this->db->where('category_id', 3);
    return $this->db->get('sub_category')->result();
    // return $query->result_array();
    }


    public function get_sub_categories_by_ids($ids) {
        $this->db->where_in('id', $ids);
        return $this->db->get('sub_category')->result();
    }
 // Get all age categories
    public function get_all_age_categories() {
        $this->db->where('category_id', 1); 
        $query = $this->db->get('sub_category');
        return $query->result();
    }
}