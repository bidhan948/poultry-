<?= $this->extend("layout/master") ?>



<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url() ?>/datepicker/datepicker.min.css">
<?= $this->endSection() ?>
<!-- content section started -->



<?= $this->section("content") ?>
<div id="app">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Main Entry</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="group_name">Shed</label>
                        <select v-on:input="onShedChanged($event.target.value)" name="shed" v-validate="'required'" v-model="mainEntry.shedId" class="form-control">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="group_name">Arrival Date</label>
                        <input id="arrival-date" v-validate="'required'" name="arrival date" placeholder="YYYY/MM/DD" v-model="mainEntry.arrivalDateBs" type="text" class="form-control">
                        <span class="text-danger">{{ errors.first('arrival date')}}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Arrival Age (days)</label>
                        <input v-validate="'required'" name="arrival age" v-model="mainEntry.arrivalAge" type="number" class="form-control">
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

                <table v-if="mainEntry.extendedMainEntry.length > 0" class="table table-striped table-bordered table-sm">
                    <thead>
                        <th>S.N</th>
                        <th>Arrival Date</th>
                        <!-- <th>Age</th> -->
                        <th>Male</th>
                        <th>Female</th>
                        <th>#</th>
                    </thead>
                    <tbody>
                        <tr v-for="(item,index) in mainEntry.extendedMainEntry">
                            <td>{{index + 1}}</td>
                            <td>
                                <input :id="'arrival-date-'+ index" v-validate="'required'" name="arrival date" placeholder="YYYY/MM/DD" v-model="item.arrivalDateBs" type="text" class="form-control form-control-sm">
                            </td>
                            <!-- <td>
                                <input v-validate="'required'" name="arrival age" v-model="item.arrivalAge" type="number" class="form-control form-control-sm">
                            </td> -->
                            <td><input v-validate="'required'" name="male quantity" v-model="item.arrivalQuantityMale" type="number" class="form-control form-control-sm"></td>
                            <td><input v-validate="'required'" name="female quantity" v-model="item.arrivalQuantityFemale" type="number" class="form-control form-control-sm"></td>
                            <td class="text-center ">
                                <button v-if="index == mainEntry.extendedMainEntry.length -1" v-on:click="onExtendedAddClicked()" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i></button>
                                <button v-if="index != 0" v-on:click="onExtendedRemoveClick(index)" class="btn btn-danger  btn-sm"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Description</label>
                        <textarea v-model="mainEntry.description" type="number" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="/mainEntry" class="btn btn-secondary float-right ml-2">Back</a>
                <button type="button" id="submit-button" v-on:click="submitMainEntry()" class="btn btn-primary float-right">Submit</button>

            </div>
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
            breedData: [],
            resArrivalAge: [],
            shedDescription: '<?php echo $shed->description; ?>',
            deactivate: true,
            mainEntry: {
                id: '<?php echo $mainEntry->id; ?>',
                shedId: '<?php echo $mainEntry->shedId; ?>',
                lot: '<?php echo $mainEntry->lot; ?>',
                arrivalDate: '<?php echo $mainEntry->arrivalDate; ?>',
                arrivalDateBs: '<?php echo $mainEntry->arrivalDateBs; ?>',
                arrivalAge: '<?php echo $mainEntry->arrivalAge; ?>',
                arrivalQuantityMale: '<?php echo $mainEntry->arrivalQuantityMale; ?>',
                arrivalQuantityFemale: '<?php echo $mainEntry->arrivalQuantityFemale; ?>',
                breedTypeId: '<?php echo $mainEntry->breedTypeId; ?>',
                status: '<?php echo $mainEntry->status; ?>',
                description: '<?php echo $mainEntry->description; ?>',
                extendedMainEntry: <?php echo json_encode($extendedMainEntry) ?>
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
            onExtendedAddClicked() {
                let vm = this;
                console.log(vm.$refs);
                vm.mainEntry.extendedMainEntry.push({
                    arrivalDate: '',
                    arrivalDateBs: '',
                    mainEntryId: '<?php echo $mainEntry->id; ?>',
                    arrivalQuantityMale: '',
                    arrivalQuantityFemale: '',
                    arrivalAge: ''
                });
                // vm.addDatePickerInExtendedEntry();
            },
            onExtendedRemoveClick(index) {
                let vm = this;
                vm.mainEntry.extendedMainEntry.splice(index, 1);
            },

            onShedChanged(id) {
                let vm = this;
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
                            vm.mainEntry.lot = response.data[0].lot;
                            vm.mainEntry.breedTypeId = response.data[0].breedTypeId;
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
            addMoreEntry(shedId, id) {
                let vm = this;
                console.log(vm.mainEntry.arrivalDateBs);
                axios.get("<?= base_url('/api/addMoreEntry') ?>", {
                        params: {
                            shedId: shedId,
                            id: id,
                        }
                    }).then(function(response) {
                        console.log(response.data);
                        vm.resArrivalAge = response.data;
                        vm.isDisplay = false;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert('Some Problems Occured');
                    })
            },
            addDatePickerInExtendedEntry() {
                let vm = this;
                vm.mainEntry.extendedMainEntry.forEach((element, index) => {
                    var arrivalDate = document.getElementById("arrival-date-" + index);
                    arrivalDate.nepaliDatePicker({
                        readOnlyInput: true,
                        ndpMonth: true,
                        ndpYear: true,
                        ndpYearCount: 10,
                        dateFormat: "YYYY/MM/DD",
                        onChange: function(event) {
                            vm.mainEntry.extendedMainEntry[index].arrivalDate = event.ad;
                            vm.mainEntry.extendedMainEntry[index].arrivalDateBs = event.bs;
                        }
                    });
                });
            },
            submitMainEntry() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
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
                                debugger;
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(error);
                                alert(error.response.data.messages.error);
                            });
                    }
                })
            }
        },
        updated() {
            let vm = this;
            vm.addDatePickerInExtendedEntry();
        },
        mounted() {
            let vm = this;
            vm.addDatePickerInExtendedEntry();
            var arrivalDate = document.getElementById("arrival-date");
            arrivalDate.nepaliDatePicker({
                readOnlyInput: true,
                ndpMonth: true,
                ndpYear: true,
                ndpYearCount: 10,
                dateFormat: "YYYY/MM/DD",
                onChange: function(event) {
                    vm.mainEntry.arrivalDate = event.ad;
                    vm.mainEntry.arrivalDateBs = event.bs;
                }
            });
            vm.loadShedData();
            vm.loadBreed();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->