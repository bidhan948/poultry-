<?= $this->extend("layout/master") ?>



<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url()?>/datepicker/datepicker.min.css">
<style>
   
</style>
<?= $this->endSection() ?>
<!-- content section started -->



<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Add Daily Entry</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="group_name">Shed</label>
                    <select v-on:input="onShedChanged($event)" name="shed" v-validate="'required'" v-model="dailyEntryModel.shedId" class="form-control">
                        <option value="">Select Shed </option>
                        <option v-for="item in shedData" :value="item.id">
                            {{item.name}}
                        </option>
                    </select>
                    <span class="text-danger">{{ errors.first('shed')}}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="group_name">Date</label>
                    <input id="arrival-date" v-validate="'required'" name="date" placeholder="YYYY/MM/DD" v-model="dailyEntryModel.dateBs" type="text" class="form-control">
                    <span class="text-danger">{{ errors.first('date')}}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Shed Details</label>
                    <textarea readonly class="form-control" v-model="shedDescription"></textarea>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="name">Description</label>
                    <textarea v-model="dailyEntryModel.description" type="number" class="form-control"></textarea>
                </div>
            </div>
        </div>
      
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="4" class="text-center"><b>Temperature</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><b>Morning</b></td>
                    <td colspan="2" class="text-center"><b>Evening</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Inside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.morningInTemp">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Outside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.morningOutTemp">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Inside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.eveningInTemp">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Outside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.eveningOutTemp">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="4" class="text-center"><b>Humidity</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center"><b>Morning</b></td>
                    <td colspan="2" class="text-center"><b>Evening</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Inside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.morningInHumidity">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Outside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.morningOutHumidity">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Inside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.eveningInHumidity">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Outside</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.eveningOutHumidity">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Egg Production</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Total Egg Production</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.totalEggProduction">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Broken Egg Count</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.brokenEggCount">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Average Egg Weight</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.avgEggWeight">
                        </div>
                    </td>

                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">S.T.D %</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.std">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">N.H.E</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.nhe">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">%</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.percent">
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Light</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Start Time</label>
                            <input type="text" class="form-control form-control-sm" v-model="dailyEntryModel.lightStart">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">End Time</label>
                            <input type="text" class="form-control form-control-sm" v-model="dailyEntryModel.lightOut">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Lux</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.lightLux">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center"></td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Feed</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Male</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.feedMale">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Female</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.feedFemale">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Feed Type</label>
                            <select name="feed type" v-model="dailyEntryModel.feedTypeId" class="form-control form-control-sm">
                                <option value="">Select Feed Type</option>
                                <option v-for="item in feedData" :value="item.id">
                                    {{item.name}}
                                </option>
                            </select>
                        </div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Body Weight</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Male</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.weightMale">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Female</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.weightFemale">
                        </div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Mortality</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Male</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.mortalityMale">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Female</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.mortalityFemale">
                        </div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Culling</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Male</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.cullingMale">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Female</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.cullingFemale">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="3" class="text-center"><b>Medicines / Vaccine Used</b></td>
                </tr>
                <tr v-for="(item, index) in dailyEntryModel.medicineVaccine" style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Medicine/Vaccine</label>
                            <select v-model="item.medicinevaccineId" class="form-control form-control-sm text-center">
                                <option value="">Select Medicine/Vaccine</option>
                                <option v-for="item in medVacData" :value="item.id">
                                    {{item.name}}
                                </option>
                            </select>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Quantity</label>
                            <input type="text" class="form-control form-control-sm" v-model="item.quantity">
                        </div>
                    </td>
                    <td class="text-center ">
                        <button v-if="index == dailyEntryModel.medicineVaccine.length -1" v-on:click="onMedicineAddClick()" class="btn btn-warning mt-4 btn-sm"><i class="fa fa-plus"></i></button>
                        <button v-if="index != 0" v-on:click="onMedicineRemoveClick(index)" class="btn btn-danger mt-4 btn-sm"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row">
            <table class="table table-bordered table-sm" style="width:100%">
                <tr>
                    <td colspan="4" class="text-center"><b>Remarks</b></td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Cooling Pad 1</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.coolingPad1">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Cooling Pad 2</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.coolingPad2">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Cooling Pad 3</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.coolingPad3">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Water</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.water">
                        </div>
                    </td>
                </tr>
                <tr style="background-color:#BCC6CC;">
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Fan</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.fan">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <label for="name">Feeding Trolly</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.feedingTrolly">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Screper / Belt</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.screeper">
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group form-group-sm">
                            <label for="name">Conveyer</label>
                            <input type="number" class="form-control form-control-sm" v-model="dailyEntryModel.conveyer">
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="myclear"></div>
        <div class="card-footer">
            <a href="/home" class="btn btn-secondary float-right ml-2">Back</a>
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
            feedData: [],
            medVacData: [],
            remarksTypeData: [],
            shedDescription: '',
            dailyEntryModel: {
                id: '',
                shedId: '',
                date: '',
                dateBs: '',
                morningInTemp: '',
                morningOutTemp: '',
                eveningInTemp: '',
                eveningOutTemp: '',
                morningInHumidity: '',
                morningOutHumidity: '',
                eveningInHumidity: '',
                eveningOutHumidity: '',
                totalEggProduction: '',
                brokenEggCount: '',
                nhe: '',
                std: '',
                percent: '',
                avgEggWeight: '',
                lightStart: '',
                lightOut: '',
                lightLux: '',
                lightTime: '',
                feedMale: '',
                feedFemale: '',
                feedTypeId: '',
                weightMale: '',
                weightFemale: '',
                mortalityMale: '',
                mortalityFemale: '',
                cullingMale: '',
                cullingFemale: '',
                male: '',
                female: '',
                description: '',
                coolingPad1: '',
                coolingPad2: '',
                coolingPad3: '',
                water: '',
                fan: '',
                feedingTrolly: '',
                screeper: '',
                conveyer: '',
                medicineVaccine: [{
                    medicinevaccineId: '',
                    quantity: ''
                }],
            },
            tempDailyEntryModel: {
                id: '',
                shedId: '',
                date: '',
                dateBs: '',
                morningInTemp: '',
                morningOutTemp: '',
                eveningInTemp: '',
                eveningOutTemp: '',
                morningInHumidity: '',
                morningOutHumidity: '',
                eveningInHumidity: '',
                eveningOutHumidity: '',
                totalEggProduction: '',
                brokenEggCount: '',
                nhe: '',
                std: '',
                percent: '',
                avgEggWeight: '',
                lightStart: '',
                lightOut: '',
                lightLux: '',
                lightTime: '',
                feedMale: '',
                feedFemale: '',
                feedTypeId: '',
                weightMale: '',
                weightFemale: '',
                mortalityMale: '',
                mortalityFemale: '',
                cullingMale: '',
                cullingFemale: '',
                male: '',
                female: '',
                description: '',
                coolingPad1: '',
                coolingPad2: '',
                coolingPad3: '',
                water: '',
                fan: '',
                feedingTrolly: '',
                screeper: '',
                conveyer: '',
                medicineVaccine: [{
                    medicinevaccineId: '',
                    quantity: ''
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
            loadDailyEntryByShedAndData(shedId) {
                let vm = this;
                if (vm.dailyEntryModel.date) {
                    axios.get("<?php echo base_url()?>/api/dailyEntry/shedAndDate", {
                            params: {
                                shed: shedId,
                                date: vm.dailyEntryModel.date
                            }
                        })
                        .then(function(response) {
                            
                            if (response.data) {
                                vm.dailyEntryModel = response.data;
                                if (response.data?.medicineVaccine.length > 0) {
                                } else {
                                    vm.dailyEntryModel.medicineVaccine = vm.tempDailyEntryModel.medicineVaccine
                                }
                            } else {
                                vm.tempDailyEntryModel.date =  vm.dailyEntryModel.date;
                                vm.tempDailyEntryModel.dateBs =  vm.dailyEntryModel.dateBs;
                                vm.tempDailyEntryModel.shedId =  vm.dailyEntryModel.shedId;
                                vm.dailyEntryModel = vm.tempDailyEntryModel;
                            }
                        })
                        .catch(function(error) {
                            debugger;
                            console.log(error);
                            alert("Some Problem Occured");
                        });
                }
            },
            loadFeedTypeData() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/feedType")
                    .then(function(response) {
                        vm.feedData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            loadMedicineVaccine() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/medicineVaccine")
                    .then(function(response) {
                        if (response.data) {
                            vm.medVacData = response.data;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
           
            onShedChanged(event) {
                let vm = this;
                var id = event.target.value;
                vm.shedDescription = '';
                if (id) {
                    var shed = vm.shedData.filter(x => x.id == id);
                    vm.loadDailyEntryByShedAndData(id);
                    if (shed.length > 0) {
                        vm.shedDescription = shed[0].description;
                    }
                }
            },
            onMedicineAddClick() {
                let vm = this;
                vm.dailyEntryModel.medicineVaccine.push({
                    type: '',
                    medicinevaccineId: '',
                    quantity: ''
                });
            },
            onMedicineRemoveClick(index) {
                let vm = this;
                vm.dailyEntryModel.medicineVaccine.splice(index, 1);
            },
            submitDailyEntry() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/dailyEntry/add", vm.dailyEntryModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                window.location = "<?php echo base_url()?>/dailyEntry";
                                console.log(response);
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
                    vm.dailyEntryModel.date = event.ad;
                    vm.dailyEntryModel.dateBs = event.bs;
                    if (vm.dailyEntryModel.shedId) {
                        vm.loadDailyEntryByShedAndData(vm.dailyEntryModel.shedId);
                    }
                }
            });
            vm.loadShedData();
            vm.loadFeedTypeData();
            vm.loadMedicineVaccine();
            //vm.loadRemarksTypeData();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->