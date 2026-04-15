<?php

namespace Tests\Unit\Models;

use App\Models\Game;
use Tests\DatabaseTestCase;

class GameModelTest extends DatabaseTestCase
{
    private Game $game;
    private int $userId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->game = new Game();
        $this->userId = $this->createTestUser(['username' => 'gamer', 'email' => 'gamer@test.com']);
    }

    // ── CREATE ──────────────────────────────────────

    public function testCreateReturnsInsertId(): void
    {
        $id = $this->game->create([
            'title' => 'Zelda TOTK',
            'description' => 'Open world adventure',
            'release_date' => '2023-05-12',
            'rating' => 9.5,
            'added_by' => $this->userId,
        ]);

        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, (int) $id);
    }

    public function testCreateWithMinimalData(): void
    {
        $id = $this->game->create([
            'title' => 'Minimal Game',
            'added_by' => $this->userId,
        ]);

        $row = self::$pdo->query("SELECT * FROM games WHERE id = $id")->fetch();
        $this->assertSame('Minimal Game', $row['title']);
        $this->assertNull($row['description']);
        $this->assertEquals(0.0, (float) $row['rating']);
    }

    // ── FIND ────────────────────────────────────────

    public function testFindReturnsGameWithJoins(): void
    {
        $gameId = $this->createTestGame($this->userId, ['title' => 'Portal 2']);

        $found = $this->game->find($gameId);

        $this->assertNotFalse($found);
        $this->assertSame('Portal 2', $found['title']);
        $this->assertArrayHasKey('tags', $found);
        $this->assertArrayHasKey('platforms', $found);
    }

    public function testFindReturnsFalseForMissingId(): void
    {
        $found = $this->game->find(99999);
        $this->assertFalse($found);
    }

    public function testFindAllReturnsGames(): void
    {
        $this->createTestGame($this->userId, ['title' => 'Game A']);
        $this->createTestGame($this->userId, ['title' => 'Game B']);

        $all = $this->game->findAll();

        $this->assertCount(2, $all);
    }

    public function testFindAllWithLimitAndOffset(): void
    {
        $this->createTestGame($this->userId, ['title' => 'Game 1']);
        $this->createTestGame($this->userId, ['title' => 'Game 2']);
        $this->createTestGame($this->userId, ['title' => 'Game 3']);

        $page = $this->game->findAll(2, 0);
        $this->assertCount(2, $page);

        $page2 = $this->game->findAll(2, 2);
        $this->assertCount(1, $page2);
    }

    // ── SEARCH ──────────────────────────────────────

    public function testSearchByTitle(): void
    {
        $this->createTestGame($this->userId, ['title' => 'Dark Souls III']);
        $this->createTestGame($this->userId, ['title' => 'Minecraft']);

        $results = $this->game->search('Dark');

        $this->assertCount(1, $results);
        $this->assertSame('Dark Souls III', $results[0]['title']);
    }

    public function testSearchByDescription(): void
    {
        $this->createTestGame($this->userId, [
            'title' => 'Mystery Game',
            'description' => 'A thrilling detective adventure',
        ]);

        $results = $this->game->search('detective');

        $this->assertCount(1, $results);
    }

    public function testSearchReturnsEmptyForNoMatch(): void
    {
        $this->createTestGame($this->userId, ['title' => 'Zelda']);

        $results = $this->game->search('nonexistent_xyz');

        $this->assertCount(0, $results);
    }

    // ── UPDATE ──────────────────────────────────────

    public function testUpdateModifiesGame(): void
    {
        $gameId = $this->createTestGame($this->userId, ['title' => 'Old Title', 'rating' => 5.0]);

        $this->game->update($gameId, [
            'title' => 'New Title',
            'description' => 'Updated desc',
            'rating' => 9.0,
        ]);

        $row = self::$pdo->query("SELECT * FROM games WHERE id = $gameId")->fetch();
        $this->assertSame('New Title', $row['title']);
        $this->assertSame('Updated desc', $row['description']);
        $this->assertEquals(9.0, (float) $row['rating']);
    }

    // ── DELETE ───────────────────────────────────────

    public function testDeleteRemovesGame(): void
    {
        $gameId = $this->createTestGame($this->userId);

        $this->game->delete($gameId);

        $row = self::$pdo->query("SELECT * FROM games WHERE id = $gameId")->fetch();
        $this->assertFalse($row);
    }

    // ── COLLECTION ──────────────────────────────────

    public function testIsInCollectionReturnsFalseByDefault(): void
    {
        $gameId = $this->createTestGame($this->userId);

        $this->assertFalse($this->game->isInCollection($this->userId, $gameId));
    }

    public function testAddToCollectionAndCheck(): void
    {
        $gameId = $this->createTestGame($this->userId);

        $this->game->addToCollection($this->userId, $gameId);

        $this->assertTrue($this->game->isInCollection($this->userId, $gameId));
    }

    public function testAddToCollectionIgnoresDuplicate(): void
    {
        $gameId = $this->createTestGame($this->userId);

        $this->game->addToCollection($this->userId, $gameId);
        $this->game->addToCollection($this->userId, $gameId); // INSERT IGNORE

        $count = self::$pdo->query("SELECT COUNT(*) FROM collections WHERE user_id = {$this->userId} AND game_id = $gameId")->fetchColumn();
        $this->assertEquals(1, $count);
    }

    public function testRemoveFromCollection(): void
    {
        $gameId = $this->createTestGame($this->userId);
        $this->game->addToCollection($this->userId, $gameId);

        $this->game->removeFromCollection($this->userId, $gameId);

        $this->assertFalse($this->game->isInCollection($this->userId, $gameId));
    }

    public function testGetByUserCollectionReturnsGames(): void
    {
        $game1 = $this->createTestGame($this->userId, ['title' => 'Collected Game']);
        $game2 = $this->createTestGame($this->userId, ['title' => 'Not Collected']);

        $this->game->addToCollection($this->userId, $game1);

        $collection = $this->game->getByUserCollection($this->userId);

        $this->assertCount(1, $collection);
        $this->assertSame('Collected Game', $collection[0]['title']);
    }

    // ── TAGS & PLATFORMS ────────────────────────────

    public function testFindIncludesTagsAndPlatforms(): void
    {
        $gameId = $this->createTestGame($this->userId, ['title' => 'Tagged Game']);

        // Ajouter un tag
        self::$pdo->exec("INSERT INTO tags (name) VALUES ('RPG')");
        $tagId = self::$pdo->lastInsertId();
        self::$pdo->exec("INSERT INTO game_tags (game_id, tag_id) VALUES ($gameId, $tagId)");

        // Ajouter une plateforme
        self::$pdo->exec("INSERT INTO platforms (name) VALUES ('PC')");
        $platformId = self::$pdo->lastInsertId();
        self::$pdo->exec("INSERT INTO game_platforms (game_id, platform_id) VALUES ($gameId, $platformId)");

        $found = $this->game->find($gameId);

        $this->assertStringContainsString('RPG', $found['tags']);
        $this->assertStringContainsString('PC', $found['platforms']);
    }

    // ── CASCADE DELETE ──────────────────────────────

    public function testDeleteUserCascadesGames(): void
    {
        $gameId = $this->createTestGame($this->userId);

        self::$pdo->exec("DELETE FROM users WHERE id = {$this->userId}");

        $row = self::$pdo->query("SELECT * FROM games WHERE id = $gameId")->fetch();
        $this->assertFalse($row);
    }
}
