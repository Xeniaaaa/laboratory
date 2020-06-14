<template>
  <div class="container">
    <div class="row justify-content-center mt-5">
        <b-form-select v-model="selected" :options="tables" ></b-form-select>
    </div>

    <div class="row mt-5" v-if="items != null && selected != 'journal'&& selected != 'sessions'" style="background-color:white;" >
        <div class="col-12 m-3">
            <h3>Добавить новый элемент</h3>
            <div class="row m-2 mt-3" v-for="(elem, key) in items[0]" v-if="key != 'id'">
                <!-- {{key}} -->
                <label for="" class="col-3 mr-2">{{key}}</label>
                <input
                    class="col-4"
                    v-if="key != 'item_id' && key != 'id' && key != 'ch_id'"
                    v-model="new_obj[key]"
                    :placeholder="[[ key ]]"

                />

                <b-form-select
                    class="col-4"
                    v-model="new_obj[key]"
                    :options="item_dict"
                    v-bind:key="key"
                    v-if="key == 'item_id'"
                >
                </b-form-select>
                <b-form-select
                    class="col-4"
                    v-model="new_obj[key]"
                    :options="ch_dict"
                    v-bind:key="key"
                    v-if="key == 'ch_id'"
                >
                </b-form-select>

            </div>
            <button type="button" @click="sendNewObject()" class="btn btn-primary">Добавить</button>
        </div>
    </div>

    <div class="row justify-content-center">
      <div class="col-12 mt-5" style="background-color:white;">
        <b-table striped hover
         :items="items"
         :fields="fields"
         responsive
         >
            <template v-for="(field, index) in fields" v-slot:[`cell(${field.key})`]="data">
              <input v-if=" field.key != 'id' && field.key != 'item_id' && field.key != 'ch_id'&& field.key != 'user_id'"
                :value="data.item[field.key]"
                class="not_border"
                v-bind:key="index"
                @change="handleQuantityChange(index,field,$event,data.item)"/>

                <b-form-select  v-model="data.item[field.key]" :options="item_dict" v-bind:key="field.key" v-if="field.key == 'item_id'" @change="handleQuantityChange(index,field,$event,data.item)">
                </b-form-select>
                <b-form-select  v-model="data.item[field.key]" :options="ch_dict" v-bind:key="field.key" v-if="field.key == 'ch_id'" @change="handleQuantityChange(index,field,$event,data.item)">
                </b-form-select>

                <b-form-select  v-model="data.item[field.key]" :options="user_dict" v-bind:key="field.key" v-if="field.key == 'user_id'" @change="handleQuantityChange(index,field,$event,data.item)">

                </b-form-select>


            </template>
            <template v-slot:cell(Действие)="field">
                <button  type="button" class="btn btn-primary" @click="saveData">Сохранить</button>
            </template>

            <template v-slot:head(id)="data" v-if="false">

            </template>

        </b-table>
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
      selected: null,
      options: [{ value: null, text: "Выбор таблицы" }],
      tables: [],
      items:null,
      fields:null,
      data:[],
      save:[],
      dictSelected: null,
      item_dict: [],
      ch_dict: [],
      session_dict: [],
      user_dict: [],
      new_obj: {}
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  created: function() {
    console.log("Component created.");
    this.getData();
    this.getChDict();
    this.getUserDict();
    this.getItemDict('items');

  },
  watch: {
    selected: function(n){
        this.getInfo(n);
    }
  },
  methods: {
    getItemDict(name){
         var app = this;
        axios
        .get("/api/admin/table/"+name)
        .then(resp => {
            console.log(resp);
            let itm = [];
            itm.push({
                text: "Размерность",
                value: "units",
            })
            resp.data.map(function(elem){
                itm.push({
                    text: elem.item_name,
                    value: elem.id,
                })
            });
            app.item_dict  = itm;
        })
        .catch(error => {
          console.warn(error);
        });
    },
    getChDict(){
        var app = this;
        axios
        .get("/api/admin/table/characteristics")
        .then(resp => {
            console.log(resp);
            let itm = [];
            itm.push({
                text: "Характеристика",
                value: "ch",
            })
            resp.data.map(function(elem){
                itm.push({
                    text: elem.ch_name,
                    value: elem.id,
                })
            });
            app.ch_dict  = itm;
        })
        .catch(error => {
          console.warn(error);
        });
    },
    getUserDict(){
        var app = this;
        axios
        .get("/api/admin/table/users")
        .then(resp => {
            console.log(resp);
            let itm = [];
            resp.data.map(function(elem){
                itm.push({
                    text: elem.name,
                    value: elem.id,
                })
            });
            app.user_dict  = itm;
        })
        .catch(error => {
          console.warn(error);
        });
    },
    getData() {
      var app = this;
      axios.get("/api/admin/tables")
        .then(resp => {
          app.tables = resp.data;
        })
        .catch(error => {
          console.warn(error);
        });
    },
    handleQuantityChange(index, field, e, item) {
        let id = item["id"];

        if (Number.isInteger(e)) {
            console.log(e);
            let tmp = { table: this.selected, field: field.key, value: e, id: id };
            let resp = this.checkSave(tmp);
            if (resp == -1) {
                this.save.push(tmp);
            } else {
                this.save[resp] = tmp;
            }
        }else{
            let val = e.target.value;;
            let tmp = { table: this.selected, field: field.key, value: val, id: id };
            console.log(tmp);
            let resp = this.checkSave(tmp);
            if (resp == -1) {
                this.save.push(tmp);
            } else {
                this.save[resp] = tmp;
            }
        }

        //   let id = item["id"];
        //   // let table = this.tableSelection(index);
        //   let val = e.target.value;
        //   if (e.target.type == "checkbox") {
        //     val = e.target.checked ? 1 : 0;
        //   }
        //   let tmp = { table: this.selected, field: field.key, value: val, id: id };
        //   var resp = this.checkSave(tmp);
        //   if (resp == -1) {
        //     this.save.push(tmp);
        //   } else {
        //     this.save[resp] = tmp;
        //   }
    },
    checkSave(arr){
      for (var i = 0; i < this.save.length; i++) {

        console.log(this.save[i].field ==  arr.field);
        if(this.save[i].field == arr.field){
          if(this.save[i].id == arr.id){
            if(this.save[i].value !=  arr.value) {
                return i;
            }else{
              return i;
            }
          }
        }
      };
      return -1;
    },
    saveData () {
      if(this.save.length != 0){
        axios
        .post("/api/admin/saveData", this.save).then(resp=>{
          this.save = [];
          this.getInfo(this.selected);
          this.getChDict();
            this.getUserDict();
            this.getItemDict('items');

         })
      }
    },
    sendNewObject(){
        let app = this;
        let request = this.new_obj;
        request.table = this.selected;
        axios.post("/api/admin/addObject", request)
        .then(resp => {
            app.getInfo(app.selected);
            app.new_obj = {};
            app.getChDict();
            app.getUserDict();
            app.getItemDict('items');
        },
        (error) => { alert(error.response.data.data.msg) } )
        .catch(error => {
          console.warn(error.response);
        });
    },
    getInfo(n){
        var app = this;
        axios
        .get("/api/admin/table/"+n)
        .then(resp => {
            app.items = resp.data;
            var kk  = Object.keys(this.items[0]);
            var fild= []

            kk.map(function(elem){
                fild.push({
                    key: elem,
                    label: elem,
                })
            });
            fild.push("Действие");
            // fild.push({
            //         key: "Действие",
            //         label: "Действие",
            //         colType: "button"
            // });
            console.log(fild);
            app.fields = fild;
        })
        .catch(error => {
          console.warn(error);
        });
    }
  },
  computed:{
    //   fields: function(){
    //       console.log(this.items);
    //     return Object.keys(this.items[0]);
    //   }
  }
};
</script>
<style>
.not_border {
  border: none !important;
}
</style>
