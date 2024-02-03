<template>
  <div>
    <div class="mb-3">
        <label for="">Type *</label>
        <select v-model="currentFormComponent" class="form-select">
            <option value="Others">Others</option>
            <option value="Labor">Labor</option>
            <option value="Materials">Materials</option>
            <option value="RentalEquipments">Rental Equipments</option>
        </select>
        <input type="hidden" name="type" v-model="selectedType">
    </div>
    <component v-bind:is="currentFormComponent"></component>
  </div>
</template>

<script>
import Labor from "./form/Labor.vue"
import Materials from "./form/Materials.vue"
import Others from "./form/Others.vue"
import RentalEquipments from "./form/RentalEquipments.vue"
export default {
    components:{
        Labor,
        Materials,
        Others,
        RentalEquipments
    },
    computed:{
        selectedType(){
            if(this.currentFormComponent == "Labor"){
                return "labor";
            }else if(this.currentFormComponent == "Materials"){
                return "material";
            }else if(this.currentFormComponent == "Others"){
                return "others";
            }
            else if(this.currentFormComponent == "RentalEquipments"){
                return "rental equipment";
            }
        }
    },
    data(){
        return {
            currentFormComponent: "Others"
        }
    }
}
</script>

<style>

</style>