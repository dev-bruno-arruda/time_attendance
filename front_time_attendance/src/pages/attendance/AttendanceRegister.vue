<template>
  <q-page class="q-pa-sm flex flex-column" style="height: 100vh;">
    <div class="text-h6 q-ma-md q-mt-lg q-ml-md">
      {{ $t('attendance.attendance_registration') }}
    </div>
    <q-separator />
    <div class="flex flex-grow-1 items-center justify-center">
      <q-btn
        :label="$t('attendance.submit')"
        color="primary"
        class="q-mt-md"
        @click="onSubmit"
      />
    </div>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
import AttendanceRecordService from 'src/services/attendance' 
import { notifyError, notifySuccess } from 'boot/helpers'
import { useI18n } from "vue-i18n"

export default defineComponent({
  name: 'AttendanceRegister',
  setup() {
    const { post } = AttendanceRecordService()
    const { t } = useI18n()

    const onSubmit = async () => {
      try {
        const response = await post()
        
        if (response.status === 'error') {
          notifyError(t(response.error))
        } else {
          notifySuccess(t('attendance.registration_success'))
        }
      } catch (error) {
        notifyError(t('error_generic'))
      }
    }

    return {
      onSubmit,
      t
    }
  }
})
</script>