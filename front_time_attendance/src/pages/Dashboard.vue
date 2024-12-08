<template>
  <q-page class="q-pa-sm">
    <q-card class="q-mb-md">
      <q-card-section class="text-h6">
        {{ $t('welcome') }}
      </q-card-section>
    </q-card>
    
  </q-page>
</template>

<script>
import {defineComponent,defineAsyncComponent} from 'vue'

export default defineComponent({
  name: 'PageIndex',
  components: {

  },
  setup() {
    return {
      mode: 'list',
    }
  },

})
</script>
