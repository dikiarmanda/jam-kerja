<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\TagModel;
use App\Models\TimeEntryModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $projectModel  = new ProjectModel();
        $tagModel      = new TagModel();
        $timeEntryModel = new TimeEntryModel();

        $tagId = (int)$this->request->getGet('tag') ?: null;

        $data = [
            'projects'    => $projectModel->getProjectsWithStats($tagId),
            'tags'        => $tagModel->findAll(),
            'activeEntry' => $timeEntryModel->getActiveEntry(),
            'activeTag'   => $tagId,
        ];

        return view('dashboard/index', $data);
    }
}
