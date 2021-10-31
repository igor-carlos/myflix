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
       <button> 
           Qq Coisa
       </button> 
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
    };
  },
  methods: {
    getSeries() {
      axios
        .get("api/v1/series")
        .then((response) => {
          this.series = response.data;
          console.log(this.series);
        })
        .catch((error) => {
          console.log(error);
        });
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