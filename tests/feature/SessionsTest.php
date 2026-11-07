<?php

namespace Tests\Feature;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\ProjectModel;
use App\Models\TimeEntryModel;

/**
 * @internal
 */
final class SessionsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    public function testSessionsIndexAndCreateFlow(): void
    {
        $projectModel = new ProjectModel();
        $projectId = $projectModel->insert([
            'name'        => 'Test Proyek A',
            'hourly_rate' => 100000,
            'status'      => 'active',
        ]);

        // GET /sessions
        $result = $this->get('/sessions');
        $result->assertStatus(200);
        $result->assertSee('Sesi Kerja');

        // GET /sessions/create
        $resultCreate = $this->get('/sessions/create?project_id=' . $projectId);
        $resultCreate->assertStatus(200);
        $resultCreate->assertSee('Catat Sesi Manual');

        // POST /sessions (Store manual session)
        $start = '2026-11-01 09:00:00';
        $end   = '2026-11-01 11:30:00'; // 2.5 hours = 9000 seconds
        $resultStore = $this->post('/sessions', [
            'project_id' => $projectId,
            'started_at' => '2026-11-01T09:00',
            'ended_at'   => '2026-11-01T11:30',
            'note'       => 'Pekerjaan manual tanpa timer',
        ]);
        $resultStore->assertRedirectTo('/projects/' . $projectId);

        $timeEntryModel = new TimeEntryModel();
        $entry = $timeEntryModel->where('project_id', $projectId)->first();
        $this->assertNotNull($entry);
        $this->assertSame(9000, (int)$entry['duration_seconds']);
        $this->assertSame('Pekerjaan manual tanpa timer', $entry['note']);

        // GET /sessions/id/edit
        $resultEdit = $this->get('/sessions/' . $entry['id'] . '/edit');
        $resultEdit->assertStatus(200);
        $resultEdit->assertSee('Edit sesi');

        // POST /sessions/id/update
        $resultUpdate = $this->post('/sessions/' . $entry['id'] . '/update', [
            'project_id' => $projectId,
            'started_at' => '2026-11-01T09:00',
            'ended_at'   => '2026-11-01T12:00', // 3 hours = 10800 seconds
            'note'       => 'Pekerjaan diperbarui',
        ]);
        $resultUpdate->assertRedirectTo('/projects/' . $projectId);

        $updatedEntry = $timeEntryModel->find($entry['id']);
        $this->assertSame(10800, (int)$updatedEntry['duration_seconds']);
        $this->assertSame('Pekerjaan diperbarui', $updatedEntry['note']);

        // POST /sessions/id/delete
        $resultDelete = $this->post('/sessions/' . $entry['id'] . '/delete');
        $this->assertNull($timeEntryModel->find($entry['id']));
    }

    public function testStoreManualSessionValidationEndBeforeStart(): void
    {
        $projectModel = new ProjectModel();
        $projectId = $projectModel->insert([
            'name'        => 'Test Proyek B',
            'hourly_rate' => 50000,
            'status'      => 'active',
        ]);

        $result = $this->post('/sessions', [
            'project_id' => $projectId,
            'started_at' => '2026-11-01T15:00',
            'ended_at'   => '2026-11-01T14:00',
            'note'       => 'Invalid time range',
        ]);

        $result->assertSessionHas('error', 'Waktu selesai harus setelah waktu mulai.');
    }
}
