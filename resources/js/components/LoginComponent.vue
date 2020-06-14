<template>
  <!-- Default form login -->
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-4 col-3-offset" style="background-color:white;">
        <form class="m-3">
          <p class="h4 text-center mb-4">Авторизация</p>
          <label for="defaultFormLoginEx" class="grey-text">Логин</label>
          <input type="text" id="defaultFormLoginEx" v-model="login" class="form-control" />
          <br />
          <label for="defaultFormLoginPasswordEx" class="grey-text">Пароль</label>
          <input
            type="password"
            id="defaultFormLoginPasswordEx"
            v-model="password"
            class="form-control"
          />
          <div class="text-center mt-4">
            <button class="btn" style="color: white;background-color: purple;" @click="auth()" type="submit">Войти</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Default form login -->
</template>

<script>
export default {
  // components: {
  //   apexchart: VueApexCharts,
  // },
  data() {
    return {
      login: null,
      password: null
    };
  },
  mounted() {
    console.log("Component mounted.");
  },
  created: function() {
    console.log("Component created.");
  },
  watch: {},
  methods: {
    auth() {
      var app = this;
      axios.post("/auth/login", {name: this.login, password: this.password})
        .then(resp => {
          console.log(resp);
        })
        .catch(error => {
          console.warn(error);
        });
    }
  }
};
</script>
<style>
.not_border {
  border: none !important;
}
</style>
