<?php
// Nastavení připojení k databázi
$host = 'a055um.forpsi.com';  // Hostitel databáze
$dbname = 'f171367';  // Název databáze
$username = 'f171367';  // Uživatelské jméno
$password = 'Gh6SLpeJ';  // Heslo k databázi

// Funkce pro získání připojení k databázi
function getDbConnection() {
    global $host, $dbname, $username, $password;

    try {
        // Vytvoření připojení k databázi
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

        // Nastavení režimu chyb (pro vývoj)
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Napojení na db proběhlo úspěšně.";
        // Vrátí PDO objekt
        return $pdo;
    } catch (PDOException $e) {
        // Pokud dojde k chybě při připojení
        die("Chyba připojení k databázi: " . $e->getMessage());
    }
}


// Volání funkce pro připojení k DB
getDbConnection();
