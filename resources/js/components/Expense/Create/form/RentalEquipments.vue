<template>
    <div>
        <div class="mb-3">
            <label for="">Search Equipment *</label>
            <v-select :filterable="false" @option:selected="onSelected" v-model="selectedEquipment" @search="searchEquipment" class="rounded-0" :options="EquipmentOptions"></v-select>
        </div>
        <div v-if="selectedEquipment">
            <div class="mb-3">
                <label for="">Pricing Type *</label>
                <select v-model="pricing_type" id="" class="form-select">
                    <option value="hourly">Hourly</option>
                    <option value="daily">Daily</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="">Items *</label>
                <textarea class="form-control mb-2" @keyup="isChanges = true"  v-model="items" style="height: auto !important" rows="6" required name="items"></textarea>
            </div>
            <input type="hidden" name="equipment_id"  v-model="EquipmentId">
            
            <div class="mb-3">
                <label for="">{{ pricing_type == "hourly" ? "Hour" : "Day" }} *</label>
                <input type="number" min=".1" step=".1" v-model="quantity" class="form-control mb-2" name="unit_quantity">
            </div>
            <div class="mb-3">
                <label for="">Per {{ pricing_type == "hourly" ? "Hour" : "Day" }} *</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">&#8369;</span>
                    <input type="number" v-model="unitPrice" step=".01" class="form-control" name="unit_price">
                </div>
            </div>
            <div class="mb-3">
                <label for="">Amount *</label>
                <div class="input-group mb-3">
                    <span class="input-group-text">&#8369;</span>
                    <input type="number" v-model="amount" required step=".01" min="1" class="form-control" name="amount">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            selectedEquipment: null,
            EquipmentOptions: [],
            quantity: 0,
            items: '',
            isChanges: false,
            pricing_type: 'hourly'
        }
    },
    computed: {
        pricingText(){
            if(this.pricing_type == "hourly"){
                return "hours"
            }else{
                if(this.quantity > 1){
                    return "days"
                }

                return "day"
            }
        },
        amount(){
            if(this.selectedEquipment){
                return this.unitPrice * this.quantity;
            }
        },
        changedItem(){
            if(this.selectedEquipment){
                return this.quantity + " " + this.pricingText +" of " + this.selectedEquipment.label;
            }
        },

        unitPrice(){
            if(this.pricing_type == "hourly"){
                return this.selectedEquipment.price ?? 0;
            }else{
                return this.selectedEquipment.daily ?? 0;
            }

            return 0;
        },
        EquipmentId(){
            if(this.selectedEquipment){
                return this.selectedEquipment.code;
            }
        }
    },
    watch:{
        changedItem(){
            if(!this.isChanges){
                this.items = this.changedItem
            }
        }
    },
    methods:{
        onSelected(item){
            this.selectedEquipment = item;
            // this.unitPrice = this.selectedEquipment.price;
            this.quantity = 1
        },
        searchEquipment(search, loading){
            loading(true)
            if(search.length > 2){
                axios.get('/api/equipment/search', {
                        params:{
                            key: search
                    }
                })
                .then( response => {
                    this.EquipmentOptions = response.data
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
    }
}
</script>