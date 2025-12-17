<h1 style="font-size: 2.5rem; margin-bottom: 1rem;"><?= htmlspecialchars($category['nom']) ?></h1>
<p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 2rem;">
    <?= htmlspecialchars($category['description']) ?>
</p>

<?php if (empty($products)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--text-secondary);">Aucun produit dans cette catégorie.</p>
    </div>
<?php else: ?>
    <div class="products-grid">
        <?php foreach ($products as $product): ?>
            <!-- (Même code que list.php pour afficher les produits) -->
            <div class="product-card">
                <a href="/produit?id=<?= $product['id'] ?>">
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['nom']) ?>" class="product-image">
                    <?php else: ?>
                        <div class="product-image" style="display: flex; align-items: center; justify-content: center;"><span style="font-size: 3rem;">🎵</span></div>
                    <?php endif; ?>
                </a>
                <div class="product-info">
                    <div class="product-category"><?= htmlspecialchars($product['categorie_nom']) ?></div>
                    <a href="/produit?id=<?= $product['id'] ?>"><h3 class="product-name"><?= htmlspecialchars($product['nom']) ?></h3></a>
                    <div class="product-price"><?= number_format($product['prix'], 2, ',', ' ') ?> €</div>
                    <div class="product-stock"><?= $product['stock'] > 0 ? "✅ En stock ({$product['stock']})" : "❌ Rupture de stock" ?></div>
                    <?php if ($product['stock'] > 0): ?>
                        <form method="POST" action="/panier/ajouter">
                            <input type="hidden" name="produit_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantite" value="1">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">🛒 Ajouter au panier</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary" style="width: 100%;" disabled>Indisponible</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>