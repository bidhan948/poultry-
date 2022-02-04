<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Daily Excel Upload</h3>

    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="group_name">Shed</label>
                    <select name="shed" v-model="shedId" v-validate="'required'" class="form-control form-control-sm">
                        <option value="">Select Shed</option>
                        <option v-for="item in shedData" :value="item.id">
                            {{item.name}}
                        </option>
                    </select>
                    <span class="text-danger">{{ errors.first('shed')}}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="group_name">Lot</label>
                    <input v-model="lot" name="lot" v-validate="'required'" type="number" class="form-control form-control-sm">
                    <span class="text-danger">{{ errors.first('lot')}}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="group_name">Excel File</label>
                    <input type="file" id="file" ref="file" class="form-control form-control-sm" v-on:change="handleFileUpload()" />
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="float-right">
                    <button type="button" v-on:click="onSubmit()" id="submit-button" class="btn btn-warning">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card-body -->


</div>
<?= $this->endSection() ?>
<!-- content section ended -->



<!-- Script section started -->
<?= $this->section("script") ?>
<script>
    new Vue({
        el: "#app",
        data: {
            shedId: '',
            lot: '',
            file: '',
            shedData: []
        },
        methods: {
            loadShedData() {
                let vm = this;
                axios.get("<?php echo base_url()?>/api/settings/shed")
                    .then(function(response) {
                        vm.shedData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            onSubmit() {
                let vm = this;
                let formData = new FormData();
                formData.append('file', vm.file);
                formData.append('shedId', vm.shedId);
                formData.append('lot', vm.lot);
                var submitbutton = document.getElementById("submit-button");
                vm.$validator.validateAll().then((validate) => {
                    if (validate) {
                        submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                        submitbutton.disabled = true;
                        axios.post('<?php echo base_url()?>/api/excel/daily',
                                formData, {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                }
                            ).then(function(response) {
                                // vm.shedId = '';
                                // vm.lot = '';
                                // vm.file = '';
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                console.log(response);
                                alert(error.response.data.messages.error);
                                alert(response.data.messages);
                            })
                            .catch(function(error) {
                                debugger;
                                submitbutton.innerHTML = 'Submit';
                                submitbutton.disabled = false;
                                if(error.response.statusText) {
                                    alert(error.response.statusText)
                                } else {
                                    alert('Some error occured');
                                }
                            });
                    }
                })
            },
            handleFileUpload() {
                let vm = this;
                vm.file = vm.$refs.file.files[0];
            },
        },
        mounted() {
            let vm = this;
            vm.loadShedData();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->