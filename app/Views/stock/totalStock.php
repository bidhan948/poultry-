<?= $this->extend("layout/master") ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Stock</h3>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">

        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="stockLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!stockLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Shed Number</th>
                        <th class="text-center">Shed Details</th>
                        <th class="text-center">Group</th>
                        <th class="text-center">Lot</th>
                        <th class="text-center">Male</th>
                        <th class="text-center">Female</th>
                        <!-- <th class="text-center">Breed Type</th> -->
                       
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in stockData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.shedName}}</td>
                        <td class="text-center">{{item.shedDetails}}</td>
                        <td class="text-center">{{item.groupName}}</td>
                        <td class="text-center">{{item.lot}}</td>
                        <td class="text-center">{{item.male}}</td>
                        <td class="text-center">{{item.female}}</td>
                        <!-- <td class="text-center">{{item.breedTypeName}}</td> -->
                       
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row text-center">
            <nav aria-label="">
                <ul class="pagination">
                    <li v-on:click="getPrevPage()" class="page-item" :class="pageIndex<=1?'disabled':''">
                        <span class="page-link">Previous</span>
                    </li>
                    <li v-on:click="pageChanged(index)" :class="pageIndex == index?'active':''" v-for="index in count" class="page-item"><a class="page-link" href="#">{{index}}</a></li>
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
            stockData: [],
            pageIndex: 0,
            pageSize: 20,
            count: 0,
            stockLoading: false,
        },
        methods: {
            loadStocks(pageIndex) {
                let vm = this;
                vm.stockLoading = true;
                axios.get("<?php echo base_url()?>/api/stock", {
                        params: {
                            pageIndex: pageIndex,
                            pageSize: vm.pageSize
                        }
                    })
                    .then(function(response) {
                        vm.stockLoading = false;
                        vm.stockData = response.data.data;
                        vm.count = response.data.count;
                        vm.pageIndex = response.data.pageIndex;
                    })
                    .catch(function(error) {
                        vm.stockLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            pageChanged(index){
                let vm = this;
                if (vm.pageIndex != index) {
                    vm.pageIndex = index;
                    vm.loadStocks(vm.pageIndex)
                }
            },
            getNextPage() {
                let vm = this;
                if (vm.pageIndex < vm.count) {
                    vm.pageIndex++;
                    vm.loadStocks(vm.pageIndex)
                }
            },
            getPrevPage() {
                let vm = this;
                if (pageIndex>1) {
                    vm.pageIndex--;
                    vm.loadStocks(vm.pageIndex)
                }
            }
        },
        mounted() {
            let vm = this;
            vm.loadStocks(1);
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->