const processTransaction = (data) => {
  console.log("Here your code here", data)
}

const getCheckout = (transactionId) => {
  console.log("Fetching transaction data for: ", transactionId)
  const url = `https://api-sandbox.wompi.co/v1/transactions/${transactionId}`
  const options = { method: 'GET' }

  fetch(url, options)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
      }
      return response;
    })
    .then(response => response.json())
    .then(data => processTransaction(data))
    .catch(error => console.error('There has been a problem with your fetch operation:', error));
}

const Main = async () => {
  const url = window.location.href

  // Crear un objeto URL
  const urlObj = new URL(url);

  // Obtener los parámetros de búsqueda
  const params = new URLSearchParams(urlObj.search)
  const result = {}
  params.forEach((value, key) => {
    result[key] = value;
  })
  
  console.log(result)
  const transactionId = result["id"]
  if (transactionId) {
    getCheckout(transactionId)
  }

}

Main()