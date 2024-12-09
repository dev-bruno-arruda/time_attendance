// boot/helpers.js
import { Notify } from 'quasar'


const notifyError = (message) => {
  Notify.create({
    message: message,
    color: 'negative',
    position: 'top',
  })
}

const notifySuccess = (message) => {
  
  Notify.create({
    message: message,
    color: 'positive',
    position: 'top',
  })
}

const formatDateToPtBr = (data) => {
  if (!data) return ''
  const [ano, mes, dia] = data.split('-')
  return `${dia}/${mes}/${ano}`
}

const validateDate = (date) => {
  const regex = /^(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/]\d{4}$/
  return regex.test(date)
}

const formatDateToEnUS = (date) => {
  if (!date) return ''
  const [day, month, year] = date.split('/')
  return `${year}-${month}-${day}`
}

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

export { formatDateToPtBr, validateDate, formatDateToEnUS, notifyError, notifySuccess, formatDate }
