<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUser extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'idUser';
    protected $allowedFields    = [
        'kodeUser', 'namaUser', 'telpUser', 'password', 'akses'
    ];

    public function check_login($username, $password)
    {
        return $this->db->table('users')->where([
            'namaUser' => $username,
            'password' => $password
        ])->get()->getRowArray();
    }
}
