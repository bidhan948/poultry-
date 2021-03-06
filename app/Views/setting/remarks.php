<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Unit</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addRemarks()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="remarksDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!remarksDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Unit Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in remarksData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.name}}</td>
                        <td class="text-center">{{item.unitName}}</td>
                        <td class="text-center">{{item.status == 1?'Active':'InActive'}}</td>
                        <td class="text-center">{{item.description}}</td>
                        <td class="text-center">
                            <button type="button" v-on:click="updateRemarks(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
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
                    <h4 class="modal-title" id="myModalLabel">Remarks Type {{remarksType.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Remarks Type Name</label>
                        <input placeholder="Remarks Type Name" name="remarks type name" v-validate="'required'" v-model="remarksType.name" type="text" class="form-control" id="name">
                        <span class="text-danger">{{ errors.first('remarks type name')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="group_name">Unit</label>
                        <select name="unit" v-validate="'required'" v-model="remarksType.unitId" class="form-control">
                            <option value="">Select Unit</option>
                            <option v-for="item in unitData" :value="item.id">
                                {{item.name}}
                            </option>
                        </select>
                        <span class="text-danger">{{ errors.first('unit')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="group_name">Status</label>
                        <select name="unit" v-validate="'required'" v-model="remarksType.status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="0">InActive</option>
                            <option value="1">Active</option>

                        </select>
                        <span class="text-danger">{{ errors.first('unit')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="password" class="form-control" id="description" v-model="remarksType.description" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-button" v-on:click="submitUnit()" class="btn btn-primary">Submit</button>
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
            remarksData: [],
            unitData: [],
            remarksType: {
                id: '',
                name: '',
                unitId: '',
                status: '',
                description: ''
            },
            isPosting: false,
            remarksDataLoading: false,
        },
        methods: {
            loadRemarks() {
                let vm = this;
                vm.remarksDataLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/remarks")
                    .then(function(response) {
                        vm.remarksDataLoading = false;
                        vm.remarksData = response.data;
                    })
                    .catch(function(error) {
                        vm.remarksDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            loadUnit() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/unit")
                    .then(function(response) {
                        vm.unitData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addRemarks() {
                let vm = this;
                vm.remarksType.id = '';
                vm.remarksType.name = '';
                vm.remarksType.unitId = '';
                vm.remarksType.status = '';
                vm.remarksType.description = '';
                openModal();
            },
            updateRemarks(group) {
                let vm = this;
                vm.remarksType.id = group.id;
                vm.remarksType.name = group.name;
                vm.remarksType.unitId = group.unitId;
                vm.remarksType.status = group.status;
                vm.remarksType.description = group.description;
                openModal();
            },
            submitUnit() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/remarks", vm.remarksType)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadRemarks();
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
            vm.loadRemarks();
            vm.loadUnit();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->