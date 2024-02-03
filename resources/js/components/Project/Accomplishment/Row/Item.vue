<template>
  <tr :class="{'bg-dark': isEdit}">
    <td>
      <input type="text" v-model="accomplishment.item_number" class="form-control form-control-sm" v-show="isEdit" >
      <span v-show="!isEdit">
        {{ accomplishment.item_number }}
      </span>
    </td>
    <td>
      <input type="text" v-model="accomplishment.name" class="form-control form-control-sm" v-show="isEdit" >
      <span v-show="!isEdit">
        {{ accomplishment.name }}
      </span>
    </td>
    <td>
      <input type="text" v-model="accomplishment.unit" class="form-control form-control-sm" v-if="isEdit" >
      <span v-show="!isEdit">
        {{ accomplishment.unit }}
      </span>
    </td>
  
    <td>
      <input type="number" min="0" step=".01" v-model="accomplishment.quantity" class="form-control form-control-sm" v-if="isEdit" >
      <span v-show="!isEdit">
        {{ accomplishment.quantity }}
      </span>
    </td>
    <td>
      <input type="number" min="0" step=".01" v-model="accomplishment.unit_cost" class="form-control form-control-sm" v-if="isEdit" >
      <span v-show="!isEdit">
      {{ toCurrency(accomplishment.unit_cost) }}</span>
    </td>
    
    <td><span v-show="!isEdit">{{ toCurrency(accomplishment.total_contract_cost) }}</span></td>
    <!-- <td><span v-show="!isEdit">{{ toCurrency(accomplishment.revised_contact_quantity) }}</span></td> -->
    <td>
      <input type="number" min="0" step=".01" v-model="accomplishment.revised_quantity" class="form-control form-control-sm" v-if="isEdit" >
      <span v-show="!isEdit">
      {{ accomplishment.revised_quantity }}</span>
    </td>
    <td>
      <input type="number" min="0" step=".01" v-model="accomplishment.revised_unit_cost" class="form-control form-control-sm" v-if="isEdit" >
      <span v-show="!isEdit">
      {{ toCurrency(accomplishment.revised_unit_cost) }}</span>
    </td>
    <td>
      <span v-show="!isEdit">
      {{ toCurrency(accomplishment.revised_contract_cost) }}</span>
    </td>
    <td>
      <input type="text" readonly v-model="weight" class="form-control form-control-sm" v-if="isEdit" >
      <span v-show="!isEdit">
        {{ accomplishment.weight }}% 
      </span>
    </td>
    <td class="with-input-tr" v-for="(monthlyItem, index) in monthly" :key="monthlyItem.id">
      <AchievementInput v-show="!isEdit"
        @changed="inputChanged"
        :project_id="project.id"
        :index="index"
        :accomplishment_item_id="accomplishment.id"
        :monthly_achievement_id="monthlyItem.id"
        :value="monthlyValues[index]"
      />
    </td>
    <td class=" text-center" style="width:50px">
      <div v-show="!isEdit">
        <a class="text-success" href="#" @click="isEdit = !isEdit"><i class="fa fa-edit"></i></a>
        <a class="text-danger" href="#" @click.prevent="deleteItem(accomplishment)"><i class="fa fa-trash"></i></a>
      </div>
      <div v-show="isEdit">
        <button @click="updateAccomplishment" class="btn btn-info text-white"><i class="fa fa-check"></i></button>
      </div>
    </td>
  </tr>
</template>

<script>
import axios from 'axios';
import AchievementInput from "./../AchievementInput/MainComponent.vue";
export default {
  props: ["accomplishment", "project", 'index'],
  data(){
    return {
      monthly: this.accomplishment.monthly_achievements,
      monthlyValues: this.accomplishment.monthly_achievements.map( (e) => e.value),
      isEdit: false
    }
  },

  methods:{
    updateAccomplishment(){
        this.isEdit = !this.isEdit
        axios.put("/accomplishment-item/" + this.accomplishment.id, {
            item_number : this.accomplishment.item_number,
            name : this.accomplishment.name,
            unit : this.accomplishment.unit,
            weight : this.weight,
            quantity : this.accomplishment.quantity,
            unit_cost : this.accomplishment.unit_cost,
            total_contract_cost: this.totalCost,

            //revised
            revised_quantity: this.accomplishment.revised_quantity,
            revised_unit_cost: this.accomplishment.revised_unit_cost,
            revised_contract_cost: this.revisedContractCost,
        })
        .then( response => {
          this.accomplishment.weight = this.weight
          this.accomplishment.total_contract_cost = this.totalCost
          this.accomplishment.revised_contract_cost = this.revisedContractCost
          this.$emit("updated")
        })
        .catch( error => {
          console.log(error)
        })
    },
    deleteItem(item){
      if(confirm('Are you sure to delete ' + item.name + "?")){
          axios.delete("/accomplishment-item/" + item.id)
            .then( response => {
              this.$emit("deleted")
            })
            .catch( error => {
              alert("There is an error. Please contact the developer for a details or fix. Thank you.")
            })
      } 
    },
    toCurrency(num){
      let currency = Intl.NumberFormat("en-US", {
          style: "currency",
          currency: "PHP",
      });

      if(!num){
        return ''
      }

      return currency.format(num)
    },
    
    updateWeightAccomplished(value){
        if(value > 0.000001){
          axios.post("/accomplishment-item/"+ this.accomplishment.id +"/updateweight", {
              weight_accomplished: value
            })
            .then( response => {

            })
            .catch( error => {
              alert("Error occured! Please inform developer for the fix!. Thanks");
            })
        }
    },
    
    updateCostBilling(value){
        if(value > 0.000001){
          axios.post("/accomplishment-item/"+ this.accomplishment.id +"/updatecostbilling", {
              cost_billing: value
            })
            .then( response => {

            })
            .catch( error => {
              alert("Error occured! Please inform developer for the fix!. Thanks");
            })
        }
    },

    inputChanged(value){
      this.$emit("updated")
      Vue.set(this.monthlyValues, value.index, parseFloat(value.achievement));
    }
  },
  watch:{
  },

  computed:{
    totalCost(){
        return parseFloat(this.accomplishment.quantity * this.accomplishment.unit_cost).toFixed(2)
    },

    revisedContractCost(){
        return parseFloat(this.accomplishment.revised_quantity * this.accomplishment.revised_unit_cost).toFixed(2)
    },

    weight(){
        return (this.revisedContractCost / parseFloat(this.project.contract_amount) * 100).toFixed(2)
    },

    totalAcchievemntQuantity(){
      return this.monthlyValues.reduce( (accumulator, curValue) => {
          return accumulator + curValue
        }, 0)
    },

    totalCostBilling(){
      var value = (parseFloat(this.totalAcchievemntQuantity) * parseFloat(this.accomplishment.unit_cost)).toFixed(2)
      this.updateCostBilling(value)
      return value
    },

    overAllWeightAccomplishment(){
        var value =  (parseFloat(this.totalCostBilling) / parseFloat(this.project.contract_amount) * 100).toFixed(2)
        this.updateWeightAccomplished(value)
        
        return value + "%";
    }
  },

  components: {
    AchievementInput,
  },
};
</script>

<style>
</style>