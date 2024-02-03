<template>
    <div>
        <apexchart type="line" height="500" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
export default {
    props: ['project'],
    created(){
        this.getGraphData()
    },

    methods:{
        getGraphData(){
            axios.get('/api/expenses/graph-data', {
                    params: {
                        project_id: this.project
                    }
                })
                .then( response => {
                    this.series = response.data;
                })
                .catch( error => {
                    console.log(error)
                })
        }
    },
    data(){
        return{
            series: [],
            chartOptions: {
                chart: {
                    height: 450,
                    type: 'line',
                    zoom: {
                        enabled: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: 'Expenses Graph Daily Basis',
                    align: 'left'
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                yaxis: {
                labels: {
                    formatter: function (value) {
                        let dollarUSLocale = Intl.NumberFormat('en-US');
                        if(value){
                            return "P" + dollarUSLocale.format(value.toFixed(2));
                        }
                    }
                },
                },
                xaxis: {
                    type: 'datetime',
                }
            },    
        }
    }
}
</script>

<style>

</style>