<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Poultry Type</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addPoultryType()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="poultryTypeLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!poultryTypeLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in poultryTypeData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.name}}</td>
                        <td class="text-center">{{item.description}}</td>
                        <td class="text-center">
                            <button type="button" v-on:click="updatePoultryType(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
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
                    <h4 class="modal-title" id="myModalLabel">Poultry Type {{poultryTypeModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Poultry Type Name</label>
                        <input placeholder="Poultry Type Name" name="poultry type name" v-validate="'required'" v-model="poultryTypeModel.name" type="text" class="form-control" id="name">
                        <span class="text-danger">{{ errors.first('poultry type name')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="password" class="form-control" id="description" v-model="poultryTypeModel.description" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-button" v-on:click="submitFeedType()" class="btn btn-primary">Submit</button>
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
            poultryTypeData: [],
            poultryTypeModel: {
                id: '',
                name: '',
                description: ''
            },
            isPosting: false,
            poultryTypeLoading: false,
        },
        methods: {
            loadFeedType() {
                let vm = this;
                vm.poultryTypeLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/poultryType")
                    .then(function(response) {
                        vm.poultryTypeLoading = false;
                        vm.poultryTypeData = response.data;
                    })
                    .catch(function(error) {
                        vm.poultryTypeLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addPoultryType() {
                let vm = this;
                vm.poultryTypeModel.id = '';
                vm.poultryTypeModel.name = '';
                vm.poultryTypeModel.description = '';
                openModal();
            },
            updatePoultryType(group) {
                let vm = this;
                vm.poultryTypeModel.id = group.id;
                vm.poultryTypeModel.name = group.name;
                vm.poultryTypeModel.description = group.description;
                openModal();
            },
            submitFeedType() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/poultryType", vm.poultryTypeModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadFeedType();
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
            vm.loadFeedType();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->