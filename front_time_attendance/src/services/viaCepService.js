const baseUrl = 'https://viacep.com.br/ws/';

async function fetchCepData(zipCode) {
  const url = `${baseUrl}${zipCode}/json/`;
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Error Postal Code ${zipCode}`);
    }
    const data = await response.json();
    return data;
  } catch (error) {
    throw new Error(`Erro Postal Code${zipCode}: ${error.message}`);
  }
}

export default {
  fetchCepData,
};
