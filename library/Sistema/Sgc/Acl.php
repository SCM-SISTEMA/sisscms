<?php
/**
 * Sindicato_Sasind_Acl class
 * ACL pattern implementação
 *
 * Classe responsável por setar as regras segurança do sistema, e gerar
 * um array para ser utilizado pelo Zend_Navigator.
 *
 * @author      Gilvan Junior
 * @since       Arquivo disponsável desde a versão 1.0
 */

Class Sistema_Sgc_Acl extends Zend_Acl {

    /**
     * Singleton instance
     *
     * @var SindicatoAcl
     */
    protected static $_instance = null;

    /**
     * Retorna a instância SindicatoAcl
     * Singleton pattern implementação
     *
     * @return SindicatoAcl Uma abstração do Zend_Acl
     */
    public static function getInstance() {
        if( self::$_instance === null ) {
            self::$_instance = new Sistema_Sgc_Acl(
                Zend_Auth::getInstance()
                    ->setStorage(new Zend_Auth_Storage_Session(
                            Zend_Registry::get('config')->sistema->sigla)
                    )
            );
        }
        return self::$_instance;
    }

    /**
     * Método construtor da classe
     * Singleton pattern implementação
     *
     * @param Zend_Auth $auth Aguarda uma instância do Zend_Auth
     * @return Zend_Registry Uma inst�ncia do Zend_Registry 'arrNavegacao'
     */
    public function __construct($auth) {
        // Array provento da autenticação realizada
        $arrAutenticacao = $auth->getStorage()->read();
        if($auth->getIdentity()) {

            if($arrAutenticacao['user']){
                //Definindo perfil
                $this->addRole($arrAutenticacao['user']->no_perfil);

                //Mapeando funcionalidades
                $this->addResource('index');
                $this->addResource('autenticacao');
                $this->addResource('usuario');
                $this->addResource('financeiro');
                $this->addResource('administracao');
                $this->addResource('entidade');
                $this->addResource('sindicato');
                $this->addResource('cargo');
                $this->addResource('patrimonio');
                $this->addResource('convenio');
                $this->addResource('funcionalidade');

                //Definindo acesso as funcionalidades
                $this->allow($arrAutenticacao['user']->no_perfil,'autenticacao','logout');

                //**** CONVENIOS
                //********************************
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','cadastro');
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','consulta');
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','alterar');
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','excluir');
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','vincular');
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','consultarsocio');
                $this->allow($arrAutenticacao['user']->no_perfil,'convenio','relatorio');

                //**** USUÁRIO
                //********************************
                $this->allow($arrAutenticacao['user']->no_perfil,'usuario','cadastro');
                $this->allow($arrAutenticacao['user']->no_perfil,'usuario','retornausuario');
                $this->allow($arrAutenticacao['user']->no_perfil,'usuario','excluir');


                //**** FUNCIONALIDADE
                //********************************
                $this->allow($arrAutenticacao['user']->no_perfil,'funcionalidade','cadastro');

                //**** SINDICATO
                //********************************
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','cadastro');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','funcionalidade');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','cargo');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','tipocargo');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','retornatipocargo');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','excluirtipocargo');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','retornacargo');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','excluircargo');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','consultafuncionalidade');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','lista-perfil-funcionalidade');
                $this->allow($arrAutenticacao['user']->no_perfil,'sindicato','excluir-funcionalidade');





            }


        }




    }
}