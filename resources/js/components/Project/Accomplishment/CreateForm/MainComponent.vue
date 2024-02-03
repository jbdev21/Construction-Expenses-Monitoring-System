<template>
    <form @submit.prevent="submit" method="POST">
        <div class="modal-body">
            <div class="mb-3">
                <label for="">Type</label>
                <select  class="form-control mb-2" v-model="type" required>
                    <option value="item">Item</option>
                    <option value="group">Group</option>
                </select>
            </div>
            <div class="mb-3" v-if="isItemMode">
                <label for="">Group Parent</label>
                <select  class="form-control mb-2" v-model="parent_id" required>
                    <option value="">- select parent -</option>
                    <option :value="group.id" v-for="group in groups" :key="group.id">{{ group.name }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Name</label>
                <input type="text" class="form-control mb-2" v-model="name" required>
            </div>
            <div class="mb-3" v-if="isItemMode">
                <label for="">Item No.</label>
                <input type="text" class="form-control mb-2" v-model="item_number">
            </div>
            <div class="mb-3" v-if="isItemMode">
                <label for="">Unit</label>
                <input type="text" class="form-control mb-2" v-model="unit">
            </div>
            <div class="mb-3" v-if="isItemMode">
                <label for="">
                    Original Contract
                </label>
                <div class="row">
                    <div class="col-4">
                        <label for="">Unit Cost</label>
                        <input type="number" min="0" step=".01" class="form-control mb-2" v-model="unit_cost">
                    </div>
                    <div class="col-4">
                        <label for="">Quantity</label>
                        <input type="number" min="0" step=".01" v-model="quantity" class="form-control mb-2" >
                    </div>
                    <div class="col-4">
                        <label for="">Total Cost</label>
                        <input type="number" readonly min="0" v-model="totalCost" class="form-control mb-2">
                    </div>
                </div>
            </div>
            <div class="mb-3" v-if="isItemMode">
                <label for="">
                    Revised Contract
                </label>
                <div class="row">
                    <div class="col-4">
                        <label for="">Unit Cost</label>
                        <input type="number" min="0" step=".01" class="form-control mb-2" v-model="revised_unit_cost">
                    </div>
                    <div class="col-4">
                        <label for="">Quantity</label>
                        <input type="number" min="0" step=".01" v-model="revised_quantity" class="form-control mb-2" >
                    </div>
                    <div class="col-4">
                        <label for="">Total Cost</label>
                        <input type="number" readonly min="0" v-model="revisedContactCost" class="form-control mb-2">
                    </div>
                </div>
            </div>
            <div class="mb-3" v-if="isItemMode">
                <label for="">% Weight</label>
                <input type="number" readonly max="100" v-model="weight" step=".01" class="form-control mb-2">
            </div>
  
        </div>  
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
            <button type="submit" for="accompliment-item-form" class="btn btn-primary text-white">Save changes</button>
        </div>
    </form>
</template>

<script>
import axios from 'axios'
export default {
    props: ['project', 'amount'],
    data(){
        return {
            groups: [],

            type: 'item',
            quantity: 0,
            unit_cost: 0,
            parent_id: '',
            name: '',
            unit: '',
            item_number: '',


            // revisions
            revised_unit_cost: 0,
            revised_quantity: 0,
            revised_contact_cost: 0,
        }
    },
    created(){
        this.getGroupsInProject()
    },

    watch:{
        unit_cost(){
            this.revised_unit_cost = this.unit_cost
        },
        
        quantity(){
            this.revised_quantity = this.quantity
        }
    },

    computed:{
        isItemMode(){
            return this.type == 'item'
        },

        totalCost(){
            return parseFloat(this.quantity * this.unit_cost).toFixed(2)
        },

        revisedContactCost(){
            return parseFloat(this.revised_quantity * this.revised_unit_cost).toFixed(2)
        },

        weight(){
            return (this.revisedContactCost / parseFloat(this.amount) * 100).toFixed(2)
        }
    },
    methods:{
        submit(){
            axios.post('/accomplishment-item', {
                project_id: this.project,
                parent_id: this.parent_id,
                name: this.name,
                type: this.type,
                unit: this.unit,
                item_number: this.item_number,
                weight: this.weight,
                quantity: this.quantity,
                unit_cost: this.unit_cost,
                total_contract_cost: this.totalCost,

                //revision
                revised_quantity: this.revised_quantity,
                revised_unit_cost: this.revised_unit_cost,
                revised_contract_cost: this.revisedContactCost,
            })
            .then( response => {
                document.getElementById('itemModal').click()
                this.getGroupsInProject()
                this.$emit("added", 1)
            })
            .catch( error =>{
                console.log(error)
            })
        },

        getGroupsInProject(){
            axios.get("/api/accomplishment-item", {
                    params: {
                        project: this.project,
                        type: 'group'
                    }
                })  
                .then( response => {
                    this.groups = response.data
                })
                .catch( error => {
                    console.log(error)
                })
        }
    }
}
</script>