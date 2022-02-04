<?= $this->extend("layout/master") ?>


<?= $this->section("content") ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User logs</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">S.no</th>
                            <th class="text-center">User</th>
                            <th class="text-center">Transfer From</th>
                            <th class="text-center">Transfer to</th>
                            <th class="text-center">From Lot</th>
                            <th class="text-center">To Lot</th>
                            <th class="text-center">Transfer male</th>
                            <th class="text-center">Transfer Female</th>
                            <th class="text-center">Entry male</th>
                            <th class="text-center">Entry Female</th>
                            <th class="text-center">Entry Shed</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Date </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($userlogs as $key => $userlog) {
                        ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td class="text-center"><?= $userlog['user'];  ?></td>
                                <td class="text-center"><?php
                                                        foreach ($sheds as $shed) {
                                                            if ($userlog['transferFrom'] == 0) {
                                                                echo "--";
                                                                break;
                                                            }
                                                            if ($userlog['transferFrom'] == $shed->id) {
                                                                echo $shed->name;
                                                            }
                                                        }
                                                        ?></td>
                                <td class="text-center"><?php
                                                        foreach ($sheds as $shed) {
                                                            if ($userlog['transferTo'] == 0) {
                                                                echo "--";
                                                                break;
                                                            }
                                                            if ($userlog['transferTo'] == $shed->id) {
                                                                echo $shed->name;
                                                            }
                                                        }
                                                        ?></td>
                                <td class="text-center"><?= $userlog['fromLot'] == 0 ? "--" : $userlog['fromLot']; ?></td>
                                <td class="text-center"><?= $userlog['toLot'] == 0 ? "--" : $userlog['toLot']; ?></td>
                                <td class="text-center"><?= $userlog['male'] == 0 ? "--" : $userlog['male']; ?></td>
                                <td class="text-center"><?= $userlog['female'] == 0 ? "--" : $userlog['female']; ?></td>
                                <td class="text-center"><?= $userlog['entryMale'] == 0 ? "--" : $userlog['entryMale']; ?></td>
                                <td class="text-center"><?= $userlog['entryFemale'] == 0 ? "--" : $userlog['entryFemale']; ?></td>
                                <td class="text-center"><?php
                                                        foreach ($sheds as $shed) {
                                                            if ($userlog['entryShedId'] == 0) {
                                                                echo "--";
                                                                break;
                                                            }
                                                            if ($userlog['entryShedId'] == $shed->id) {
                                                                echo $shed->name;
                                                            }
                                                        }
                                                        ?></td>
                                <td class="text-center"><?= $userlog['action'] ?></td>
                                <td class="text-center"><?= date('l jS \of F Y h:i:s A', strtotime($userlog['date']));   ?></td>
                            </tr>
                        <?php     }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<?= $this->endSection() ?>