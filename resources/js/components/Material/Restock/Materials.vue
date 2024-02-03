<template>
    <div>
        Materials
        <v-select style="width:300px" :filterable="false" @option:selected="onSelected" v-model="selectedMaterial" @search="searchMaterial" class="rounded-0" :options="materialOptions"></v-select>
        <table class="table mt-0">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Description/Material</th>
                    <th>Quantity</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(material, index) in materials" :key="material.code">
                    <td>{{index + 1}}</td>
                    <td>{{material.label}}</td>
                    <td>
                        <input type="hidden" name="material_ids[]" v-model="material.code">
                        <input type="hidden" name="quantity[]" v-model="material.quantity">

                        <div class="input-group" style="width:100px">
                            <input type="number" v-model="material.quantity" min="0.01" value="1" step=".01" class="form-control h-auto border-0 shadow-0 bg-white p-0" style="width:100px" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </td>
                    <td class="text-end">
                        <a href="#" class="text-danger"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
                <tr v-if="!materials.length">
                    <td colspan="4" class="text-center">No material added</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data(){
        return {
            selectedMaterial: {},
            materialOptions: [],
            materials: [],
        }
    },
    methods:{
        onSelected(item){
            this.materials.push({
                code: item.code,
                label: item.label,
                quantity: 1,
            });
            this.selectedMaterial = {};
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
        }
    }
}
</script>

<style>

</style>