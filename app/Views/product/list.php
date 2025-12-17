<h1 style="font-size: 2.5rem; margin-bottom: 2rem;">Tous nos instruments</h1>

<!-- Filtres -->
<div class="card" style="margin-bottom: 2rem;">
    <form method="GET" action="/produits" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
        <!-- Filtre par catégorie -->
        <div>
            <label class="form-label">Catégorie</label>
            <select name="categorie" class="form-input">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($currentCategory) && $currentCategory == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Recherche -->
        <div>
            <label class="form-label">Recherche</label>
            <input type="text" 
                   name="search" 
                   class="form-input" 
                   placeholder="Nom du produit..."
                   value="<?= htmlspecialchars($search ?? '') ?>">
        </div>
        
        <!-- Prix minimum -->
        <div>
            <label class="form-label">Prix min (€)</label>
            <input type="number" 
                   name="min_prix" 
                   class="form-input" 
                   placeholder="0"
                   step="0.01"
                   min="0"
                   value="<?= htmlspecialchars($_GET['min_prix'] ?? '') ?>">
        </div>
        
        <!-- Prix maximum -->
        <div>
            <label class="form-label">Prix max (€)</label>
            <input type="number" 
                   name="max_prix" 
                   class="form-input" 
                   placeholder="99999"
                   step="0.01"
                   min="0"
                   value="<?= htmlspecialchars($_GET['max_prix'] ?? '') ?>">
        </div>
        
        <!-- Boutons -->
        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                🔍 Filtrer
            </button>
            <a href="/produits" class="btn btn-secondary">
                ↺ Réinitialiser
            </a>
        </div>
    </form>
</div>

<!-- Nombre de résultats -->
<p style="color: var(--text-secondary); margin-bottom: 1.5rem;">
    <?= count($products) ?> produit(s) trouvé(s)
</p>

<!-- Liste des produits -->
<?php if (empty($products)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 1rem;">
            Aucun produit ne correspond à vos critères.
        </p>
        <a href="/produits" class="btn btn-primary">
            Voir tous les produits
        </a>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
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
                    
                    <?php if (!empty($product['description'])): ?>
                        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                    <?php endif; ?>
                    
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