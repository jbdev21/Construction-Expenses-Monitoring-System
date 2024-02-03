<template>
    <div>
        <label for="">Items</label>
        <a href="#" @click.prevent="addField()" class="float-end">Add Field</a>
        <div class="input-group mb-1" v-for="(item, index) in items" :key="index">
            <span class="input-group-text">{{ index+1 }}</span>
            <input name="items[]" v-model="item.item" required type="text" class="form-control">
            <span class="input-group-text">P</span>
            <input name="amount[]" v-model="item.amount" required type="number" step=".5" min="1" class="form-control">
            <span class="input-group-text">Type</span>
            <select name="category[]" v-model="item.category" class="form-control">
                <option value="labor">Labor</option>
                <option value="material">Material</option>
                <option value="others">Others</option>
            </select>
            <button type="button" :disabled="index == 0" class="btn btn-danger text-white" @click.prevent="removeField(index)"><div class="fa fa-remove"></div></button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['inputed'],
    data(){
        return {
            items:  [
                {
                    item: '',
                    amount: '',
                    category: 'labor'
                }
            ],
        }
    },
    created(){
      if(this.inputed){
          this.items = JSON.parse(this.inputed)
         
      }
    }, 
    methods:{
        addField(){
            this.items.unshift({
                item: '',
                amount: '',
                category: 'labor'
            });
        },
        removeField(index){
            this.items.splice(index, 1);
        }

    }
}
</script>

<style>

</style>