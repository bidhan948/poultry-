<?= $this->extend("layout/master") ?>

<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url() ?>/datepicker/datepicker.min.css">
<?= $this->endSection() ?>
<!-- content section started -->



<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Add Transfer</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Custom Select</label>
                        <select class="custom-select" v-on:input="onShedChanged($event.target.value,'')" name="shedId" v-model="transferModel.fromShed">
                            <option value="">--Select shed--</option>
                            <?php
                            foreach ($sheds as $shed) {
                            ?>
                                <option value="<?= $shed->id ?>"><?= $shed->name  ?></option>
                            <?php   }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Date</span>
                        </div>
                        <input type="text" class="form-control" placeholder="YYYY/MM/DD" id="arrival-date" spellcheck="false" data-ms-editor="true" v-model="transferModel.dateBs" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <table class="table table-bordered table-sm" style="width:100%">

                    <tr>
                        <td>Lot</td>
                        <td>{{shedDetails.lot}}</td>
                    </tr>
                    <tr>
                        <td>Male</td>
                        <td>{{shedDetails.male}}</td>
                    </tr>
                    <tr>
                        <td>Female</td>
                        <td>{{shedDetails.female}}</td>
                    </tr>
                    <tr>
                        <td>Age in days</td>
                        <td>{{shedDetails.ageInDays}}</td>
                    </tr>
                    <tr>
                        <td>Age in weeks</td>
                        <td>{{shedDetails.ageInWeeks}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12 my-4">
                <table class="table table-bordered table-sm" style="width:100%">
                    <thead>
                        <tr>
                            <td rowspan="2" class="text-center">#</td>
                            <td rowspan="2" class="text-center">To Shed</td>
                            <td rowspan="2" class="text-center">Transfer Male Quantity</td>
                            <td rowspan="2" class="text-center">Transfer Female Quantity</td>
                            <td rowspan="2" class="text-center">Running Lot</td>
                            <td colspan="2" class="text-center">Running Age</td>
                            <td rowspan="2" class="text-center">Description</td>
                            <td rowspan="2" class="text-center"></td>
                        </tr>
                        <tr>
                            <td class="text-center">Days</td>
                            <td class="text-center">Weeks</td>
                        </tr>
                    </thead>
                    <tr>
                        <td class="text-center"></td>
                        <td class="text-center">
                            <div class="form-group pt-4">
                                <select name="shed" v-validate="'required'" class="form-control form-control-sm" v-model="transferModel.transferDetails[0].toShed">
                                    <option value="" selected>--Select Shed--</option>
                                    <?php
                                    foreach ($sheds as $shed) {
                                    ?>
                                        <option value="<?= $shed->id ?>"><?= $shed->name ?></option>
                                    <?php    }
                                    ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-group pt-4">

                                <input type="number" v-validate="'required'" name="male" class="form-control form-control-sm" v-model="transferModel.transferDetails[0].male">
                                <!-- <span class="text-danger">{{ errors.first('male')}}</span> -->
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="form-group form-group-sm pt-4">
                                <input type="number" v-validate="'required'" v-model="transferModel.transferDetails[0].female" name="female" class="form-control form-control-sm">
                                <span class="text-danger"></span>
                            </div>
                        </td>
                        <td class="text-center">
                            <!-- <div class="form-group form-group-sm">
                            <input type="number" readonly class="form-control form-control-sm" v-model="item.toLot">
                        </div> -->
                        </td>
                        <td class="text-center">
                        </td>
                        <td class="text-center">
                        </td>
                        <td class="text-center">
                            <div class="form-group pt-4">

                                <textarea type="number" class="form-control form-control-sm"></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?php echo base_url() ?>/" class="btn btn-secondary float-right ml-2">Back</a>
            <button type="button" id="submit-button" v-on:click="submitTransfer()" class="btn btn-primary float-right">Submit</button>
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
            shedDetails: {
                shedDetails: '',
                male: '',
                female: '',
                lot: '',
                breedTypeId: '',
                ageInDays: '',
                ageInWeeks: '',
            },
            transferModel: {
                id: '',
                fromShed: '',
                fromLot: '',
                transferAge: '',
                breedTypeId: '',
                date: '',
                dateBs: '',
                transferDetails: [{
                    toShed: '',
                    toLot: '',
                    ageInDays: '',
                    ageInWeeks: '',
                    male: 0,
                    female: 0,
                    description: ''
                }],
            }
        },
        methods: {
            onShedChanged(id) {
                let vm = this;
                vm.shedDetails.breedTypeId = '';
                vm.shedDetails.shedDetails = '';
                vm.shedDetails.male = '';
                vm.shedDetails.female = '';
                vm.shedDetails.lot = '';
                vm.shedDetails.ageInDays = '';
                vm.shedDetails.ageInWeeks = '';
                if (id) {
                    axios.get("<?php echo base_url() ?>/api/stock/shed", {
                            params: {
                                shedId: id,
                                transferDate: vm.transferModel.date
                            }
                        })
                        .then(function(response) {
                            console.log(response.data);
                            if (response.data.length > 0) {
                                vm.shedDetails.shedDetails = response.data[0].shedDetails;
                                vm.shedDetails.male = response.data[0].male;
                                vm.shedDetails.female = response.data[0].female;
                                vm.shedDetails.lot = response.data[0].lot;
                                vm.shedDetails.breedTypeId = response.data[0].breedTypeId;
                                vm.shedDetails.ageInDays = response.data[0].ageInDays;
                                vm.shedDetails.ageInWeeks = response.data[0].ageInWeeks;
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            alert("Some Problem Occured");
                        });
                }
            },
            submitTransfer() {
                let vm = this;
                vm.transferModel.fromLot = vm.shedDetails.lot;
                vm.transferModel.transferAge = vm.shedDetails.ageInDays;
                vm.transferModel.breedTypeId = vm.shedDetails.breedTypeId;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url() ?>/api/stock/transfer/addT", vm.transferModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                // window.location ='/transfer';
                                alert(response.data.messages);

                            })
                            .catch(function(error) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(error);
                                alert(error.response.data.messages.error);
                            });
                    } else {
                        alert("Form is Invalid. Please check")
                    }
                })
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
                    if (vm.transferModel.fromShed) {
                        vm.onShedChanged(vm.transferModel.fromShed);
                        // vm.getByTransferDateAndFromShed(vm.transferModel.fromShed);
                    }
                }
            });
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->