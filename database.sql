-- Base de données pour la boutique d'instruments de musique --
-- Fichier à placer à la racine du projet : database.sql --

drop database is exists emusic;
create database emusic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE emusic;

-- Table des catégories -- 
create table categories (
    id int primary key auto_increment,
    name varchar(100) NOT NULL,
    description text,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits -- 
create table products (
    id int primary key auto_increment,
    name varchar(200) NOT NULL,
    description text,
    price DECIMAL(10, 2) NOT NULL,
    stock int not null DEFAULT 0,
    category_id INT,
    image varchar(255),
    brand varchar(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    foreign key (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Table des utilisateurs --
create table users (
    id int primary key auto_increment,
    email varchar(150) UNIQUE NOT NULL,
    password varchar(255) NOT NULL,
    firstname varchar(100) NOT NULL,
    lastname varchar(100) NOT NULL,
    address text,
    phone varchar(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes --
create table orders (
    id int primary key auto_increment,
    user_id int not null,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address text NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    foreign key (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des détails de commande --
create table order_items (
    id int primary key auto_increment,
    order_id int not null,
    product_id int not null,
    quantity int not null,
    price DECIMAL(10, 2) NOT NULL,
    foreign key (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    foreign key (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Table du panier --
create table cart (
    id int primary key auto_increment,
    user_id int not null,
    product_id int not null,
    quantity int not null DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    foreign key (user_id) REFERENCES users(id) ON DELETE CASCADE,
    foreign key (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- Insertion des catégories --
INSERT INTO categories (name, description) VALUES
('Guitares', 'Guitares acoustiques, électriques et basses'),
('Pianos & Claviers', 'Pianos numériques, synthétiseurs et claviers'),
('Batteries', 'Batteries acoustiques et électroniques'),
('Instruments à vent', 'Saxophones, flûtes, clarinettes, trompettes'),
('Accessoires', 'Câbles, médiators, cordes, étuis et supports');

-- Insertion de produits d'exemple --
INSERT INTO products (name, description, price, stock, category_id, image, brand) VALUES
('Guitare Électrique Stratocaster', 'Guitare électrique professionnelle avec 3 micros simple bobinage', 899.99, 15, 1, 'strat.jpg', 'Fender'),
('Guitare Acoustique Folk', 'Guitare acoustique avec table en épicéa massif', 449.99, 20, 1, 'acoustic.jpg', 'Yamaha'),
('Basse Électrique Precision', 'Basse 4 cordes au son puissant et défini', 749.99, 10, 1, 'bass.jpg', 'Fender'),
('Piano Numérique 88 touches', 'Piano numérique avec toucher lourd et sons réalistes', 1299.99, 8, 2, 'piano.jpg', 'Roland'),
('Synthétiseur Analogique', 'Synthétiseur avec oscillateurs analogiques', 1899.99, 5, 2, 'synth.jpg', 'Moog'),
('Batterie Acoustique Complète', 'Kit 5 fûts avec cymbales et hardware', 1499.99, 6, 3, 'drums.jpg', 'Pearl'),
('Batterie Électronique', 'Batterie électronique silencieuse avec module de sons', 899.99, 12, 3, 'edrums.jpg', 'Roland'),
('Saxophone Alto', 'Saxophone alto en laiton doré avec étui', 1199.99, 7, 4, 'sax.jpg', 'Yamaha'),
('Trompette Sib', 'Trompette en Sib avec finition argentée', 599.99, 9, 4, 'trumpet.jpg', 'Bach'),
('Câble Jack 6m', 'Câble jack 6.35mm de haute qualité', 19.99, 50, 5, 'cable.jpg', 'Monster'),
('Médiators Pack de 12', 'Assortiment de médiators différentes épaisseurs', 8.99, 100, 5, 'picks.jpg', 'Dunlop'),
('Cordes Guitare Électrique', 'Jeu de 6 cordes pour guitare électrique', 12.99, 75, 5, 'strings.jpg', 'Ernie Ball');

-- Insertion d'un utilisateur de test --
-- Mot de passe: test123 (haché avec password_hash) --
INSERT INTO users (email, password, firstname, lastname, address, phone) VALUES
('test@music.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Dupont', '123 Rue de la Musique, 75001 Paris', '0123456789');