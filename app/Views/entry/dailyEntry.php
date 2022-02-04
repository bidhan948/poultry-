<?= $this->extend("layout/master") ?>
<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url() ?>/datepicker/datepicker.min.css">
<?= $this->endSection() ?>

<!-- content section started -->
<?= $this->section("content") ?>
<div id="app" class="card">
    <div class="card-header">
        <h3 class="card-title">Main Entry</h3>
        <div class="float-right">
            <div role="group" class="btn-group-sm btn-group">
                <a v-on:click="addDailyEntry()" class="btn btn-success"><i class="fa fa-plus"></i> Add </a>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <!-- form start -->

    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="group_name">Shed</label>
                    <select name="shed" v-model="shedId" class="form-control form-control-sm">
                        <option value="">Select Shed</option>
                        <option v-for="item in shedData" :value="item.id">
                            {{item.name}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="group_name">Lot</label>
                    <input v-model="lot" type="number" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="group_name">Date</label>
                    <input id="search-date" placeholder="YYYY/MM/DD" v-model="dateBs" type="text" class="form-control form-control-sm">
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <button type="button" v-on:click="search()" class="btn btn-warning"><i class="fa fa-search"></i></button>
                <button type="button" v-on:click="cancelSearch()" class="btn btn-danger"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="table-content-padding">
            <div class="spinner-div text-center" v-if="dailyEntryDataLoading">
                <i class="fa fa-spinner fa-spin"></i> Please Wait...
            </div>
            <table v-if="!dailyEntryDataLoading" id="datatable" class="table table-striped table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">#</th>
                        <th rowspan="2" class="text-center">Date</th>
                        <th rowspan="2" class="text-center">Shed Number</th>
                        <th rowspan="2" class="text-center">Shed Details</th>
                        <th rowspan="2" class="text-center">Lot</th>
                        <th colspan="2" class="text-center">Culling</th>
                        <th colspan="2" class="text-center">Mortality</th>
                        <th rowspan="2" class="text-center">Description</th>
                        <th rowspan="2" class="text-center">&nbsp;</th>
                    </tr>
                    <tr>
                        <th>Male</th>
                        <th>Female</th>
                        <th>Male</th>
                        <th>Female</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in dailyEntryData">
                        <td class="text-center">{{index + 1}}</td>
                        <td class="text-center">{{item.dateBs}}</td>
                        <td class="text-center">{{item.shedName}}</td>
                        <td class="text-center">{{item.shedDetails}}</td>
                        <td class="text-center">{{item.lot}}</td>
                        <td class="text-center">{{item.cullingMale}}</td>
                        <td class="text-center">{{item.cullingFemale}}</td>
                        <td class="text-center">{{item.mortalityMale}}</td>
                        <td class="text-center">{{item.mortalityFemale}}</td>
                        <td class="text-center">{{item.description}}</td>
                        <td class="text-center">
                            <button type="button" v-on:click="viewDailyEntry(item)" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></button>
                            <!-- <button v-if="item.status == 0" type="button" v-on:click="updateDailyEntry(item)" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></button> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row text-center" style="overflow: scroll;">
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

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- <div id="app" class="card"> -->
                    <div class="card-header">
                        <h3 class="card-title">Daily Entry Detail</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->

                    <div class="card-body">
                        <table id="" class="table table-striped table-bordered " style="width:100%">
                            <tbody>
                                <tr>
                                    <th colspan="12">Daily Farm Report</th>
                                </tr>
                                <tr>
                                    <th colspan="3">Shed Number:</th>
                                    <td colspan="3">{{dailyEnteryDataDetail.shedName}} </td>
                                    <th colspan="3">Date</th>
                                    <td colspan="3">{{dailyEnteryDataDetail.dateBs}}</td>
                                </tr>
                                <tr>
                                    <th colspan="6">Shed Details: </th>
                                    <td colspan="6">
                                        Breed: {{dailyEnteryDataDetail.breedTypeName}} , Lot: {{dailyEnteryDataDetail.lot}}, 
                                        Male Quantity:{{dailyEnteryDataDetail.male}} , 
                                        Female Quantity: {{dailyEnteryDataDetail.female}}
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="5">Morning</th>
                                    <th colspan="5">Mid-Day</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Temperature Inside</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.morningInTemp}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.eveningInTemp}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Temperature Outside</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.morninOutTemp}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.eveningOutTemp}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2">Humidity Inside</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.morningInHumidity}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.eveningOutHumidity}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Humidity Outside</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.morningOutHumidity}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.eveningOutHumidity}}</td>
                                </tr>
                                <tr>
                                    <th>Light</th>
                                    <th colspan="2">Time</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.lightTime}}</td>
                                    <th colspan="2">Out</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.lightOut}}</td>
                                    <th colspan="2">Lux</th>
                                    <th colspan="2">{{dailyEnteryDataDetail.lightLux}}</th>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th colspan="2">Feed</th>
                                    <th>Total Male</th>
                                    <td>{{dailyEnteryDataDetail.feedMale}}</td>
                                    <th>Total Female</th>
                                    <td>{{dailyEnteryDataDetail.feedFemale}}</td>
                                    <th>Male Per Bird</th>
                                    <td>{{feedperMaleFemale.malePerBird}}</td>
                                    <th>Female Per Bird</th>
                                    <td>{{feedperMaleFemale.femalePerBird}}</td>
                                    <th>Feed Type</th>
                                    <td>{{dailyEnteryDataDetail.name}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">&nbsp;</th>
                                    <th colspan="5">Male</th>
                                    <th colspan="5">Female</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Body Weight</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.weightMale}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.weightFemale}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Mortality</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.mortalityMale}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.mortalityFemale}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Culling</th>
                                    <td colspan="5">{{dailyEnteryDataDetail.cullingMale}}</td>
                                    <td colspan="5">{{dailyEnteryDataDetail.cullingFemale}}</td>
                                </tr>
                                <tr>
                                    <th colspan="12">Egg Production</th>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>{{dailyEnteryDataDetail.totalEggProduction}}</td>
                                    <th>Broken Eggs</th>
                                    <td>{{dailyEnteryDataDetail.brokenEggCount}}</td>
                                    <th>N.H.E</th>
                                    <td>{{dailyEnteryDataDetail.nhe}}</td>
                                    <th>%</th>
                                    <td>0.00</td>
                                    <th>Average Egg Weight</th>
                                    <td>{{dailyEnteryDataDetail.avgEggWeight}}</td>
                                    <th>S.T.D. %</th>
                                    <td>{{dailyEnteryDataDetail.std}}</td>
                                </tr>
                                <tr>
                                    <th colspan="6">Medicine</th>
                                    <th colspan="6">Vaccine</th>
                                </tr>
                                <tr>
                                    <th colspan="2">Cooling Pad 1</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.coolingPad1}}</td>
                                    <th colspan="2">Cooling Pad 2</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.coolingPad2}}</td>
                                    <th colspan="2">Cooling Pad 3</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.coolingPad3}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Water</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.water}}</td>
                                    <th colspan="2">Fan</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.fan}}</td>
                                    <th colspan="2">Light</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.lightStart}}</td>
                                </tr>
                                <tr>
                                    <th colspan="2">Feeding Trolly</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.feedingTrolly}}</td>
                                    <th colspan="2">Screper / Belt</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.screeper}}</td>
                                    <th colspan="2">Conveyer</th>
                                    <td colspan="2">{{dailyEnteryDataDetail.conveyer}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->


                    <!-- </div> -->
                </div>
                <div class="modal-footer">
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
<script src="<?php echo base_url() ?>/datepicker/datepicker.min.js"></script>
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
            dailyEntryData: [],
            dailyEnteryDataDetail: [],
            feedperMaleFemale:[],
            shedData: [],
            pageIndex: 0,
            pageSize: 20,
            count: 0,
            shedId: '',
            lot: '',
            date: '',
            dateBs: '',
            dailyEntryDataLoading: false,
        },
        methods: {
            loadShedData() {
                let vm = this;
                axios.get("<?php echo base_url() ?>/api/settings/shed")
                    .then(function(response) {
                        vm.shedData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            loadDailyEntry(pageIndex) {
                let vm = this;
                vm.dailyEntryDataLoading = true;
                axios.get("<?php echo base_url() ?>/api/dailyEntry", {
                        params: {
                            pageIndex: pageIndex,
                            pageSize: vm.pageSize,
                            shedId: vm.shedId,
                            date: vm.date,
                            lot: vm.lot
                        }
                    })
                    .then(function(response) {
                        // console.log(response);
                        vm.dailyEntryDataLoading = false;
                        vm.dailyEntryData = response.data.data;
                        vm.count = parseInt(response.data.count);
                        vm.pageIndex = response.data.pageIndex;
                    })
                    .catch(function(error) {
                        vm.dailyEntryDataLoading = false;
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            viewDailyEntry(item) {
                let vm = this;
                axios.get(`<?= base_url("/api/dailyEntry/detail/") ?>`, {
                        params: {
                            id: item.id
                        }
                    })
                    .then(function(response) {
                        console.log(response.data);
                        vm.dailyEnteryDataDetail = response.data.data[0];
                        vm.feedperMaleFemale = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some problem Occured");
                    });
                openModal();
            },
            addDailyEntry() {
                window.location.href = `<?php echo base_url() ?>/dailyEntry/add`;
            },
            pageChanged(index) {
                let vm = this;
                if (vm.pageIndex != index) {
                    vm.pageIndex = index;
                    vm.loadDailyEntry(vm.pageIndex)
                }
            },
            getNextPage() {
                let vm = this;
                if (vm.pageIndex < vm.count) {
                    vm.pageIndex++;
                    vm.loadDailyEntry(vm.pageIndex)
                }
            },
            getPrevPage() {
                let vm = this;
                if (vm.pageIndex > 1) {
                    vm.pageIndex--;
                    vm.loadDailyEntry(vm.pageIndex)
                }
            },
            search() {
                let vm = this;
                vm.loadDailyEntry(1);
            },
            cancelSearch() {
                let vm = this;
                vm.date = '';
                vm.dateBs = '';
                vm.shedId = '';
                vm.lot = '';
                vm.loadDailyEntry(1);
            }
        },
        mounted() {
            let vm = this;
            var searchDate = document.getElementById("search-date");
            searchDate.nepaliDatePicker({
                readOnlyInput: true,
                ndpMonth: true,
                ndpYear: true,
                ndpYearCount: 10,
                dateFormat: "YYYY/MM/DD",
                onChange: function(event) {
                    vm.date = event.ad;
                    vm.dateBs = event.bs;
                }
            });
            vm.loadShedData();
            vm.loadDailyEntry(1);
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->