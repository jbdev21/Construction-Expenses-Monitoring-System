<template>
    <div>
        <label for="">Destination *</label>
        <div class="input-group mb-3">
            <button style="width:150px;" class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ destinationType }}
            </button>
            <ul class="dropdown-menu">
                <li v-for="destinationList in typeList" :key="destinationList"><a class="dropdown-item" @click="setdestinationType(destinationList)" href="#" >{{ destinationList }}</a></li>
            </ul>
            <v-select :filterable="false" v-model="selectedDestination" @search="searchDestinationType" style="width:calc(100% - 150px)" class="rounded-0" :options="destinationTypeOptions"></v-select>
        </div>
        <input type="hidDen" name="deliverable_to_type" v-model="fromType">
        <input type="hidDen" name="deliverable_to_id" v-model="fromId">
    </div>
</template>

<script>
export default {
    props: [
        'defaultType', 
        
        //defaults 
        'defaultType',
        'defaultId',
        ],
    data(){
        return {
            destinationTypeOptions: [],
            selectedDestination: {},
            typeList: [],

            //defaults 
            fromType: this.defaultType,
            fromId: this.defaultId,
            destinationType: this.destinationType ?? 'Project',
        }
    },
    created(){
        this.getTypeList();
    },
    watch:{
        destinationType(){
            this.destinationTypeOptions = []
            this.selectedDestination = {}
        },

        selectedDestination(){
            if(this.selectedDestination){
                this.fromType = this.selectedDestination.class
                this.fromId = this.selectedDestination.code
            }else{
                this.fromType = ""
                this.fromId = ""
            }
        },
    },
    methods:{
        getTypeList(){
            axios.get('/api/delivery/type-list')
                .then( response => {
                    this.typeList = response.data
                })
                .catch( error => {
                    console.log(error)
                })
        },
        searchDestinationType(search, loading){
            loading(true);
            if(search.length > 2){
                axios.get(`/api/${this.destinationType.toLowerCase()}/select-data`, {
                        params:{
                            key: search
                    }
                })
                .then( response => {
                    this.destinationTypeOptions = response.data
                    loading(false);
                })
                .catch(error => {
                    console.log(error);
                    loading(false);
                })
            }else{
                loading(false);
            }
        },

        setdestinationType(type){
            this.destinationType = type;
        },
    },
}
</script>

<style>

</style>