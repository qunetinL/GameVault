<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\DatabaseTestCase;

class UserModelTest extends DatabaseTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = new User();
    }

    // ── CREATE ──────────────────────────────────────

    public function testCreateUserReturnsStatement(): void
    {
        $result = $this->user->create([
            'username' => 'john',
            'email' => 'john@example.com',
            'password' => 'Password123!',
        ]);

        $this->assertNotFalse($result);
    }

    public function testCreateUserHashesPassword(): void
    {
        $this->user->create([
            'username' => 'john',
            'email' => 'john@example.com',
            'password' => 'Password123!',
        ]);

        $row = self::$pdo->query("SELECT password_hash FROM users WHERE username = 'john'")->fetch();
        $this->assertTrue(password_verify('Password123!', $row['password_hash']));
    }

    public function testCreateUserDefaultRoleIsUser(): void
    {
        $this->user->create([
            'username' => 'john',
            'email' => 'john@example.com',
            'password' => 'Password123!',
        ]);

        $row = self::$pdo->query("SELECT role FROM users WHERE username = 'john'")->fetch();
        $this->assertSame('user', $row['role']);
    }

    public function testCreateUserWithAdminRole(): void
    {
        $this->user->create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'Admin123!',
            'role' => 'admin',
        ]);

        $row = self::$pdo->query("SELECT role FROM users WHERE username = 'admin'")->fetch();
        $this->assertSame('admin', $row['role']);
    }

    // ── FIND ────────────────────────────────────────

    public function testFindByEmailReturnsUser(): void
    {
        $this->createTestUser(['username' => 'alice', 'email' => 'alice@test.com']);

        $found = $this->user->findByEmail('alice@test.com');

        $this->assertNotFalse($found);
        $this->assertSame('alice', $found['username']);
    }

    public function testFindByEmailReturnsFalseWhenNotFound(): void
    {
        $result = $this->user->findByEmail('nobody@test.com');
        $this->assertFalse($result);
    }

    public function testFindByUsernameReturnsUser(): void
    {
        $this->createTestUser(['username' => 'bob', 'email' => 'bob@test.com']);

        $found = $this->user->findByUsername('bob');

        $this->assertNotFalse($found);
        $this->assertSame('bob@test.com', $found['email']);
    }

    public function testFindByIdReturnsUser(): void
    {
        $id = $this->createTestUser(['username' => 'charlie']);

        $found = $this->user->findById($id);

        $this->assertNotFalse($found);
        $this->assertSame('charlie', $found['username']);
    }

    public function testFindAllReturnsAllUsers(): void
    {
        $this->createTestUser(['username' => 'user1', 'email' => 'u1@test.com']);
        $this->createTestUser(['username' => 'user2', 'email' => 'u2@test.com']);

        $all = $this->user->findAll();

        $this->assertCount(2, $all);
    }

    // ── EMAIL VERIFICATION ──────────────────────────

    public function testEmailTokenFlow(): void
    {
        $id = $this->createTestUser();
        $token = bin2hex(random_bytes(32));

        $this->user->updateEmailToken($id, $token);

        $found = $this->user->findByEmailToken($token);
        $this->assertNotFalse($found);
        $this->assertEquals($id, $found['id']);
    }

    public function testVerifyEmailClearsToken(): void
    {
        $id = $this->createTestUser();
        $token = bin2hex(random_bytes(32));

        $this->user->updateEmailToken($id, $token);
        $this->user->verifyEmail($id);

        $found = $this->user->findByEmailToken($token);
        $this->assertFalse($found);

        $row = self::$pdo->query("SELECT email_verified_at FROM users WHERE id = $id")->fetch();
        $this->assertNotNull($row['email_verified_at']);
    }

    // ── RESET PASSWORD ──────────────────────────────

    public function testSetResetTokenAndFind(): void
    {
        $id = $this->createTestUser();
        $token = bin2hex(random_bytes(32));

        $this->user->setResetToken($id, $token);

        $found = $this->user->findByResetToken($token);
        $this->assertNotFalse($found);
        $this->assertEquals($id, $found['id']);
    }

    public function testResetPasswordClearsToken(): void
    {
        $id = $this->createTestUser();
        $token = bin2hex(random_bytes(32));

        $this->user->setResetToken($id, $token);
        $this->user->resetPassword($id, 'NewPassword456!');

        // Token should no longer be findable
        $found = $this->user->findByResetToken($token);
        $this->assertFalse($found);

        // New password should work
        $row = self::$pdo->query("SELECT password_hash FROM users WHERE id = $id")->fetch();
        $this->assertTrue(password_verify('NewPassword456!', $row['password_hash']));
    }

    // ── UPDATE ──────────────────────────────────────

    public function testUpdateProfile(): void
    {
        $id = $this->createTestUser(['username' => 'old_name', 'email' => 'old@test.com']);

        $this->user->updateProfile($id, [
            'username' => 'new_name',
            'email' => 'new@test.com',
        ]);

        $found = $this->user->findById($id);
        $this->assertSame('new_name', $found['username']);
        $this->assertSame('new@test.com', $found['email']);
    }

    public function testUpdateStatus(): void
    {
        $id = $this->createTestUser();

        $this->user->updateStatus($id, 'banned');

        $found = $this->user->findById($id);
        $this->assertSame('banned', $found['status']);
    }

    public function testUpdateRole(): void
    {
        $id = $this->createTestUser();

        $this->user->updateRole($id, 'admin');

        $found = $this->user->findById($id);
        $this->assertSame('admin', $found['role']);
    }

    // ── DELETE ───────────────────────────────────────

    public function testDeleteAccount(): void
    {
        $id = $this->createTestUser();

        $this->user->deleteAccount($id);

        $found = $this->user->findById($id);
        $this->assertFalse($found);
    }

    // ── STATS ───────────────────────────────────────

    public function testGetStatsReturnsZerosForNewUser(): void
    {
        $id = $this->createTestUser();

        $stats = $this->user->getStats($id);

        $this->assertEquals(0, $stats['collection_count']);
        $this->assertEquals(0, $stats['contributions_count']);
        $this->assertEquals(0, $stats['sessions_count']);
        $this->assertEquals(0, $stats['messages_count']);
        $this->assertEquals(0, $stats['invitations_count']);
    }

    public function testGetStatsCountsContributions(): void
    {
        $id = $this->createTestUser();
        $this->createTestGame($id);
        $this->createTestGame($id);

        $stats = $this->user->getStats($id);

        $this->assertEquals(2, $stats['contributions_count']);
    }

    public function testGetStatsCountsCollection(): void
    {
        $userId = $this->createTestUser();
        $gameId = $this->createTestGame($userId);

        self::$pdo->prepare("INSERT INTO collections (user_id, game_id) VALUES (?, ?)")
            ->execute([$userId, $gameId]);

        $stats = $this->user->getStats($userId);
        $this->assertEquals(1, $stats['collection_count']);
    }

    public function testGetGlobalStats(): void
    {
        $id = $this->createTestUser();
        $this->createTestGame($id);

        $stats = $this->user->getGlobalStats();

        $this->assertEquals(1, $stats['total_users']);
        $this->assertEquals(1, $stats['total_games']);
    }

    // ── EXPORT DATA (RGPD) ──────────────────────────

    public function testExportDataReturnsUserInfo(): void
    {
        $id = $this->createTestUser(['username' => 'exportme']);

        $data = $this->user->exportData($id);

        $this->assertSame('exportme', $data['user']['username']);
        $this->assertIsArray($data['collections']);
        $this->assertIsArray($data['messages']);
        $this->assertIsArray($data['sessions']);
        $this->assertIsArray($data['votes']);
    }
}
