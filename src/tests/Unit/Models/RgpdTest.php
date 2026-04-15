<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\DatabaseTestCase;

class RgpdTest extends DatabaseTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    // ── EXPORT (droit d'accès / portabilité) ────────

    public function testExportDataContainsAllSections(): void
    {
        $id = $this->createTestUser(['username' => 'rgpd_user', 'email' => 'rgpd@test.com']);

        $data = $this->user->exportData($id);

        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('collections', $data);
        $this->assertArrayHasKey('messages', $data);
        $this->assertArrayHasKey('sessions', $data);
        $this->assertArrayHasKey('votes', $data);
    }

    public function testExportDataExcludesPasswordHash(): void
    {
        $id = $this->createTestUser();

        $data = $this->user->exportData($id);

        $this->assertArrayNotHasKey('password_hash', $data['user']);
    }

    public function testExportDataIncludesConsentTimestamp(): void
    {
        $consent = '2026-01-15 10:30:00';
        $id = $this->createTestUser(['consent_at' => $consent]);

        $data = $this->user->exportData($id);

        $this->assertNotNull($data['user']['consent_at']);
    }

    public function testExportDataIncludesRelatedRecords(): void
    {
        $userId = $this->createTestUser();
        $gameId = $this->createTestGame($userId);

        // Ajouter le jeu à la collection
        self::$pdo->prepare("INSERT INTO collections (user_id, game_id) VALUES (?, ?)")
            ->execute([$userId, $gameId]);

        $data = $this->user->exportData($userId);

        $this->assertCount(1, $data['collections']);
    }

    // ── SUPPRESSION (droit à l'effacement) ──────────

    public function testDeleteAccountRemovesUser(): void
    {
        $id = $this->createTestUser();

        $this->user->deleteAccount($id);

        $this->assertFalse($this->user->findById($id));
    }

    public function testDeleteAccountCascadesRelatedData(): void
    {
        $userId = $this->createTestUser();
        $gameId = $this->createTestGame($userId);

        // Ajouter des données liées
        self::$pdo->prepare("INSERT INTO collections (user_id, game_id) VALUES (?, ?)")
            ->execute([$userId, $gameId]);

        $this->user->deleteAccount($userId);

        // Vérifier que tout est supprimé en cascade
        $collections = self::$pdo->query("SELECT COUNT(*) FROM collections WHERE user_id = $userId")->fetchColumn();
        $games = self::$pdo->query("SELECT COUNT(*) FROM games WHERE added_by = $userId")->fetchColumn();

        $this->assertEquals(0, $collections);
        $this->assertEquals(0, $games);
    }

    // ── CONSENTEMENT ────────────────────────────────

    public function testUserCreatedWithConsentTimestamp(): void
    {
        $this->user->create([
            'username' => 'consented_user',
            'email' => 'consent@test.com',
            'password' => 'Password123!',
            'consent_at' => '2026-04-12 14:00:00',
        ]);

        $found = $this->user->findByEmail('consent@test.com');
        $this->assertNotNull($found['consent_at']);
    }

    public function testUserCanBeCreatedWithoutConsent(): void
    {
        // consent_at par défaut = date du jour dans User::create
        $this->user->create([
            'username' => 'auto_consent',
            'email' => 'auto@test.com',
            'password' => 'Password123!',
        ]);

        $found = $this->user->findByEmail('auto@test.com');
        $this->assertNotNull($found['consent_at']);
    }

    // ── MODIFICATION (droit de rectification) ───────

    public function testUpdateProfileAllowsRectification(): void
    {
        $id = $this->createTestUser(['username' => 'old_name', 'email' => 'old@test.com']);

        $this->user->updateProfile($id, [
            'username' => 'corrected_name',
            'email' => 'corrected@test.com',
        ]);

        $found = $this->user->findById($id);
        $this->assertSame('corrected_name', $found['username']);
        $this->assertSame('corrected@test.com', $found['email']);
    }
}
