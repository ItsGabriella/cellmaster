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


class TesteAutomatizadoServicos:

  def __init__(self, url_base="http://localhost/CellMaster"):
    self.url_base = url_base
    self.diretorio_teste = "TesteServicos"

    if not os.path.exists(self.diretorio_teste):
      os.makedirs(self.diretorio_teste)

    self.resultados_testes = []

    chrome_options = Options()
    chrome_options.add_argument("--start-maximized")
    self.driver = webdriver.Chrome(options=chrome_options)
    self.wait = WebDriverWait(self.driver, 10)
    print("Ambiente preparado e pasta 'TesteServicos' verificada!")

  def gerar_dados_aleatorios(self):
    servicos_nomes = [
        "Troca de Tela",
        "Formatação de Sistema",
        "Limpeza Interna",
        "Troca de Conector",
        "Remoção de Senha",
    ]
    descricoes = [
        "Serviço com peças originais e garantia",
        "Inclui backup e reinstalação de drivers",
        "Remoção completa de poeira e troca de pasta térmica",
        "Substituição do componente danificado por um novo",
        "Desbloqueio seguro via software",
    ]
    tempos = ["00:30", "01:00", "01:30", "02:00", "00:45"]

    nome_servico = random.choice(servicos_nomes)
    nome_servico_completa = f"{nome_servico} - {random.randint(100, 999)}"

    return {
        "servico": nome_servico_completa,
        "valor": f"{random.randint(50, 400)}.00",
        "tempo": random.choice(tempos),
        "status": random.choice(["Ativo", "Inativo"]),
        "descricao": random.choice(descricoes),
    }

  def tirar_screenshot(self, nome_arquivo):
    caminho = os.path.join(self.diretorio_teste, nome_arquivo)
    self.driver.save_screenshot(caminho)
    return nome_arquivo

  def gerar_relatorio_html(self):
    caminho_html = os.path.join(self.diretorio_teste, "dashboard_servicos.html")
    sucessos = sum(1 for r in self.resultados_testes if r["status"] == "Sucesso")
    falhas = len(self.resultados_testes) - sucessos

    html_content = f"""
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard de Testes - CellMaster Serviços</title>
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
            <h1>Relatório de Automação de Serviços</h1>
            <div class="summary">
                <div class="card"><h3>Total</h3><h2>{len(self.resultados_testes)}</h2></div>
                <div class="card"><h3 class="status-sucesso">Sucessos</h3><h2>{sucessos}</h2></div>
                <div class="card"><h3 class="status-falha">Falhas</h3><h2>{falhas}</h2></div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome do Serviço</th>
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
      print(f"\nIniciando cadastro do serviço {i+1} de {quantidade}...")
      dados = self.gerar_dados_aleatorios()
      status = "Falha"

      try:
        # Acessa a página de serviços
        self.driver.get(f"{self.url_base}/servicos.php")

        # Clica no botão "Novo Serviço" para abrir o Modal
        btn_novo = self.wait.until(
            EC.element_to_be_clickable(
                (By.XPATH, "//button[@data-bs-target='#modalServico']")
            )
        )
        btn_novo.click()

        # REMOVE A CLASSE 'fade' DO MODAL VIA JS: 
        # Isso elimina instantaneamente o atraso de transição que causa o 'element not interactable'
        self.driver.execute_script("""
                    var modal = document.getElementById('modalServico');
                    if(modal) {
                        modal.classList.remove('fade');
                    }
                """)

        # Aguarda brevemente para garantir que o DOM registrou a mudança
        time.sleep(0.5)

        # Preenche os campos usando preenchimento direto e disparando os eventos do JS
        self.driver.execute_script(
            f"""
                    document.getElementById('iServico').value = "{dados['servico']}";
                    document.getElementById('iServico').dispatchEvent(new Event('input'));
                    document.getElementById('iServico').dispatchEvent(new Event('change'));

                    document.getElementById('iValor').value = "{dados['valor']}";
                    document.getElementById('iValor').dispatchEvent(new Event('input'));
                    document.getElementById('iValor').dispatchEvent(new Event('change'));

                    document.getElementById('iTempo').value = "{dados['tempo']}";
                    document.getElementById('iTempo').dispatchEvent(new Event('input'));
                    document.getElementById('iTempo').dispatchEvent(new Event('change'));

                    document.getElementById('iStatus').value = "{dados['status']}";
                    document.getElementById('iStatus').dispatchEvent(new Event('change'));

                    document.getElementById('iDescricao').value = "{dados['descricao']}";
                    document.getElementById('iDescricao').dispatchEvent(new Event('input'));
                    document.getElementById('iDescricao').dispatchEvent(new Event('change'));
                """
        )

        time.sleep(1)

        # Clica no botão de Salvar do modal utilizando JavaScript puro
        btn_salvar = self.wait.until(
            EC.presence_of_element_located(
                (
                    By.XPATH,
                    "//div[@id='modalServico']//button[@type='submit']",
                )
            )
        )
        self.driver.execute_script("arguments[0].click();", btn_salvar)

        time.sleep(2)

        # Validação do sucesso do cadastro
        if (
            "sucesso=1" in self.driver.current_url
            or dados["servico"] in self.driver.page_source
        ):
          status = "Sucesso"

      except Exception as e:
        print(f"❌ Erro no processo: {e}")

      # Tira screenshot da evidência e armazena
      nome_print = self.tirar_screenshot(f"servico_{i+1}.png")
      self.resultados_testes.append({
          "id": i + 1,
          "info": dados["servico"],
          "status": status,
          "screenshot": nome_print,
      })

    caminho_report = self.gerar_relatorio_html()
    self.driver.quit()
    print(f"\nTestes finalizados! Relatório gerado em: {caminho_report}")
    webbrowser.open("file://" + os.path.realpath(caminho_report))


if __name__ == "__main__":
  print("--- SISTEMA DE AUTOMAÇÃO CELLMASTER (SERVIÇOS) ---")
  try:
    qtd = int(input("Quantos serviços você deseja cadastrar hoje? "))
    if qtd > 0:
      URL_LOCAL = "http://localhost/CellMaster"
      teste = TesteAutomatizadoServicos(url_base=URL_LOCAL)
      teste.executar_teste_completo(qtd)
    else:
      print("Quantidade inválida.")
  except ValueError:
    print("Por favor, digite apenas números inteiros.")