-- Création de la base de données Mozikako
DROP DATABASE IF EXISTS mozikako;
CREATE DATABASE mozikako CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mozikako;

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'admin') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des catégories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    stock INT DEFAULT 0,
    categorie_id INT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Table des paniers
CREATE TABLE paniers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE,
    UNIQUE KEY unique_panier (user_id, produit_id)
);

-- Table des commandes
CREATE TABLE commandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    montant_total DECIMAL(10, 2) NOT NULL,
    statut ENUM('en_attente', 'validee', 'expediee', 'livree') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table de liaison commandes-produits
CREATE TABLE commande_produits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commande_id INT NOT NULL,
    produit_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE
);

-- Insertion des catégories
INSERT INTO categories (nom, slug, description, image_url) VALUES
('Piano', 'piano', 'Pianos acoustiques et numériques de qualité professionnelle', 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=800'),
('Guitare', 'guitare', 'Guitares acoustiques, électriques et basses pour tous niveaux', 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800'),
('Batterie', 'batterie', 'Batteries acoustiques et électroniques complètes', 'https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?w=800'),
('Saxophone', 'saxophone', 'Saxophones alto, ténor et soprano de grandes marques', 'https://images.unsplash.com/photo-1551689750-4f5b7877ae01?w=800');

-- Insertion des produits - PIANOS
INSERT INTO produits (nom, description, prix, stock, categorie_id, image_url) VALUES
('Piano Numérique Yamaha P-125', 'Piano numérique 88 touches avec son GHS (Graded Hammer Standard). Idéal pour débutants et intermédiaires.', 699.00, 15, 1, 'https://images.unsplash.com/photo-1520523839897-bd0b52f945a0?w=600'),
('Piano à Queue Steinway Model D', 'Le piano de concert par excellence. Son riche et puissant, mécanique de précision.', 149999.00, 2, 1, 'https://images.unsplash.com/photo-1552422535-c45813c61732?w=600'),
('Piano Droit Kawai K-300', 'Piano droit haut de gamme, 122cm de hauteur. Son brillant et équilibré.', 8500.00, 5, 1, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600'),
('Piano Numérique Roland FP-30X', 'Compact et portable avec son SuperNATURAL. Bluetooth intégré.', 799.00, 20, 1, 'https://images.unsplash.com/photo-1512733596533-7b00ccf8ebaf?w=600');

-- Insertion des produits - GUITARES
INSERT INTO produits (nom, description, prix, stock, categorie_id, image_url) VALUES
('Guitare Électrique Fender Stratocaster', 'La légendaire Strat avec 3 micros simples. Son polyvalent et iconique.', 1299.00, 12, 2, 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?w=600'),
('Guitare Acoustique Martin D-28', 'Dreadnought classique avec table en épicéa massif. Son puissant et riche.', 2899.00, 8, 2, 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=600'),
('Guitare Électrique Gibson Les Paul', 'Modèle Standard avec micros humbucker. Rock et blues par excellence.', 2499.00, 6, 2, 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?w=600'),
('Basse Électrique Fender Precision', 'La P-Bass originale, son rond et profond. Idéale pour le rock et la pop.', 1199.00, 10, 2, 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?w=600'),
('Guitare Classique Yamaha C40', 'Parfaite pour débuter, table en épicéa, dos et éclisses en meranti.', 179.00, 25, 2, 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?w=600');

-- Insertion des produits - BATTERIES
INSERT INTO produits (nom, description, prix, stock, categorie_id, image_url) VALUES
('Batterie Acoustique Pearl Export', 'Set 5 fûts avec cymbales. Configuration standard idéale pour débuter.', 899.00, 7, 3, 'https://images.unsplash.com/photo-1519892300165-cb5542fb47c7?w=600'),
('Batterie Électronique Roland TD-17KVX', 'V-Drums avec peaux maillées, module TD-17, sons ultra-réalistes.', 1799.00, 10, 3, 'https://images.unsplash.com/photo-1571327073757-71d13c24de30?w=600'),
('Batterie Acoustique DW Collector''s', 'Série haut de gamme, fûts en érable, finition Custom Shop.', 5999.00, 3, 3, 'https://images.unsplash.com/photo-1586041828039-7ccde4908115?w=600'),
('Batterie Électronique Alesis Nitro', 'Kit complet débutant, 8 pads sensibles, 385 sons intégrés.', 399.00, 15, 3, 'https://images.unsplash.com/photo-1618385044276-4d6c616f0f67?w=600');

-- Insertion des produits - SAXOPHONES
INSERT INTO produits (nom, description, prix, stock, categorie_id, image_url) VALUES
('Saxophone Alto Yamaha YAS-280', 'Alto en mi bémol, idéal débutants. Justesse et facilité de jeu.', 999.00, 12, 4, 'https://images.unsplash.com/photo-1551689750-4f5b7877ae01?w=600'),
('Saxophone Ténor Selmer Mark VI', 'Légende du jazz, vintage 1960s. Son chaud et expressif.', 12999.00, 1, 4, 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=600'),
('Saxophone Alto Selmer Serie III', 'Professionnel, mécanique perfectionnée, son riche et homogène.', 4299.00, 5, 4, 'https://images.unsplash.com/photo-1567493206228-8a196e1e7e6f?w=600'),
('Saxophone Soprano Yanagisawa S-WO1', 'Soprano en si bémol, facture japonaise d\'exception.', 3599.00, 4, 4, 'https://images.unsplash.com/photo-1511192336575-5a79af67a629?w=600');

-- Insertion des utilisateurs de test (mots de passe: Client123 et Admin123)
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Dupont', 'Jean', 'client@mozikako.com', '$2y$10$YourHashedPasswordHere1', 'client'),
('Martin', 'Sophie', 'admin@mozikako.com', '$2y$10$YourHashedPasswordHere2', 'admin');