<template>
    <div>
        <input type="number" min="0" v-model="achievement" class="form-control form-control-sm">
    </div>
</template>

<script>
export default {
    props: [
        'project_id',
        'accomplishment_item_id',
        'monthly_achievement_id',
        'value',
        'index'
    ],
    
    data(){
        return {
            achievement: this.value ? this.value : 0
        }
    },

    watch:{
        achievement: _.debounce(function(newVal){
                this.updateAchievement()
            }, 1000)
    },

    methods:{
        updateAchievement(){
            this.$emit("changed", {
                achievement: this.achievement,
                index: this.index
            })
            axios.post('/api/accomplishment-achievement/update', {
                    project_id: this.project_id,
                    accomplishment_item_id: this.accomplishment_item_id,
                    monthly_achievement_id: this.monthly_achievement_id,
                    achievement: this.achievement
                })
                .then( response => {
                    // this.achievement = response.data.data.achievement
                })
                .catch(error => {
                    console.log(error)
                })
        },
    }
}
</script>

<style>

</style>