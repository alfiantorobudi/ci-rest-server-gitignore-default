<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';


// buat class mahasiswa 
class Mahasiswa extends CI_Controller
{
    use REST_Controller {
    REST_Controller::__construct as private __resTraitConstruct;
    }

    public function __construct()
    {
        parent::__construct();
        $this->__resTraitConstruct();
        $this->load->model('Mahasiswa_model', 'mahasiswa');
    }
    // index_get(), get adalah request method dari httpnya
    public function index_get()
    {
        $id = $this->get('id'); // cek direquest method get http, ada idnya atau engga

        // cek apabila tidak ada
        if ($id === null) {
            $mahasiswa = $this->mahasiswa->getMahasiswa(); //ambil mahasiswa semua
        } else {
            $mahasiswa = $this->mahasiswa->getMahasiswa($id);
        }

        // output keluaran buat menghasilkan json
        if ($mahasiswa) {
            $this->response([
                'status' => true,
                'data' => $mahasiswa
            ], 200); // 200 pesan 
        } else {
            // Set the response and exit
            $this->response([
                'status' => false,
                'message' => 'id not found'
            ], 404); // NOT_FOUND (404) being the HTTP response code
        }
    }

    // untuk request method hapus, delete
    public function index_delete()
    {
        $id = $this->delete('id');

        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id'
            ], 400); // 400 pesan
        } else {
            if ($this->mahasiswa->deleteMahasiswa($id) > 0) {
                $message = [
                    'status' => true,
                    'id' => $id,
                    'message' => 'Deleted the resource',
                ];

                $this->set_response($message, 200); // gak tau kenapa gak bisa make 204 (no content), gak tampil pesannya
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'id not found'
                ], 400); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function index_post()
    {
        $data = [
            'nrp' => $this->post('nrp'),
            'nama' => $this->post('nama'),
            'email' => $this->post('email'),
            'jurusan' => $this->post('jurusan'),
        ];

        if ($this->mahasiswa->createMahasiswa($data) > 0) {
            $this->response([
                'status' => true,
                'data' => 'new mahasiswa has been created'
            ], 201); // 200 pesan 
        } else {
            $this->response([
                'status' => false,
                'message' => 'failed to create new data'
            ], 400);
        }
    }

    public function index_put()
    {
        $id = $this->put('id');

        $data = [
            'nrp' => $this->put('nrp'),
            'nama' => $this->put('nama'),
            'email' => $this->put('email'),
            'jurusan' => $this->put('jurusan'),
        ];

        if ($this->mahasiswa->updateMahasiswa($data, $id) > 0) {
            $this->response([
                'status' => true,
                'data' => ' data mahasiswa has been updated'
            ], 200);
        } else {
            $this->response([
                'status' => false,
                'message' => 'failed to update data'
            ], 400);
        }
    }
}
