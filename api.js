function carregarpdf () {
     // Evento de clique do link ou botão para iniciar o download.
    document.getElementById('downloadLink').addEventListener('click', () => {
        // Caminho para o arquivo PHP que busca o PDF no banco de dados.
        const url = './funcao.php';

        // Faça a requisição usando fetch.
        fetch(url, {
            method: 'GET'
        })
        .then(response => response.blob())
        .then(blob => {
            // Crie um URL temporário para o blob do PDF.
            console.log(blob);
            const urlPdf = URL.createObjectURL(blob);

            // Crie um link <a> para abrir o PDF em uma nova guia do navegador.
            const link = document.createElement('a');
            link.href = urlPdf;
            link.target = '_blank';
            link.click();

            // Remova o URL temporário criado para o blob.
            URL.revokeObjectURL(urlPdf);
        })
        .catch(error => {
            console.error('Erro ao obter o PDF:', error);
        });
    });
}