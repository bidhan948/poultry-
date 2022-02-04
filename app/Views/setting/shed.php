<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Shed</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addShed()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="shedDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!shedDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Group Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in shedData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.name}}</td>
                        <td class="text-center">{{item.groupName}}</td>
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
                    <h4 class="modal-title" id="myModalLabel">Shed {{shedModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Shed Name</label>
                        <input placeholder="Shed Name" name="shed name" v-validate="'required'" v-model="shedModel.name" type="text" class="form-control" id="name">
                        <span class="text-danger">{{ errors.first('shed name')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="group_name">Group</label>
                        <select name="group" v-validate="'required'" v-model="shedModel.groupId"
                                                    class="form-control">
                                                    <option value="">Select Group</option>
                                                    <option v-for="item in groupData" :value="item.id">
                                                        {{item.name}}</option>
                                                </select>
                        <span class="text-danger">{{ errors.first('group')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="password" class="form-control" id="description" v-model="shedModel.description" placeholder="Description"></textarea>
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
            shedData: [],
            groupData: [],
            shedModel: {
                id: '',
                name: '',
                groupId: '',
                description: ''
            },
            isPosting: false,
            shedDataLoading: false,
        },
        methods: {
            loadShedData() {
                let vm = this;
                vm.shedDataLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/shed")
                    .then(function(response) {
                        vm.shedDataLoading = false;
                        vm.shedData = response.data;
                    })
                    .catch(function(error) {
                        vm.shedDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            loadGroup() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/group")
                    .then(function(response) {
                        vm.groupData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addShed() {
                let vm = this;
                vm.shedModel.id = '';
                vm.shedModel.name = '';
                vm.shedModel.groupId = '';
                vm.shedModel.description = '';
                openModal();
            },
            updateShed(group) {
                let vm = this;
                vm.shedModel.id = group.id;
                vm.shedModel.name = group.name;
                vm.shedModel.groupId = group.groupId;
                vm.shedModel.description = group.description;
                openModal();
            },
            submitFeedType() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/shed", vm.shedModel)
                            .then(function(response) {
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                closeModal();
                                alert(response.data.messages);
                                vm.loadShedData();
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
            vm.loadShedData();
            vm.loadGroup();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->