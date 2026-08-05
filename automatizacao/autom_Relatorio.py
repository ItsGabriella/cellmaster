import os
import random
import time
import webbrowser
from datetime import datetime, timedelta
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.chrome.options import Options


class TesteAutomatizadoRelatorios:

  def __init__(self, url_base="http://localhost/CellMaster"):
    self.url_base = url_base
    self.diretorio_teste = "TesteRelatorios"

    if not os.path.exists(self.diretorio_teste):
      os.makedirs(self.diretorio_teste)

    self.resultados_testes = []

    chrome_options = Options()
    chrome_options.add_argument("--start-maximized")
    self.driver = webdriver.Chrome(options=chrome_options)
    self.wait = WebDriverWait(self.driver, 10)
    print("Ambiente preparado e pasta 'TesteRelatorios' verificada!")

  def gerar_dados_aleatorios(self):
    relatorios_nomes = [
        "Relatório de Faturamento Mensal",
        "Relatório de Produtividade da Equipe",
        "Relatório de Controle de Estoque",
        "Relatório de Serviços Realizados",
        "Relatório de Orçamentos Aprovados",
    ]
    tipos = ["Clientes", "Funcionários", "Serviços", "Estoque", "Orçamento", "Ordem de Serviço"]
    responsaveis = ["Patrícia Oliveira", "Julia De andrade", "Gabriella Galdino", "Fernanda Cristina Lima"] 

    # Gera datas recentes aleatórias
    hoje = datetime.now()
    inicio = hoje - timedelta(days=random.randint(5, 30))
    
    return {
        "relatorio": f"{random.choice(relatorios_nomes)} - {random.randint(100, 999)}",
        "tipo": random.choice(tipos),
        "data_inicio": inicio.strftime("%Y-%m-%d"),
        "data_fim": hoje.strftime("%Y-%m-%d"),
        "responsavel": random.choice(responsaveis)
    }

  def tirar_screenshot(self, nome_arquivo):
    caminho = os.path.join(self.diretorio_teste, nome_arquivo)
    self.driver.save_screenshot(caminho)
    return nome_arquivo

  def gerar_relatorio_html(self):
    caminho_html = os.path.join(self.diretorio_teste, "dashboard_relatorios.html")
    sucessos = sum(1 for r in self.resultados_testes if r["status"] == "Sucesso")
    falhas = len(self.resultados_testes) - sucessos

    html_content = f"""
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard de Testes - CellMaster Relatórios</title>
        <style>
            body {{ font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 20px; }}
            .container {{ max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }}
            h1 {{ color: #1b4d22; text-align: center; }}
            .summary {{ display: flex; justify-content: space-around; margin-bottom: 30px; padding: 15px; background: #e9ecef; border-radius: 5px; }}
            .card {{ text-align: center; }}
            .card h2 {{ margin: 0; font-size: 2em; }}
            .status-sucesso {{ color: #28a745; }}
            .status-falha {{ color: #dc3545; }}
            table {{ width: 100%; border-collapse: collapse; margin-top: 20px; }}
            th, td {{ padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }}
            th {{ background-color: #1b4d22; color: white; }}
            .img-link {{ color: #007bff; text-decoration: none; font-weight: bold; }}
            tr:hover {{ background-color: #f1f1f1; }}
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Relatório de Automação de Relatórios</h1>
            <div class="summary">
                <div class="card"><h3>Total</h3><h2>{len(self.resultados_testes)}</h2></div>
                <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Relatório</th>
                        <th>Status do Teste</th>
                        <th>Evidência</th>
                    </tr>
                </thead>
                <tbody>
    """

    for r in self.resultados_testes:
      cor_status = (
          "status-sucesso" if r["status"] == "Sucesso" else "status-falha"
      )
      html_content += f"""
                    <tr>
                        <td>{r['id']}</td>
                        <td>{r['info']}</td>
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
      print(f"\nIniciando cadastro do relatório {i+1} de {quantidade}...")
      dados = self.gerar_dados_aleatorios()
      status = "Falha"

      try:
        # Acessa a página de relatórios
        self.driver.get(f"{self.url_base}/relatorio.php")
        time.sleep(1)

        # Clica no botão "Novo relatório" usando JavaScript para evitar erro de elemento não interativo
        btn_novo = self.wait.until(
            EC.presence_of_element_located(
                (By.XPATH, "//button[@data-bs-target='#modalRelatorio']")
            )
        )
        self.driver.execute_script("arguments[0].click();", btn_novo)
        
        self.driver.execute_script("""
                    var modal = document.getElementById('modalRelatorio');
                    if(modal) {
                        modal.classList.remove('fade');
                    }
                """)
        time.sleep(1)

        # Preenche os campos do modal via JavaScript com disparo de eventos
        self.driver.execute_script(
            f"""
                    document.getElementById('iRelatorio').value = "{dados['relatorio']}";
                    document.getElementById('iRelatorio').dispatchEvent(new Event('input'));
                    document.getElementById('iRelatorio').dispatchEvent(new Event('change'));

                    document.getElementById('iTipo').value = "{dados['tipo']}";
                    document.getElementById('iTipo').dispatchEvent(new Event('change'));

                    document.getElementById('iDataInicio').value = "{dados['data_inicio']}";
                    document.getElementById('iDataInicio').dispatchEvent(new Event('input'));
                    document.getElementById('iDataInicio').dispatchEvent(new Event('change'));

                    document.getElementById('iDataFim').value = "{dados['data_fim']}";
                    document.getElementById('iDataFim').dispatchEvent(new Event('input'));
                    document.getElementById('iDataFim').dispatchEvent(new Event('change'));
                """
        )

        # Seleciona o responsável de forma segura usando o elemento select
        try:
            select_resp = Select(self.driver.find_element(By.ID, "iResponsavel"))
            # Tenta selecionar pelo texto gerado, se não existir seleciona o primeiro disponível
            select_resp.select_by_visible_text(dados["responsavel"])
        except Exception:
            select_resp = Select(self.driver.find_element(By.ID, "iResponsavel"))
            select_resp.select_by_index(1) # Seleciona o primeiro funcionário da lista se o aleatório não bater exato

        time.sleep(1)

        # Clica no botão de Salvar do modal via JavaScript
        btn_salvar = self.wait.until(
            EC.presence_of_element_located(
                (
                    By.XPATH,
                    "//div[@id='modalRelatorio']//button[@type='submit']",
                )
            )
        )
        self.driver.execute_script("arguments[0].click();", btn_salvar)

        time.sleep(2)

        # Validação do sucesso do cadastro
        if (
            "sucesso=1" in self.driver.current_url
            or dados["relatorio"] in self.driver.page_source
        ):
          status = "Sucesso"

      except Exception as e:
        print(f"❌ Erro no processo: {e}")

      # Tira screenshot da evidência e armazena
      nome_print = self.tirar_screenshot(f"relatorio_{i+1}.png")
      self.resultados_testes.append({
          "id": i + 1,
          "info": dados["relatorio"],
          "status": status,
          "screenshot": nome_print,
      })

    caminho_report = self.gerar_relatorio_html()
    self.driver.quit()
    print(f"\nTestes finalizados! Relatório gerado em: {caminho_report}")
    webbrowser.open("file://" + os.path.realpath(caminho_report))


if __name__ == "__main__":
  print("--- SISTEMA DE AUTOMAÇÃO CELLMASTER (RELATÓRIOS) ---")
  try:
    qtd = int(input("Quantos relatórios você deseja cadastrar hoje? "))
    if qtd > 0:
      URL_LOCAL = "http://localhost/CellMaster"
      teste = TesteAutomatizadoRelatorios(url_base=URL_LOCAL)
      teste.executar_teste_completo(qtd)
    else:
      print("Quantidade inválida.")
  except ValueError:
    print("Por favor, digite apenas números inteiros.")