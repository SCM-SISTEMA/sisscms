<?php
/**
 *
 *
 * Descrição do arquivo (opcional).
 *
 * @author      Gilvan Junior
 * @copyright   Sistema
 * @package     Sistema_
 * @since       Arquivo disponível desde a versão 1.0
 * @version     $Id$
 */

/**
 * Bootstrap class
 *
 * Bootstrap responsável pela inicialização de plugins, views, layout,
 * models, tradução e demais configurações do sistema
 *
 * @author      Gilvan Junior
 * @since       Arquivo disponível desde a versão 1.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    /**
     * Método responsável pelo autoload das classes do zend framework.
     *
     * @return Zend_Loader_Autoloader Retorna uma instancia do Zend_Application
     */
    protected function _initAutoLoad() {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath' => APPLICATION_PATH
        ));

        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->setFallbackAutoloader(true);
        return $autoloader;
    }

    protected function _initModules()
    {
        // Call to prevent ZF from loading all modules
    }

    /**
     * Método responsável pelo cache dos metadata do Zend_Db_Table.
     *
     * @return Cache da descrição das tabelas
     */
    protected function _initCacheDbTable() {
        $frontendOptions = array(
            'lifetime'				   => (86400*7), /* 24 horas */
            'automatic_serialization' => true
        );

        $backendOptions  = array(
            'cache_dir'  => APPLICATION_PATH . '/../data/cache/'
        );
        /* $cache = Zend_Cache::factory(
              'Core',
              'File',
              $frontendOptions,
              $backendOptions
       );
       Zend_Db_Table_Abstract::setDefaultMetadataCache($cache); */
    }


    protected function _initDefaultModuleAutoloader()
    {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Administracao',
            'basePath' => APPLICATION_PATH . "/modules/administracao",
            'resourceTypes' => array(
                'models' => array(
                    'path' => 'models',
                    'namespace' => 'Model'
                ),
            ),
        ));

        Zend_Controller_Action_HelperBroker::addPrefix('App_Action_Helper');

        return $moduleLoader;
    }

    /**
     * Método responsável por registrar a configurações em cache para uso em
     * outras partes do sistema;
     *
     * @return Zend_Registry Retorna uma instancia setada como 'config'
     *
     * Exemplo de uso
     * <code>
     * <?php
     * Zend_Registry::get('config')->sistema->sigla;
     * ?>
     * </code>
     */
    protected function _initConfig() {
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/config.ini', APPLICATION_ENV);
        Zend_Registry::getInstance()->set('config', $config);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagination/pagination.phtml');
    }

    /**
     * Método responsável por inicializar o sistema de segurança do sistema,
     * é realizado uma instancia do Zend_Auth e Zend_ACL
     */
    protected function _initSeguranca() {
         $auth = Zend_Auth::getInstance();
         $acl = Sistema_Sgc_Acl::getInstance();

         $fc = Zend_Controller_Front::getInstance();
         //$fc->registerPlugin(new Sistema_Plugin_Seguranca($acl, $auth));
         $fc->registerPlugin(new Sistema_Plugin_Utils());
     }



    /**
     * Método responsável por gerar a navegação do sistema com base no array
     * gerado pela classe Sindicato_Plugin_Seguranca, adicionar os path dos helpers
     * de visão e todo o container para o layout
     */
    protected function _initNavegacao() {

        $view = new Zend_View();
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $view->addHelperPath('Sistema/Helpers/', 'Sistema_Helpers');
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        $fc = Zend_Controller_Front::getInstance();
        $fc->registerPlugin(new Sistema_Plugin_Utils());

        if(Zend_Registry::isRegistered("arrNavegacao")) {
            $view->navigation(new Zend_Navigation(Zend_Registry::get("arrNavegacao")));
            $view->navigation()->menu()->setPartial(array('menu.phtml','default'));
        }
    }

}

