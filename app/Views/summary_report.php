<?= $this->extend("layout/master") ?>


<?= $this->section("content") ?>
<div class="row">
    <?php
    foreach ($mainData as $key => $singleData) {
    ?>
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h4 class=" pb-1 text-center"><strong><?= $singleData->name ?></strong></h4>
                    <p class="text-warning">Active lot: <span class="text-white"><?=  $singleData->lot[0]->lot ?? 0;   ?></span></p>
                    <p class="text-warning">Total male:  <span class="text-white"> <?=  $singleData->lot[0]->male ?? 0;   ?></span></p>
                    <p class="text-warning">Total female:  <span class="text-white"> <?=  $singleData->lot[0]->female ?? 0;   ?></span></p>
                    <p class="text-warning">Total Egg Prodcution: <span class="text-white"> <?=  $singleData->lot[0]->totalEggProduction ?? 0;   ?></span></p>
                </div>
                <div class="icon">
                    <i class="fab fa-the-red-yeti"></i>
                </div>
                <a href="<?= base_url()."/summary-report-detail/".$singleData->id ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    <?php     }

    ?>
</div>
<?= $this->endSection() ?>