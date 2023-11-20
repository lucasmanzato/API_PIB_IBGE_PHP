document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("limpar-historico")
    .addEventListener("click", function () {
      if (
        confirm("Tem certeza que deseja apagar todo o histórico de buscas?")
      ) {
        fetch("historico.php", {
          method: "POST",
        })
          .then((response) => response.text()) // ou .json() se o PHP retornar um JSON
          .then((resposta) => {
            alert(resposta); // Exibe uma mensagem de resposta
            location.reload(); // Recarrega a página para atualizar o estado da tabela
          })
          .catch((error) => {
            console.error("Erro ao limpar o histórico:", error);
          });
      }
    });
});

function fetchBuscas() {
  fetch("historico.php")
    .then((response) => response.json())
    .then((dados) => {
      const tbody = document.querySelector("#tabela-buscas tbody");
      tbody.innerHTML = ""; // Limpa o corpo da tabela
      dados.forEach((busca) => {
        // Cria uma linha da tabela para cada busca
        const tr = document.createElement("tr");
        tr.innerHTML = `
                <td>${busca.DataHora_Consulta}</td>
                <td>${busca.PAIS_BUSCA}</td>
            `;
        tbody.appendChild(tr);
      });
    })
    .catch((error) => {
      console.error("Erro ao buscar os dados de buscas:", error);
    });
}

// Chama a função quando a página carrega
document.addEventListener("DOMContentLoaded", fetchBuscas);
