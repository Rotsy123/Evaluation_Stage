
 
 
            <div class="card-body">
                <div class="table-responsive">
                    <div id="export-content">
        
                    <!-- <div style="width: 300px; margin: auto;"> Ajustez la largeur du div parent selon vos besoins -->
                        <!-- <canvas id="myChart" width="150" height="150"></canvas> -->
                    <!-- </div> -->
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                            <th>
                                ID
                            </th>
                            <th>
                                Designation
                            </th>
                            <th>
                                Quantite
                            </th>
                            <th>
                                Prix Unitaire
                            </th>
                            <th>
                                Unite                            
                            </th>
                            <th>
                                Maison
                            </th>
                            
                            <th>
                                Total
                            </th>
                            
                        </thead>
                        <tbody> 
                            <?php if (!empty($hierarchie)) : ?>
                                <?php foreach ($hierarchie as $row) : ?>
                                    <tr> 
                                        <td><?= $row->iddetailtravaux ?></td>
                                        <td><?= $row->nomtravaux ?></td> 
                                        <td><?= !empty($row->quantite) ? $row->quantite : '--' ?></td>
                                        <td><?= !empty($row->prix) ? $row->prix : '--' ?></td> 
                                        <td><?= !empty($row->nomunite) ? $row->nomunite : '--' ?></td> 

                                        <td><?= !empty($row->nommaison) ? $row->nommaison : '--' ?></td>
                                        <td><?= !empty($row->total) ? $row->total : '--' ?></td>

                                    
                                        
                                        
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6">Aucune donnée trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                    <hr>
        PRIX TOTAL AVEC FINITION:  <?php echo $finition?><br>
        <hr>

        PRIX SANS FINITION: <?php echo $prix?><br>

        <hr>
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>

                            <th>
                                DATE PAIEMENT
                            </th>
                            <th>
                                A PAYE
                            </th>
                            <th>
                                ID_DEVIS
                            </th>
                            
                        </thead>
                        <tbody> 
                            <?php if (!empty($listepaiement)) : ?>
                                <?php foreach ($listepaiement as $row) : ?>
                                    <tr> 
                                        <td><?= $row->datepaiement ?></td>
                                        <td><?= $row->a_paye ?></td> 
                                        <td><?= $row->iddevis ?></td>                                    
                                        
                                        
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6">Aucune donnée trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>


    <hr>    
    PRIX TOTAL DEJA PAYE:  <?php echo $total?><br>

    </div>
                
                
 

