<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table      = 'projects';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = ['name', 'description', 'client', 'hourly_rate', 'status'];

    public function getProjectsWithStats(?int $tagId = null): array
    {
        $db = \Config\Database::connect();

        $builder = $db->table('projects p')
            ->select('p.*, COALESCE(SUM(te.duration_seconds), 0) AS total_seconds_done')
            ->select('COALESCE((SELECT duration_seconds_running FROM (SELECT project_id, TIMESTAMPDIFF(SECOND, started_at, UTC_TIMESTAMP()) AS duration_seconds_running FROM time_entries WHERE ended_at IS NULL LIMIT 1) active WHERE active.project_id = p.id), 0) AS active_seconds', false)
            ->join('time_entries te', 'te.project_id = p.id AND te.ended_at IS NOT NULL', 'left')
            ->where('p.status', 'active')
            ->groupBy('p.id')
            ->orderBy('p.created_at', 'DESC');

        if ($tagId) {
            $builder->join('project_tag pt', 'pt.project_id = p.id')
                ->where('pt.tag_id', $tagId);
        }

        $projects = $builder->get()->getResultArray();

        foreach ($projects as &$project) {
            $project['total_seconds'] = (int)$project['total_seconds_done'] + (int)$project['active_seconds'];
            $project['tags'] = $this->getProjectTags((int)$project['id']);
        }

        return $projects;
    }

    public function getProjectWithDetails(int $id): ?array
    {
        $db = \Config\Database::connect();

        $project = $db->table('projects p')
            ->select('p.*')
            ->where('p.id', $id)
            ->get()->getRowArray();

        if (!$project) return null;

        $project['tags'] = $this->getProjectTags($id);
        $project['entries'] = $db->table('time_entries')
            ->where('project_id', $id)
            ->orderBy('started_at', 'DESC')
            ->get()->getResultArray();

        $totalDone = array_reduce($project['entries'], function ($carry, $e) {
            return $carry + (int)$e['duration_seconds'];
        }, 0);

        $activeEntry = null;
        foreach ($project['entries'] as $e) {
            if ($e['ended_at'] === null) {
                $activeEntry = $e;
                break;
            }
        }

        $activeSeconds = 0;
        if ($activeEntry) {
            $started = strtotime($activeEntry['started_at']);
            $activeSeconds = time() - $started;
        }

        $project['total_seconds'] = $totalDone + $activeSeconds;
        $project['active_entry'] = $activeEntry;

        return $project;
    }

    public function getProjectTags(int $projectId): array
    {
        $db = \Config\Database::connect();
        return $db->table('tags t')
            ->select('t.*')
            ->join('project_tag pt', 'pt.tag_id = t.id')
            ->where('pt.project_id', $projectId)
            ->get()->getResultArray();
    }

    public function syncTags(int $projectId, array $tagIds): void
    {
        $db = \Config\Database::connect();
        $db->table('project_tag')->where('project_id', $projectId)->delete();
        foreach ($tagIds as $tagId) {
            $db->table('project_tag')->insert(['project_id' => $projectId, 'tag_id' => $tagId]);
        }
    }

    public function getActiveTimer(): ?array
    {
        $db = \Config\Database::connect();
        return $db->table('time_entries te')
            ->select('te.*, p.name AS project_name')
            ->join('projects p', 'p.id = te.project_id')
            ->where('te.ended_at IS NULL', null, false)
            ->get()->getRowArray() ?: null;
    }
}
