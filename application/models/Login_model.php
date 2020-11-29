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
		$this->db->where('id_usuario', $dados['id_usuario']);
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
		$this->db->select('*');
		$this->db->from('disponibilidade');
		if ($dadosPesquisa['cidade'] != '' && $dadosPesquisa['cidade'] != 'null'){
			$this->db->like('cidade', $dadosPesquisa['cidade']);
		}
		if ($dadosPesquisa['disponibilidade'] != ''){
			$this->db->where('disponibilidade', $dadosPesquisa['disponibilidade']);
		}
		$this->db->join('usuario', 'disponibilidade.id_usuario = usuario.id');
		//$this->db->join('avaliacao', 'disponibilidade.id_usuario = avaliacao.id_recebeu', 'LEFT');
		$dados = $this->db->get();
		$dados = $dados->result_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return $dados ;
		}else {
			return false;
		}
	}

	public function buscar_conversa($dados=null){
		$this->db->select('*');
		$this->db->from('conversa');
		$this->db->where('id_enviou', $dados['outro_id']);
		$this->db->where('id_recebeu', $dados['meu_id']);
		$this->db->or_where('id_recebeu', $dados['outro_id']);
		$this->db->where('id_enviou', $dados['meu_id']);
		$this->db->limit('11');
		$this->db->order_by('data', 'desc');
		//
		$dados =  $this->db->get();
		return $dados->result_array();
	}

	public function deletar_mensagem_duplicada($id=null){
		$this->db->where('id', $id);
		$this->db->from('conversa');
		return $this->db->delete();
	}

	

	public function enviar_conversa($dados=null){
		$this->db->set('id_enviou', $dados['id_enviou']);
		$this->db->set('id_recebeu', $dados['id_enviado']);
		$this->db->set('conversa', $dados['mensagem']);
		$this->db->insert('conversa');
		return $this->db->insert_id();
	}

	public function listarcontados($id=null){
		$this->db->select('DISTINCT(id_enviou)');
		$this->db->from('conversa');
		$this->db->where('id_recebeu', $id);
		$dados = $this->db->get();
		return $dados->result_array();
	}

	public function buscardadoscontato($dados=null, $id){
		$this->db->select('usuario.id, usuario.nome, usuario.numero, avaliacao.estrelas, avaliacao.id as id_avaliacao');
		$this->db->from('usuario');
		$this->db->join('avaliacao', 'avaliacao.id_recebeu = usuario.id and avaliacao.id_enviou = '.$id.'', 'LEFT');
		$this->db->where_in('usuario.id', $dados);
		$dados = $this->db->get();
		return $dados->result_array();
	}

	public function editarAvaliacao($dados){
		$this->db->set('estrelas', $dados['estrelas']);
		$this->db->where('id', $dados['id']);
		return $this->db->update('avaliacao');
	}

	public function inserirAvaliacao($dados){
		$this->db->set('id_enviou', $dados['id_enviou']);
		$this->db->set('id_recebeu', $dados['id_recebeu']);
		$this->db->set('estrelas', $dados['estrelas']);
		return $this->db->insert('avaliacao');
	}

	public function verificar_avaliacao($dados){
		$this->db->select('*');
		$this->db->from('avaliacao');
		$this->db->where('id_enviou', $dados['id_enviou']);
		$this->db->where('id_recebeu', $dados['id_recebeu']);
		$this->db->where('estrelas', $dados['estrelas']);
		$dados = $this->db->get();
		$dados = $dados->row_array();
		$verificar = (is_array($dados) ? count($dados) : 0);
		if ($verificar > 0){
			return true ;
		}else {
			return false;
		}
	}

	public function contarEstrelas($id_usuario){
		$this->db->select('COUNT(id) as quantidade_votos, SUM(estrelas) as quantidade_estrelas');
		$this->db->from('avaliacao');
		$this->db->where('id_recebeu', $id_usuario);
		return $this->db->get()->row_array();
	}
}