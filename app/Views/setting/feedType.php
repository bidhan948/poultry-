<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Feed Type</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addFeedType()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="feedTypeDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!feedTypeDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in groupData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.name}}</td>
                        <td class="text-center">{{item.description}}</td>
                        <td class="text-center">
                            <button type="button" v-on:click="updateFeedType(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
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
                    <h4 class="modal-title" id="myModalLabel">Feed Type {{feedTypeModel.id?'Update':'Add'}} </h4>
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Feed Type Name</label>
                        <input placeholder="Feed Type Name" name="feed type name" v-validate="'required'" v-model="feedTypeModel.name" type="text" class="form-control" id="name">
                        <span class="text-danger">{{ errors.first('feed type name')}}</span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea type="password" class="form-control" id="description" v-model="feedTypeModel.description" placeholder="Description"></textarea>
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
            groupData: [],
            feedTypeModel: {
                id: '',
                name: '',
                description: ''
            },
            isPosting: false,
            feedTypeDataLoading: false,
        },
        methods: {
            loadFeedType() {
                let vm = this;
                vm.feedTypeDataLoading = true;
                axios.get("<?php echo base_url()?>/api/settings/feedType")
                    .then(function(response) {
                        vm.feedTypeDataLoading = false;
                        vm.groupData = response.data;
                    })
                    .catch(function(error) {
                        vm.feedTypeDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addFeedType() {
                let vm = this;
                vm.feedTypeModel.id = '';
                vm.feedTypeModel.name = '';
                vm.feedTypeModel.description = '';
                openModal();
            },
            updateFeedType(group) {
                let vm = this;
                vm.feedTypeModel.id = group.id;
                vm.feedTypeModel.name = group.name;
                vm.feedTypeModel.description = group.description;
                openModal();
            },
            submitFeedType() {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post("<?php echo base_url()?>/api/settings/feedType", vm.feedTypeModel)
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