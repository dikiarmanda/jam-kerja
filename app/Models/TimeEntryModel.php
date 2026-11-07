<?php

namespace App\Models;

use CodeIgniter\Model;

class TimeEntryModel extends Model
{
    protected $table      = 'time_entries';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $allowedFields = ['project_id', 'started_at', 'ended_at', 'duration_seconds', 'note'];

    public function getActiveEntry(): ?array
    {
        return $this->where('ended_at IS NULL', null, false)->first();
    }

    public function getEntriesWithProject(?int $projectId = null): array
    {
        $builder = $this->db->table('time_entries te')
            ->select('te.*, p.name AS project_name, p.hourly_rate')
            ->join('projects p', 'p.id = te.project_id')
            ->orderBy('te.started_at', 'DESC');

        if ($projectId) {
            $builder->where('te.project_id', $projectId);
        }

        return $builder->get()->getResultArray();
    }

    public function stopEntry(int $id): void
    {
        $entry = $this->find($id);
        if (!$entry) return;

        $started   = strtotime($entry['started_at']);
        $ended     = time();
        $duration  = $ended - $started;
        $endedAt   = date('Y-m-d H:i:s', $ended);

        $this->update($id, [
            'ended_at'         => $endedAt,
            'duration_seconds' => $duration,
        ]);
    }
}
