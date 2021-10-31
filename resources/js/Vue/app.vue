<template>
  <div>
    <ul>
      <li>
        <p class="myflix-logo" href="#home">
          <span class="font-red">My</span>flix
        </p>
      </li>
      <!-- <li style="float: right"><a class="active" href="#about">About</a></li> -->
    </ul>
    <div class="container-fluid">
      <div class="container-input">
        <input v-model="serieName" type="text" placeholder="Nome da série" />
        <button @click="createSeries">Cadastrar</button>
      </div>
      <list-view :series="series" />
    </div>
  </div>
</template>
<script>
import listView from "./listView";

export default {
  components: {
    listView,
  },
  data: function () {
    return {
      series: [],
      serieName: "",
    };
  },
  methods: {
    getSeries() {
      axios
        .get("api/v1/series")
        .then((response) => {
          this.series = response.data;
        })
        .catch((error) => {});
    },
    createSeries() {
      console.log(this.serieName);
    },
  },
  created() {
    this.getSeries();
  },
};
</script>

<style scoped>
.series-container {
  padding: 50px;
}

.container-input {
  width: 100%;
  justify-content: center;
  text-align: center;

  margin-top: 20px;
  margin-bottom: 30px;
}

.container-input input {
  width: 30%;

  padding: 12px 20px;
  margin: 8px 0;

  box-sizing: border-box;
  border-radius: 5px;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
}

.container-input button {
  padding: 12px 20px;
  margin: 8px 0;
  cursor: pointer;

  color: white;
  background-color: rgb(185, 33, 33);

  border-radius: 5px;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  letter-spacing: 1px;
}

.container-input button:hover {
  background-color: rgb(112, 22, 22);
}

.myflix-logo {
  font-family: "ZCOOL KuaiLe", cursive;
  font-size: 48px;
}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #333;
}

li {
  float: left;
}

li p {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover {
  background-color: #111;
}

.font-red {
  color: rgb(185, 33, 33);
}
</style>