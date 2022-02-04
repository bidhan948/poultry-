<?= $this->extend("layout/master") ?>
<!-- style section started -->
<?= $this->section("style") ?>
<link rel="stylesheet" href="<?php echo base_url() ?>/datepicker/datepicker.min.css">

<style>
    body {
        margin: 0;
        padding: 2rem;
    }

    /* table {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
th, td {
  padding: 0.25rem;
} */

    .sticky {
        background: white;
        position: sticky;
        top: 0;
        /* Don't forget this, required for the stickiness */
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    }
</style>
<?= $this->endSection() ?>
<!-- content section started -->
<?= $this->section("content") ?>
<div id="app">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Farm Report</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->

        <div class="card-body ">
            <div class="row p-5" style="background-color:#A9A9A9;">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_name">Group</label>
                                <select v-on:change="onGroupChange()" name="shed" v-model="group" class="form-control form-control-sm">
                                    <option value="">Select Group</option>
                                    <option v-for="item in groupData" :value="item.id">
                                        {{item.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_name">Shed</label>
                                <select name="shed" v-model="shedId" class="form-control form-control-sm">
                                    <option value="">Select Shed</option>
                                    <option v-for="item in tempShedData" :value="item.id">
                                        {{item.name}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_name">Lot</label>
                                <input placeholder="" v-model="lot" type="number" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_name">From Date</label>
                                <input id="from-date" placeholder="YYYY/MM/DD" v-model="fromDateBs" type="text" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="group_name">To Date</label>
                                <input id="to-date" placeholder="YYYY/MM/DD" v-model="toDateBs" type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-2 my-4">
                    <button type="button" v-on:click="search()" id="submit-button" class="btn btn-warning"><i class="fa fa-search"></i></button>
                    <button type="button" v-on:click="cancelSearch()" class="btn btn-danger"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="table-content-padding table-div">
                <div class="spinner-div text-center" v-if="reportDataLoading">
                    <i class="fa fa-spinner fa-spin"></i> Please Wait...
                </div>
                <table v-if="!reportDataLoading" id="table" class="table table-striped table-bordered table-sm " style="width:100%">
                    <thead class="sticky" style="background-color:#BCC6CC;">
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Date (AD)</th>
                            <th rowspan="2">Date (BS)</th>
                            <th rowspan="2">Shed</th>
                            <th rowspan="2">Lot</th>
                            <th rowspan="2">Age</th>
                            <th colspan="2">Total Birds</th>
                            <th colspan="2">Mortality</th>
                            <th rowspan="2">Mortality Male %</th>
                            <th rowspan="2">Mortality Female %</th>
                            <th colspan="2">Cumulative Mortality</th>
                            <th colspan="2">Culling</th>
                            <th colspan="2">Cumulative Culling</th>
                            <th colspan="4">Cumulative Flock Depletion</th>
                            <th colspan="12">Eggs</th>
                            <th colspan="2">Henday Production %</th>
                            <th colspan="2">Feed Bird</th>
                            <th colspan="2">Cumulative Feed</th>
                            <th colspan="2">Water</th>
                            <th colspan="2">Temperature</th>
                            <th rowspan="2">Medicine/Vaccination</th>
                            <th rowspan="2">Remarks</th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Male</th>
                            <th>Female</th>
                            <th>Male(%)</th>
                            <th>Female(%)</th>
                            <th>Total Eggs</th>
                            <th>H.E.</th>
                            <th>N.H.E.</th>
                            <th>Broken Eggs</th>
                            <th>Cumulative Number Of Eggs</th>
                            <th>Cumulative Number Of HE</th>
                            <th>HE% (Actual)</th>
                            <th>HE% (Standard)</th>
                            <th>HHHE(Actual)</th>
                            <th>HHHE(Standard)</th>
                            <th>Hen House Number(Actual)</th>
                            <th>Hen House Number(Standard)</th>
                            <th>Actual</th>
                            <th>Standard</th>
                            <th>Male</th>
                            <th>Female</th>

                            <th>Male</th>
                            <th>Female</th>
                            <th>Liters</th>
                            <th>Per Bird (ml)</th>
                            <th>Inside</th>
                            <th>Outside</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,index) in reportData">
                            <td>{{index + 1}}</td>
                            <td>{{item.entryDate}}</td>
                            <td>{{item.entryDateBs}}</td>
                            <td>{{item.shedName}}</td>
                            <td>{{item.lot}}</td>
                            <td>{{item.age}}</td>
                            <td>{{item.totalMale}}</td>
                            <td>{{item.totalFemale}}</td>
                            <td>{{item.mortalityMale || 0}}</td>
                            <td>{{item.mortalityFemale || 0}}</td>
                            <td>{{item.mortalityPercentMale || 0}}</td>
                            <td>{{item.mortalityPercentFemale || 0}}</td>
                            <td>{{item.cumMortalityMale||0}}</td>
                            <td>{{item.cumMortalityFemale||0}}</td>
                            <td>{{item.cullingMale||0}}</td>
                            <td>{{item.cullingFemale||0}}</td>
                            <td>{{item.cumCullingMale||0}}</td>
                            <td>{{item.cumCullingFemale||0}}</td>
                            <td>{{item.cumDeplitionMale|| 0}}</td>
                            <td>{{item.cumDeplitionFemale || 0}}</td>
                            <td>{{item.cumDeplitionPercentMale || 0}}</td>
                            <td>{{item.cumDeplitionPercentFemale || 0}}</td>
                            <td>{{item.totalEggsProduction || 0}}</td>
                            <td>{{item.he || 0}}</td>
                            <td>{{item.nhe || 0}}</td>
                            <td>{{item.brokenEggCount || 0}}</td>
                            <td>{{item.cumTotalEggs || 0}}</td>
                            <td>{{item.cumTotalHeEggs || 0}}</td>
                            <td>{{item.hePercent || 0}}</td>
                            <td>to do</td>
                            <td>{{item.hhhe}}</td>
                            <td>to do</td>
                            <td>{{item.henHouseNumber || 0}}</td>
                            <td>to do</td>
                            <td>{{item.henDayProduction || 0}}</td>
                            <td>to do</td>
                            <td>{{item.feedMale || 0}}</td>
                            <td>{{item.feedFemale || 0}}</td>
                            <td>{{item.cumfeedMale || 0}}</td>
                            <td>{{item.cumfeedFemale || 0}}</td>
                            <td>{{item.water}}</td>
                            <td>{{item.water_in_ml}}</td>
                            <td>{{item.eveningInTemp}}</td>
                            <td>{{item.eveningOutTemp}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- <div class="row text-center" style="overflow: scroll;">
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
        </div> -->

        </div>
        <!-- /.card-body -->
    </div>
</div>
<?= $this->endSection() ?>
<!-- content section ended -->



<!-- Script section started -->
<?= $this->section("script") ?>

<script src="<?php echo base_url() ?>/datepicker/datepicker.min.js"></script>
<script src="<?php echo base_url() ?>/hammerjs/hammer.min.js"></script>

<script>
    new Vue({
        el: "#app",
        data: {
            reportData: [],
            shedData: [],
            tempShedData: [],
            groupData: [],
            pageIndex: 0,
            pageSize: 20,
            count: 0,
            shedId: '',
            fromDate: '',
            toDate: '',
            fromDateBs: '',
            toDateBs: '',
            lot: '',
            group: '',
            reportDataLoading: false,
        },
        methods: {
            loadShedData() {
                let vm = this;
                axios.get("<?php echo base_url() ?>/api/settings/shed")
                    .then(function(response) {
                        vm.shedData = response.data;
                        vm.tempShedData = response.data;
                        console.log(vm.shedData);
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            onGroupChange() {
                let vm = this;
                vm.shedId = '';
                if (vm.group) {
                    var filteredShed = vm.shedData.filter(x => x.groupId == vm.group);
                    if (filteredShed) {
                        vm.tempShedData = filteredShed;
                    }
                } else {
                    vm.tempShedData = vm.shedData;
                }
            },
            loadGroupData() {
                let vm = this;
                axios.get("<?php echo base_url() ?>/api/settings/group")
                    .then(function(response) {
                        vm.groupData = response.data;
                    })
                    .catch(function(error) {
                        console.log(error);
                        alert("Some Problem Occured");
                    });
            },
            loadReport(pageIndex) {
                let vm = this;
                var submitbutton = document.getElementById("submit-button");
                if (vm.fromDate || vm.toDate || vm.group || vm.lot || vm.shedId) {
                    submitbutton.innerHTML = "<i class='fa fa-spinner fa-spin'></i> Please Wait";
                    submitbutton.disabled = true;
                    vm.reportDataLoading = true;
                    axios.get("<?php echo base_url() ?>/api/farmReport", {
                            params: {
                                pageIndex: pageIndex,
                                pageSize: vm.pageSize,
                                shedId: vm.shedId,
                                fromDate: vm.fromDate,
                                toDate: vm.toDate,
                                group: vm.group,
                                lot: vm.lot
                            }
                        })
                        .then(function(response) {
                            console.log(response.data);
                            submitbutton.innerHTML = "<i class='fa fa-search'></i>";
                            submitbutton.disabled = false;
                            vm.reportDataLoading = false;
                            vm.reportData = response.data;

                            vm.count = response.data.count;
                            vm.pageIndex = response.data.pageIndex;
                            vm.pageSize = response.data.pageSize;
                        })
                        .catch(function(error) {
                            submitbutton.innerHTML = "<i class='fa fa-search'></i>";
                            submitbutton.disabled = false;
                            vm.reportDataLoading = false;
                            console.log(error);
                            alert(error.response.statusText);
                        });
                } else {
                    alert('Please select filters');
                }

            },
            search() {
                let vm = this;
                vm.loadReport(1);
            },
            cancelSearch() {
                let vm = this;
                vm.fromDateBs = '';
                vm.fromDate = '';
                vm.toDateBs = '';
                vm.toDate = '';
                vm.group = '';
                vm.lot = '';
                vm.shedId = '';
                vm.reportData = [];
            }
        },
        mounted() {

            let vm = this;
            var fromDate = document.getElementById("from-date");
            fromDate.nepaliDatePicker({
                readOnlyInput: true,
                ndpMonth: true,
                ndpYear: true,
                ndpYearCount: 10,
                dateFormat: "YYYY/MM/DD",
                onChange: function(event) {
                    vm.fromDate = event.ad;
                    vm.fromDateBs = event.bs;
                }
            });
            var toDate = document.getElementById("to-date");
            toDate.nepaliDatePicker({
                readOnlyInput: true,
                ndpMonth: true,
                ndpYear: true,
                ndpYearCount: 10,
                dateFormat: "YYYY/MM/DD",
                onChange: function(event) {
                    vm.toDate = event.ad;
                    vm.toDateBs = event.bs;
                }
            });
            vm.loadGroupData();
            vm.loadShedData();

            axios.get("<?php echo base_url() ?>/api/summary-report-detail", {
                    params: {
                        shedId: vm.shedId,
                        fromDate: vm.fromDate,
                        toDate: vm.toDate,
                        group: vm.group,
                        lot: vm.lot
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                    submitbutton.innerHTML = "<i class='fa fa-search'></i>";
                    submitbutton.disabled = false;
                    vm.reportDataLoading = false;
                    vm.reportData = response.data;

                    vm.count = response.data.count;
                    vm.pageIndex = response.data.pageIndex;
                    vm.pageSize = response.data.pageSize;
                })
                .catch(function(error) {
                    submitbutton.innerHTML = "<i class='fa fa-search'></i>";
                    submitbutton.disabled = false;
                    vm.reportDataLoading = false;
                    console.log(error);
                    alert(error.response.statusText);
                });

            var object = document.getElementById('table'),
                initX, initY, firstX, firstY;

            object.addEventListener('mousedown', function(e) {

                e.preventDefault();
                initX = this.offsetLeft;
                initY = this.offsetTop;
                firstX = e.pageX;
                firstY = e.pageY;

                this.addEventListener('mousemove', dragIt, false);

                window.addEventListener('mouseup', function() {
                    object.removeEventListener('mousemove', dragIt, false);
                }, false);

            }, false);
            //$('.table-responsive').doubleScroll();
        }
    })
</script>
<?= $this->endSection() ?>
<!-- Script section ended -->