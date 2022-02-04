<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Medicine/Vaccine</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addMedicineVaccine()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="medicineVaccineDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!medicineVaccineDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Physical Form</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in medicineVaccineData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.type==1?'Medicine': 'Vaccine'}}</td>
                        <td class="text-center">{{item.name}}</td>
                        <td class="text-center">{{item.physicalForm}}</td>
                        <td class="text-center">{{item.description}}</td>
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
                    <h4 class="modal-title" id="myModalLabel">Medicine/Vaccine {{medicineVaccineModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select name="type" v-validate="'required'" v-model="medicineVaccineModel.type"
                                                    class="form-control">
                                                    <option value="">Select Type</option>
                                                    <option value="1">Medicine</option>
                                                    <option value="2">Vaccine</option>
                                                   
                                                </select>
                        <span class="text-danger">{{ errors.first('type')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input placeholder="Name" name="name" v-validate="'required'" v-model="medicineVaccineModel.name" type="text" class="form-control" id="name">
                        <span class="text-danger">{{ errors.first('name')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="physical">Physical Form</label>
                        <input placeholder="Physical Form" name="physical form" v-validate="'required'" v-model="medicineVaccineModel.physicalForm" type="text" class="form-control" id="physical">
                        <span class="text-danger">{{ errors.first('physical form')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="password" class="form-control" id="description" v-model="medicineVaccineModel.description" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-button" v-on:click="submitMedicineVaccine()" class="btn btn-primary">Submit</button>
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
            medicineVaccineData: [],
            medicineVaccineModel: {
                id: '',
                type: '',
                name: '',
                physicalForm: '',
                description: ''
            },
            isPosting: false,
            medicineVaccineDataLoading: false,
        },
        methods: {
            loadMedicineVaccine() {
                let vm = this;
                vm.medicineVaccineDataLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/medicineVaccine")
                    .then(function(response) {
                        vm.medicineVaccineDataLoading = false;
                        vm.medicineVaccineData = response.data;
                    })
                    .catch(function(error) {
                        vm.medicineVaccineDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addMedicineVaccine() {
                let vm = this;
                vm.medicineVaccineModel.id = '';
                vm.medicineVaccineModel.type = '';
                vm.medicineVaccineModel.name = '';
                vm.medicineVaccineModel.physicalForm = '';
                vm.medicineVaccineModel.description = '';
                openModal();
            },
            updateMedicineVaccine(group) {
                let vm = this;
                vm.medicineVaccineModel.id = group.id;
                vm.medicineVaccineModel.type = group.type;
                vm.medicineVaccineModel.name = group.name;
                vm.medicineVaccineModel.physicalForm = group.physicalForm;
                vm.medicineVaccineModel.description = group.description;
                openModal();
            },
            submitMedicineVaccine() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/medicineVaccine", vm.medicineVaccineModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadMedicineVaccine();
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
            vm.loadMedicineVaccine();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->