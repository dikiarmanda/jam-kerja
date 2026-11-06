<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table      = 'tags';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];

    public function getAllWithProjectCount(): array
    {
        $db = \Config\Database::connect();
        return $db->table('tags t')
            ->select('t.*, COUNT(pt.project_id) AS project_count')
            ->join('project_tag pt', 'pt.tag_id = t.id', 'left')
            ->groupBy('t.id')
            ->orderBy('t.name', 'ASC')
            ->get()->getResultArray();
    }
}
