<template>
    <div>
        <div class="mb-3">
            <label for="materialInput" class="form-label">Material</label>
            <input list="browsers" @keyup="searchMaterial" v-model="materialText" class="form-control" name="browser">
            <datalist id="browsers">
                <option v-for="material in materialList" :key="material.id">{{material.name}}</option>
            </datalist>
        </div>
        <div class="mb-3">
            <label for="quantityInput" class="form-label">Quantity</label>
            <input type="text" min="1" class="form-control" value="1" id="quantityInput" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="priceInput" class="form-label">Price</label>
            <input type="text" step=".01" min="1" class="form-control" id="priceInput" aria-describedby="emailHelp">
        </div>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
    export default{
        components:{
            Select2
        },
        data(){
            return{
                material: null,
                quantity: 1,
                price: 0,
                materialText: '',
                materialList: []
            }
        },
        methods:{
            searchMaterial(){
                axios.get('/api/material/search', {
                    params:{
                        q: this.materialText
                    }
                }).then(response => {
                    this.materialList = response.body;
                    console.log(this.materialList);
                });
            },
            // addMaterial(){
            //     this.$emit('addMaterial', {
            //         material_id: this.material,
            //         quantity: this.quantity,
            //         price: this.price,
            //     });
            // },
        },
        mounted(){
            // this.getMaterial();
        }
    }

</script>