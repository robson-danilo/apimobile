<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');//Carrega o helper de url(link)
		$this->load->helper('form');//Carrega o helper de formul�rio
		$this->load->helper('array');//Carrega o helper array
		$this->load->library('session');//Carrega a biblioteca de sess�o
		$this->load->library('table');// Carrega a bibioteca de tabela
		$this->load->library('form_validation');//Carrega a biblioteca de valida��o de formul�rio
		$this->load->model('login_model');//Carrega o model
		
		
		//Limpa o cache, não permitindo ao usuário visualizar nenhuma página logo depois de ter feito logout do sistema
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$this->output->set_header("Pragma: no-cache");
	}


	public function index($id = null)
	{

		//echo "testando";
		//$resultado = 'testando123';

		//echo json_encode($resultado);
		echo "Cai fora";
		//session_destroy();
		//$this->load->view('welcome_message');
	}

	public function inserir($nome,$email,$senha,$numero){
		$nome = str_replace('%20', ' ', $nome);
		$nome = str_replace('%C3%A7', 'ç', $nome);
		$nome = str_replace('%C3%A3', 'ã', $nome);
		$nome = str_replace('%C3%A9', 'é', $nome);
		$nome = str_replace('%C3%89', 'É', $nome);
		$nome = str_replace('%C3%A1', 'á', $nome);
		$nome = str_replace('%C3%AD', 'í', $nome);

		$dados = array('nome'=>$nome,
			'email'=>$email,
			'senha'=>$senha,
			'numero'=>$numero);
		$inserir = $this->login_model->verificar_email($email);
		$mensagem = '';
		if ($inserir == false){
			$retornar = $this->login_model->inserir_usuario($dados);
			$mensagem = true;
		}else {
			$mensagem = false;
		}
		//$retornar = $this->login_model->buscar_dados($dados);
		
		echo json_encode($mensagem);
	}

	public function editarUsuario($nome,$email,$senha,$numero,$id_usuario){
		$dados = array('nome'=>$nome,
			'email'=>$email,
			'senha'=>$senha,
			'numero'=>$numero,
			'id_usuario'=>$id_usuario);

		//print_r($dados);exit;
		$retornar = $this->login_model->editar_usuario($dados);
		//$mensagem = true;
		
		//$retornar = $this->login_model->buscar_dados($dados);
		
		echo json_encode($retornar);
	}

	public function verificar($email){
		$inserir = $this->login_model->verificar_email($email);
		echo json_encode($inserir);
	}

	public function buscar($email,$senha){
		$dados = array('email'=>$email,
			'senha'=>$senha);
		$retornar = $this->login_model->buscar_usuario_login($dados);
		echo json_encode($retornar);

	}

	public function buscarUsuario($id){
		$retornar = $this->login_model->buscar_usuario_mobile($id);
		echo json_encode($retornar);

	}

	public function buscarDisponibilidade($id){
		$retornar = $this->login_model->buscar_disponibilidade_mobile($id);
		echo json_encode($retornar);

	}

	public function buscarDisponibilidadePesquisa($cidade=null,$disponibilidade=null){
		$dados = array('cidade'=>$cidade,
			'disponibilidade'=>$disponibilidade);
		$retornar = $this->login_model->buscar_disponibilidade_pesquisa($dados);

		foreach ($retornar as $key => $value) {
			 $dadosretorno = $this->login_model->contarEstrelas($value['id_usuario']);
			 if(empty($dadosretorno)){
			 	$retornar[$key]['estrelas'] = null;
			 }else if ($dadosretorno['quantidade_votos'] > 1 ){
			 	$estrelas = $dadosretorno['quantidade_estrelas']/$dadosretorno['quantidade_votos'];
			 	$retornar[$key]['estrelas'] = $estrelas;
			 }else {
			 	$retornar[$key]['estrelas'] = $dadosretorno['quantidade_estrelas'];
			 }
		}
		
		echo json_encode($retornar);

	}

	public function inserirDisponibilidade($cidade,$bairro,$numero,$id_usuario,$opcao,$valor,$cadastrado){
		$dados = array('cidade'=>$cidade,
			'bairro'=>$bairro,
			'numero'=>$numero,
			'id_usuario'=>$id_usuario,
			'opcao'=>$opcao,
			'valor'=>$valor,
			'cadastrado'=>$cadastrado);

		$verificar = $this->login_model->verificar_disponibilidade($id_usuario);

		if ($verificar == true){
			$retornar = $this->login_model->inserir_disponibilidade($dados);
		}else {
			$retornar = true;
		}
		
		echo json_encode($retornar);
	}

	public function editarDisponibilidade($cidade,$bairro,$numero,$id_usuario,$opcao,$valor,$cadastrado){
		$dados = array('cidade'=>$cidade,
			'bairro'=>$bairro,
			'numero'=>$numero,
			'id_usuario'=>$id_usuario,
			'opcao'=>$opcao,
			'valor'=>$valor,
			'cadastrado'=>$cadastrado);

		//print_r($dados);exit;
		$retornar = $this->login_model->iditar_disponibilidade($dados);
		//$mensagem = true;
		
		//$retornar = $this->login_model->buscar_dados($dados);
		
		echo json_encode($retornar);
	}


	public function ListarConversa($meu_id,$outro_id){
		$dados = array('outro_id' =>$outro_id,
			'meu_id' =>$meu_id);
		$dados = $this->login_model->buscar_conversa($dados);


		echo json_encode($dados,JSON_UNESCAPED_UNICODE);
	}

	public function EnviarConversa($meu_id,$outro_id,$mensagem){
		$mensagem = str_replace('%20', ' ', $mensagem);
		$mensagem = str_replace('%C3%A7', 'ç', $mensagem);
		$mensagem = str_replace('%C3%A3', 'ã', $mensagem);
		$mensagem = str_replace('%C3%A9', 'é', $mensagem);
		$mensagem = str_replace('%C3%89', 'É', $mensagem);
		$mensagem = str_replace('%C3%A1', 'á', $mensagem);
		$mensagem = str_replace('%C3%AD', 'í', $mensagem);
		$dadosEnviar = array('id_enviado' =>$outro_id,
			'id_enviou' =>$meu_id,
			'mensagem' =>$mensagem);
		$dados = $this->login_model->enviar_conversa($dadosEnviar);
		echo json_encode($dados,JSON_UNESCAPED_UNICODE);
	}

	public function deletarMensagem($id){
		$dados = $this->login_model->deletar_mensagem_duplicada($id);
		echo json_encode($dados,JSON_UNESCAPED_UNICODE);
	}


	public function ListarContatos($id){
		$ids = $this->login_model->listarcontados($id);

		if (!empty($ids)){
			//print_r($dados);exit;
			foreach ($ids as $key => $value) {
				foreach ($value as $key2 => $value2) {
					$dados[] = $value2;
				}
			}
			$dados_pacientes = $this->login_model->buscardadoscontato($dados, $id);
		}else {
			$dados_pacientes = false;
		}
		
		//print_r($dados_pacientes);exit;

		echo json_encode($dados_pacientes,JSON_UNESCAPED_UNICODE);
	}

	public function editarAvaliacao($estrelas,$id_avaliacao){
		$dados = array('estrelas'=>$estrelas,
			'id'=>$id_avaliacao);
		$retornar = $this->login_model->editarAvaliacao($dados);
		
		echo json_encode($retornar);
	}

	public function inserirAvaliacao($estrelas,$id_enviou, $id_recebeu){
		$dados = array('estrelas'=>$estrelas,
			'id_enviou'=> $id_enviou,
			'id_recebeu'=> $id_recebeu);

		$inserir = $this->login_model->verificar_avaliacao($dados);
		if ($inserir == false){
			$retornar = $this->login_model->inserirAvaliacao($dados);
			$mensagem = true;
		}else {
			$mensagem = true;
		}	
		
		echo json_encode($mensagem);
	}


}
