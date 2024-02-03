<template>
    <div v-if="!hideSelect2">
        <select name="voter_id" ref="select2"><slot></slot></select>
    </div>
</template>

<script>
export default {
    props:{
        value: {
            type: String,
            default: null,
            required: false
        },
        url:{
            type: String,
            default: '/voter-select2'
        }
    },
    data(){
        return {
            hideSelect2: false
        }
    },
    mounted: function() {
        this.initiateSelect2()
    },
    watch: {
        value: function(value) {
            $(this.$refs.select2)
                .val(value)
                .trigger("change");
        },
        url(){
            this.value = null 
            this.initiateSelect2()
        }
    },
    computed:{
        config(){
            return {
                url: this.url,
                dataType: 'json',
                delay: 500,
                data: function (params) {
                    return {
                        searchTerm: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        }
    },
    methods:{
        destroySelect2(){
            $(this.$refs.select2)
                .select2("destroy");
        },
        initiateSelect2(){
            var vm = this;
            $(this.$refs.select2)
                .select2({
                    placeholder: "Select a voter",
                    theme: 'bootstrap4',
                    allowClear: true,
                    ajax:vm.config
                })
                .trigger("change")
                .on("change", function() {
                    vm.$emit("input", this.value);
                });
        }
    },
    destroyed: function() {
        $(this.$refs.select2)
        .off()
        .select2("destroy");
    }
}
</script>

<style scoped>
    .select2-container, .select2-selection {
        width: auto !important;
    }
</style>
