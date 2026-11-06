<?php

namespace App\Controllers;

use App\Models\TimeEntryModel;
use App\Models\ProjectModel;

class Sessions extends BaseController
{
    protected TimeEntryModel $timeEntryModel;
    protected ProjectModel $projectModel;

    public function __construct()
    {
        $this->timeEntryModel = new TimeEntryModel();
        $this->projectModel   = new ProjectModel();
    }

    public function edit(int $id)
    {
        $entry = $this->timeEntryModel->find($id);
        if (!$entry) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $project = $this->projectModel->find($entry['project_id']);
        return view('sessions/edit', ['entry' => $entry, 'project' => $project]);
    }

    public function update(int $id)
    {
        $entry = $this->timeEntryModel->find($id);
        if (!$entry) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $startedAt = $this->request->getPost('started_at');
        $endedAt   = $this->request->getPost('ended_at');
        $note      = $this->request->getPost('note');

        if ($endedAt && strtotime($endedAt) <= strtotime($startedAt)) {
            return redirect()->back()->with('error', 'Waktu selesai harus setelah waktu mulai.');
        }

        $duration = null;
        if ($endedAt) {
            $duration = strtotime($endedAt) - strtotime($startedAt);
        }

        $this->timeEntryModel->update($id, [
            'started_at'       => $startedAt,
            'ended_at'         => $endedAt ?: null,
            'duration_seconds' => $duration,
            'note'             => $note,
        ]);

        return redirect()->to('/projects/' . $entry['project_id'])->with('success', 'Sesi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $entry = $this->timeEntryModel->find($id);
        if (!$entry) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $projectId = $entry['project_id'];
        $this->timeEntryModel->delete($id);

        return redirect()->to('/projects/' . $projectId)->with('success', 'Sesi berhasil dihapus.');
    }
}
