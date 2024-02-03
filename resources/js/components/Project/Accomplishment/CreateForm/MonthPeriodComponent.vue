<template>
  <form @submit.prevent="submit" method="POST">
        <input type="hidden" name="project_id" v-model="project">
        <div class="modal-body">
            <div class="mb-3">
                <label for="">Label</label>
                <input type="text" class="form-control mb-2" v-model="label" required>
            </div>
            <div class="mb-3">
                <label for="">Month</label>
                <input type="month" class="form-control mb-2" v-model="month" required>
            </div>
            <div class="mb-3">
                <label for="">Weight Accomplishment</label>
                <input type="number" max="100"  step=".01" class="form-control mb-2" v-model="weight">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="closeMonthlyModal" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary text-white">Save</button>
        </div>
    </form>
</template>

<script>
import axios from 'axios'
export default {
    props: ['project'],
    data(){
        return {
            label:'',
            month: '',
            weight: ''
        }
    },
    
    methods:{
        submit(){
            axios.post('/monthly-achievement', {
                    project_id: this.project,
                    label: this.label,
                    month: this.month,
                    weight: this.weight,
                })
                .then( response => {
                    this.label = ''
                    this.month = ''
                    this.weight = ''
                    document.getElementById('closeMonthlyModal').click();
                    this.$emit("added", 1)
                })
                .catch( error => {
                    console.log(error)
                })
        }
    }
}
</script>

<style>

</style>