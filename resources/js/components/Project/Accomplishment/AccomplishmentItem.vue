<template>
  <component v-bind:is="type" @deleted="deleted" @updated="updated" :project="project" :accomplishment="accomplishment"></component>
</template>

<script>
import Group from "./Row/Group.vue"
import Item from "./Row/Item.vue"

export default {
  props: ['project', 'accomplishment'],
  components:{

  },
  data(){
    return {
        type: this.accomplishment.type == "item" ? Item : Group 
    }
  },
  methods:{
    updated(){
      this.$emit("updated");
    },
    
    deleted(){
      this.$emit("deleted");
    }
  }
}
</script>

<style>

</style>