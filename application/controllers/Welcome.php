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


}
