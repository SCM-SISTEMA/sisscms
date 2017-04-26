<?php

class DownloadController  extends Zend_Controller_Action
{
	public function indexAction()
    {
    	$this->_redirect( "/cadastro" );
    }
    
    /**
     * Metodo para fazer download de arquivos
     * 
     * @return VOID
     */
    public function getAction()
    {
    	$nome 		= $this->_getParam( 'arquivo' );
    	$path 		= $this->_getParam( 'path' );
    	$filename	= $this->_getParam( 'filename' );

    	if( empty( $filename ) )
    	{
	    	if( strstr( $nome, '/' ) )
	    	{
	    		$filename	= substr( $nome, strrpos( $nome, '/' ) + 1 );
	    	}
	    	else
	    	{
	    		$filename = $nome;
	    	}
    	}
        
    	$arrNomeParts = pathinfo( $nome );
    	
    	if( file_exists( $path . '/' . $arrNomeParts[ 'filename' ] . '.' . strtolower( $arrNomeParts[ 'extension' ] ) ) )
    	{
    		$nome = $arrNomeParts[ 'filename' ] . '.' . strtolower( $arrNomeParts[ 'extension' ] );
    	}
    	elseif( file_exists( $path . '/' . $arrNomeParts[ 'filename' ] . '.' . strtoupper( $arrNomeParts[ 'extension' ] ) ) )
    	{
    		$nome = $arrNomeParts[ 'filename' ] . '.' . strtoupper( $arrNomeParts[ 'extension' ] );
    	}
    	else
        {
            # Correcao para casos de arquivos na tela de comprovantes de imoveis de endereco que estao com tipo errado
            $arrFileNameAlternativo = explode( '_', $arrNomeParts[ 'filename' ] );

            if( in_array( $arrFileNameAlternativo[ 1 ], array(
                GEN_ANEXO_LOCAL_DE_OFERTA_ATO,
                GEN_ANEXO_LOCAL_DE_OFERTA_IMOVEL,
                GEN_ANEXO_LOCAL_DE_OFERTA_PARCERIA
            ) ) )
            {
                # Alterando o tipo para GEN_ANEXO_LOCAL_DE_OFERTA 234
                $arrNomeParts[ 'filename' ] = $arrFileNameAlternativo[ 0 ] . '_' . GEN_ANEXO_LOCAL_DE_OFERTA;

                if( file_exists( $path . '/' . $arrNomeParts[ 'filename' ] . '.' . strtolower( $arrNomeParts[ 'extension' ] ) ) )
                {
                    $nome = $arrNomeParts[ 'filename' ] . '.' . strtolower( $arrNomeParts[ 'extension' ] );
                }
                elseif( file_exists( $path . '/' . $arrNomeParts[ 'filename' ] . '.' . strtoupper( $arrNomeParts[ 'extension' ] ) ) )
                {
                    $nome = $arrNomeParts[ 'filename' ] . '.' . strtoupper( $arrNomeParts[ 'extension' ] );
                }
            }
        }
        
    	if( substr( $nome, 0, 1 ) != '/' && substr( $path, -1, 1) != '/' )
    	{
    		$nome	= '/' . $nome;
    	}

    	header( "Content-Type: application/download; charset=iso-8859-1" );
		header( "Content-Disposition: attachment; filename=\"{$filename}\"" );

		readfile( $path . $nome );
		
		$this->_helper->layout->disableLayout = true;
		
		exit();
    }
}