<?php
// Definindo qual domínio pode acessar esse arquivo
header('Access-Control-Allow-Origin: http://127.0.0.1:5500');

// Definindo quais métodos podem ser usados
header('Access-Control-Allow-Methods: GET, POST');

// Definindo quais cabeçalhos serão permitidos na requisição
header('Access-Control-Allow-Headers: Content-Type');

// Cabeçalho informando para o navegador que será retornado um JSON
header("Content-Type: application/json");



// Verifique se a solicitação é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifique se um arquivo foi enviado com o nome "pdfFile"
    if (isset($_FILES['pdfFile'])) {
        $pdfContent = file_get_contents($_FILES['pdfFile']['tmp_name']); //Essa 
        //linha lê o conteúdo do arquivo PDF enviado pelo formulário. Ela utiliza 
        //a função file_get_contents() para obter o conteúdo do arquivo a partir 
        //do caminho temporário onde o arquivo é armazenado temporariamente após 
        //o envio.
        $id = savePdfToDatabase($pdfContent);

        if ($id !== false) {
            echo "PDF salvo com sucesso com o ID: " . $id;
        } else {
            echo "Falha ao salvar o PDF no banco de dados.";
        }
    } else {
        echo "Nenhum arquivo PDF enviado.";
    }
} else {
    echo "Método de solicitação não permitido.";
}

// Função para salvar o conteúdo do PDF no banco de dados (exemplo).
function savePdfToDatabase ($pdfContent) {   
    $host = "localhost"; // Host do banco de dados
    $usuario = "root"; // Usuário do banco de dados
    $senha = ""; // Senha do banco de dados
    $banco = "request"; // Nome do banco de dados
    $conn = mysqli_connect($host, $usuario, $senha, $banco);
    // Prepare a consulta SQL para inserir o conteúdo do PDF no banco.
    $sql = "INSERT INTO pdf (pdf_data) VALUES (?)"; // Substitua 'tabela_pdf' pelo nome da tabela que você utiliza.

    // Preparar a declaração SQL.
    $stmt = $conn->prepare($sql);

    // Vincular o conteúdo do PDF como parâmetro da declaração SQL.
    $stmt->bind_param("s", $pdfContent);

    // Executar a declaração SQL.
    if ($stmt->execute()) {
        // Obter o ID do registro recém-inserido.
        $id = $conn->insert_id;
    } else {
        // Caso ocorra um erro na inserção, defina o ID como falso.
        $id = false;
    }

    // Fechar a declaração e a conexão.
    $stmt->close();
    $conn->close();

    // Retornar o ID do registro recém-inserido ou false em caso de falha.
    return $id;

}
?>