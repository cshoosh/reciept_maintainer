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
        $query = $this->db->get('wicollection');

        echo $this->table->generate($query);
    }

    public function json($credit)
    {
        $this->db->order_by('date', 'asc');
        $query = $this->db->get_where('wicollection', array('credit' => $credit));
        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($query->result()));
    }

    public function update()
    {
        $headers = apache_request_headers();
        if (isset($headers['Auth']) && $headers['Auth'] === 'eu3euQ81z0AwMxeHSb3d78L5TX83vkp3') {
            $id = $this->input->post('id');
            $amount = $this->input->post('amount');
            $desc = $this->input->post('desc');

            $data = array('amount' => $amount, 'description' => $desc);

            $this->db->where('_id', $id);
            $ret = $this->db->update('wicollection', $data);;
            echo $ret;
        } else {
            $this->load->view('errors/authfailed');
        }
    }

    public function insert()
    {
        $headers = apache_request_headers();
        if (isset($headers['Auth']) && $headers['Auth'] === 'eu3euQ81z0AwMxeHSb3d78L5TX83vkp3') {
            $amount = $this->input->post('amount');
            $desc = $this->input->post('desc');
            $iscredit = $this->input->post('credit');

            $sql = 'INSERT INTO wicollection (amount,credit,description) VALUES(' . $this->db->escape($amount) . ','
                . $this->db->escape($iscredit) . ',' . $this->db->escape($desc) . ')';
            $ret = $this->db->query($sql);
            echo $ret;
        } else {
            $this->load->view('errors/authfailed');
        }
    }

    public function delete($id)
    {
        $headers = apache_request_headers();
        if (isset($headers['Auth']) && $headers['Auth'] === 'eu3euQ81z0AwMxeHSb3d78L5TX83vkp3') {
            $id = $this->db->escape($id);
            $sql = 'DELETE FROM wicollection WHERE _id = ' . $id;

            $ret = $this->db->query($sql);
            echo $ret;
        } else {
            $this->load->view('errors/authfailed');
        }
    }

    public function calculate($type)
    {
        $sql = 'SELECT (sum_credit - sum_paid) AS diff, sum_credit, sum_paid FROM (SELECT sum(amount) AS sum_credit FROM wicollection
                WHERE credit = 0) AS amount1, (SELECT sum(amount) AS sum_paid FROM wicollection WHERE credit != 0) AS amount2';
        $result = $this->db->query($sql);
        if ($result) {
            $row = $result->row();
            if (isset($row)) {
                switch ($type) {
                    case 'all':
                        echo $row->diff . ',' . $row->sum_credit . ',' . $row->sum_paid;
                        break;
                    case 'json':
                        return $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($row));
                    case 'credit':
                        echo $row->sum_credit;
                        break;
                    case 'paid':
                        echo $row->sum_paid;
                        break;
                    case 'diff':
                        echo $row->diff;
                        break;
                }
            }
        } else {
            echo $result;
        }
    }
}