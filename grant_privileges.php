<?php
/**
 * Ideiglenes szkript a root felhasználó hálózati jogosultságainak beállítására.
 */

try {
    // A 'localhost' használata kikerüli a Windows hálózati IP-cím fordítási hibáit
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        CREATE USER IF NOT EXISTS 'root'@'172.18.0.1' IDENTIFIED BY '';
        GRANT ALL PRIVILEGES ON *.* TO 'root'@'172.18.0.1' WITH GRANT OPTION;
        CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '';
        GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
        FLUSH PRIVILEGES;
    ";

    $pdo->exec($sql);
    echo "Siker: A root felhasználó jogosultságai frissítve lettek a 172.18.0.1 és % címekre!\n";

} catch (PDOException $e) {
    echo "Hiba történt az adatbázis-kapcsolat során: " . $e->getMessage() . "\n";
}