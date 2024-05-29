<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>En stock</th>
            <th>État</th>
            <th>Catégorie</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td><?= $produit['nom'] ?></td>
                <td><?= $produit['description'] ?></td>
                <td><?= $produit['prix'] ?></td>
                <td><?= $produit['quantite'] ?></td>
                <td><?= ($produit['en_stock'] == '1') ? 'Oui' : 'Non' ?></td>
                <td><?= $produit['etat'] ?></td>
                <td><?= $produit['categorie'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
