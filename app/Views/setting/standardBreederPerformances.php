<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Standard Breeder Information</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addStandardBreederInformation()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">
        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="breederPerformanceLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!breederPerformanceLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Age In Weeks</th>
                        <th>Total Eggs (%HW)</th>
                        <th>Hatching Eggs (%HW)</th>
                        <th>Mortality Cum (%)</th>
                        <th>%HE (Weekly)</th>
                        <th>Total Eggs (HH)</th>
                        <th>Hatching Eggs (HH)</th>
                        <th>HHHE</th>
                        <th>Hen House Number</th>
                        <th>Feed Conversion Ratio</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in breederPerformanceeData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.ageInWeeks}}</td>
                        <td class="text-center">{{item.totalEggsPercentageHw}}</td>
                        <td class="text-center">{{item.hatchingEggsPercentageHw}}</td>
                        <td class="text-center">{{item.mortalityCumPercentage}}</td>
                        <td class="text-center">{{item.percentageHeWeekly}}</td>
                        <td class="text-center">{{item.totalEggsHh}}</td>
                        <td class="text-center">{{item.hatchingEggsHh}}</td>
                        <td class="text-center">{{item.hhhe}}</td>
                        <td class="text-center">{{item.henHouseNumber}}</td>
                        <td class="text-center">{{item.feedConversionRatio}}</td>
                        <td class="text-center">
                            <button type="button" v-on:click="updateMedicineVaccine(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.card-body -->


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Standard Breeder Performance {{standardBreederPerformanceModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Total Eggs (%HW)</label>
                                <input placeholder="Total Eggs (%HW)" name="Total Eggs (%HW)" v-validate="'required'" v-model="standardBreederPerformanceModel.totalEggsPercentageHw" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Total Eggs (%HW)')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatching Eggs (%HW) </label>
                                <input placeholder="Hatching Eggs (%HW) " name="Hatching Eggs (%HW) " v-validate="'required'" v-model="standardBreederPerformanceModel.hatchingEggsPercentageHw" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Hatching Eggs (%HW) ')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Mortality Cum (%)</label>
                                <input placeholder="Mortality Cum (%)" name="Mortality Cum (%)" v-validate="'required'" v-model="standardBreederPerformanceModel.mortalityCumPercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Mortality Cum (%)')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">%HE (Weekly)</label>
                                <input placeholder="%HE (Weekly)" name="%HE (Weekly)" v-validate="'required'" v-model="standardBreederPerformanceModel.percentageHeWeekly" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('%HE (Weekly)')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Total Eggs (HH)</label>
                                <input placeholder="Fertility (%) Weekly" name="Total Eggs (HH)" v-validate="'required'" v-model="standardBreederPerformanceModel.totalEggsHh" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Total Eggs (HH)')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatching Eggs (HH)</label>
                                <input placeholder="Hatching Eggs (HH)" name="Hatching Eggs (HH)" v-validate="'required'" v-model="standardBreederPerformanceModel.hatchingEggsHh" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Hatching Eggs (HH)')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">HHHE</label>
                                <input placeholder="HHHE" name="HHHE" v-validate="'required'" v-model="standardBreederPerformanceModel.hhhe" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('HHHE')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hen House Number</label>
                                <input placeholder="Hen House Number" name="Hen House Number" v-validate="'required'" v-model="standardBreederPerformanceModel.henHouseNumber" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Hen House Number')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Feed Conversion Ratio</label>
                                <input placeholder="Feed Conversion Ratio" name="Feed Conversion Ratio" v-validate="'required'" v-model="standardBreederPerformanceModel.feedConversionRatio" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Feed Conversion Ratio')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Age In Weeks</label>
                                <input placeholder="Age In Weeks" name="age in weeks" v-validate="'required'" v-model="standardBreederPerformanceModel.ageInWeeks" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('age in weeks')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-button" v-on:click="submitStandardBreederInformation()" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
<!-- content section ended -->



<!-- Script section started -->
<?= $this->section("script") ?>
<script>
    function openModal() {
        $("#myModal").modal('show');
    }

    function closeModal() {
        $("#myModal").modal('hide');
    }
    new Vue({
        el: "#app",
        data: {
            breederPerformanceeData: [],
            standardBreederPerformanceModel: {
                id: '',
                ageInWeeks: '',
                totalEggsPercentageHw: '',
                hatchingEggsPercentageHw: '',
                mortalityCumPercentage: '',
                percentageHeWeekly: '',
                totalEggsHh: '',
                hatchingEggsHh: '',
                hhhe: '',
                henHouseNumber: '',
                feedConversionRatio: '',
            },
            isPosting: false,
            breederPerformanceLoading: false,
        },
        methods: {
            loadStandardBreederInformation() {
                let vm = this;
                vm.breederPerformanceLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/standardBreederPerformances")
                    .then(function(response) {
                        vm.breederPerformanceLoading = false;
                        vm.breederPerformanceeData = response.data;
                    })
                    .catch(function(error) {
                        vm.breederPerformanceLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addStandardBreederInformation() {
                let vm = this;
                vm.standardBreederPerformanceModel.id = '';
                vm.standardBreederPerformanceModel.ageInWeeks = '';
                vm.standardBreederPerformanceModel.totalEggsPercentageHw = '';
                vm.standardBreederPerformanceModel.hatchingEggsPercentageHw = '';
                vm.standardBreederPerformanceModel.mortalityCumPercentage = '';
                vm.standardBreederPerformanceModel.percentageHeWeekly = '';
                vm.standardBreederPerformanceModel.totalEggsHh = '';
                vm.standardBreederPerformanceModel.hatchingEggsHh = '';
                vm.standardBreederPerformanceModel.hhhe = '';
                vm.standardBreederPerformanceModel.henHouseNumber = '';
                vm.standardBreederPerformanceModel.feedConversionRatio = '';
                openModal();
            },
            updateMedicineVaccine(item) {
                let vm = this;
                vm.standardBreederPerformanceModel.id = item.id;
                vm.standardBreederPerformanceModel.ageInWeeks = item.ageInWeeks;
                vm.standardBreederPerformanceModel.totalEggsPercentageHw = item.totalEggsPercentageHw;
                vm.standardBreederPerformanceModel.hatchingEggsPercentageHw = item.hatchingEggsPercentageHw;
                vm.standardBreederPerformanceModel.mortalityCumPercentage = item.mortalityCumPercentage;
                vm.standardBreederPerformanceModel.percentageHeWeekly = item.percentageHeWeekly;
                vm.standardBreederPerformanceModel.totalEggsHh = item.totalEggsHh;
                vm.standardBreederPerformanceModel.hatchingEggsHh = item.hatchingEggsHh;
                vm.standardBreederPerformanceModel.hhhe = item.hhhe;
                vm.standardBreederPerformanceModel.henHouseNumber = item.henHouseNumber;
                vm.standardBreederPerformanceModel.feedConversionRatio = item.feedConversionRatio;
                openModal();
            },
            submitStandardBreederInformation() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/standardBreederPerformances", vm.standardBreederPerformanceModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadStandardBreederInformation();
                            })
                            .catch(function(error) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(error);
                                alert(error.response.data.messages.error);
                                // alert("Some Problem Occured");
                            });
                    }
                })
            },
        },
        mounted() {
            let vm = this;
            vm.loadStandardBreederInformation();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->