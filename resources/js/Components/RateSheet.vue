<template>
  <div>
    <div class="row">
      <div class="col rate-sheet">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">名前</th>
              <th scope="col">割合</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(r, i) in recipe" :key="i">
              <th scope="row">{{ i + 1 }}</th>
              <td>{{ fishInfo[r.fish].name }}</td>
              <td>{{ r.rate }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "rate-sheet",
  props: {
    fishInfo: {
        type: Array,
        default: () => [],
    },
    recipe: {
        type: Array,
        default: () => [],
    },
  },

  data() {

    return {
    };
  },
};
</script>

<style lang="scss" scoped>
.rate-sheet {
    box-shadow: 10px 10px 20px -9px rgba(0, 0, 0, 0.69);
    background: #ffffff;
}
</style>
