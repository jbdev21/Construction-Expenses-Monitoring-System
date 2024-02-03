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
                    <th>Unit Price</th>
                    <th>Sub-total</th>
                    <th>Remarks</th>
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
                        <input type="hidden" name="remarks[]" v-model="material.remarks">
                        <input type="hidden" name="prices[]" v-model="material.price">

                        <div class="input-group" style="width:100px">
                            <input type="number" v-model="material.quantity" min="0.01" value="1" step=".01" class="form-control h-auto border-0 shadow-0 bg-white p-0" style="width:100px" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </td>
                    <td>
                        <div class="input-group" >
                            <input v-model="material.price" min="0.01" step=".01" type="number" class="form-control h-auto border-0 shadow-0 bg-white p-0"  aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </td>
                    <td>{{ toPesoFormat(material.price * material.quantity) }} </td>
                    <td>
                        <div class="input-group" >
                            <input v-model="material.remarks" type="text" class="form-control h-auto border-0 shadow-0 bg-white p-0"  aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                        </div>
                    </td>
                    <td class="text-end">
                        <a href="#" class="text-danger"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
                <tr v-if="!materials.length">
                    <td colspan="7" class="text-center">No material added</td>
                </tr>
            </tbody>
            <tfoot v-if="materials.length">
                <tr>
                    <td colspan="4"><strong>Total</strong></td>
                    <td><strong>{{ toPesoFormat(total) }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
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
    computed:{
        total(){
            return this.materials.reduce((total, material) => {
                return total + material.price * material.quantity;
            }, 0);
        }
    },
    methods:{
        onSelected(item){
            this.materials.push({
                code: item.code,
                label: item.label,
                quantity: 1,
                price: item.price,
                remarks: '',
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
        },
        toPesoFormat(number){
            const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2
            })
            return formatter.format(number)
        }
    }
}
</script>

<style>

</style>