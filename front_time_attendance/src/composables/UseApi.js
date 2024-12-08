import { api } from 'boot/axios'

export default function UseApi(url) {
  const list = async (params = {}) => {
    try {
      const { data } = await api.get(url, { params })
      return data.data
    } catch (error) {
      handleError(error)
    }
  }

  const getByDates = async (startDate, EndDate) => {
    try {
      const { data } = await api.get(`${url}/?start_date=${startDate}&end_date=${EndDate}`)
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const getById = async (id) => {
    try {
      const { data } = await api.get(`${url}/${id}`)
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const post = async (form) => {
    try {
      let config = { headers: { 'Content-Type': 'application/json' } };
      const { data } = await api.post(url, form, config)
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const update = async (id, form) => {
    try {
      let config = { headers: { 'Content-Type': 'application/json' } };
      const { data } = await api.put(`${url}/${id}`, form, config);
      return data;
    } catch (error) {
      handleError(error);
    }
  };

  const remove = async (id) => {
    try {
      const { data } = await api.delete(`${url}/${id}`)
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const login = async (credentials) => {
    try {
      const { data } = await api.post('/login', credentials)
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const handleError = (error) => {
    const errors = error.response?.data?.data
    if (errors) {
      const message = Object.values(errors).flat().join('\n')
      throw new Error(message)
    }
    throw new Error(error.message)
  }

  return { list, getByDates, post, login, remove, update, getById }
}
