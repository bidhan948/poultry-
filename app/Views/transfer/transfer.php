<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Transfer Details</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addTransfer()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="transferDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!transferDataLoading" id="datatable" class="table table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">#</th>
                        <th class="text-center" rowspan="2">From Shed</th>
                        <th class="text-center" rowspan="2">Transfer Date</th>
                        <th class="text-center" colspan="2">Transfer Age</th>
                        <th class="text-center" rowspan="2">To Shed</th>
                        <th class="text-center" rowspan="2">To Lot</th>
                        <th class="text-center" rowspan="2">Male</th>
                        <th class="text-center" rowspan="2">Female</th>
                        <th class="text-center" rowspan="2">Description</th>
                        <th class="text-center" rowspan="2">edit</th>
                        <!-- <th>&nbsp;</th> -->
                    </tr>
                    <tr>
                        <th class="text-center">Days</th>
                        <th class="text-center">Weeks</th>
                        <!-- <th>&nbsp;</th> -->
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item,index) in transferData">
                        <tr>
                            <td :rowspan="item.transferDetails.length" class="text-center">{{index + 1}}</td>
                            <td :rowspan="item.transferDetails.length" class="text-center">{{item.fromShedName}}</td>
                            <td :rowspan="item.transferDetails.length" class="text-center">{{item.transferDateBs}}</td>
                            <td :rowspan="item.transferDetails.length" class="text-center">{{item.transferAge}}</td>
                            <td :rowspan="item.transferDetails.length" class="text-center">{{item.transferAgeWeeks}}</td>
                            <td class="text-center">{{item.transferDetails[0].toShedName || ''}}</td>
                            <td class="text-center">{{item.transferDetails[0].toLot || ''}}</td>
                            <td class="text-center">{{item.transferDetails[0].male || 0}}</td>
                            <td class="text-center">{{item.transferDetails[0].female || 0}}</td>
                            <td class="text-center">{{item.transferDetails[0].description || ''}}</td>
                            <td class="text-center">
                                <button type="button" v-on:click="updateTransfer(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr v-for="(detail,index) in item.transferDetails" v-if="index > 0">
                            <!-- <td class="text-center">{{index + 1}}</td>
                            <td class="text-center">{{item.fromShedName}}</td>
                            <td class="text-center">{{item.transferDateBs}}</td>
                            <td class="text-center">{{item.transferAge}}</td>
                            <td class="text-center">{{item.transferAgeWeeks}}</td> -->
                            <td class="text-center">{{detail.toShedName}}</td>
                            <td class="text-center">{{detail.toLot}}</td>
                            <td class="text-center">{{detail.male || 0}}</td>
                            <td class="text-center">{{detail.female || 0}}</td>
                            <td class="text-center">{{detail.description || ''}}</td>
                        </tr>
                    </template>

                </tbody>
            </table>
        </div>

        <div class="row text-center">
            <nav aria-label="">
                <ul class="pagination">
                    <li v-on:click="getPrevPage()" class="page-item" :class="pageIndex<=1?'disabled':''">
                        <span class="page-link">Previous</span>
                    </li>
                    <li v-on:click="pageChanged(n)" :class="pageIndex == n?'active':''" v-for="(n, index) in count" class="page-item"><a class="page-link" href="#">{{n}}</a></li>
                    <li v-on:click="getNextPage()" class="page-item" :class="pageIndex < count?'':'disabled'">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
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
            transferData: [],
            pageIndex: 0,
            pageSize: 20,
            count: 0,
            transferDataLoading: false,
        },
        methods: {
            loadTransferData(pageIndex) {
                let vm = this;
                vm.transferDataLoading = true;
                axios.get("<?php echo base_url() ?>/api/stock/transfer", {
                        params: {
                            pageIndex: pageIndex,
                            pageSize: vm.pageSize,
                        }
                    })
                    .then(function(response) {
                        console.log(response);
                        vm.transferDataLoading = false;
                        vm.transferData = response.data.data;
                        vm.count = parseInt(response.data.count);
                        vm.pageIndex = response.data.pageIndex;
                    })
                    .catch(function(error) {
                        vm.transferDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addTransfer() {
                window.location.href = `<?php echo base_url() ?>/transfer/add`;
            },
            updateTransfer(item) {
                window.location.href = `<?php echo base_url() ?>/transfer/update/${item.id}`;
            },
            pageChanged(index) {
                let vm = this;
                if (vm.pageIndex != index) {
                    vm.pageIndex = index;
                    vm.loadTransferData(vm.pageIndex)
                }
            },
            getNextPage() {
                let vm = this;
                if (vm.pageIndex < vm.count) {
                    vm.pageIndex++;
                    vm.loadTransferData(vm.pageIndex)
                }
            },
            getPrevPage() {
                let vm = this;
                if (pageIndex > 1) {
                    vm.pageIndex--;
                    vm.loadTransferData(vm.pageIndex)
                }
            },
            updateTransfer(item) {
                window.location.href = `<?php echo base_url() ?>/transfer/update/${item.id}`;
            },
        },
        mounted() {
            let vm = this;
            vm.loadTransferData(1);
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->