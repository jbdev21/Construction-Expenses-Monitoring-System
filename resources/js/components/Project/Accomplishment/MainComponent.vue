<template>
    <div class="p-3">
        <div class="row">
            <div class="col">
                <h4>{{ project.name }}</h4>
            </div>
            <div class="col">
                <div class="text-end mb-3">
                    <a type="button"
                        class="btn btn-primary text-white" :href="`/project/${project.id}/accomplishment?whole=1`" target="_blank"><i class="fa fa-expand"></i> Full Screen</a>
                    <button type="button"
                        class="btn btn-primary text-white" @click="refresh"><i class="fa fa-refresh"></i> Refresh</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#itemModal"
                        class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Item</button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#monthlyModal"
                        class="btn btn-primary text-white"><i class="fa fa-plus"></i> Add Monthly</button>
                </div>
            </div>
        </div>
        <div v-if="loading">
                <h4 class="text-center">
                    <i class="fa fa-spin fa-spinner"></i> Loading contents..
                </h4>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" style="font-size:12px;" v-if="!loading "> 
                <thead>
                    <tr>
                        <th rowspan="2" valign="middle" >Item No.</th>
                        <th rowspan="2" valign="middle" >Description</th>
                        <th rowspan="2" valign="middle" >UNIT</th>
                        <th colspan="3" class="text-center" valign="middle">
                            Orginal Contract
                        </th>
                        <th colspan="3" class="text-center">
                            Revised Contract
                            <small class="d-block">(As per lastest V.O)</small>
                        </th>
                        <th rowspan="2" class="text-center" valign="middle" >% Weight</th>
                        <!-- <th>QUANTITY</th>
                        <th>UNIT COST</th>
                        <th>REVISED</th>
                        <th>TOTAL COST</th> -->
                        <th rowspan="2" valign="middle" class="text-uppercase" v-for="monthlyAchievement in monthlyAchievements" :key="monthlyAchievement.id">
                            {{  monthlyAchievement.label }}
                            <a href="#" @click.prevent="removeMonthly(monthlyAchievement)" class="float-end text-danger"><i class="fa fa-trash"></i></a>
                        </th>
                        <!-- <th>% WT. ACCOMP</th>
                        <th>COST BILLING</th>
                        <th>COST TO DATE</th> -->
                        <th rowspan="2"></th>
                    </tr>
                    <tr>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Cost</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Unit Cost</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <AccomplishmentItem @deleted="refresh" @updated="updatedInput" v-for="accomplishmentItem in accomplishmentItems" :accomplishment="accomplishmentItem" :key="accomplishmentItem.id" :project="project" />
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ toCurrency(project.total_contract_cost) }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ toCurrency(project.total_revised_contract_cost) }}</td>
                        <td>{{ toPercentage(project.total_weight) }}</td>
                        <td class="text-uppercase" v-for="monthlyAchievement in monthlyAchievements" :key="monthlyAchievement.id">
                        </td>
                        <!-- <td>{{ toPercentage(project.total_weight_accomplished) }}</td>
                        <td>{{ toCurrency(project.total_cost_billing) }}</td>
                        <td>{{ toCurrency(project.total_cost_billing) }}</td> -->
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="monthlyModal" tabindex="-1" aria-labelledby="monthlyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="monthlyModalLabel">Add Month Period</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <MonthPeriodComponentVue @added="refresh" :project="project_id" />
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel">Add New Accomplishment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <accomplishment-item-form  @added="refresh" :project="project.id" :amount="project.contract_amount"></accomplishment-item-form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import AccomplishmentItem from './AccomplishmentItem.vue';
import MonthPeriodComponentVue from './CreateForm/MonthPeriodComponent.vue';
export default {
    props: ['project_id'],
    components:{
        AccomplishmentItem,
        MonthPeriodComponentVue
    },
    data(){
        return {
            project: {},
            loading: false,
            accomplishmentItems: [],
            monthlyAchievements: [],
        }
    },

    created(){
        this.getProjectDetails()
    },

    methods:{
        updatedInput(){
            this.getProjectOnlyDetails()
            axios.post('/api/project/'+ this.project.id +'/weight-progress')
            .then( response =>{
                
            })
            .catch( error =>  {

            })
        },  
        toPercentage(value){
            return parseFloat(value).toFixed(2)  + "%"
        },
        toCurrency(num){
            let currency = Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "PHP",
            });

            if(!num){
                return 0
            }

            return currency.format(num)
        },

        refresh(){
            this.getProjectDetails()
        },

        removeMonthly(month){
            if(confirm(`Are you sure to delete ${month.label}?`)){
                axios.delete('/monthly-achievement/' + month.id)
                    .then(response => {
                        this.refresh()
                    }) 
                    .catch( error => {
                        alert("There is an error in deleting. Please inform the developer!")
                    })
            }
        },

        getProjectDetails(){
            this.loading = true
            axios.get("/api/project/" + this.project_id + "/achievement", {
                params: {
                    accomplishment: 1,
                    monthly: 1
                }
            })
            .then( response => {
                this.loading = false
                this.project = response.data.project
                this.accomplishmentItems = response.data.accomplishment_items
                this.monthlyAchievements = response.data.monthly_achievements
            })
            .catch( error => {
                this.loading = false
                console.log(error)
            })
        },

        getProjectOnlyDetails(){
            axios.get("/api/project/" + this.project_id + "/achievement", {
                params: {
                    accomplishment: 1,
                    monthly: 1
                }
            })
            .then( response => {
                this.project = response.data.project
            })
            .catch( error => {
                this.loading = false
                console.log(error)
            })
        }
    }
}
</script>

<style scoped lang="scss">
    *{
        font-size:12px;
    }
</style>