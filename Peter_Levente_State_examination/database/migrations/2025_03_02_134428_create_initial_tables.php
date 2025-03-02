<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE DATABASE IF NOT EXISTS state_examination;
        USE state_examination;

        CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS products (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            category VARCHAR(50) NOT NULL,
            description TEXT NOT NULL,
            image VARCHAR(255) NOT NULL
        );

        CREATE TABLE IF NOT EXISTS orders (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            address TEXT NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT "Pending",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS cart (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            product_id INT(11) NOT NULL,
            quantity INT(11) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS order_items (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            order_id INT(11) NOT NULL,
            product_id INT(11) NOT NULL,
            quantity INT(11) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        );

        -- Insert sample data into the products table
        INSERT INTO products (name, price, category, description, image) VALUES
        ("Adidas Real Madrid 23/24 Training Top", 299.99, "Clothings", "", "https://gfx.r-gol.com/media/res/products/493/180493/465x605/ib0036_1.webp"),
        ("Nike Liverpool FC 23/24 Strike Track Suit", 549.99, "Clothings", "", "https://gfx.r-gol.com/media/res/products/606/183606/465x605/fd7117-061_1.webp"),
        ("Adidas FC Bayern 23/24 Training Top", 274.99, "Clothings", "", "https://gfx.r-gol.com/media/res/products/572/180572/465x605/iq0609_1.webp"),
        ("Ferencváros FTC Nike Men\'s Black Hoodie", 219.15, "Clothings", "", "https://shop.fradi.hu/api/uploads/0c00/0d00/0c00/0c00/3dc0/6760/7120/7030/6810/6818/6448/6848/7d88/7e08/7880/250x.jpg"),
        ("Sepsi OSK Unisex Zip-up Hoodie", 135.00, "Clothings", "", "https://fanshop.sepsiosk.ro/cdn/shop/files/DF108D68-25F3-4BEC-8DB5-3AF8456476E7.jpg?v=1698248329&width=600"),
        ("FK Csíkszereda Ultra-Light Premium Cotton Anorak", 93.00, "Clothings", "", "https://soldigo.azureedge.net/images/15861/600x600/pcebmpioik.jpg"),
        ("Nike Tottenham Hotspur 23/24 Strike Hoodie", 328.85, "Clothings", "", "https://gfx.r-gol.com/media/res/products/296/185296/465x605/fn4684-437_2.jpg"),
        ("Nike FC Barcelona 23/24 Strike Hoodie", 612.50, "Clothings", "", "https://gfx.r-gol.com/media/res/products/605/183605/465x605/fj5427-620_1.webp"),
        ("Adidas Real Madrid 23/24 Home Replica Jersey", 462.67, "Jerseys", "", "https://gfx.r-gol.com/media/res/products/322/159322/465x605/hr3796_1.jpg"),
        ("Nike Liverpool FC 23/24 Home Vapor Match Jersey", 694.08, "Jerseys", "", "https://gfx.r-gol.com/media/res/products/340/162340/465x605/dx2618-688_1.jpg"),
        ("Adidas FC Bayern 23/24 Home Authentic Jersey", 552.66, "Jerseys", "", "https://gfx.r-gol.com/media/res/products/321/159321/465x605/hr3729_1.jpg"),
        ("Ferencváros Nike Men\'s Home Jersey", 321.26, "Jerseys", "", "https://shop.fradi.hu/api/uploads/0300/0280/0280/0380/0380/0648/c66c/c666/8fb6/cec6/cec4/3ec0/1ee0/1e60/1f30/1e30/250x.jpg");
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_tables');
    }
};
