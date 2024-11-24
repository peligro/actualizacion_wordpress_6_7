<?php 
if(!defined('ABSPATH')) die();
global $wpdb;

 
$datos=$wpdb->get_results("select * from {$wpdb->prefix}tamila_cotizaciones order by id desc;");
?>
<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <h1 class="wp-heading-inline"><?php echo get_admin_page_title();?> <span style="font-size:40px!important;" class="dashicons dashicons-pdf"></span></h1>
            <p class="d-flex justify-content-end">
                <a href="<?php echo get_site_url()."/wp-admin/admin.php?page=tamila_cotizaciones%2Fadmin%2Flistar.php&excel=1";?>" class="btn btn-success" title="Exportar a excel"><i class="fas fa-file-excel"></i> Exportar a excel</a>
            </p>
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
            <hr/>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>E-Mail</th>
                            <th>Teléfono</th>
                            <th>Fecha</th>
                            <th>PDF</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         foreach ($datos as $dato) 
                         {
                           ?>
                        <tr>
                            <td><?php echo $dato->id;?></td>
                            <td><?php echo $dato->nombre;?></td>
                            <td><?php echo $dato->correo;?></td>
                            <td><?php echo $dato->telefono;?></td>
                            <td><?php $date = date_create($datos->fecha);echo  date_format($date, 'd/m/Y'); ?></td>
                            <td style="text-align:center;">
                                <a href="<?php echo get_site_url()."/wp-admin/admin.php?page=tamila_cotizaciones%2Fadmin%2Flistar.php&pdf=".$dato->id;?>" ><i class="fas fa-file-pdf text text-danger"></i></a>
                            </td>
                            <td style="text-align:center;">
                                <?php echo ($dato->estado==0) ? '<i class="far fa-thumbs-down  text text-danger"></i>':'<i class="far fa-thumbs-up  text text-success"></i>'?>
                                
                            </td>
                        </tr>
                           <?php
                         }
                         ?>
                    </tbody>
                </table>
            
            </div>
            </div>
        </div>
    </div>
</div> 
<!--
<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <h1 class="wp-heading-inline"><?php echo get_admin_page_title();?> <span style="font-size:40px!important;" class="dashicons dashicons-pdf"></span></h1>
            <p class="d-flex justify-content-end">
                <a href="javascript:void(0);" class="btn btn-success" title="Exportar a excel" onclick="get_crear_formulario('1', 'Crear nuevo formulario', '', '', '');"><i class="fas fa-file-excel"></i> Exportar a excel</a>
            </p>
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
            <hr/>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>E-Mail</th>
                            <th>Teléfono</th>
                            <th>Fecha</th>
                            <th>PDF</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            
            </div>
            </div>
        </div>
    </div>
</div> 
-->