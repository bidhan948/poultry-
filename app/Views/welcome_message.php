<?= $this->extend("layout/master") ?>


<?= $this->section("content") ?>
<div class="row">
    <?php
    foreach ($groups as $key => $group) {
    ?>
        <div class="col-lg-4 col-6 mt-3">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 class="p-3"></h3>
                    <p><?= $group->name; ?></p>
                </div>
                <div class="icon">
                    <i class="fab fa-the-red-yeti"></i>
                </div>
                <a href="<?=  base_url().'/summary-report/'.$group->id  ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    <?php   }
    ?>
</div>
<?= $this->endSection() ?>
<?= $this->section("script") ?>
<!-- <script>
    new Vue({
        el: "#app",
        data: {
            reportData: [],
        },
        methods: {
            loadGroup() {
                let vm = this;
                axios.get("<?php echo base_url() ?>/api/Group")
                .then(function(response) {
                    console.log(response.data);
                })
                .catch(function(error) {
                    console.log(error);
                    alert("Some Problem Occured");
                });
            },
            mounted() {
                alert("hello");
                let vm = this;
                vm.loadGroup();
            }
        }
    })
</script> -->
<?= $this->endSection() ?>
