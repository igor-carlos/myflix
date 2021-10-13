<template>
  <div class="card-serie">
    <div class="card-content">
      <p>
        <span class="text-red-small"># {{ serie.id }} &nbsp;&nbsp;</span
        ><strong class="text-bold-medium">{{ serie.nome }}</strong>
      </p>
      <div class="card-serie-status">
        <p class="serie-status">
          <i class="fas fa-eye-slash"></i> {{ serie.status }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["serie"],
};
</script>

<style scoped>
.card-serie {
  width: 400px;
  height: 200px;
  border-radius: 8px;
}

.card-content {
  height: 90%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.text-bold-medium {
  font-weight: bold;
  font-size: 18px;
}

.text-red-small {
  color: rgb(185, 33, 33);
  font-size: 12px;
}

.card-content {
  margin: 10px;
}

.card-serie-status {
  text-align: right;
  color: rgb(175, 175, 175);
  font-weight: bold;
}
</style>