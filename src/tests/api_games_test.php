<?php
/**
 * Test de l'API Games (AJAX Backend)
 * ---------------------------------
 * Ce script simule des requêtes GET vers api/games.php et vérifie
 * que le moteur de recherche et de filtrage renvoie les bons résultats.
 */

// Couleurs pour la console
define('GREEN', "\033[0;32m");
define('RED', "\033[0;31m");
define('NC', "\033[0m"); // No Color

function assertTrue($condition, $message)
{
    if ($condition) {
        echo GREEN . "[PASS] " . NC . $message . PHP_EOL;
        return true;
    } else {
        echo RED . "[FAIL] " . NC . $message . PHP_EOL;
        return false;
    }
}

function testEndpoint($params = [])
{
    // On simule $_GET
    $_GET = $params;

    // On capture l'output du script API
    ob_start();
    include __DIR__ . '/../public/api/games.php';
    $output = ob_get_clean();

    return json_decode($output, true);
}

echo "--- Démarrage des tests API PHP (Simulated AJAX) ---" . PHP_EOL;

// Test 1: Liste complète
$res = testEndpoint([]);
assertTrue(count($res) === 4, "T1 : Devrait retourner 4 jeux par défaut.");

// Test 2: Recherche par titre
$res = testEndpoint(['q' => 'Cyberpunk']);
assertTrue(count($res) === 1 && $res[0]['title'] === 'Cyberpunk 2077', "T2 : La recherche 'Cyberpunk' devrait retourner 1 résultat précis.");

// Test 3: Filtrage par genre
$res = testEndpoint(['genre' => 'FPS']);
assertTrue(count($res) === 1 && $res[0]['title'] === 'Valorant', "T3 : Le filtre genre 'FPS' devrait retourner uniquement 'Valorant'.");

// Test 4: Recherche inexistante
$res = testEndpoint(['q' => 'Mario']);
assertTrue(count($res) === 0, "T4 : Une recherche vide (Mario) devrait retourner un tableau vide.");

// Test 5: Combinaison Recherche + Genre
$res = testEndpoint(['q' => 'The', 'genre' => 'RPG']);
assertTrue(count($res) === 1 && $res[0]['title'] === 'The Witcher 3', "T5 : Recherche 'The' + Genre 'RPG' devrait retourner 'The Witcher 3'.");

echo "--- Tests terminés ---" . PHP_EOL;
