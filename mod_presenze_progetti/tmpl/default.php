<?php
	// no direct access
defined('_JEXEC') or die('Restricted access'); 
global $mainframe;
$utente	=& JFactory::getUser();
$user =& JFactory::getUser();

$matricola  =JRequest::getVar('matricola',$user->get('id'));
$dipendente =& JFactory::getUser($matricola);

$anno   =JRequest::getVar('anno',date("Y"));
$mese   =JRequest::getVar('mese',date("m")+0);
$db     =& JFactory::getDBO();
$doc = & JFactory::getDocument();

$query = "SELECT id AS value, nome as text FROM #__presenze_progetti where id in (SELECT project_id FROM #__pf_project_members where user_id=$matricola) order by text";
$db->setQuery( $query );
$progetti = $db->loadObjectList();

//Ajax adattamento
$ajax = <<<EOD
//Inizio modulo progetti
jQuery(function($) {
	
	$('div').click(function() {
		if (this.className.toString()=='OreProgetto') {
			this.innerHTML="";
			var a=this.id.split('-');
			delOreProgetto(this);
			mioForm=document.moduloOreProgetto;
			addHiddenField( 'anno',document.adminForm.anno.value,mioForm);
			addHiddenField( 'mese',document.adminForm.mese.value,mioForm);
			addHiddenField( 'matricola',document.adminForm.matricola.value,mioForm);
			addHiddenField( 'task','delOreProgetto',mioForm);
			addHiddenField( 'idOreProgetto',a[1],mioForm);
			addField( '','Eliminare',mioForm,'submit');
		}
	});
	
});

function delOreProgetto(ident){
	mioForm = document.createElement('form');
	mioForm.setAttribute('name','moduloOreProgetto');
	mioForm.setAttribute('method','post');
	mioForm.setAttribute('action','index.php?option=com_presenze');	
	ident.appendChild(mioForm);
}

    function addHiddenField2( nome,tipo,form,id){
    	
    	id = typeof id !== 'undefined' ? id : nome;
    	
        hidden = document.createElement( 'input' );
        hidden.setAttribute( 'type', 'hidden' );
        hidden.setAttribute( 'name',  nome );
        hidden.setAttribute( 'id',  id );
        hidden.setAttribute( 'value', tipo );
        form.appendChild( hidden );
    }
    
    function inserisciProgetti(){
        idProgetto=document.getElementById('idProgetto').value;
        inizio=document.getElementById('oreInizio').value;
        fine=document.getElementById('oreFine').value;
        commento=document.getElementById('commento').value;

        addHiddenField('idProgetto',idProgetto,document.adminForm);
        addHiddenField('oreInizio',inizio,document.adminForm);
        addHiddenField('oreFine',fine,document.adminForm);
        addHiddenField('commento',commento,document.adminForm);
        
        document.adminForm.task.value='setCaricaOreProgetto';
        document.adminForm.option.value='com_presenze';
        document.adminForm.submit( );
    }
    
    function doKey(key) {
        if (key == 13) {
            inserisciProgetti();
        } 
    }
    
    function cancellaOreProgetti(){
        document.adminForm.task.value='delOreProgetti';
        document.adminForm.option.value='com_presenze';
        document.adminForm.submit( );
    }
    
    function stampaOreProgetti(){
    
    	preparaOreOrdinario();
        idProgetto=document.getElementById('idProgetto').value;
        addHiddenField('idProgetto',idProgetto,document.adminForm);
        
        document.adminForm.task.value='printOreProgetti';
        document.adminForm.option.value='com_presenze';
        document.adminForm.submit( );
    }	
	
	function preparaOreOrdinario(){
		var elms = document.getElementsByClassName("orelavorate"),
        l = elms.length, i;
	    for( i=0; i<l; i++) {
	    	if (elms[i].innerHTML.length>0){	    	
	    		var a=elms[i].id.split('-');
				addHiddenField2('orelavorate[]',a[1] + '-' + elms[i].innerHTML,document.adminForm,'orelavorate' + a[1]);
			}
	    }
	}
//Fine modulo progetti
EOD;

$doc->addScriptDeclaration( $ajax );

?>



<div class="todbox">
    
<div class="BlockHeader">
    <div class="header-tag">
        <div class="BlockHeader-text">
            Progetti
        </div>
    </div>
    <div class="l"></div>
    <div class="r"><div></div></div>
</div>    

<input class='text_area' placeholder="Inserire orario inizio" type='text' name='oreInizio' id='oreInizio' />
                    <input class='text_area' placeholder="Inserire orario fine" type='text' name='oreFine' id='oreFine' /> 
                    <input class='text_area' placeholder="Commenta" type='text' name='commento' id='commento' onKeyPress="doKey(window.event.keyCode)"/> 
    
                    <?php  echo JHTML::_( 'select.genericlist', $progetti , 'idProgetto','onKeyPress="doKey(window.event.keyCode)"');?>
    
    <table class="toolbar">
        <tbody>
            <tr>
                <td>
                    
                </td>
                <td id="toolbar-save" class="button"><a class="toolbar" onclick="javascript: inserisciProgetti();" href="#"> <span title="Applica" class="icon-32-new"> </span> Inserisci </a>
                </td>
                <td id="toolbar-print" class="button"><a class="toolbar" onclick="javascript: stampaOreProgetti();" href="#"> <span title="Applica" class="icon-32-print"> </span> Stampa </a>
                </td>                
            </tr>
        </tbody>
    </table>
    
</div>