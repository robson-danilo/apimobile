<?php
class Login_model extends CI_Model
{

	public function buscar_usuario_mobile($id=null){
		$this->db->select('*');
		$this->db->from('usuario');
		$this->db->where('id', $id);
		$dados = $this->db->get();
		$dados = $dados->row_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return $dados ;
		}else {
			return false;
		}
	}

	public function buscar_usuario_login($dados=null){
		$this->db->select('*');
		$this->db->from('usuario');
		$this->db->where('email', $dados['email']);
		$this->db->where('senha', $dados['senha']);
		$dados = $this->db->get();
		$dados = $dados->row_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return $dados ;
		}else {
			return false;
		}
	}

	public function inserir_usuario($dados=null){
		$this->db->set('nome', $dados['nome']);
		$this->db->set('email', $dados['email']);
		$this->db->set('senha', $dados['senha']);
		$this->db->set('numero', $dados['numero']);
		return $this->db->insert('usuario');
	}

	public function editar_usuario($dados=null){
		$this->db->set('nome', $dados['nome']);
		$this->db->set('email', $dados['email']);
		$this->db->set('senha', $dados['senha']);
		$this->db->set('numero', $dados['numero']);
		$this->db->where('id', $dados['id_usuario']);
		return $this->db->update('usuario');
	}

	public function inserir_disponibilidade($dados=null){
		$this->db->set('cidade', $dados['cidade']);
		$this->db->set('bairro', $dados['bairro']);
		$this->db->set('num', $dados['numero']);
		$this->db->set('id_usuario', $dados['id_usuario']);
		$this->db->set('disponibilidade', $dados['opcao']);
		$this->db->set('valor', $dados['valor']);
		$this->db->set('cadastrado', $dados['cadastrado']);
		return $this->db->insert('disponibilidade');
	}

	public function verificar_disponibilidade($id_usuario){
		$this->db->select('*');
		$this->db->from('disponibilidade');
		$this->db->where('id_usuario', $id_usuario);
		$dados = $this->db->get();
		$dados = $dados->row_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return false;
		}else {
			return true;
		}
	}

	public function iditar_disponibilidade($dados=null){
		$this->db->set('cidade', $dados['cidade']);
		$this->db->set('bairro', $dados['bairro']);
		$this->db->set('num', $dados['numero']);
		$this->db->set('id_usuario', $dados['id_usuario']);
		$this->db->set('disponibilidade', $dados['opcao']);
		$this->db->set('valor', $dados['valor']);
		$this->db->set('cadastrado', $dados['cadastrado']);
		return $this->db->update('disponibilidade');
	}

	public function verificar_email($email=null){
		$this->db->select('*');
		$this->db->from('usuario');
		$this->db->where('email', $email);
		$dados = $this->db->get();
		$dados = $dados->row_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return true ;
		}else {
			return false;
		}
	}

	public function buscar_disponibilidade_mobile($id){
		$this->db->select('*');
		$this->db->from('disponibilidade');
		$this->db->where('id_usuario', $id);
		$dados = $this->db->get();
		$dados = $dados->row_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return $dados ;
		}else {
			return false;
		}
	}

	public function buscar_disponibilidade_pesquisa($dadosPesquisa){
		//print_r($dadosPesquisa);exit;
		$this->db->select('*');
		$this->db->from('disponibilidade');
		if ($dadosPesquisa['cidade'] != '' && $dadosPesquisa['cidade'] != 'null'){
			$this->db->like('cidade', $dadosPesquisa['cidade']);
		}
		if ($dadosPesquisa['disponibilidade'] != ''){
			$this->db->where('disponibilidade', $dadosPesquisa['disponibilidade']);
		}
		$this->db->join('usuario', 'disponibilidade.id_usuario = usuario.id');
		$dados = $this->db->get();
		$dados = $dados->result_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return $dados ;
		}else {
			return false;
		}
	}
}