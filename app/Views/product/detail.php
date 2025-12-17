<div style="max-width: 1200px; margin: 0 auto;">
    <a href="/produits" style="display: inline-block; margin-bottom: 2rem; color: var(--text-secondary);">
        ← Retour aux produits
    </a>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <!-- Image du produit -->
        <div>
            <?php if (!empty($product['image_url'])): ?>
                <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                     alt="<?= htmlspecialchars($product['nom']) ?>"
                     style="width: 100%; border-radius: 12px; border: 1px solid var(--border);">
            <?php else: ?>
                <div style="width: 100%; height: 500px; background-color: var(--bg-tertiary); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <span style="font-size: 5rem;">🎵</span>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Informations produit -->
        <div>
            <div style="color: var(--accent); font-weight: 500; margin-bottom: 0.5rem;">
                <?= htmlspecialchars($product['categorie_nom']) ?>
            </div>
            
            <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">
                <?= htmlspecialchars($product['nom']) ?>
            </h1>
            
            <div style="font-size: 3rem; font-weight: bold; color: var(--accent); margin-bottom: 2rem;">
                <?= number_format($product['prix'], 2, ',', ' ') ?> €
            </div>
            
            <div style="margin-bottom: 2rem; padding: 1rem; background-color: var(--bg-tertiary); border-radius: 8px;">
                <?php if ($product['stock'] > 0): ?>
                    <span style="color: var(--success); font-weight: 500;">✅ En stock</span>
                    <span style="color: var(--text-secondary);"> (<?= $product['stock'] ?> disponible<?= $product['stock'] > 1 ? 's' : '' ?>)</span>
                <?php else: ?>
                    <span style="color: var(--error); font-weight: 500;">❌ Rupture de stock</span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($product['description'])): ?>
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; margin-bottom: 1rem;">Description</h2>
                    <p style="color: var(--text-secondary); line-height: 1.8;">
                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <!-- Formulaire d'ajout au panier -->
            <?php if ($product['stock'] > 0): ?>
                <?php if (isset($_SESSION['cart_success'])): ?>
                    <div class="alert alert-success">
                        ✅ <?= htmlspecialchars($_SESSION['cart_success']) ?>
                    </div>
                    <?php unset($_SESSION['cart_success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['cart_error'])): ?>
                    <div class="alert alert-error">
                        ❌ <?= htmlspecialchars($_SESSION['cart_error']) ?>
                    </div>
                    <?php unset($_SESSION['cart_error']); ?>
                <?php endif; ?>
                
                <form method="POST" action="/panier/ajouter" style="display: flex; gap: 1rem; align-items: flex-end;">
                    <input type="hidden" name="produit_id" value="<?= $product['id'] ?>">
                    
                    <div style="flex: 0 0 120px;">
                        <label class="form-label">Quantité</label>
                        <input type="number" 
                               name="quantite" 
                               class="form-input" 
                               value="1" 
                               min="1" 
                               max="<?= $product['stock'] ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="flex: 1; padding: 1rem 2rem; font-size: 1.1rem;">
                        🛒 Ajouter au panier
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-error">
                    Ce produit est actuellement en rupture de stock.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Produits similaires -->
    <?php if (!empty($similarProducts)): ?>
        <div>
            <h2 style="font-size: 2rem; margin-bottom: 2rem;">Produits similaires</h2>
            <div class="products-grid">
                <?php foreach ($similarProducts as $similar): ?>
                    <div class="product-card">
                        <a href="/produit?id=<?= $similar['id'] ?>">
                            <?php if (!empty($similar['image_url'])): ?>
                                <img src="<?= htmlspecialchars($similar['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($similar['nom']) ?>" 
                                     class="product-image">
                            <?php else: ?>
                                <div class="product-image" style="display: flex; align-items: center; justify-content: center;">
                                    <span style="font-size: 3rem;">🎵</span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="product-info">
                            <div class="product-category"><?= htmlspecialchars($similar['categorie_nom']) ?></div>
                            <a href="/produit?id=<?= $similar['id'] ?>">
                                <h3 class="product-name"><?= htmlspecialchars($similar['nom']) ?></h3>
                            </a>
                            <div class="product-price"><?= number_format($similar['prix'], 2, ',', ' ') ?> €</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>