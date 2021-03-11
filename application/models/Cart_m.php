<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cart_m extends CI_Model
{

    public function get($id = null)
    {
        $this->db->from('midtrans_payment');
        if ($id != null) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function getallkeranjang()
    {
        // $this->db->select('*, p_item.name as item_name, t_cart.price as cart_price');
        // $this->db->from('t_cart');
        // $this->db->join('p_item', 't_cart.item_id = p_item.item_id');
        // $query = $this->db->where('user_id', $this->session->userdata('userid'));
        // return $query->result_array();


        $this->db->select('*, p_item.name as item_name, t_cart.price as cart_price')
            ->from('t_cart')
            ->join('p_item', 't_cart.item_id = p_item.item_id')
            ->where('user_id', $this->session->userdata('userid'));
        $query = $this->db->get();
        return $query->result_array();
    }
}
