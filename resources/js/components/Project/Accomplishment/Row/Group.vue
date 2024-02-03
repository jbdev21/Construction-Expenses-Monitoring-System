<template>
  <tr>
    <td colspan="8">
      <input type="text" v-model="accomplishment.name" class="form-control form-control-sm" v-show="isEdit" >
      <strong  v-show="!isEdit">
        {{ accomplishment.name }}
      </strong>
    </td>
    <td v-for="monthlyItem in accomplishment.monthly_achievements" :key="monthlyItem.id">
    </td>
    <td></td>
    <td></td>
    <td class="text-center" style="width:50px">
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
export default {
    props: ['accomplishment'],
    data(){
        return {
          isEdit: false,
          currentSpan : this.accomplishment.monthly_achievements.length + 6
        }
    },
    methods:{
      updateAccomplishment(){
          this.isEdit = !this.isEdit
          axios.put("/accomplishment-item/" + this.accomplishment.id, {
              name : this.accomplishment.name,
          })
          .then( response => {
            // this.$emit("updated")
          })
          .catch( error => {
            console.log(error)
          })
      },
      deleteItem(item){
        if(confirm('Are you sure to delete ' + item.name + "?. Child items will also be deleted!")){
          axios.delete("/accomplishment-item/" + item.id)
              .then( response => {
                this.$emit("deleted")
              })
              .catch( error => {
                alert("There is an error. Please contact the developer for a details or fix. Thank you.")
              })
        }
      },
    }
}
</script>

<style>

</style>