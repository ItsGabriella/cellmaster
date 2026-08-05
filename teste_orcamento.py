import os
import random
import time
import webbrowser
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select, WebDriverWait
from selenium.webdriver.chrome.options import Options


class TesteAutomatizadoOrcamento:

  def __init__(self, url_base="http://localhost/CellMaster"):
    self.url_base = url_base
    self.diretorio_teste = "TesteOrcamento"

    # Cria a pasta se não existir
    if not os.path.exists(self.diretorio_teste):
      os.makedirs(self.diretorio_teste)

    # Lista para armazenar resultados do relatório
    self.resultados_testes = []

    chrome_options = Options()
    chrome_options.add_argument("--start-maximized")
    self.driver = webdriver.Chrome(options=chrome_options)
    self.wait = WebDriverWait(self.driver, 10)
    print("Ambiente preparado e pasta 'TesteOrcamento' verificada!")

  def gerar_dados_aleatorios(self):
    marcas = ["Apple", "Samsung", "Motorola", "Xiaomi", "LG"]
    modelos = ["iPhone 13", "Galaxy S22", "Moto G60", "Redmi Note 11", "One Macro"]
    defeitos = [
        "Tela quebrada",
        "Bateria viciada",
        "Não liga",
        "Conector de carga danificado",
        "Sem som",
    ]
    observacoes = [
        "Avarias visíveis no aro",
        "Trinca na tampa traseira",
        "Sem riscos na tela",
        "Entregue sem chip/cartão",
    ]

    marca = random.choice(marcas)
    modelo = random.choice(modelos)
    imei = "".join([str(random.randint(0, 9)) for _ in range(15)])

    return {
        "marca": marca,
        "modelo": modelo,
        "imei": imei,
        "defeito": random.choice(defeitos),
        "observacoes": random.choice(observacoes),
        "valor_total": f"{random.randint(150, 1200)}.00",
        "data_dia": datetime.now().strftime("%d/%m/%Y"),
        "status": random.choice(["Aguardando", "Aprovado", "Reprovado"]),
    }

  def tirar_screenshot(self, nome_arquivo):
    caminho = os.path.join(self.diretorio_teste, nome_arquivo)
    self.driver.save_screenshot(caminho)
    return nome_arquivo

  def gerar_relatorio_html(self):
    caminho_html = os.path.join(self.diretorio_teste, "dashboard.html")
    sucessos = sum(1 for r in self.resultados_testes if r["status"] == "Sucesso")
    falhas = len(self.resultados_testes) - sucessos

    html_content = f"""
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard de Testes - CellMaster Orçamentos</title>
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
            <h1>Relatório de Automação de Orçamentos</h1>
            <div class="summary">
                <div class="card"><h3>Total</h3><h2>{len(self.resultados_testes)}</h2></div>
                <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Modelo / IMEI</th>
                        <th>Status</th>
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
      print(f"\nIniciando orçamento {i+1} de {quantidade}...")
      dados = self.gerar_dados_aleatorios()
      status = "Falha"

      try:
        # Acessa a tela de orçamentos
        self.driver.get(f"{self.url_base}/orcamento.php")

        # Clica no botão "Novo Orçamento" para abrir o Modal
        btn_novo = self.wait.until(
            EC.element_to_be_clickable(
                (By.XPATH, "//button[@data-bs-target='#modalNovoOrcamento']")
            )
        )
        btn_novo.click()

        # Aguarda o modal carregar
        self.wait.until(
            EC.visibility_of_element_located((By.ID, "modalNovoOrcamento"))
        )

        # Seleciona o 1º cliente disponível na lista (índice 1)
        select_cliente = Select(
            self.driver.find_element(By.NAME, "cliente_idcliente")
        )
        if len(select_cliente.options) > 1:
          select_cliente.select_by_index(1)

        # Seleciona o 1º funcionário disponível na lista (índice 1)
        select_func = Select(
            self.driver.find_element(By.NAME, "funcionario_idfuncionario")
        )
        if len(select_func.options) > 1:
          select_func.select_by_index(1)

        # Preenche os campos text
        self.driver.find_element(By.NAME, "marca").send_keys(dados["marca"])
        self.driver.find_element(By.NAME, "modelo").send_keys(dados["modelo"])
        self.driver.find_element(By.NAME, "imei").send_keys(dados["imei"])
        self.driver.find_element(By.NAME, "defeito").send_keys(dados["defeito"])
        self.driver.find_element(By.NAME, "observacoes").send_keys(
            dados["observacoes"]
        )

        # Campo valor total
        campo_valor = self.driver.find_element(By.NAME, "valor_total")
        campo_valor.clear()
        campo_valor.send_keys(dados["valor_total"])

        # Campo data_dia (DD/MM/AAAA)
        campo_data = self.driver.find_element(By.NAME, "data_dia")
        campo_data.clear()
        campo_data.send_keys(dados["data_dia"])

        # Select de status
        Select(self.driver.find_element(By.NAME, "status")).select_by_visible_text(
            dados["status"]
        )

        # Submete o formulário dentro do modal
        btn_salvar = self.driver.find_element(
            By.XPATH,
            "//div[@id='modalNovoOrcamento']//button[@type='submit']",
        )
        btn_salvar.click()

        time.sleep(2)

        # Verifica redirecionamento de sucesso ou presença do IMEI na tabela
        if (
            "sucesso=1" in self.driver.current_url
            or dados["imei"] in self.driver.page_source
        ):
          status = "Sucesso"

      except Exception as e:
        print(f"❌ Erro no processo: {e}")

      # Tira screenshot e salva na lista de resultados
      nome_print = self.tirar_screenshot(f"orcamento_{i+1}.png")
      self.resultados_testes.append({
          "id": i + 1,
          "info": f"{dados['modelo']} (IMEI: {dados['imei']})",
          "status": status,
          "screenshot": nome_print,
      })

    # Finalização
    caminho_report = self.gerar_relatorio_html()
    self.driver.quit()
    print(f"\nTestes finalizados! Relatório gerado em: {caminho_report}")
    webbrowser.open("file://" + os.path.realpath(caminho_report))


if __name__ == "__main__":
  print("--- SISTEMA DE AUTOMAÇÃO CELLMASTER ---")
  try:
    qtd = int(input("Quantos orçamentos você deseja cadastrar hoje? "))
    if qtd > 0:
      URL_LOCAL = "http://localhost/CellMaster"
      teste = TesteAutomatizadoOrcamento(url_base=URL_LOCAL)
      teste.executar_teste_completo(qtd)
    else:
      print("Quantidade inválida.")
  except ValueError:
    print("Por favor, digite apenas números inteiros.")