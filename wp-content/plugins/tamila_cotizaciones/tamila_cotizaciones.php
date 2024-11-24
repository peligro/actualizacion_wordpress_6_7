<?php 
/*
Plugin Name: Tamila Cotizaciones
Plugin URI: https://www.cesarcancino.com/
Description: Este plugin es para recibir y gestionar cotizaciones
Version: 1.0.1
Author: César Cancino
Author URI: https://www.cesarcancino.com/
License: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: tamila_cotizaciones 
*/

if(!function_exists('tamila_cotizaciones_activar')){
    function tamila_cotizaciones_activar(){
        global $wpdb;
        $sql="create table if not exists 
        {$wpdb->prefix}tamila_cotizaciones(
            id int not null auto_increment,
            nombre varchar(100) not null,
            correo varchar(50) not null,
            telefono varchar(50) not null,
            ciudad varchar(50) not null,
            direccion varchar(150) not null,
            detalle text,
            fecha datetime,
            estado int default 0,
            primary key(id) 
        );          
        "; 
        $wpdb->query($sql);  
    }
}

if(!function_exists('tamila_cotizaciones_desactivar')){
    function tamila_cotizaciones_desactivar(){
        //$wpdb->query("drop table if exists {$wpdb->prefix}tamila_cotizaciones;");
        #limpiador de enlaces permanentes
        flush_rewrite_rules();
    }
}
register_activation_hook(__FILE__, 'tamila_cotizaciones_activar');
register_activation_hook(__FILE__, 'tamila_cotizaciones_desactivar');

