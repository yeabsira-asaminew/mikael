<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Academic_model extends CI_Model
{
    public function get_categories()
    {
        return $this->db->get('category')->result();
    }

    public function get_sub_categories_by_category($category_id)
    {
        $this->db->where('category_id', $category_id);
        return $this->db->get('sub_category')->result();
    }

    public function get_sub_categories_for_schedule($schedule_id)
    {
        $this->db->select('sub_category.id, sub_category.name');
        $this->db->from('sub_category_schedule');
        $this->db->join('sub_category', 'sub_category_schedule.sub_category_id = sub_category.id');
        $this->db->where('sub_category_schedule.schedule_id', $schedule_id);
        return $this->db->get()->result_array();
    }

    public function get_departments()
    {
        return $this->db->get('department')->result_array();
    }

    public function get_schedules()
    {
        $this->db->select('schedule.id, schedule.day, schedule.time, schedule.description, GROUP_CONCAT(sub_category.name SEPARATOR ", ") as sub_categories');
        $this->db->from('schedule');
        $this->db->join('sub_category_schedule', 'schedule.id = sub_category_schedule.schedule_id', 'left');
        $this->db->join('sub_category', 'sub_category_schedule.sub_category_id = sub_category.id', 'left');
        $this->db->group_by('schedule.id');
        return $this->db->get()->result_array();
    }

    public function add_schedule($data, $sub_categories)
    {
        $this->db->trans_start();
        
        $this->db->insert('schedule', $data);
        $schedule_id = $this->db->insert_id();
        
        foreach ($sub_categories as $sub_category_id) {
            $this->db->insert('sub_category_schedule', [
                'schedule_id' => $schedule_id,
                'sub_category_id' => $sub_category_id
            ]);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function update_schedule($id, $data, $sub_categories)
    {
        $this->db->trans_start();
        
        $this->db->where('id', $id)->update('schedule', $data);
        
        $this->db->where('schedule_id', $id)->delete('sub_category_schedule');
        
        foreach ($sub_categories as $sub_category_id) {
            $this->db->insert('sub_category_schedule', [
                'schedule_id' => $id,
                'sub_category_id' => $sub_category_id
            ]);
        }
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    public function delete_schedule($id)
    {
        $this->db->trans_start();
        
        $this->db->where('schedule_id', $id)->delete('sub_category_schedule');
        $this->db->where('id', $id)->delete('schedule');
        
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }


}


