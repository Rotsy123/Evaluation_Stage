 
 <?= $this->extend("layouts/headeradmin") ?>

<?= $this->section("body") ?>
 <!-- Formulaire de mise à jour de produit -->
    <form action="<?= site_url('mettreAJourTravaux') ?>" method="post" >
        <input type="hidden" name="id" value="<?= $travaux['iddetailtravaux'] ?>">   
        <div class="container-fluid">
                
                <div class="form-group row">
                    <label for="quantite">QUANTITE</label>
                    <input type="number" step="0.01" name="quantite" value="<?= $travaux['quantite']?>" class="form-control form-control-user">
                </div>
                <div class="form-group row">
                <label for="quantite">PRIX</label>
                    <input type="number" step="0.01" name="prix" value="<?= $travaux['prix'] ?>" class="form-control form-control-user">
                </div>
                <div class="form-group row">
                <label for="quantite">NOM</label>
                    <input type="text" name="nom" value="<?= $travaux['nom'] ?>" class="form-control form-control-user" readonly>
                </div>
                <div class="form-group row">
                
                 <div class="form-group row">
    
                     <label for="quantite">UNITE</label>
                    <select name="unite" class="form-control form-control-user">
                        <?php foreach($unite as $mais): ?>
                            <option value="<?= $mais['id'] ?>" <?= ($travaux['idunite'] == $mais['id']) ? 'selected' : '' ?>>
                                <?= $mais['nom'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                 </div>

            </div>
 
        <button type="submit">Mettre à jour produit</button>
    </form>
</body>
</html>
<?= $this->endSection() ?>
