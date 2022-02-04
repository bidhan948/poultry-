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
            <div class="spinner-div text-center" v-if="breederInfoLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!breederInfoLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2">#</th>
                        <th colspan="2">Hatchability (%)</th>
                        <th colspan="2">Fertility (%)</th>
                        <th colspan="2">Hatch of fertiles (%)</th>
                        <th colspan="2">Chick no./hen housed</th>
                        <th rowspan="2">Chick weight (gram)</th>
                        <th rowspan="2">Age In Weeks</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        <th>Weekly</th>
                        <th>Cumulative</th>
                        <th>Weekly</th>
                        <th>Cumulative</th>
                        <th>Weekly</th>
                        <th>Cumulative</th>
                        <th>Weekly</th>
                        <th>Cumulative</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in breederInfoData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.hatchabilityWeekly}}</td>
                        <td class="text-center">{{item.hatchabilityCum}}</td>
                        <td class="text-center">{{item.fertilityWeekly}}</td>
                        <td class="text-center">{{item.fertilityCum}}</td>
                        <td class="text-center">{{item.hatchOfFertilesWeekly}}</td>
                        <td class="text-center">{{item.hatchOfFertilesCum}}</td>
                        <td class="text-center">{{item.chickNoHenHousedWeekly}}</td>
                        <td class="text-center">{{item.chickNoHenHousedCum}}</td>
                        <td class="text-center">{{item.chickWeightGram}}</td>
                        <td class="text-center">{{item.ageInWeeks}}</td>
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
                    <h4 class="modal-title" id="myModalLabel">Medicine/Vaccine {{standardBreederInformationModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatchability (%) Weekly</label>
                                <input placeholder="Hatchability (%) Weekly" name="hatchability (%) weekly" v-validate="'required'" v-model="standardBreederInformationModel.hatchabilityWeekly" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('hatchability (%) weekly')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatchability (%) Cumulative</label>
                                <input placeholder="Hatchability (%) Cumulative" name="hatchability (%) cumulative" v-validate="'required'" v-model="standardBreederInformationModel.hatchabilityCum" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('hatchability (%) cumulative')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fertility (%) Weekly</label>
                                <input placeholder="Fertility (%) Weekly" name="fertility (%) weekly" v-validate="'required'" v-model="standardBreederInformationModel.fertilityWeekly" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('fertility (%) weekly')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Fertility (%) Cumulative</label>
                                <input placeholder="Fertility (%) Cumulative" name="fertility (%) cumulative" v-validate="'required'" v-model="standardBreederInformationModel.fertilityCum" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('fertility (%) cumulative')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatch of fertiles (%) Weekly</label>
                                <input placeholder="Fertility (%) Weekly" name="hatch of fertiles (%) weekly" v-validate="'required'" v-model="standardBreederInformationModel.hatchOfFertilesWeekly" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('hatch of fertiles (%) weekly')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatch of fertiles (%) Cumulative</label>
                                <input placeholder="Hatch of fertiles (%) Cumulative" name="hatch of fertiles (%) cumulative" v-validate="'required'" v-model="standardBreederInformationModel.hatchOfFertilesCum" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('hatch of fertiles (%) cumulative')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Chick no./hen housed Weekly</label>
                                <input placeholder="Chick no./hen housed Weekly" name="chick no./hen housed weekly" v-validate="'required'" v-model="standardBreederInformationModel.chickNoHenHousedWeekly" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('chick no./hen housed weekly')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Chick no./hen housed Cumulative</label>
                                <input placeholder="Chick no./hen housed Cumulative" name="chick no./hen housed cumulative" v-validate="'required'" v-model="standardBreederInformationModel.chickNoHenHousedCum" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('chick no./hen housed cumulative')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Chick weight (gram)</label>
                                <input placeholder="Chick weight (gram)" name="chick weight (gram)" v-validate="'required'" v-model="standardBreederInformationModel.chickWeightGram" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('chick weight (gram)')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Age In Weeks</label>
                                <input placeholder="Age In Weeks" name="age in weeks" v-validate="'required'" v-model="standardBreederInformationModel.ageInWeeks" type="text" class="form-control">
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
            breederInfoData: [],
            standardBreederInformationModel: {
                id: '',
                ageInWeeks: '',
                hatchabilityWeekly: '',
                hatchabilityCum: '',
                fertilityWeekly: '',
                fertilityCum: '',
                hatchOfFertilesWeekly: '',
                hatchOfFertilesCum: '',
                chickNoHenHousedWeekly: '',
                chickNoHenHousedCum: '',
                chickWeightGram: '',
            },
            isPosting: false,
            breederInfoLoading: false,
        },
        methods: {
            loadStandardBreederInformation() {
                let vm = this;
                vm.breederInfoLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/standardBreederInformation")
                    .then(function(response) {
                        vm.breederInfoLoading = false;
                        vm.breederInfoData = response.data;
                    })
                    .catch(function(error) {
                        vm.breederInfoLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addStandardBreederInformation() {
                let vm = this;
                vm.standardBreederInformationModel.id = '';
                vm.standardBreederInformationModel.ageInWeeks = '';
                vm.standardBreederInformationModel.hatchabilityWeekly = '';
                vm.standardBreederInformationModel.hatchabilityCum = '';
                vm.standardBreederInformationModel.fertilityWeekly = '';
                vm.standardBreederInformationModel.fertilityCum = '';
                vm.standardBreederInformationModel.hatchOfFertilesWeekly = '';
                vm.standardBreederInformationModel.hatchOfFertilesCum = '';
                vm.standardBreederInformationModel.chickNoHenHousedWeekly = '';
                vm.standardBreederInformationModel.chickNoHenHousedCum = '';
                vm.standardBreederInformationModel.chickWeightGram = '';
                openModal();
            },
            updateMedicineVaccine(item) {
                let vm = this;
                vm.standardBreederInformationModel.id = item.id;
                vm.standardBreederInformationModel.ageInWeeks = item.ageInWeeks;
                vm.standardBreederInformationModel.hatchabilityWeekly = item.hatchabilityWeekly;
                vm.standardBreederInformationModel.hatchabilityCum = item.hatchabilityCum;
                vm.standardBreederInformationModel.fertilityWeekly = item.fertilityWeekly;
                vm.standardBreederInformationModel.fertilityCum = item.fertilityCum;
                vm.standardBreederInformationModel.hatchOfFertilesWeekly = item.hatchOfFertilesWeekly;
                vm.standardBreederInformationModel.hatchOfFertilesCum = item.hatchOfFertilesCum;
                vm.standardBreederInformationModel.chickNoHenHousedWeekly = item.chickNoHenHousedWeekly;
                vm.standardBreederInformationModel.chickNoHenHousedCum = item.chickNoHenHousedCum;
                vm.standardBreederInformationModel.chickWeightGram = item.chickWeightGram;
                openModal();
            },
            submitStandardBreederInformation() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/standardBreederInformation", vm.standardBreederInformationModel)
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