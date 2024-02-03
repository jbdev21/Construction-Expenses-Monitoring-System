<template>
    <div>
        <label for="">Origin *</label>
        <div class="input-group mb-3">
            <button style="width:150px;" class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ originType }}
            </button>
            <ul class="dropdown-menu">
                <li v-for="originList in typeList" :key="originList"><a class="dropdown-item" @click="setOriginType(originList)" href="#" >{{ originList }}</a></li>
            </ul>
            <v-select :filterable="false" v-model="selectedOrigin" @search="searchOriginType" style="width:calc(100% - 150px)" class="rounded-0" :options="originTypeOptions"></v-select>
        </div>
        <input type="hidden" name="deliverable_from_type" v-model="fromType">
        <input type="hidden" name="deliverable_from_id" v-model="fromId">
    </div>
</template>

<script>
export default {
    props: [
        'defaultType', 
        'destinationType', 
        
        //defaults 
        'defaultType',
        'defaultId',
        ],
    data(){
        return {
            originTypeOptions: [],
            selectedOrigin: {},
            typeList: [],

            //defaults 
            fromType: this.defaultType,
            fromId: this.defaultId,
            originType: this.destinationType ?? 'Warehouse',
        }
    },
    created(){
        this.getTypeList()
    },
    watch:{
        originType(){
            this.originTypeOptions = []
            this.selectedOrigin = {}
        },

        selectedOrigin(){
            if(this.selectedOrigin){
                this.fromType = this.selectedOrigin.class
                this.fromId = this.selectedOrigin.code
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
        searchOriginType(search, loading){
            loading(true);
            if(search.length > 2){
                axios.get(`/api/${this.originType.toLowerCase()}/select-data`, {
                        params:{
                            key: search
                    }
                })
                .then( response => {
                    this.originTypeOptions = response.data
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

        setOriginType(type){
            this.originType = type;
        },
    },
}
</script>

<style>

</style>