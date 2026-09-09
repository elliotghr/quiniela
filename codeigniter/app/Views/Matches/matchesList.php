<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0;}
    input[type=number] {-moz-appearance: textfield;}
    input[type=number] {text-align: center;font-size: 30px;}

    .div_score {height: 150px;}
    .logo_team {max-width: 50px !important; max-height: 50px !important;}
    .score_input{width: 50px; height: 50px;}
</style>

<?php if($fixtures): ?>

    <table class="table table-striped mt-3">
        <tbody>
            <?php foreach ($fixtures as $fixture): ?>
                <?php $matchKickedOff  = $fixture['date'] >= date('c') ? true : false; ?>

                <tr>
                    <td>
                        <div class="d-flex align-middle text-center mb-2 mt-2">

                            <div class="col-4 text-center d-flex flex-column gap-2 justify-content-between">
                                <div>
                                    <img src="<?= $fixture["home_logo"] ?>" class="card-img-top logo_team">
                                </div>
                                <div>
                                    <?= $fixture["home_name"] ?>
                                </div>
                                <?php if ($matchKickedOff): ?>
                                    <div class="align-self-bottom">
                                        <input type="number" name="" id="" class="score_input">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-4 text-center d-flex flex-column gap-2 justify-content-between align-self-center">
                                <?php if ($matchKickedOff): ?>
                                    <?= date_format(new DateTime($fixture["date"]), "d-M-Y"); ?>
                                    <br />
                                    <?= date_format(new DateTime($fixture["date"]), "H:i"); ?>
                                <?php else: ?>
                                    <span style="text-align: center; font-size: 40px;">
                                        <?= $fixture['home_goals'] ?> - <?= $fixture['away_goals'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-4 text-center d-flex flex-column gap-2 justify-content-between">
                                <div>
                                    <img src="<?= $fixture["away_logo"] ?>" class="card-img-top logo_team">
                                </div>
                                <div>
                                    <?= $fixture["away_name"] ?>
                                </div>
                                <?php if ($matchKickedOff): ?>
                                    <div class="align-self-bottom">
                                        <input type="number" name="" id="" class="score_input">
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </td>
                </tr> 

            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>
    No hay eventos que mostrar
<?php endif; ?>