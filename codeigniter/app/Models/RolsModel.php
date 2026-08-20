<?php

namespace App\Models;

use CodeIgniter\Model;

class RolsModel extends Model
{
    public function getRols()
    {
        $sql = "SELECT  *
                FROM    Rol R
                ORDER BY R.descripcion ASC";

        $query = $this->db->query($sql);

        return $query;
    }

    public function getRolById($id)
    {
        $sql = "SELECT  *
                FROM    Rol R
                WHERE id = :id:";

        $query = $this->db->query($sql, ['id' => $id]);

        return $query;
    }

    public function newRol($rol)
    {
        $builder = $this->db->table('Rol');
        $builder->insert($rol);
    }

    public function deleteRol($rol)
    {
        $builder = $this->db->table('Rol');
        $builder->delete($rol);
    }

    public function saveRol($rol)
    {
        $builder = $this->db->table('Rol');
        $builder->where('id', $rol['id']);
        $builder->update($rol);
    }
}
