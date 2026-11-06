<?php

namespace App\Controllers;

use App\Models\TimeEntryModel;
use App\Models\ProjectModel;

class Timer extends BaseController
{
    protected TimeEntryModel $timeEntryModel;
    protected ProjectModel $projectModel;

    public function __construct()
    {
        $this->timeEntryModel = new TimeEntryModel();
        $this->projectModel   = new ProjectModel();
    }

    public function start(int $projectId)
    {
        $project = $this->projectModel->find($projectId);
        if (!$project) {
            return $this->response->setJSON(['error' => 'Proyek tidak ditemukan'])->setStatusCode(404);
        }

        $active = $this->timeEntryModel->getActiveEntry();
        if ($active) {
            $this->timeEntryModel->stopEntry((int)$active['id']);
        }

        $now = date('Y-m-d H:i:s');
        $entryId = $this->timeEntryModel->insert([
            'project_id' => $projectId,
            'started_at' => $now,
        ]);

        return $this->response->setJSON([
            'entry_id'   => $entryId,
            'started_at' => $now,
            'project_id' => $projectId,
        ]);
    }

    public function stop(int $entryId)
    {
        $entry = $this->timeEntryModel->find($entryId);
        if (!$entry || $entry['ended_at'] !== null) {
            return $this->response->setJSON(['error' => 'Sesi tidak ditemukan atau sudah berhenti'])->setStatusCode(400);
        }

        $this->timeEntryModel->stopEntry($entryId);
        $updated = $this->timeEntryModel->find($entryId);

        return $this->response->setJSON([
            'duration_seconds' => $updated['duration_seconds'],
        ]);
    }

    public function status()
    {
        $active = $this->timeEntryModel->getActiveEntry();
        if (!$active) {
            return $this->response->setJSON(['active' => false]);
        }

        $elapsed = time() - strtotime($active['started_at']);
        return $this->response->setJSON([
            'active'     => true,
            'entry_id'   => $active['id'],
            'project_id' => $active['project_id'],
            'started_at' => $active['started_at'],
            'elapsed'    => $elapsed,
        ]);
    }
}
