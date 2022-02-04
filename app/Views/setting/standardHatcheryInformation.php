<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Standard Hatchery Information</h3>
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
            <div class="spinner-div text-center" v-if="hatcheryInformationLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!hatcheryInformationLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">s.no</th>
                        <th rowspan="2" class="text-center">Age In Weeks</th>
                        <th rowspan="2" class="text-center">Fertility %</th>
                        <th rowspan="2" class="text-center">Hatchability (%)</th>
                        <th colspan="4" class="text-center">EMBRYODIAGNOSIS (%)</th>
                        <th rowspan="2" class="text-center">HOF (%)</th>
                        <th rowspan="2" class="text-center">edit</th>

                    </tr>
                    <tr>
                        <th class="text-center">Infertile</th>
                        <th class="text-center">Early</th>
                        <th class="text-center">Mid</th>
                        <th class="text-center">Late</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in hatcheryInformationData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.ageInWeeks}}</td>
                        <td class="text-center">{{item.fertilityPercentage}}</td>
                        <td class="text-center">{{item.hatchabilityPercentage}}</td>
                        <td class="text-center">{{item.embInfertilePercentage}}</td>
                        <td class="text-center">{{item.embEarlyPercentage}}</td>
                        <td class="text-center">{{item.embMidPercentage}}</td>
                        <td class="text-center">{{item.embLatePercentage}}</td>
                        <td class="text-center">{{item.hofPercentage}}</td>
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
                    <h4 class="modal-title" id="myModalLabel">Standard Hatchery Information Add {{standardHatcheryInformationModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Age In Weeks</label>
                                <input placeholder="Age In Weeks" name="Age In Weeks" v-validate="'required'" v-model="standardHatcheryInformationModel.ageInWeeks" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Age In Weeks')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> Fertility % </label>
                                <input placeholder=" Fertility % " name=" Fertility % " v-validate="'required'" v-model="standardHatcheryInformationModel.fertilityPercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first(' Fertility % ')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Hatchability (%)</label>
                                <input placeholder="Hatchability (%)" name="Hatchability (%)" v-validate="'required'" v-model="standardHatcheryInformationModel.hatchabilityPercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('Hatchability (%)')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">EMBRYODIAGNOSIS (%) Infertile</label>
                                <input placeholder="EMBRYODIAGNOSIS (%) Infertile" name="EMBRYODIAGNOSIS (%) Infertile" v-validate="'required'" v-model="standardHatcheryInformationModel.embInfertilePercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('EMBRYODIAGNOSIS (%) Infertile')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> EMBRYODIAGNOSIS (%) Early </label>
                                <input placeholder="Hatchability (%)" name=" EMBRYODIAGNOSIS (%) Early " v-validate="'required'" v-model="standardHatcheryInformationModel.embEarlyPercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first(' EMBRYODIAGNOSIS (%) Early ')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> EMBRYODIAGNOSIS (%) Mid</label>
                                <input placeholder=" EMBRYODIAGNOSIS (%) Mid" name=" EMBRYODIAGNOSIS (%) Mid" v-validate="'required'" v-model="standardHatcheryInformationModel.embMidPercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first(' EMBRYODIAGNOSIS (%) Mid')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for=""> EMBRYODIAGNOSIS (%) Late</label>
                                <input placeholder=" EMBRYODIAGNOSIS (%) Late" name=" EMBRYODIAGNOSIS (%) Late" v-validate="'required'" v-model="standardHatcheryInformationModel.embLatePercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first(' EMBRYODIAGNOSIS (%) Late')}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">HOF (%)</label>
                                <input placeholder="HOF (%)" name="HOF (%)" v-validate="'required'" v-model="standardHatcheryInformationModel.hofPercentage" type="text" class="form-control">
                                <span class="text-danger">{{ errors.first('HOF (%)')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-button" v-on:click="submitStandardHatcheryInformation()" class="btn btn-primary">Submit</button>
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
            hatcheryInformationData: [],
            standardHatcheryInformationModel: {
                id: '',
                ageInWeeks: '',
                fertilityPercentage: '',
                hatchabilityPercentage: '',
                embInfertilePercentage: '',
                embEarlyPercentage: '',
                embMidPercentage: '',
                embLatePercentage: '',
                hofPercentage: '',
            },
            isPosting: false,
            hatcheryInformationLoading: false,
        },
        methods: {
            loadStandardHatcheryInformation() {
                let vm = this;
                vm.hatcheryInformationLoading = true;
                axios.get("<?php echo base_url() ?>/api/settings/standardHatcheryInformation")
                    .then(function(response) {
                        vm.hatcheryInformationLoading = false;
                        vm.hatcheryInformationData = response.data;
                    })
                    .catch(function(error) {
                        vm.hatcheryInformationLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addStandardBreederInformation() {
                let vm = this;
                vm.standardHatcheryInformationModel.id = '';
                vm.standardHatcheryInformationModel.ageInWeeks = '';
                vm.standardHatcheryInformationModel.fertilityPercentage = '';
                vm.standardHatcheryInformationModel.hatchabilityPercentage = '';
                vm.standardHatcheryInformationModel.embInfertilePercentage = '';
                vm.standardHatcheryInformationModel.embEarlyPercentage = '';
                vm.standardHatcheryInformationModel.embMidPercentage = '';
                vm.standardHatcheryInformationModel.embLatePercentage = '';
                vm.standardHatcheryInformationModel.hofPercentage = '';
                openModal();
            },
            updateMedicineVaccine(item) {
                let vm = this;
                vm.standardHatcheryInformationModel.id = item.id;
                vm.standardHatcheryInformationModel.ageInWeeks = item.ageInWeeks;
                vm.standardHatcheryInformationModel.fertilityPercentage = item.fertilityPercentage;
                vm.standardHatcheryInformationModel.hatchabilityPercentage = item.hatchabilityPercentage;
                vm.standardHatcheryInformationModel.embInfertilePercentage = item.embInfertilePercentage;
                vm.standardHatcheryInformationModel.embEarlyPercentage = item.embEarlyPercentage;
                vm.standardHatcheryInformationModel.embMidPercentage = item.embMidPercentage;
                vm.standardHatcheryInformationModel.embLatePercentage = item.embLatePercentage;
                vm.standardHatcheryInformationModel.hofPercentage = item.hofPercentage;
                openModal();
            },
            submitStandardHatcheryInformation() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url() ?>/api/settings/standardHatcheryInformation", vm.standardHatcheryInformationModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadStandardHatcheryInformation();
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
            vm.loadStandardHatcheryInformation();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->