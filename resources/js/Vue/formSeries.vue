<template>
  <div class="container-form">
    <div class="container-input">
      <input
        v-model="serieName"
        type="text"
        placeholder="Nome da série"
        class="form-control"
        required
      />
    </div>
    <div class="container-others">
      <select v-model="selectedCategory">
        <option disabled selected hidden>Categoria</option>
        <option v-bind:value="'Ação'">Ação</option>
        <option v-bind:value="'Comédia'">Comédia</option>
        <option v-bind:value="'Terror'">Terror</option>
      </select>
      <select v-model="selectedStreaming">
        <option disabled selected hidden>Streaming</option>
        <option v-bind:value="'Netflix'">Netflix</option>
        <option v-bind:value="'Prime Video'">Prime Video</option>
        <option v-bind:value="'Disney Plus'">Disney Plus</option>
      </select>
      <button @click="createSeries">Cadastrar</button>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      serieName: "",
      selectedCategory: "Categoria",
      selectedStreaming: "Streaming",
    };
  },
  methods: {
    createSeries() {
      if (this.serieName == "") return;
      if (["", "Categoria"].includes(this.selectedCategory)) return;
      if (["", "Streaming"].includes(this.selectedStreaming)) return;

      axios
        .post("api/v1/serie", {
          nome: this.serieName,
          categoria: this.selectedCategory,
          streaming: this.selectedStreaming,
        })
        .then((response) => {
          if (response.status == "201") {
            this.$emit("reloadlist");
            this.clearInputs();
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    clearInputs() {
      this.serieName = "";
      this.selectedCategory = "Categoria";
      this.selectedStreaming = "Streaming";
    },
  },
};
</script>

<style scoped>
.container-form {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;

  width: 100%;
  justify-content: center;
  text-align: center;

  margin-top: 20px;
  margin-bottom: 30px;
}

.container-input input {
  width: 700px;

  padding: 12px 20px;
  margin: 5px 0;

  box-sizing: border-box;
  border-radius: 5px;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
}

.container-form button {
  padding: 12px 20px;
  cursor: pointer;

  color: white;
  background-color: rgb(185, 33, 33);

  border-radius: 5px;

  border-color: transparent;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  letter-spacing: 1px;
}

.container-form button:hover {
  background-color: rgb(112, 22, 22);
}

.container-others {
  width: 700px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: row;
}

.container-others select {
  width: 100%;

  padding: 12px 20px;
  margin-right: 5px;

  box-sizing: border-box;
  border-radius: 5px;

  font-family: "Epilogue", sans-serif;
  font-weight: bold;

  color: rgb(112, 112, 112);
}

.container-others select option {
  font-family: "Epilogue", sans-serif;
  font-weight: bold;
  font-size: 16px;

  color: rgb(112, 112, 112);
}
</style>