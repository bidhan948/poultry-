<?= $this->extend("layout/master") ?>



<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url() ?>/datepicker/datepicker.min.css">
<?= $this->endSection() ?>
<!-- content section started -->



<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Add Main Entry</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="group_name">Shed</label>
                    <select v-on:input="onShedChanged($event)" name="shed" v-validate="'required'" v-model="mainEntry.shedId" class="form-control">
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
                    <label for="name">Shed Details</label>
                    <input readonly class="form-control" v-model="shedDescription">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="group_name">Breed</label>
                    <select name="breed" :disabled="deactivate" v-validate="'required'" v-model="mainEntry.breedTypeId" class="form-control">
                        <option value="">Select Breed</option>
                        <option v-for="item in breedData" :value="item.id">
                            {{item.name}}
                        </option>
                    </select>
                    <span class="text-danger">{{ errors.first('shed')}}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Lot</label>
                    <input v-validate="'required'" :readonly="deactivate" name="lot" v-model="mainEntry.lot" type="number" class="form-control">
                    <span class="text-danger">{{ errors.first('lot')}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-group date" id="ReportDate">
                <div id="txtdatefrom">
                    <input accesskey="r" readonly="true" class="form-control datepicker" placeholder="Select Date" ng-required="true" ng-model="SelectedReportDate" type="text" id="txtReportDate" name="txtReportDate" required="required" data-ng-animate="2">
                </div>
                <span class="input-group-addon text-pointer">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="group_name">Arrival Date
                    </label>
                    <button id="button" onclick="toggle_dp();">Toggle Datepicker</button>
                    <input id="arrival-date" :style="'display: none;'" v-validate="'required'" name="arrival date" placeholder="YYYY/MM/DD" v-model="mainEntry.arrivalDateBs" type="text" class="form-control"> {{mainEntry.arrivalDateBs}}
                    <!-- <input onclick="toggle_dp();" v-validate="'required'" name="arrival date" placeholder="YYYY/MM/DD" v-model="mainEntry.arrivalDate" type="text" class="form-control"> -->
                    <div id="date-container"></div>
                    <span class="text-danger">{{ errors.first('arrival date')}}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Arrival Age (days)</label>
                    <input v-validate="'required'" :readonly="deactivate" name="arrival age" v-model="mainEntry.arrivalAge" type="number" class="form-control">
                    <span class="text-danger">{{ errors.first('arrival age')}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="group_name">Male Quantity</label>
                    <input v-validate="'required'" name="male quantity" v-model="mainEntry.arrivalQuantityMale" type="text" class="form-control">
                    <span class="text-danger">{{ errors.first('male quantity')}}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Female Quantity</label>
                    <input v-validate="'required'" name="female quantity" v-model="mainEntry.arrivalQuantityFemale" type="number" class="form-control">
                    <span class="text-danger">{{ errors.first('female quantity')}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- <div class="col-md-6">
                <div class="form-group">
                    <label for="group_name">Status</label>
                    <select name="status" v-validate="'required'" v-model="mainEntry.status" class="form-control">
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="2">InActive</option>
                    </select>
                    <span class="text-danger">{{ errors.first('status')}}</span>
                </div>
            </div> -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea v-model="mainEntry.description" type="number" class="form-control"></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?php echo base_url() ?>/mainEntry" class="btn btn-secondary float-right ml-2">Back</a>
            <button type="button" id="submit-button" v-on:click="submitMainEntry()" class="btn btn-primary float-right">Submit</button>

        </div>
    </div>
    <!-- /.card-body -->
</div>
<?= $this->endSection() ?>
<!-- content section ended -->

<script>
    $('#ReportDate').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        forceParse: false,
        Default: true,
        pickDate: true,
        todayHighlight: true,

    });
</script>

<!-- Script section started -->
<?= $this->section("script") ?>
<script src="<?php echo base_url() ?>/datepicker/datepicker.min.js"></script>
<script>
    new Vue({
        el: "#app",
        data: {
            shedData: [],
            breedData: [],
            shedDescription: '',
            deactivate: false,
            // resArrivalAge: '',
            mainEntry: {
                id: '',
                shedId: '',
                lot: '',
                arrivalDate: '',
                arrivalDateBs: '',
                arrivalAge: '',
                arrivalQuantityMale: '',
                arrivalQuantityFemale: '',
                breedTypeId: '',
                status: '',
                description: '',
            }
        },
        methods: {
            loadShedData() {
                let vm = this;
                axios.get("<?php echo base_url() ?>/api/settings/shed")
                    .then(function(response) {
                        vm.shedData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            changeCalenderType() {
                let vm = this;
            },
            loadBreed() {
                let vm = this;
                axios.get("<?php echo base_url() ?>/api/settings/breed")
                    .then(function(response) {
                        vm.breedData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            onShedChanged(event) {
                let vm = this;
                var id = event.target.value;
                vm.mainEntry.lot = '';
                vm.mainEntry.breedTypeId = '';
                vm.shedDescription = '';
                vm.deactivate = false;
                if (id)
                    axios.get("<?php echo base_url() ?>/api/stock/shed", {
                        params: {
                            shedId: id
                        }
                    }).then(function(response) {
                        if (response.data.length > 0) {
                            console.log(response.data);
                            vm.mainEntry.lot = response.data[0].lot;
                            vm.mainEntry.shedId = response.data[0].shedId;
                            vm.mainEntry.breedTypeId = response.data[0].breedTypeId;
                            vm.mainEntry.arrivalAge = response.data[0].ageInDays;
                            vm.shedDescription = response.data[0].shedDetail;
                            vm.deactivate = true;
                        } else {
                            var shed = vm.shedData.filter(x => x.id == id);
                            if (shed.length > 0) {
                                vm.shedDescription = shed[0].description;
                            }
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            submitMainEntry() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                console.log(vm.mainEntry);
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        console.log(vm.mainEntry);
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url() ?>/api/mainEntry", vm.mainEntry)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                // submitbutton.disabled = false;
                                console.log(response);
                                alert(response.data.messages);
                                window.location.href = `<?php echo base_url() ?>/mainEntry`;
                            })
                            .catch(function(error) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(error);
                                vm.mainEntry.arrivalDateBs.value = '';
                                alert(error.response.data.messages.error);
                            });
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
                ndpTriggerButton: true,
                container: '#date-container',
                ndpTriggerButtonText: 'pick nepali date',
                onChange: function(event) {
                    vm.mainEntry.arrivalDate = event.ad;
                    vm.mainEntry.arrivalDateBs = event.bs;
                    console.log(vm.mainEntry);

                }
            });
            vm.loadShedData();
            vm.loadBreed();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->