#enqueue
add_action('admin_enqueue_scripts', function($hook){
    
        if($hook=='tamila_cotizaciones/admin/listar.php'){
            wp_enqueue_style( "bootstrapcss",  plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ) );
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
            wp_enqueue_style( "sweetalert2",  plugins_url( 'assets/css/sweetalert2.css', __FILE__ ) );
            wp_enqueue_script( "bootstrapjs",  plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array('jquery')); 
            wp_enqueue_script( "sweetalert2",  plugins_url( 'assets/js/sweetalert2.js', __FILE__ ), array('jquery'));
            wp_enqueue_script( "funcionesj",  plugins_url( 'assets/js/funciones.js', __FILE__ ) );
            wp_localize_script('funcionesj','datosajax',[
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('seg')
            ]);
        }
       
});
#cargamos el menú
if(!function_exists('tamila_cotizaciones_menu')){
    add_action('admin_menu', 'tamila_cotizaciones_menu');
    function tamila_cotizaciones_menu(){
        add_menu_page(
            "Tamila Cotizaciones",
            "Tamila Cotizaciones",
            "manage_options",
            plugin_dir_path( __FILE__ )."admin/listar.php", 
            null,
            "dashicons-pdf",
            137
        );
         
    }
}
//registrar el shortcode
//[tamila_cotizaciones]
add_action('init', function(){
    add_shortcode( 'tamila_cotizaciones', 'tamila_cotizaciones_display' );
});
if(!function_exists('tamila_cotizaciones_display')){
    function tamila_cotizaciones_display($argumentos, $content=""){
        global $wpdb;
       
        if(isset($_POST['nonce'])){
            $mensajeError="";
            if(!filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING))
            {
                $mensajeError.="<li>El campo nombre está vacío</li>";
            }
            if(!filter_var(trim($_POST['correo']), FILTER_SANITIZE_STRING))
            {
                $mensajeError.="<li>El campo correo está vacío</li>";
            }
            if (!filter_var(trim($_POST["correo"]), FILTER_VALIDATE_EMAIL)) 
            {
                $mensajeError.="<li>El E-Mail ingresado no es válido</li>";
            } 
            if(!filter_var(trim($_POST['telefono']), FILTER_SANITIZE_STRING))
            {
                $mensajeError.="<li>El campo teléfono está vacío</li>";
            }
            if(!filter_var(trim($_POST['ciudad']), FILTER_SANITIZE_STRING))
            {
                $mensajeError.="<li>El campo ciudad está vacío</li>";
            }
            if(!filter_var(trim($_POST['direccion']), FILTER_SANITIZE_STRING))
            {
                $mensajeError.="<li>El campo dirección está vacío</li>";
            }
            if(!filter_var(trim($_POST['detalle']), FILTER_SANITIZE_STRING))
            {
                $mensajeError.="<li>El campo detalle está vacío</li>";
            }
            if(empty($mensajeError)){
               
                $data=[
              
                    'nombre' => sanitize_text_field($_POST['nombre']),
                    'correo' => sanitize_text_field($_POST['correo']),
                    'telefono' => sanitize_text_field($_POST['telefono']),
                    'ciudad' => sanitize_text_field($_POST['ciudad']),
                    'direccion' => sanitize_text_field($_POST['direccion']),
                    'detalle' => sanitize_text_field($_POST['detalle']),
                    'fecha'=>date('Y-m-d H:i:s')
                ];
                $wpdb->insert("{$wpdb->prefix}tamila_cotizaciones", $data);
                ?>
                <script>
                   
                   alert("Se envió la cotización exitosamente.\nPronto nos pondremos en contacto contigo");
                   window.location=location.href;
                </script>
                <?php 
            } 
             
        }
       
        ?>
        <style>
            input[type=text], select, textarea {
                width: 100%;
                padding: 12px 20px;
                margin: 8px 0;
                display: inline-block;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                }

                input[type=submit] {
                width: 100%;
                background-color: #4CAF50;
                color: white;
                padding: 14px 20px;
                margin: 8px 0;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                }

                input[type=submit]:hover {
                background-color: #45a049;
                }
                .alert-danger {
                        color: #842029;
                        background-color: #f8d7da;
                        border-color: #f5c2c7;
                        position: relative;
    padding: 1rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
                    }
        </style>
       <?php 
        $html='';
      
        if(!empty($mensajeError)){
               
            $html.= "<div class='alert-danger'><ul> ".$mensajeError."</ul> </div>";
            
        }
          
                $html.='<form method="POST" action="" name="tamila_cotizaciones">';
                    $html.='<label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="'.((isset($_POST["nombre"])) ? $_POST["nombre"]:"").'" />';
                    $html.='<label for="correo" class="form-label">E-Mail:</label>
                            <input type="text" name="correo" id="correo" placeholder="E-Mail" value="'.((isset($_POST["correo"])) ? $_POST["correo"]:"").'" />';
                    $html.='<label for="telefono" class="form-label">Teléfono:</label>
                            <input type="text" name="telefono" id="telefono" placeholder="Telefono" value="'.((isset($_POST["telefono"])) ? $_POST["telefono"]:"").'" />';
                    $html.='<label for="ciudad" class="form-label">Ciudad:</label>
                            <input type="text" name="ciudad" id="ciudad" placeholder="Ciudad" value="'.((isset($_POST["ciudad"])) ? $_POST["ciudad"]:"").'" />';
                    $html.='<label for="direccion" class="form-label">Dirección:</label>
                            <input type="text" name="direccion" id="direccion" placeholder="Dirección" value="'.((isset($_POST["direccion"])) ? $_POST["direccion"]:"").'" />';
                    $html.='<label for="detalle" class="form-label">Detalle:</label>
                            <textarea name="detalle" id="detalle" placeholder="Detalle">'.((isset($_POST["detalle"])) ? $_POST["detalle"]:"").'</textarea>';
                    $html.='<input type="hidden" name="nonce" value="'.wp_create_nonce('seg').'" id="nonce" />'; 
                    $html.='<hr/><input type="submit" value="Enviar" />';
                $html.='</form>';
            
            
        
        return $html;
    }
}

 
use Dompdf\Dompdf; 
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
add_action('after_setup_theme', function(){
    //generamos excel
    if(isset($_GET['excel']) and is_numeric($_GET['excel'])){  
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
        global $wpdb;
        $datos=$wpdb->get_results("select id, nombre, correo, telefono, ciudad, direccion, detalle, fecha,estado from {$wpdb->prefix}tamila_cotizaciones order by id desc;"); 
        require 'vendor/autoload.php';
        $helper = new Sample();
            if ($helper->isCli()) {
                $helper->log('Este ejemplo solo debe ejecutarse desde un navegador web' . PHP_EOL);

                return;
            }
            $spreadsheet = new Spreadsheet();

            $spreadsheet->getProperties()
                        ->setCreator('Tamila')
                        ->setLastModifiedBy('Tamila.cl')
                        ->setTitle('Office 2007 XLSX Test Document')
                        ->setSubject('Office 2007 XLSX Test Document')
                        ->setDescription('Excel creado con PHP.')
                        ->setKeywords('office 2007 openxml php')
                        ->setCategory('Test result file');

            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'N°')
                        ->setCellValue('B1', 'Nombre')
                        ->setCellValue('C1', 'E-Mail')
                        ->setCellValue('D1', 'Teléfono')
                        ->setCellValue('E1', 'Ciudad')
                        ->setCellValue('F1', 'Dirección')
                        ->setCellValue('G1', 'Fecha')
                        ->setCellValue('H1', 'Detalle');
            
            $i=2;
            foreach($datos as $dato) 
                {
                    $date = date_create($dato->fecha);
                            $spreadsheet->getActiveSheet()
                            ->setCellValue('A'.$i, $dato->id)
                            ->setCellValue('B'.$i, $dato->nombre)
                            ->setCellValue('C'.$i, $dato->correo )
                            ->setCellValue('D'.$i, $dato->telefono)
                            ->setCellValue('E'.$i, $dato->ciudad)
                            ->setCellValue('F'.$i, $dato->direccion)
                            ->setCellValue('G'.$i, date_format($date, 'd/m/Y'))
                            ->setCellValue('H'.$i, $dato->detalle);
                            $i++;
                }            
            $spreadsheet->getActiveSheet()->setTitle('Hoja 1');

            $spreadsheet->setActiveSheetIndex(0);
                        
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="reporte_'.time().'.xlsx"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); 
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');
                        
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
    }
    //generamos pdf
    if(isset($_GET['pdf']) and is_numeric($_GET['pdf'])){  
        global $wpdb;
          
        $datos=$wpdb->get_results("select id, nombre, correo, telefono, ciudad, direccion, detalle, fecha,estado from {$wpdb->prefix}tamila_cotizaciones where id ='".sanitize_text_field($_GET['pdf'])."';"); 
       
        if(sizeof($datos)==0)
        {
            die("error");
        }
        $wpdb->query("update {$wpdb->prefix}tamila_cotizaciones set estado=1 where id ='".sanitize_text_field($_GET['pdf'])."';");
        require 'vendor/autoload.php';
        $dompdf = new Dompdf(array('enable_remote' => true));
        $html='';
        $html.='<h1>Cotización '.sanitize_text_field($_GET['pdf']).'</h1>';
        $html.='<ul>';
        $html.='<li> ID: '.$datos[0]->id.'</li>';
        $html.='<li> Nombre: '.$datos[0]->nombre.'</li>';
        $html.='<li> E-Mail: '.$datos[0]->correo.'</li>';
        $html.='<li> Teléfono: '.$datos[0]->telefono.'</li>';
        $html.='<li> Ciudad: '.$datos[0]->ciudad.'</li>';
        $html.='<li> Dirección: '.$datos[0]->direccion.'</li>';
        $date = date_create($datos[0]->fecha); 
        $html.='<li> Fecha: '.date_format($date, 'd/m/Y').'</li>';
        $html.='<li> Detalle: '.$datos[0]->detalle.'</li>';
        $html.='</ul>';
         
        $dompdf->loadHtml($html); 
        $dompdf->setPaper('A4', 'landscape'); 
        $dompdf->render();  
        return $dompdf->stream(time().'.pdf');
    }
});
 