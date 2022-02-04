<?= $this->extend("layout/master") ?>



<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url()?>/datepicker/datepicker.min.css">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="group_name">Shed</label>
                        <select v-on:input="onShedChanged($event.target.value,'')" name="shed" v-validate="'required'" v-model="transferModel.fromShed" class="form-control">
                            <option value="">Select Shed</option>
                            <option v-for="item in shedData" :value="item.id">
                                {{item.name}}
                            </option>
                        </select>
                        <span class="text-danger">{{ errors.first('shed')}}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="group_name">Date</label>
                        <input id="arrival-date" v-validate="'required'" name="date" placeholder="YYYY/MM/DD" v-model="transferModel.dateBs" type="text" class="form-control">
                        <span class="text-danger">{{ errors.first('date')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">

                <table class="table table-bordered table-sm" style="width:100%">
                    <!-- <tr>
                        <td>Shed Detail</td>
                        <td>{{shedDetails.shedDetails}}</td>
                    </tr> -->
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
                        <td>Age In Days</td>
                        <td>{{shedDetails.ageInDays}}</td>
                    </tr>
                    <tr>
                        <td>Age In Weeks</td>
                        <td>{{shedDetails.ageInWeeks}}</td>
                    </tr>
                </table>

            </div>
        </div>
        <div class="row">
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
                <tr v-for="(item, index) in transferModel.transferDetails">
                    <td class="text-center">{{index + 1}}</td>
                    <td class="text-center">
                        <div class="form-group">
                            <select v-on:input="onShedChangedForChild($event, index)" name="shed" v-validate="'required'" v-model="item.toShed" class="form-control form-control-sm">
                                <option value="">Select Shed</option>
                                <option v-for="item in shedData" :value="item.id">
                                    {{item.name}}
                                </option>
                            </select>
                            <span class="text-danger">{{ errors.first('shed')}}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">

                            <input type="number" v-validate="'required'" name="male" class="form-control form-control-sm" v-model="item.male">
                            <span class="text-danger">{{ errors.first('male')}}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <input type="number" v-validate="'required'" name="female" class="form-control form-control-sm" v-model="item.female">
                            <span class="text-danger">{{ errors.first('female')}}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        {{item.toLot}}
                        <!-- <div class="form-group form-group-sm">
                            <input type="number" readonly class="form-control form-control-sm" v-model="item.toLot">
                        </div> -->
                    </td>
                    <td class="text-center">
                        {{item.ageInDays}}
                    </td>
                    <td class="text-center">
                        {{item.ageInWeeks}}
                    </td>
                    <td class="text-center">
                        <div class="form-group">

                            <textarea type="number" class="form-control form-control-sm" v-model="item.description"></textarea>
                        </div>
                    </td>
                    <td class="text-center">
                        <button v-if="index == transferModel.transferDetails.length -1" v-on:click="onTransferDetailAddClick()" class="btn btn-warning  btn-sm"><i class="fa fa-plus"></i></button>
                        <button v-if="index != 0" v-on:click="onTransferDetailRemoveClick(index)" class="btn btn-danger  btn-sm"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card-footer">
            <a href="<?php echo base_url()?>/" class="btn btn-secondary float-right ml-2">Back</a>
            <button type="button" id="submit-button" v-on:click="submitDailyEntry()" class="btn btn-primary float-right">Submit</button>
        </div>
    </div>
    <!-- /.card-body -->
</div>
<?= $this->endSection() ?>
<!-- content section ended -->



<!-- Script section started -->
<?= $this->section("script") ?>
<script src="<?php echo base_url()?>/datepicker/datepicker.min.js"></script>
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
            loadShedData() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/shed")
                    .then(function(response) {
                        vm.shedData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            getByTransferDateAndFromShed(fromShed) {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/stock/transfer/transferDateAndFromShed", {
                        params: {
                            transferDate: vm.transferModel.date,
                            fromShed: fromShed
                        }
                    })
                    .then(function(response) {
                       console.log(response.data);
                        if (response.data.length > 0) {
                            vm.transferModel.transferDetails = response.data;
                        } else {
                            vm.transferModel.transferDetails = [{
                                toShed: '',
                                toLot: '',
                                ageInDays: '',
                                ageInWeeks: '',
                                male: 0,
                                female: 0,
                                description: ''
                            }];
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
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
                    axios.get("<?php echo base_url()?>/api/stock/shed", {
                            params: {
                                shedId: id,
                                transferDate:vm.transferModel.date
                            }
                        })
                        .then(function(response) {
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
                    if (vm.transferModel.date) {
                        vm.getByTransferDateAndFromShed(id);
                    }
                }
            },
            onShedChangedForChild(event, index) {
                let vm = this;
                var id = event.target.value;
                vm.transferModel.transferDetails[index].toLot = '';
                if (id) {
                    axios.get("<?php echo base_url()?>/api/stock/shed", {
                            params: {
                                shedId: id,
                                transferDate:vm.transferModel.date
                            }
                        })
                        .then(function(response) {
                            if (response.data.length > 0) {
                                vm.transferModel.transferDetails[index].toLot = response.data[0].lot;
                                vm.transferModel.transferDetails[index].ageInDays = response.data[0].ageInDays;
                                vm.transferModel.transferDetails[index].ageInWeeks = response.data[0].ageInWeeks;
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                            alert("Some Problem Occured");
                        });
                }
            },
            onTransferDetailAddClick() {
                let vm = this;
                vm.transferModel.transferDetails.push({
                    toShed: '',
                    toLot: '',
                    male: 0,
                    female: 0,
                    description: ''
                });
            },
            onTransferDetailRemoveClick(index) {
                let vm = this;
                vm.dailyEntryModel.medicineVaccine.splice(index, 1);
            },
            submitDailyEntry() {
                let vm = this;
                vm.transferModel.fromLot = vm.shedDetails.lot;
                vm.transferModel.transferAge = vm.shedDetails.ageInDays;
                vm.transferModel.breedTypeId = vm.shedDetails.breedTypeId;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/stock/transfer/add", vm.transferModel)
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
                    if(vm.transferModel.fromShed) {
                        vm.onShedChanged(vm.transferModel.fromShed);
                        vm.getByTransferDateAndFromShed(vm.transferModel.fromShed);
                    }
                }
            });
            vm.loadShedData();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->