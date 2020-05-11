<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card not_border" >
                    <div class="card-header">Информация и включение датчиков</div>

                    <div class="card-body" >
                        <div class="row">
                            <div class="col-md-12">
                                <p>Свет</p>
                                <b-button @click="OnOff('r1',1)" variant="success">Включить</b-button>
                                <b-button @click="OnOff('r1',0)" variant="danger">Выключить</b-button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul>
                                    <li v-for="(el, key) in data">
                                        {{el.name }}: {{el.value}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card not_border">
              
                    <div class="card-body">
                        <img class='col-md-12' src="http://82.137.162.129:1721/mjpg/video.mjpg" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-5">

            </div>
            <div class="col-md-7">
                <div id="chart">
                    <apexchart type="line" height="350" :options="chart1.chartOptions" :series="chart1.series"></apexchart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
        // components: {
        //   apexchart: VueApexCharts,
        // },
        data() {
            return {
                sensorUrl : 'http://82.137.162.129:1722',
                data: {},
                temp: [],     
                chart1 :{
                    series: [{
                        name: "Температура",
                        data: []
                    }],
                    //В этом блоке можно прописывать стили 
                    chartOptions: {
                        chart: {
                        //    height: 350,
                            type: 'line',
                            zoom: {
                                enabled: true
                            }
                        },
                       
                        title: {
                            text: 'График изменения концентрации компонента №1',
                            align: 'center'
                        },
                    //    xaxis: {
                    //        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    //    }
                    }
                }
            }      
        },
        mounted() {
            console.log('Component mounted.')
        },
        created: function(){
            console.log('Component created.');
            this.getData();
            var app = this;
            //каждую минуту запрашиваем данные с сервера.
            setInterval(()=> app.getData(), 30000);
            //пока для тестов каждые 15 сек

        },
        methods:{
            OnOff(sensor, action) {
                axios.get('/api/OnOff?sensorname='+sensor+'&act='+action)
                .then(resp=>{
                    console.log(resp);
                }).catch(error=>{
                    console.warn(error);
                })
            },
            getData(){
                var app = this;             
                var data = app.chart1.series[0].data;   
                axios.get('/api/data')
                .then(resp=>{
                    console.log(resp);
                    
                    app.data = resp.data.data;
                    var temp = app.data[0].value + this.randomInteger(-1,1);
                    var obj = {
                        x: new Date().getHours()+":"+new Date().getMinutes()+":"+new Date().getSeconds(),
                        y:  temp,
                    }
                    app.data[0].value = temp;
                    data.push(obj);
                    
                }).catch(error=>{
                    console.warn(error);
                })
                this.chart1.series[0].data = data;
                this.$nextTick();
                this.chart1.series = [{
                    name:"Концентрация, %",
                    data: data
                }];
            },
            randomInteger(min, max) {
                 // получить случайное число от (min-0.5) до (max+0.5)
                let rand = min - 0.5 + Math.random() * (max - min + 1);
                return Math.round(rand);
            },             
        }
    }
</script>
<style>
.not_border{
    border: none !important;
}
</style>