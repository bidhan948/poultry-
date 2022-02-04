<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Breed</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addBreed()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="breedDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!breedDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Poultry Type Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in breedData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.name}}</td>
                        <td class="text-center">{{item.poultryTypeName}}</td>
                        <td class="text-center">{{item.description}}</td>
                        <td class="text-center">
                            <button type="button" v-on:click="updateShed(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
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
                    <h4 class="modal-title" id="myModalLabel">Breed {{breedModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Breed Name</label>
                        <input placeholder="Breed Name" name="breed name" v-validate="'required'" v-model="breedModel.name" type="text" class="form-control" id="name">
                        <span class="text-danger">{{ errors.first('breed name')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="group_name">Poultry Type</label>
                        <select name="group" v-validate="'required'" v-model="breedModel.poultryTypeId"
                                                    class="form-control">
                                                    <option value="">Select Group</option>
                                                    <option v-for="item in poultryTypeData" :value="item.id">
                                                        {{item.name}}</option>
                                                </select>
                        <span class="text-danger">{{ errors.first('group')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="password" class="form-control" id="description" v-model="breedModel.description" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-button" v-on:click="submitBreed()" class="btn btn-primary">Submit</button>
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
            breedData: [],
            poultryTypeData: [],
            breedModel: {
                id: '',
                name: '',
                poultryTypeId: '',
                description: ''
            },
            isPosting: false,
            breedDataLoading: false,
        },
        methods: {
            loadBreed() {
                let vm = this;
                vm.breedDataLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/breed")
                    .then(function(response) {
                        vm.breedDataLoading = false;
                        vm.breedData = response.data;
                    })
                    .catch(function(error) {
                        vm.breedDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            loadPoultryType() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/poultryType")
                    .then(function(response) {
                        vm.poultryTypeData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addBreed() {
                let vm = this;
                vm.breedModel.id = '';
                vm.breedModel.name = '';
                vm.breedModel.poultryTypeId = '';
                vm.breedModel.description = '';
                openModal();
            },
            updateShed(group) {
                let vm = this;
                vm.breedModel.id = group.id;
                vm.breedModel.name = group.name;
                vm.breedModel.poultryTypeId = group.poultryTypeId;
                vm.breedModel.description = group.description;
                openModal();
            },
            submitBreed() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/breed", vm.breedModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadBreed();
                            })
                            .catch(function(error) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(error);
                                alert(error.response.data.messages.error);
                                alert("Some Problem Occured");
                            });
                    }
                })
            },
        },
        mounted() {
            let vm = this;
            vm.loadBreed();
            vm.loadPoultryType();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->