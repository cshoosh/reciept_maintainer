<?php

/**
 * Created by PhpStorm.
 * User: Shahnawaz
 * Date: 6/13/2016
 * Time: 10:25 AM
 */
class View extends CI_Controller
{
    public function index()
    {
        $this->load->library('table');
        $query = $this->db->get('collection');
        echo $this->table->generate($query);
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

    public
    function delete($id)
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