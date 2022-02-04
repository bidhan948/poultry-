<?= $this->extend("layout/master") ?>



<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url() ?>/datepicker/datepicker.min.css">
<?= $this->endSection() ?>
<!-- content section started -->



<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Update Transfer</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">From Shed</label>
                    <select name="shedId" class="custom-select rounded-0" id="exampleSelectRounded0" disabled>
                        <option value="<?= $stockTransfer->id  ?>"><?php
                                                                    foreach ($sheds as $key => $shed) {
                                                                        if ($shed->id == $stockTransfer->fromShed) {
                                                                            echo $shed->name;
                                                                        }
                                                                    ?>

                            <?php     }
                            ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">To Shed</label>
                    <select name="shedId" class="custom-select rounded-0" id="exampleSelectRounded0" disabled>
                        <option value="<?= $stockTransfer->id  ?>"><?php
                                                                    foreach ($sheds as $key => $shed) {
                                                                        if ($shed->id == $stockTransferDetail->toShed) {
                                                                            echo $shed->name;
                                                                        }
                                                                    ?>

                            <?php     }
                            ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="group_name">Date</label>
                    <input id="arrival-date" v-model="transferModel.dateBs" v-validate="'required'" name="date" placeholder="YYYY/MM/DD" type="text" class="form-control" >
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Transfer male Quantity</span>
                    </div>
                    <input type="number" class="form-control" placeholder="male quantity" v-model="transferModel.transferDetails[0].male">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Transfer female Quantity</span>
                    </div>
                    <input type="number" class="form-control" placeholder="female" v-model="transferModel.transferDetails[0].female">
                </div>
            </div>
        </div>

        <div class="card-footer">
            <a href="<?php echo base_url() ?>/transfer" class="btn btn-secondary float-right ml-2">Back</a>
            <button type="button" id="submit-button" v-on:click="updateTransfer()" class="btn btn-primary float-right">update</button>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<?= $this->endSection() ?>
<!-- content section ended -->



<!-- Script section started -->
<?= $this->section("script") ?>
<script src="<?php echo base_url() ?>/datepicker/datepicker.min.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            shedData: [],
            transferModel: {
                id: '<?= $stockTransfer->id  ?>',
                fromShed: '<?= $stockTransfer->fromShed  ?>',
                fromLot: '<?= $stockTransfer->fromLot  ?>',
                transferAge: '<?= $stockTransfer->transferAge  ?>',
                date: '<?= $stockTransfer->transferDate  ?>',
                dateBs: '<?= $stockTransfer->transferDateBs  ?>',
                transferDetails: [{
                    toShed: '<?= $stockTransferDetail->toShed  ?>',
                    toLot: '<?= $stockTransferDetail->toLot  ?>',
                    male: '<?= $stockTransferDetail->male  ?>',
                    female: '<?= $stockTransferDetail->female ?>',
                    description: ''
                }],
            }
        },
        methods: {
            updateTransfer() {
                let vm = this;
                axios.post("<?php echo base_url() ?>/api/transfer/update", vm.transferModel)
                    .then(function(response) {
                        console.log(response);
                        alert(response.data.messages);
                        window.location.href = `<?php echo base_url() ?>/transfer`;
                    })
                    .catch(function(error) {
                        // debugger;
                        console.log(error);
                        alert(error.response.data.messages.error);
                    });
            }
        },
        mounted() {
            let vm = this;
            var arrivalDate = document.getElementById("arrival-date");
            arrivalDate.nepaliDatePicker({
                readOnlyInput: true,
                ndpMonth: true,
                ndpYear: true,
                ndpYearCount: 10,
                dateFormat: "YYYY/MM/DD",
                onChange: function(event) {
                    vm.transferModel.date = event.ad;
                    vm.transferModel.dateBs = event.bs;
                }
            });
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->