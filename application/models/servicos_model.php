<?php
class Caixasessao_record extends Record{
  function __construct()
  {
    $metadata = array(
        'caixasessao_id'   =>  array('type'=>'int',                    'null'=>false, 'primary key'=>true, 'auto_increment'=>true),
        'caixa_id'         =>  array('type'=>'int',                    'null'=>false),
        'usuario_id'       =>  array('type'=>'int',                    'null'=>false),
        'entidade_id'      =>  array('type'=>'int',                    'null'=>false),
        'status'           =>  array('type'=>'int',                    'null'=>false), // 1 - Aberta; 2-Fechada;
        'statusValor'      =>  array('type'=>'int',                    'null'=>false), // 0-Sem Divergência; 1 - Divergente; 2-Ajustada
        'statusConferencia'=>  array('type'=>'int',                    'null'=>true),  // 0 - Não conferido; 1 - Conferido;
        'saldoAbertura'    =>  array('type'=>'numeric', 'size'=>'15,2', 'null'=>true),
        'saldoFechamento'  =>  array('type'=>'numeric', 'size'=>'15,2', 'null'=>true),
        'vlAjuste'         =>  array('type'=>'numeric', 'size'=>'15,2', 'null'=>true),
        'dtAbertura'       =>  array('type'=>'datetime',               'null'=>false),
        'dtFechamento'     =>  array('type'=>'datetime',               'null'=>true)
        );
    parent::__construct('caixasessao',$metadata);
  }
}

class Caixasessao_model extends MY_Model {

    function __construct()
    {
        parent::__construct('Caixasessao_record');
    }
    
    //UPDATE STATUS DA SESSÃO
    public function processar_status($caixasessao){
      if(is_numeric($caixasessao))
          $caixasessao = $this->get($caixasessao);
      //CARREGA MODEL
      $mcaixasessaotot = model_load_model('caixa/caixasessaotot_model');
      
      //BUSCA TODOS OS TOTALIZADORES DESSA SESSÃO
      $caixasessaotot = $mcaixasessaotot->list_by(array('caixasessao_id' => $caixasessao->caixasessao_id, 'entidade_id' => $caixasessao->entidade_id));
      
      $valorAjusteTotal = 0;
      $statusDivergente = false;
      $statusAjustado = false;
      foreach($caixasessaotot as $cxstot){
        //VALOR DE AJUSTE
        $valorAjusteTotal += $cxstot->vlAjuste * 1;
        //SE TOTALIZADOR FOR DIVERGENTE
        if($cxstot->status == 1){
          $statusDivergente = true;
          break;
          
        //SE TOTALIZADOR FOR AJUSTADO
        }elseif($cxstot->status == 2){
          $statusAjustado = true;
        }
      }
      $status = 0;
      if($statusDivergente)
        $status = 1;
      elseif($statusAjustado)
        $status = 2;
      //ATUALIZA SESSÃO DE CAIXA
      $caixasessao->statusValor = $status;
      $caixasessao->vlAjuste = $valorAjusteTotal;
      $this->update($caixasessao);
    }
    

    public function buscarSessaoAberta($usuario_id)
    {
      $sql = "SELECT caixasessao.caixa_id, 
              caixasessao.dtAbertura,
              caixasessao.caixasessao_id, 
              caixa.nome
              FROM caixasessao
              INNER JOIN caixa ON caixa.caixa_id = caixasessao.caixa_id
              WHERE caixasessao.entidade_id = {$this->entidade_id}
              AND caixasessao.usuario_id = {$usuario_id}
              AND caixasessao.status = 1";
      $query = $this->db->query($sql);
      $caixasessao = $query->num_rows() == 1?$query->row():false;
      return $caixasessao;
    }

    public function verificaSessaoAberta()
    {
      $sql = "SELECT caixasessao.caixa_id, 
              caixasessao.dtAbertura,
              TIMESTAMPDIFF(HOUR, caixasessao.dtAbertura,NOW()) AS TESTE,
              (CASE
                WHEN TIMESTAMPDIFF(HOUR, caixasessao.dtAbertura,NOW()) >= 24 THEN 1
                ELSE 0
              END)sessaoPendente,
              caixasessao.caixasessao_id 
              FROM caixasessao
              WHERE caixasessao.entidade_id = {$this->entidade_id}
              AND caixasessao.usuario_id = {$this->user_profile->uacc_id}
              AND caixasessao.status = 1";
      $query = $this->db->query($sql);
      $caixasessao = $query->num_rows() == 1?$query->row():false;
      return $caixasessao;
    }
}