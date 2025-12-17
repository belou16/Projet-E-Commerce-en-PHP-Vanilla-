<!-- Section Hero -->
<section style="background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%); padding: 4rem 2rem; border-radius: 20px; margin-bottom: 3rem; text-align: center;">
    <h1 style="font-size: 3rem; margin-bottom: 1rem; background: linear-gradient(135deg, #ff6b35 0%, #ff8555 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
        Bienvenue chez Mozikako
    </h1>
    <p style="font-size: 1.25rem; color: var(--text-secondary); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
        Votre boutique en ligne d'instruments de musique de qualité. Pianos, guitares, batteries et saxophones.
    </p>
    <a href="/produits" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
        🎸 Découvrir nos instruments
    </a>
</section>

<!-- Section Catégories -->
<section style="margin-bottom: 3rem;">
    <h2 style="font-size: 2rem; margin-bottom: 2rem; text-align: center;">Nos Catégories</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
        <?php foreach ($categories as $category): ?>
            <a href="/categorie?slug=<?= urlencode($category['slug']) ?>" 
               style="display: block; border-radius: 12px; overflow: hidden; position: relative; height: 200px; text-decoration: none;">
                <div style="position: absolute; inset: 0; background-image: url('<?= htmlspecialchars($category['image_url']) ?>'); background-size: cover; background-position: center; filter: brightness(0.7);"></div>
                <div style="position: relative; z-index: 1; padding: 1.5rem; height: 100%; display: flex; flex-direction: column; justify-content: flex-end; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                    <h3 style="font-size: 1.5rem; color: white; margin-bottom: 0.5rem;">
                        <?= htmlspecialchars($category['nom']) ?>
                    </h3>
                    <p style="color: #e0e0e0; font-size: 0.9rem;">
                        <?= htmlspecialchars($category['description']) ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- Section Produits Vedettes -->
<section>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="font-size: 2rem;">Derniers Arrivages</h2>
        <a href="/produits" style="color: var(--accent); font-weight: 500;">
            Voir tout →
        </a>
    </div>
    
    <?php if (empty($featuredProducts)): ?>
        <div class="card" style="text-align: center; padding: 3rem;">
            <p style="color: var(--text-secondary); font-size: 1.1rem;">
                Aucun produit disponible pour le moment.
            </p>
        </div>
    <?php else: ?>
        <div class="products-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <a href="/produit?id=<?= $product['id'] ?>">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($product['nom']) ?>" 
                                 class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; background-color: var(--bg-tertiary);">
                                <span style="font-size: 3rem;">🎵</span>
                            </div>
                        <?php endif; ?>
                    </a>
                    
                    <div class="product-info">
                        <div class="product-category">
                            <?= htmlspecialchars($product['categorie_nom']) ?>
                        </div>
                        
                        <a href="/produit?id=<?= $product['id'] ?>">
                            <h3 class="product-name">
                                <?= htmlspecialchars($product['nom']) ?>
                            </h3>
                        </a>
                        
                        <div class="product-price">
                            <?= number_format($product['prix'], 2, ',', ' ') ?> €
                        </div>
                        
                        <div class="product-stock">
                            <?php if ($product['stock'] > 0): ?>
                                ✅ En stock (<?= $product['stock'] ?>)
                            <?php else: ?>
                                ❌ Rupture de stock
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($product['stock'] > 0): ?>
                            <form method="POST" action="/panier/ajouter">
                                <input type="hidden" name="produit_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantite" value="1">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    🛒 Ajouter au panier
                                </button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary" style="width: 100%; cursor: not-allowed;" disabled>
                                Indisponible
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>