<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Main Entry</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addMainEntry()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="mainEntryDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!mainEntryDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Shed Number</th>
                        <th>Shed Details</th>
                        <th>Lot</th>
                        <th>Arrival Date</th>
                        <th>Arrival Age</th>
                        <th>Arrival Male Quantity </th>
                        <th>Arrival Female Quantity </th>
                        <th>Breed Type</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item,index) in mainEntryData">
                        <tr>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">{{index + 1}}</td>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">{{item.shedName}}</td>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">{{item.shedDetails}}</td>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">{{item.lot}}</td>
                            <td class="text-center">{{item.arrivalDateBs}}</td>
                            <td class="text-center">{{item.arrivalAge}}</td>
                            <td class="text-center">{{item.arrivalQuantityMale}}</td>
                            <td class="text-center">{{item.arrivalQuantityFemale}}</td>
                            <td class="text-center">{{item.breedTypeName}}</td>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">{{item.status ==1?'Active':(item.status ==2 || item.status == 3)?'Completed':'New Entry'}}</td>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">{{item.description ? item.description  : ''}}</td>
                            <td :rowspan="item.extendedMainEntries.length ? item.extendedMainEntries.length + 1 : 1" class="text-center">
                                <button v-if="item.status == 0" type="button" v-on:click="updateMainEntry(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button>
                            </td>
                        </tr>
                        <tr v-for="(detail,index) in item.extendedMainEntries" >
                            <!-- <td class="text-center">{{index + 1}}</td> -->
                            <td class="text-center">{{detail.arrivalDateBs}}</td>
                            <td class="text-center">{{detail.arrivalAge}}</td>
                            <td class="text-center">{{detail.arrivalQuantityMale}}</td>
                            <td class="text-center">{{detail.arrivalQuantityFemale}}</td>
                            <td class="text-center">{{detail.breedTypeId}}</td>
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
            mainEntryData: [],
            pageIndex: 0,
            pageSize: 20,
            count: 0,
            mainEntryDataLoading: false,
        },
        methods: {
            loadMainEntry(pageIndex) {
                let vm = this;
                vm.mainEntryDataLoading = true;
                axios.get("<?php echo base_url() ?>/api/mainEntry", {
                        params: {
                            pageIndex: pageIndex,
                            pageSize: vm.pageSize
                        }
                    })
                    .then(function(response) {
                        vm.mainEntryDataLoading = false;
                        vm.mainEntryData = response.data.data;
                        vm.count = parseInt(response.data.count);
                        vm.pageIndex = response.data.pageIndex;
                    })
                    .catch(function(error) {
                        vm.mainEntryDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            addMainEntry() {
                window.location.href = `<?php echo base_url() ?>/mainEntry/add`;
            },
            updateMainEntry(item) {
                window.location.href = `<?php echo base_url() ?>/mainEntry/update/${item.id}`;
            },
            pageChanged(index) {
                let vm = this;
                if (vm.pageIndex != index) {
                    vm.pageIndex = index;
                    vm.loadMainEntry(vm.pageIndex)
                }
            },
            getNextPage() {
                let vm = this;
                if (vm.pageIndex < vm.count) {
                    vm.pageIndex++;
                    vm.loadMainEntry(vm.pageIndex)
                }
            },
            getPrevPage() {
                let vm = this;
                if (pageIndex > 1) {
                    vm.pageIndex--;
                    vm.loadMainEntry(vm.pageIndex)
                }
            }
        },
        mounted() {
            let vm = this;
            vm.loadMainEntry(1);
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->