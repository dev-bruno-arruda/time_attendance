import { api } from 'boot/axios'

export default function UseApi(url) {
  const getToken = () => {
    return localStorage.getItem('auth_token')
  }

  const list = async (params = {}) => {
    try {
      const token = getToken();
      const { data } = await api.get(url, {
        params,
        headers: { Authorization: `Bearer ${token}` }
      })
      return data.data
    } catch (error) {
      handleError(error)
    }
  }

  const getByDates = async (startDate, endDate) => {
    try {
      const token = getToken(); 
      const { data } = await api.get(`${url}/?start_date=${startDate}&end_date=${endDate}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const getById = async (id) => {
    try {
      const token = getToken();
      const { data } = await api.get(`${url}/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const post = async (form) => {
    try {
      const token = getToken();
      const { data } = await api.post(url, form, {
        headers: { 
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}` 
        }
      })
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const update = async (id, form) => {
    try {
      const token = getToken();
      const { data } = await api.put(`${url}/${id}`, form, {
        headers: { 
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}` 
        }
      })
      return data
    } catch (error) {
      handleError(error)
    }
  }

  const remove = async (id) => {
    try {
      const token = getToken();
      const { data } = await api.delete(`${url}/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
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
    const errors = error.response.data.message
    if (errors) {
      const message = error.response.data.message
      throw new Error(message)
    }
    throw new Error(error.message)
  }

  return { list, getByDates, post, login, remove, update, getById }
}
