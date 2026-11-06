<?php

namespace App\Controllers;

use App\Models\TagModel;

class Tags extends BaseController
{
    protected TagModel $tagModel;

    public function __construct()
    {
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        return view('tags/index', ['tags' => $this->tagModel->getAllWithProjectCount()]);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));
        if (!$name) return redirect()->to('/tags')->with('error', 'Nama tag wajib diisi.');

        $existing = $this->tagModel->where('name', $name)->first();
        if ($existing) return redirect()->to('/tags')->with('error', 'Tag sudah ada.');

        $this->tagModel->insert(['name' => $name]);
        return redirect()->to('/tags')->with('success', 'Tag berhasil ditambahkan.');
    }

    public function update(int $id)
    {
        $name = trim($this->request->getPost('name'));
        if (!$name) return redirect()->to('/tags')->with('error', 'Nama tag wajib diisi.');

        $existing = $this->tagModel->where('name', $name)->where('id !=', $id)->first();
        if ($existing) return redirect()->to('/tags')->with('error', 'Nama tag sudah dipakai.');

        $this->tagModel->update($id, ['name' => $name]);
        return redirect()->to('/tags')->with('success', 'Tag berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $this->tagModel->delete($id);
        return redirect()->to('/tags')->with('success', 'Tag berhasil dihapus.');
    }
}
