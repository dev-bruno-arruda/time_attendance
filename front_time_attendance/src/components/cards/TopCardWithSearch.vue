<template>
  <div class="q-mb-md q-mt-md">
    <q-card class="q-pa-md" :style="{ borderRadius: borderRadius }">
      <div class="fit row wrap justify-center items-center content-center">
        <div class="col-8 q-pa-md">
          <div class="text-h6">{{ title }}</div>
        </div>
        <!-- Campos de data para filtrar a busca -->
        <div class="col q-pa-sm">
          <q-input
            v-model="start_date"
            type="date"
            label="Start Date"
            dense
          />
        </div>
        <div class="col q-pa-sm">
          <q-input
            v-model="end_date"
            type="date"
            label="End Date"
            dense
          />
        </div>
        <!-- Botão de buscar -->
        <div class="col-auto q-pa-sm">
          <q-btn
            label="Search"
            :color="buttonColor"
            @click="emitSearch"
            :style="{ width: '100%' }"
          />
        </div>
      </div>
    </q-card>
  </div>
</template>

<script>
export default {
  name: "TopCardWithSearch",
  props: {
    title: { type: String, required: true },
    buttonLabel: { type: String, required: true },
    buttonRoute: { type: [String, Object], required: true },
    buttonColor: { type: String, default: "purple-8" },
    borderRadius: { type: String, default: "16px" },
  },
  data() {
    return {
      start_date: '', // Novo campo para data de início
      end_date: '',   // Novo campo para data de término
    };
  },
  methods: {
    emitSearch() {
      this.$emit("search-dates", { start_date: this.start_date, end_date: this.end_date });
    },
  },
};
</script>
