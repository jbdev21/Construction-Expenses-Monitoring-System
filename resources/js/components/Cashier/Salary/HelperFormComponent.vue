<template>
    <div>
        <label for="">Helpers</label>
        <a href="#" @click.prevent="addField()" class="float-end">Add Field</a>
        <div class="input-group mb-1" v-for="(item, index) in items" :key="index">
            <span class="input-group-text">{{ index+1 }}</span>
            <input name="items[]" v-model="item.item" :required="isRequired" type="text" class="form-control">
            <span class="input-group-text">P</span>
            <input name="amount[]"  v-model="item.amount" :required="isRequired" type="number" step=".05" min="1" class="form-control">
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
                    amount: ''
                }
            ],
        }
    },
    computed:{
        isRequired(){
            return this.items.length != 1;
        }
    },
    created(){
      if(this.inputed){
          this.items = JSON.parse(this.inputed)
      }
    }, 
    methods:{
        addField(){
            // this.items.push(this.items.length+1);
             this.items.unshift({
                item: '',
                amount: '',
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