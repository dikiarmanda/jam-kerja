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

    public function index()
    {
        $projectId = (int)$this->request->getGet('project_id') ?: null;
        $projects  = $this->projectModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();
        $entries   = $this->timeEntryModel->getEntriesWithProject($projectId);

        return view('sessions/index', [
            'entries'           => $entries,
            'projects'          => $projects,
            'selectedProjectId' => $projectId,
        ]);
    }

    public function create(?int $projectId = null)
    {
        $projectId = $projectId ?: ((int)$this->request->getGet('project_id') ?: null);
        $projects  = $this->projectModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();

        if (empty($projects)) {
            return redirect()->to('/projects/create')->with('error', 'Buat proyek terlebih dahulu sebelum mencatat sesi.');
        }

        return view('sessions/create', [
            'projects'          => $projects,
            'selectedProjectId' => $projectId,
        ]);
    }

    public function store()
    {
        $projectId = (int)$this->request->getPost('project_id');
        $startedAt = $this->request->getPost('started_at');
        $endedAt   = $this->request->getPost('ended_at');
        $note      = trim((string)$this->request->getPost('note'));

        $project = $this->projectModel->find($projectId);
        if (!$project) {
            return redirect()->back()->withInput()->with('error', 'Pilih proyek yang valid.');
        }

        if (!$startedAt || !strtotime($startedAt)) {
            return redirect()->back()->withInput()->with('error', 'Waktu mulai wajib diisi.');
        }

        $formattedEndedAt = null;
        $duration         = null;

        if ($endedAt) {
            if (!strtotime($endedAt)) {
                return redirect()->back()->withInput()->with('error', 'Format waktu selesai tidak valid.');
            }
            if (strtotime($endedAt) <= strtotime($startedAt)) {
                return redirect()->back()->withInput()->with('error', 'Waktu selesai harus setelah waktu mulai.');
            }
            $duration         = strtotime($endedAt) - strtotime($startedAt);
            $formattedEndedAt = date('Y-m-d H:i:s', strtotime($endedAt));
        } else {
            $active = $this->timeEntryModel->getActiveEntry();
            if ($active) {
                return redirect()->back()->withInput()->with('error', 'Sudah ada timer aktif yang sedang berjalan. Mohon isi waktu selesai.');
            }
        }

        $this->timeEntryModel->insert([
            'project_id'       => $projectId,
            'started_at'       => date('Y-m-d H:i:s', strtotime($startedAt)),
            'ended_at'         => $formattedEndedAt,
            'duration_seconds' => $duration,
            'note'             => $note ?: null,
        ]);

        return redirect()->to('/projects/' . $projectId)->with('success', 'Sesi kerja manual berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $entry = $this->timeEntryModel->find($id);
        if (!$entry) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $projects = $this->projectModel->where('status', 'active')->orderBy('name', 'ASC')->findAll();
        $project  = $this->projectModel->find($entry['project_id']);

        return view('sessions/edit', [
            'entry'    => $entry,
            'project'  => $project,
            'projects' => $projects,
        ]);
    }

    public function update(int $id)
    {
        $entry = $this->timeEntryModel->find($id);
        if (!$entry) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $projectId = (int)($this->request->getPost('project_id') ?: $entry['project_id']);
        $project   = $this->projectModel->find($projectId);
        if (!$project) {
            return redirect()->back()->withInput()->with('error', 'Pilih proyek yang valid.');
        }

        $startedAt = $this->request->getPost('started_at');
        $endedAt   = $this->request->getPost('ended_at');
        $note      = trim((string)$this->request->getPost('note'));

        if (!$startedAt || !strtotime($startedAt)) {
            return redirect()->back()->withInput()->with('error', 'Waktu mulai wajib diisi.');
        }

        $formattedEndedAt = null;
        $duration         = null;

        if ($endedAt) {
            if (!strtotime($endedAt)) {
                return redirect()->back()->withInput()->with('error', 'Format waktu selesai tidak valid.');
            }
            if (strtotime($endedAt) <= strtotime($startedAt)) {
                return redirect()->back()->withInput()->with('error', 'Waktu selesai harus setelah waktu mulai.');
            }
            $duration         = strtotime($endedAt) - strtotime($startedAt);
            $formattedEndedAt = date('Y-m-d H:i:s', strtotime($endedAt));
        } else {
            $active = $this->timeEntryModel->where('id !=', $id)->where('ended_at IS NULL', null, false)->first();
            if ($active) {
                return redirect()->back()->withInput()->with('error', 'Sudah ada timer lain yang sedang berjalan.');
            }
        }

        $this->timeEntryModel->update($id, [
            'project_id'       => $projectId,
            'started_at'       => date('Y-m-d H:i:s', strtotime($startedAt)),
            'ended_at'         => $formattedEndedAt,
            'duration_seconds' => $duration,
            'note'             => $note ?: null,
        ]);

        return redirect()->to('/projects/' . $projectId)->with('success', 'Sesi berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $entry = $this->timeEntryModel->find($id);
        if (!$entry) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $projectId = $entry['project_id'];
        $this->timeEntryModel->delete($id);

        return redirect()->back()->with('success', 'Sesi berhasil dihapus.');
    }
}
