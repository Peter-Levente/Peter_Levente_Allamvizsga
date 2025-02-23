<?php
session_start();
include "../Database/db_connection.php";
include "Cart.php";

// Ellenőrizzük, hogy az adatbázis-kapcsolati fájl létezik-e
if (!file_exists('../Database/db_connection.php')) {
    die("Connection initiation file not found!"); // Hibával leáll, ha hiányzik a fájl
}

// Ellenőrizzük, hogy az adatbázis-kapcsolat inicializálva van-e
if (!isset($conn)) {
    die("Error: No database connection!"); // Hibával leáll, ha nincs adatbázis kapcsolat
}

// Ellenőrizzük az adatbázis-kapcsolat hibáit
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error); // Hibával leáll, ha hiba történt a kapcsolódás során
}

// Ellenőrizzük, hogy a felhasználó be van-e jelentkezve
$userId = $_SESSION['user_id'] ?? null; // Lekérdezzük a bejelentkezett felhasználó azonosítóját
if (!$userId) {
    header("Location: ../Authentication/login.php"); // Ha nincs bejelentkezve, átirányítjuk a bejelentkezési oldalra
    exit;
}

// Ellenőrizzük, hogy POST kérés érkezett-e (form beküldése)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formból érkező adatok lekérése
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];


    $cart = new Cart($conn, $_SESSION['user_id']);
    $cart->addToCart($productId, $quantity, $size); // A termék hozzáadása a kosárhoz

    // Átirányítás a kosár oldalra a művelet után
    header("Location: my_cart.php");
    exit;
}


session_start();
include "../Database/db_connection.php";
include "Cart.php";

// Ellenőrzi, hogy létezik-e az adatbázis kapcsolat fájl
if (!file_exists('../Database/db_connection.php')) {
    die("Connection initiation file not found!");  // Ha nem találja, hibát dob
}

// Ellenőrzi, hogy az adatbázis kapcsolat létrejött-e
if (!isset($conn)) {
    die("Error: No database connection!");  // Ha nincs kapcsolat, hibát dob
}

// Ha van kapcsolat, de hiba történt, akkor azt is jelezzük
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);  // Hibát dob a kapcsolódáskor fellépő hibák esetén
}

// Ellenőrzi, hogy a felhasználó be van-e jelentkezve (azaz van-e user_id a session-ben)
$userId = $_SESSION['user_id'] ?? null;  // Lekérdezzük a bejelentkezett felhasználó azonosítóját
if (!$userId) {
    header("Location: ../Authentication/login.php");  // Ha nincs bejelentkezve, átirányítja a login oldalra
    exit;
}

// Csak akkor fut le a következő kód, ha a kérés POST metódusú
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = $_POST['cart_id'];  // Megkapjuk a kosárban lévő termék azonosítóját, amit eltávolítunk

    $cart = new Cart($conn, $userId);
    $cart->removeFromCart($cartId);

    // Az eltávolítás után átirányítjuk a felhasználót a my_cart.php oldalra, hogy frissítse a kosarát
    header("Location: my_cart.php");
    exit;
}


session_start();
include "../Database/db_connection.php";
include "Cart.php";

// Ellenőrzi, hogy létezik-e az adatbázis kapcsolat fájl
if (!file_exists('../Database/db_connection.php')) {
    die("Connection initiation file not found!");  // Ha nem találja, hibát dob
}

// Ellenőrzi, hogy az adatbázis kapcsolat létrejött-e
if (!isset($conn)) {
    die("Error: No database connection!");  // Ha nincs kapcsolat, hibát dob
}

// Ha van kapcsolat, de hiba történt, akkor azt is jelezzük
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);  // Hibát dob a kapcsolódáskor fellépő hibák esetén
}

// Ellenőrzi, hogy a felhasználó be van-e jelentkezve (azaz van-e user_id a session-ben)
$userId = $_SESSION['user_id'] ?? null;  // Lekérdezzük a bejelentkezett felhasználó azonosítóját
if (!$userId) {
    header("Location: ../Authentication/login.php");  // Ha nincs bejelentkezve, átirányítja a login oldalra
    exit;  // Azonnal kilép, hogy a további kód ne fusson le
}

$cart = new Cart($conn, $userId);

// Csak akkor fut le a következő kód, ha a kérés POST metódusú
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartId = $_POST['cart_id'] ?? null;  // Megkapjuk a kosárban lévő termék azonosítóját
    $quantity = $_POST['quantity'] ?? 1;  // Megkapjuk a kívánt mennyiséget (alapértelmezett 1)

    // Ha a cartId és a quantity érvényes (nem null és a quantity >= 1), akkor frissítjük a mennyiséget
    if ($cartId && $quantity >= 1) {
        $cart->updateQuantity($cartId, $quantity);
    }

    // Átirányítja a felhasználót a kosár oldalra, hogy a módosítások megjelenjenek
    header("Location: my_cart.php");
    exit;
}