<?php

/**
 * Created by PhpStorm.
 * User: Shahnawaz
 * Date: 6/13/2016
 * Time: 10:25 AM
 */
class Receipt extends CI_Controller
{
    public function index()
    {
        $this->load->library('table');
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('collection');

        echo $this->table->generate($query);
    }

    public function json()
    {
        $this->db->order_by('date', 'asc');
        $query = $this->db->get('collection');
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode($query->result()));
    }

    public function update()
    {
        if ($_SESSION['auth']) {
            $id = $this->input->post('id');
            $amount = $this->input->post('amount');
            $desc = $this->input->post('desc');

            $data = array('amount' => $amount, 'description' => $desc);

            $this->db->where('_id', $id);
            $this->db->update('collection', $data);;
            echo '1';
        } else {
            $this->load->view('errors/authfailed');
        }
    }

    public function insert()
    {
        if ($_SESSION['auth']) {
            $amount = $this->input->post('amount');
            $desc = $this->input->post('desc');

            $sql = 'INSERT INTO collection (amount,description) VALUES(' . $this->db->escape($amount) . ','
                . $this->db->escape($desc) . ')';
            $this->db->query($sql);
            echo '1';
        } else {
            $this->load->view('errors/authfailed');
        }
    }

    public function delete($id)
    {
        if ($_SESSION['auth']) {
            $id = $this->db->escape($id);
            $sql = 'DELETE FROM collection WHERE _id = ' . $id;

            $this->db->query($sql);
            echo '1';
        } else {
            $this->load->view('errors/authfailed');
        }
    }
}