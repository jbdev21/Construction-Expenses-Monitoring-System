<template>
    <div>
        <div class="mb-3">
            <div class="row">
                <div class="col-6">
                    <label for="">Search Material *</label>
                    <v-select :filterable="false" @option:selected="onSelectedMaterial" v-model="selectedMaterial" @search="searchMaterial" class="rounded-0" :options="materialOptions"></v-select>
                </div>
                <div class="col-6">
                    <label for="">Search Warehouse *</label>
                    <v-select :filterable="false" @option:selected="onSelectedWarehouse" v-model="selectedWarehouse" @search="searchWarehouse" class="rounded-0" :options="warehouseOptions"></v-select>
                </div>
            </div>
            
        </div>
        <div v-if="selectedMaterial && selectedWarehouse">
            <input type="hidden" name="material_id"  v-model="materialId">
            <input type="hidden" name="warehouse_id"  v-model="warehouseId">
            <div class="mb-3">
                <label for="">Items *</label>
                <textarea required class="form-control mb-2" @keyup="isChanges = true"  v-model="items" style="height: auto !important" rows="6" name="items"></textarea>
            </div>
            <div class="mb-3">
                <label for="">Quantity *</label> <small><i>Stocks {{currentStock}}</i></small>
                <input required type="number" :disabled="currentStock == 0" min=".1" :max="currentStock" step=".1" v-model="quantity" class="form-control mb-2" name="unit_quantity">
            </div>
            <div class="mb-3">
                <label for="">Unit Price *</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">&#8369;</span>
                    <input required type="number" v-model="unitPrice" step=".01"  class="form-control" name="unit_price">
                </div>
            </div>
            <div class="mb-3">
                <label for="">Amount *</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">&#8369;</span>
                    <input required type="number" v-model="amount"  step=".01" min="1" class="form-control" name="amount">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            selectedMaterial: null,
            materialOptions: [],

            selectedWarehouse: null,
            warehouseOptions: [],

            unitPrice: 0,
            quantity: 0,
            items: '',
            isChanges: false,

            currentStock: 0
        }
    },
    computed: {
        amount(){
            if(this.selectedMaterial){
                return this.unitPrice * this.quantity;
            }
        },
        changedItem(){
            if(this.selectedMaterial){
                return this.quantity + " " + this.selectedMaterial.label;
            }
        },
        materialId(){
            if(this.selectedMaterial){
                return this.selectedMaterial.code;
            }
        },
        warehouseId(){
            if(this.selectedWarehouse){
                return this.selectedWarehouse.code;
            }
        },
    },
     watch:{
        changedItem(){
            if(!this.isChanges){
                this.items = this.changedItem
            }
        },
        selectedMaterial(){
            this.getCurrentStock();
        },
        selectedWarehouse(){
            this.getCurrentStock();
        }
    },
    methods:{
        //material
        onSelectedMaterial(item){
            this.selectedMaterial = item;
            this.unitPrice = this.selectedMaterial.price;
            this.quantity = 1
        },
        searchMaterial(search, loading){
            loading(true)
            if(search.length > 2){
                axios.get('/api/material/search', {
                        params:{
                            key: search
                    }
                })
                .then( response => {
                    this.materialOptions = response.data
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
        // Warehouse
        onSelectedWarehouse(item){
            this.selectedWarehouse = item;
            this.unitPrice = this.selectedWarehouse.price;
            this.quantity = 1
        },
        searchWarehouse(search, loading){
            loading(true)
            if(search.length > 2){
                axios.get('/api/warehouse/search', {
                        params:{
                            key: search
                    }
                })
                .then( response => {
                    this.warehouseOptions = response.data
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

        getCurrentStock(){
            if(this.selectedMaterial && this.selectedWarehouse){
                axios.get('/api/material/stock-in-warehouse', {
                    params:{
                        material: this.selectedMaterial.code,
                        warehouse: this.selectedWarehouse.code,
                    }
                })
                .then( response => {
                    this.currentStock = response.data

                    if(this.currentStock == 0){
                        this.quantity = 0
                    }
                })
                .catch( error => {
                    console.log(error)
                })
            }
        }
    }
}
</script>

<style>

</style>