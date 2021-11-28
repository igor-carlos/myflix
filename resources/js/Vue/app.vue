<template>
  <div>
    <ul>
      <li>
        <a href="#" style="text-decoration: none">
          <p class="myflix-logo" href="#home">
            <span class="font-red">My</span>flix
          </p>
        </a>
      </li>
    </ul>
    <div class="container-fluid">
      <form-series v-on:reloadlist="getSeries()" />
    </div>
    <list-view :series="series" />
  </div>
</template>

<script>
import axios from "axios";
import listView from "./listView";
import formSeries from "./formSeries.vue";

export default {
  components: {
    formSeries,
    listView,
  },
  data: function () {
    return {
      series: [],
    };
  },
  methods: {
    getSeries() {
      axios
        .get("api/v1/series")
        .then((response) => {
          this.series = response.data;
        })
        .catch((error) => {
            notify({
                text: `Erro: ${error}`,
                theme: "red",
            });
        });
    },
  },
  created() {
    this.getSeries();
  },
};
</script>

<style scoped>
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
