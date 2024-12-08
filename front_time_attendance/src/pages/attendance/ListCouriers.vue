<template>
  <q-page class="q-pa-sm">
    <q-table
      :title="$t('couriers.title')"
      :rows="couriers"
      :columns="columns"
      row-key="id"
    >
      <template v-slot:top>
        <span class="text-h5">{{ $t('couriers.title') }}</span>
        <q-space />
        <q-btn color="primary" :label="$t('btns.new')" :to="{ name: 'formCouriers' }"/>
      </template>
      <template v-slot:body-cell-actions="props">
        <q-td :props="props" class="q-gutter-sm" style="width: 100px;">
          <q-btn
            color="primary"
            icon="edit" dense size="sm"
            :to="{ name: 'formCouriers', params: { id: props.row.id } }"
          >
            <q-tooltip>
              {{ $t('btns.edit') }}
            </q-tooltip>
          </q-btn>
        </q-td>
      </template>
    </q-table>
  </q-page>
</template>

<script>
import { defineComponent, ref, onMounted, computed } from 'vue'
import couriersService from 'src/services/attendanceRecord'
import countriesService from 'src/services/countries'
import { notifyError, notifySuccess } from 'boot/helpers'
import { useI18n } from "vue-i18n"

export default defineComponent({
  name: 'listCouriers',
  setup () {
    const couriers = ref([])
    const { list, remove } = couriersService()
    const { list: listCountries } = countriesService()
    const { t } = useI18n()
    const columns = computed(() => [
      { name: 'name', label: t('name'), field: 'name', sortable: true, align: 'left' },
      { name: 'country', label: t('country'), field: 'country', sortable: true, align: 'left' },
      { name: 'email', label: t('email'), field: 'email', sortable: true, align: 'left' },
      { name: 'phone', label: t('phone'), field: 'phone', sortable: true, align: 'left' },
      { name: 'contact_name', label: t('contact_name'), field: 'contact_name', sortable: true, align: 'left' },
      { name: 'actions', label: t('columns.action'), field: 'actions', align: 'left' }
    ])

    onMounted(() => {
      getCouriers()
    })

    const getCouriers = async () => {
      try {
        const [couriersList, countries] = await Promise.all([list(), listCountries()])
        const countryMap = countries.reduce((map, country) => {
          map[country.id] = country.name
          return map
        }, {})

        couriers.value = couriersList.map(courier => ({
          id: courier.id,
          name: courier.attributes.name,
          country: countryMap[courier.attributes.country_id] || courier.attributes.country_id,
          email: courier.attributes.email,
          phone: courier.attributes.phone,
          contact_name: courier.attributes.contactName,
          createdAt: courier.attributes.createdAt,
          updatedAt: courier.attributes.updatedAt,
          link: courier.links.self
        }))
      } catch (error) {
        notifyError(error.message)
      }
    }

    const deleteCourier = async (id) => {
      try {
        const response = await remove(id)
        if (response.success) {
          notifySuccess(response.message)
        }
        getCouriers()
      } catch (error) {
        notifyError(error.message)
      }
    }

    return {
      couriers,
      columns,
      deleteCourier
    }
  }
})
</script>
