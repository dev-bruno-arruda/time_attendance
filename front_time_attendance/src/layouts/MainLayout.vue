<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="toggleLeftDrawer"
          icon="menu"
          aria-label="Menu"
          v-if="isAdmin"
        />
        <q-toolbar-title>
          Ticto Attendance and Time
        </q-toolbar-title>
        <q-space/>
        <div class="q-gutter-sm row items-center no-wrap">
          <q-btn round dense flat color="white" :icon="$q.fullscreen.isActive ? 'fullscreen_exit' : 'fullscreen'"
                 @click="$q.fullscreen.toggle()"
                 v-if="$q.screen.gt.sm">
          </q-btn>
          <q-btn round flat>
            <q-avatar size="26px">
              <img src="https://cdn.quasar.dev/img/boy-avatar.png">
            </q-avatar>
          </q-btn>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      class="bg-primary text-white"
    >
      <q-list>
        <q-item to="/" active-class="q-item-no-link-highlighting">
          <q-item-section avatar>
            <q-icon name="dashboard"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>Dashboard</q-item-label>
          </q-item-section>
        </q-item>
        <q-item to="/attendance/register" active-class="q-item-no-link-highlighting">
          <q-item-section avatar>
            <q-icon name="timer"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{$t('attendance.title')}}</q-item-label>
          </q-item-section>
        </q-item>
        <q-item v-if="isAdmin" to="/employees" active-class="q-item-no-link-highlighting">
          <q-item-section avatar>
            <q-icon name="badge"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{$t('employees.title')}}</q-item-label>
          </q-item-section>
        </q-item>
        <q-item v-if="isAdmin" to="/employees/attendance/registers" active-class="q-item-no-link-highlighting">
          <q-item-section avatar>
            <q-icon name="app_registration"/>
          </q-item-section>
          <q-item-section>
            <q-item-label>{{$t('employees_attendance_registers.title')}}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container class="bg-grey-2">
      <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script>
import EssentialLink from 'components/EssentialLink.vue'
import { defineComponent, ref, computed } from 'vue'
import { useQuasar } from "quasar"
import { useI18n } from "vue-i18n"

export default defineComponent({
  name: 'MainLayout',

  components: {
    EssentialLink,
  },

  setup() {
    const leftDrawerOpen = ref(false)
    const $q = useQuasar()
    const { t } = useI18n()

    const isAdmin = computed(() => {
      const role = JSON.parse(localStorage.getItem('role'))
      return role === 'admin'
    })

    const toggleLeftDrawer = () => {
      leftDrawerOpen.value = !leftDrawerOpen.value
    }

    return {
      $q,
      leftDrawerOpen,
      toggleLeftDrawer,
      t,
      isAdmin, // Agora isAdmin é uma propriedade computada
    }
  }
})
</script>

<style>
/* FONT AWESOME GENERIC BEAT */
.fa-beat {
  animation: fa-beat 5s ease infinite;
}

@keyframes fa-beat {
  0% {
    transform: scale(1);
  }
  5% {
    transform: scale(1.25);
  }
  20% {
    transform: scale(1);
  }
  30% {
    transform: scale(1);
  }
  35% {
    transform: scale(1.25);
  }
  50% {
    transform: scale(1);
  }
  55% {
    transform: scale(1.25);
  }
  70% {
    transform: scale(1);
  }
}
</style>
