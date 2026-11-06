<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TagModel;
use App\Models\TimeEntryModel;

class Projects extends BaseController
{
    protected ProjectModel $projectModel;
    protected TagModel $tagModel;
    protected TimeEntryModel $timeEntryModel;

    public function __construct()
    {
        $this->projectModel   = new ProjectModel();
        $this->tagModel       = new TagModel();
        $this->timeEntryModel = new TimeEntryModel();
    }

    public function create()
    {
        $data = ['tags' => $this->tagModel->findAll(), 'project' => null, 'errors' => []];
        return view('projects/form', $data);
    }

    public function store()
    {
        $rules = ['name' => 'required|min_length[1]|max_length[255]'];
        if (!$this->validate($rules)) {
            return view('projects/form', [
                'tags'    => $this->tagModel->findAll(),
                'project' => null,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $id = $this->projectModel->insert([
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'client'      => $this->request->getPost('client'),
            'hourly_rate' => (int)$this->request->getPost('hourly_rate'),
            'status'      => 'active',
        ]);

        $tagIds = $this->request->getPost('tags') ?? [];
        $this->projectModel->syncTags((int)$id, array_map('intval', $tagIds));

        return redirect()->to('/')->with('success', 'Proyek berhasil dibuat.');
    }

    public function edit(int $id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $project['tags'] = array_column($this->projectModel->getProjectTags($id), 'id');

        $data = ['tags' => $this->tagModel->findAll(), 'project' => $project, 'errors' => []];
        return view('projects/form', $data);
    }

    public function update(int $id)
    {
        $project = $this->projectModel->find($id);
        if (!$project) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $rules = ['name' => 'required|min_length[1]|max_length[255]'];
        if (!$this->validate($rules)) {
            $project['tags'] = array_column($this->projectModel->getProjectTags($id), 'id');
            return view('projects/form', [
                'tags'    => $this->tagModel->findAll(),
                'project' => $project,
                'errors'  => $this->validator->getErrors(),
            ]);
        }

        $this->projectModel->update($id, [
            'name'        => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'client'      => $this->request->getPost('client'),
            'hourly_rate' => (int)$this->request->getPost('hourly_rate'),
        ]);

        $tagIds = $this->request->getPost('tags') ?? [];
        $this->projectModel->syncTags($id, array_map('intval', $tagIds));

        return redirect()->to('/')->with('success', 'Proyek berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->projectModel->delete($id);
        return redirect()->to('/')->with('success', 'Proyek berhasil dihapus.');
    }

    public function show(int $id)
    {
        $project = $this->projectModel->getProjectWithDetails($id);
        if (!$project) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('projects/detail', ['project' => $project]);
    }
}
