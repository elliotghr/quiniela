<table class="table table-striped mt-3 table-hover">
    <thead>
        <tr>
            <th class="col text-center" scope="col" style="width: 35%;">Local</th>
            <th class="col text-center" scope="col" style="width: 30%;">Marcador</th>
            <th class="col text-center" scope="col" style="width: 35%;">Visitante</th>
        </tr>
    </thead>
    <tbody style="cursor: pointer;">
        <?php foreach($fixtures as $fixture): ?>
            <tr>
                <td class="align-middle text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">
                                <img src="<?=$fixture["home_logo"]?>" class="card-img-top" style="max-width: 50px; max-height: 50px;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=$fixture["home_name"]?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="align-middle text-center fs-4">
                    <?php if(is_null($fixture["home_goals"]) || is_null($fixture["away_goals"])): ?>
                        <?=date_format(new DateTime($fixture["date"]), "d-M-Y");?>
                        <br />
                        <?=date_format(new DateTime($fixture["date"]), "H:i");?>
                    <?php else: ?>
                        <?=$fixture["home_goals"]?> - <?=$fixture["away_goals"]?>
                    <?php endif; ?>
                </td>
                <td class="align-middle text-center">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">
                                <img src="<?=$fixture["away_logo"]?>" class="card-img-top" style="max-width: 50px; max-height: 50px;">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?=$fixture["away_name"]?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>