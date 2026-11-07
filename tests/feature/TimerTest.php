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
final class TimerTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate     = true;
    protected $migrateOnce = false;
    protected $refresh     = true;
    protected $namespace   = 'App';

    public function testStartAndStopTimerRedirectsOnFormSubmit(): void
    {
        $projectModel = new ProjectModel();
        $projectId = $projectModel->insert([
            'name'        => 'Proyek Uji Timer',
            'hourly_rate' => 150000,
            'status'      => 'active',
        ]);

        // Standard form submit should redirect back, not return JSON
        $resultStart = $this->withSession()->post('/timer/start/' . $projectId);
        $resultStart->assertStatus(302);
        $resultStart->assertSessionHas('success', 'Timer berhasil dimulai.');

        $timeEntryModel = new TimeEntryModel();
        $activeEntry = $timeEntryModel->getActiveEntry();
        $this->assertNotNull($activeEntry);
        $this->assertSame((int)$projectId, (int)$activeEntry['project_id']);

        // Standard stop submit should redirect back, not return JSON
        $resultStop = $this->withSession()->post('/timer/stop/' . $activeEntry['id']);
        $resultStop->assertStatus(302);
        $resultStop->assertSessionHas('success', 'Timer berhasil dihentikan.');

        $stoppedEntry = $timeEntryModel->find($activeEntry['id']);
        $this->assertNotNull($stoppedEntry['ended_at']);
    }

    public function testStartAndStopTimerReturnsJsonWhenRequested(): void
    {
        $projectModel = new ProjectModel();
        $projectId = $projectModel->insert([
            'name'        => 'Proyek API Timer',
            'hourly_rate' => 100000,
            'status'      => 'active',
        ]);

        // JSON / AJAX request
        $resultStart = $this->withHeaders(['Accept' => 'application/json'])
                            ->post('/timer/start/' . $projectId);
        $resultStart->assertStatus(200);
        $resultStart->assertJSONExact([
            'entry_id'   => 1,
            'started_at' => json_decode($resultStart->getJSON(), true)['started_at'],
            'project_id' => $projectId,
        ]);

        $entryId = json_decode($resultStart->getJSON(), true)['entry_id'];

        $resultStop = $this->withHeaders(['Accept' => 'application/json'])
                           ->post('/timer/stop/' . $entryId);
        $resultStop->assertStatus(200);
        $this->assertArrayHasKey('duration_seconds', json_decode($resultStop->getJSON(), true));
    }
}
