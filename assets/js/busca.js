document.addEventListener('DOMContentLoaded', function() {
    // Obtendo a referência para o elemento select
    var selectPaises = document.getElementById('paises');

    // Evento de mudança para quando um país for selecionado
    selectPaises.addEventListener('change', function() {
        var paisSelecionado = this.value; // O valor selecionado do dropdown

        // Verificando se o usuário selecionou uma opção válida (não vazia)
        if(paisSelecionado) {
            // Criar um objeto FormData para enviar o país selecionado
            var formData = new FormData();
            formData.append('pais', paisSelecionado);

            // Fazer a requisição para busca.php
            fetch('busca.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json()) // Assumindo que a resposta será um JSON
            .then(dados => {
                d3.select("svg").remove();
                // Os dados fornecidos
                var rawData = dados;
                
                // Filtrar os dados para remover entradas com valores nulos
                const data = rawData.filter(d => Object.values(d)[0] !== null);

                console.log(data);
                
                // Mapear os dados para o formato correto para o gráfico de linha
                const parsedData = data.map(d => {
                    const year = Object.keys(d)[0];
                    const value = +Object.values(d)[0];
                    return { year: new Date(year), value };
                });
                
                // Configurações básicas para o gráfico
                const margin = { top: 20, right: 30, bottom: 30, left: 120 },
                        width = 1600 - margin.left - margin.right,
                        height = 500 - margin.top - margin.bottom;
                
                // Adiciona o elemento SVG ao corpo da página
                const svg = d3.select("body").append("svg")
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
                
                // Define as escalas para x e y
                const x = d3.scaleTime()
                    .domain(d3.extent(parsedData, d => d.year))
                    .range([ 0, width ]);
                
                const y = d3.scaleLinear()
                    .domain([0, d3.max(parsedData, d => d.value)])
                    .range([ height, 0 ]);
                
                // Adiciona os eixos X e Y
                svg.append("g")
                    .attr("transform", "translate(0," + height + ")")
                    .call(d3.axisBottom(x));
                
                svg.append("g")
                    .call(d3.axisLeft(y));

                // Adiciona o rótulo ao eixo X
                svg.append("text")
                .attr("class", "axis-label")
                .attr("x", width / 2)
                .attr("y", 10)
                .style("text-anchor", "middle")
                .text("Anos");

                // Adiciona o rótulo ao eixo Y
                svg.append("text")
                .attr("class", "axis-label")
                .attr("transform", "rotate(-90)")
                .attr("y", -margin.left)
                .attr("x", -height / 2)
                .attr("dy", "1em")
                .style("text-anchor", "middle")
                .text("Dólares");
                
                // Adiciona o caminho do gráfico de linha
                svg.append("path")
                    .datum(parsedData)
                    .attr("fill", "none")
                    .attr("stroke", "steelblue")
                    .attr("stroke-width", 1.5)
                    .attr("d", d3.line()
                    .x(d => x(d.year))
                    .y(d => y(d.value))
                    );
  
            })
            .catch(error => {
                console.error('Erro ao buscar dados:', error);
            });
        }
    });    
});