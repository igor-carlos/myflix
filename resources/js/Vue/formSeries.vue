<template>
  <div class="container-input">
    <input
      v-model="serieName"
      type="text"
      placeholder="Nome da série"
      class="form-control"
      required
    />
    <button @click="createSeries">Cadastrar</button>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      serieName: "",
    };
  },
  methods: {
    createSeries() {
      if (this.serieName == "") {
        return;
      }
      axios
        .post("api/v1/serie", {
          nome: this.serieName,
        })
        .then((response) => {
          if (response.status == "201") {
            this.serieName = "";
            this.$emit("reloadlist");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>

<style scoped>
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

  border-color: transparent;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  letter-spacing: 1px;
}

.container-input button:hover {
  background-color: rgb(112, 22, 22);
}
</style>