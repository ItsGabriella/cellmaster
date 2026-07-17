"""
TESTE AUTOMATIZADO - CADASTRO DE ESTOQUE (VERSÃO DASHBOARD)
Sistema: Gestão de Estoque
Ferramenta: Selenium WebDriver com Python
"""

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
import time
import random
import os
import webbrowser

class TesteAutomatizadoEstoque:
    def __init__(self, url_base="http://localhost:8080/cellmaster/estoque.php"):
        self.url_base = url_base
        self.diretorio_teste = "TesteCadastroEstoque"
        
        # Cria a pasta se não existir
        if not os.path.exists(self.diretorio_teste):
            os.makedirs(self.diretorio_teste)
            
        # Lista para armazenar resultados do relatório
        self.resultados_testes = []

        chrome_options = Options()
        chrome_options.add_argument("--start-maximized")
        
        self.driver = webdriver.Chrome(options=chrome_options)
        self.wait = WebDriverWait(self.driver, 10)
        
        print("✓ Ambiente preparado e pasta 'TesteCadastroEstoque' verificada!")

    def gerar_dados_aleatorios(self):
        pecas = ["Tela Touch iPhone 11", "Bateria Galaxy S20", "Botão Power Moto G9", 
                 "Conector de Carga USB-C", "Tela LCD Redmi Note 10", "Bateria iPhone XR"]
        categorias = ["Tela", "Bateria", "Botões", "Conectores"]
        
        peca_escolhida = f"{random.choice(pecas)} #{random.randint(100, 999)}"
        
        return {
            "nome_peca": peca_escolhida,
            "categoria": random.choice(categorias),
            "quantidade": random.randint(10, 100),
            "estoque_minimo": random.randint(2, 8),
            "valor_unitario": round(random.uniform(15.0, 350.0), 2)
        }

    def tirar_screenshot(self, nome_arquivo):
        caminho = os.path.join(self.diretorio_teste, nome_arquivo)
        self.driver.save_screenshot(caminho)
        return nome_arquivo

    def gerar_relatorio_html(self):
        caminho_html = os.path.join(self.diretorio_teste, "dashboard.html")
        
        # Contagem para o resumo
        sucessos = sum(1 for r in self.resultados_testes if r['status'] == 'Sucesso')
        falhas = len(self.resultados_testes) - sucessos

        html_content = f"""
        <!DOCTYPE html>
        <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Dashboard de Testes - Estoque</title>
            <style>
                body {{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 20px; }}
                .container {{ max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }}
                h1 {{ color: #198754; text-align: center; }}
                .summary {{ display: flex; justify-content: space-around; margin-bottom: 30px; padding: 15px; background: #e9ecef; border-radius: 5px; }}
                .card {{ text-align: center; }}
                .card h2 {{ margin: 0; font-size: 2em; }}
                .status-sucesso {{ color: #198754; }}
                .status-falha {{ color: #dc3545; }}
                table {{ width: 100%; border-collapse: collapse; margin-top: 20px; }}
                th, td {{ padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }}
                th {{ background-color: #198754; color: white; }}
                .img-link {{ color: #0d6efd; text-decoration: none; font-weight: bold; }}
                tr:hover {{ background-color: #f1f1f1; }}
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Relatório de Automação de Estoque</h1>
                <div class="summary">
                    <div class="card"><h3>Total</h3><h2>{len(self.resultados_testes)}</h2></div>
                    <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                    <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Peça</th>
                            <th>Status</th>
                            <th>Evidência</th>
                        </tr>
                    </thead>
                    <tbody>
        """
        
        for r in self.resultados_testes:
            cor_status = "status-sucesso" if r['status'] == 'Sucesso' else "status-falha"
            html_content += f"""
                <tr>
                    <td>{r['id']}</td>
                    <td>{r['nome']}</td>
                    <td class="{cor_status}">{r['status']}</td>
                    <td><a class="img-link" href="{r['screenshot']}" target="_blank">Visualizar Screenshot</a></td>
                </tr>
            """

        html_content += """
                    </tbody>
                </table>
            </div>
        </body>
        </html>
        """

        with open(caminho_html, "w", encoding="utf-8") as f:
            f.write(html_content)
        
        return caminho_html

    def executar_teste_completo(self, quantidade):
        for i in range(quantidade):
            print(f"\n🚀 Iniciando cadastro de peça {i+1} de {quantidade}...")
            dados = self.gerar_dados_aleatorios()
            status = "Falha"
            
            try:
                # 1. Acessa a página principal do Estoque
                self.driver.get(self.url_base)
                time.sleep(1.5) # Aguarda a renderização básica da página
                
                # 2. Aguarda e clica no botão "Nova Peça"
                botao_nova_peca = self.wait.until(
                    EC.element_to_be_clickable((By.XPATH, "//button[contains(normalize-space(), 'Nova Peça')]"))
                )
                botao_nova_peca.click()
                
                # 3. PAUSA CRÍTICA: Aguarda a animação do modal do Bootstrap abrir totalmente
 # 3. PAUSA CRÍTICA: Aguarda o modal do Bootstrap abrir e estabilizar
                time.sleep(2.0) 
                
                # 4. CAPTURA EXCLUSIVA: Garante que estamos pegando os inputs APENAS de dentro do Modal de Cadastro
                # Isso impede o Selenium de tentar preencher modais de edição ou linhas da tabela que usem IDs parecidos
                container_modal = self.wait.until(
                    EC.visibility_of_element_located((By.CSS_SELECTOR, "#modalProduto"))
                )

                # Localizando os elementos estritamente FILHOS do modal de cadastro
                input_nome = container_modal.find_element(By.CSS_SELECTOR, "input#iPeca")
                select_elem = container_modal.find_element(By.CSS_SELECTOR, "select#iCategoria")
                input_qtd = container_modal.find_element(By.CSS_SELECTOR, "input#iQuantidade")
                input_min = container_modal.find_element(By.CSS_SELECTOR, "input#iEstoqueMin")
                input_valor = container_modal.find_element(By.CSS_SELECTOR, "input#iValor")

                # --- 5. Preenchimento focado ---
                # Usamos JavaScript para limpar e definir os valores diretamente nos elementos corretos
                self.driver.execute_script("arguments[0].value = ''; arguments[0].value = arguments[1];", input_nome, dados["nome_peca"])
                
                # Seleciona a categoria de forma segura
                select_categoria = Select(select_elem)
                select_categoria.select_by_visible_text(dados["categoria"])
                
                # Preenche os campos numéricos limpando qualquer resíduo anterior
                self.driver.execute_script("arguments[0].value = ''; arguments[0].value = arguments[1];", input_qtd, str(dados["quantidade"]))
                self.driver.execute_script("arguments[0].value = ''; arguments[0].value = arguments[1];", input_min, str(dados["estoque_minimo"]))
                self.driver.execute_script("arguments[0].value = ''; arguments[0].value = arguments[1];", input_valor, str(dados["valor_unitario"]))

                # Pequena pausa para o preenchimento aparecer no print de evidência
                time.sleep(1.5)
                
                # 5. Envia o formulário clicando em "Salvar Peça" via JavaScript para evitar overlays
                botao_salvar = self.wait.until(
                    EC.presence_of_element_located((By.XPATH, "//button[contains(normalize-space(), 'Salvar Peça')]"))
                )
                self.driver.execute_script("arguments[0].click();", botao_salvar)
                
                # Aguarda o redirecionamento e salvamento no XAMPP
                time.sleep(2.5)
                
                # 6. Verifica se a peça cadastrada agora aparece listada na tabela da página
                if dados["nome_peca"] in self.driver.page_source:
                    status = "Sucesso"
                    print(f"✓ Peça '{dados['nome_peca']}' cadastrada com sucesso!")
                else:
                    # Recarrega a página caso o redirect do PHP não tenha atualizado o DOM a tempo
                    self.driver.get(self.url_base)
                    time.sleep(1.5)
                    if dados["nome_peca"] in self.driver.page_source:
                        status = "Sucesso"
                        print(f"✓ Peça '{dados['nome_peca']}' cadastrada com sucesso (pós-refresh)!")
                    else:
                        print("✗ A peça cadastrada não foi encontrada na tabela.")
                
            except Exception as e:
                print(f"✗ Erro no processo: {e}")
            
            # Tira screenshot e salva na lista de resultados
            nome_print = self.tirar_screenshot(f"cadastro_peca_{i+1}.png")
            self.resultados_testes.append({
                "id": i+1,
                "nome": dados["nome_peca"],
                "status": status,
                "screenshot": nome_print
            })

        # Finalização
        caminho_report = self.gerar_relatorio_html()
        self.driver.quit()
        
        print(f"\n✅ Testes finalizados! Relatório gerado em: {caminho_report}")
        webbrowser.open('file://' + os.path.realpath(caminho_report))

if __name__ == "__main__":
    print("--- SISTEMA DE AUTOMAÇÃO DE ESTOQUE ---")
    try:
        qtd = int(input("Quantas peças você deseja cadastrar hoje? "))
        if qtd > 0:
            URL_LOCAL = "http://localhost:8080/cellmaster/estoque.php"
            teste = TesteAutomatizadoEstoque(url_base=URL_LOCAL)
            teste.executar_teste_completo(qtd)
        else:
            print("Quantidade inválida.")
    except ValueError:
        print("Por favor, digite apenas números inteiros.")