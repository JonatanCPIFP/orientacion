<?php require_once RUTA_APP.'/vistas/inc/header.php' ?>

<h1>Asesorias </h1>



<table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Título</th>
                <th scope="col">Datos Personales (Nombre, DNI, teléfono, email)</th>
                <th scope="col">Descripción</th>
                <th scope="col">Domicilio</th>
                <th scope="col">Estado</th>
                <?php if (tienePrivilegios($datos['usuarioSesion']->id_rol,[200,300])): ?>
                    <th scope="col">Acciones</th>
                <?php endif ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $datos["asesorias"] as $asesoria ): ?>
                <tr>
                    <th scope="row"><?php echo $asesoria->id_asesoria ?></th>
                    <td><?php echo $asesoria->titulo_as ?></td>
                    <td><?php 
                        echo ($asesoria->nombre_as) ? "Nombre: ".$asesoria->nombre_as."<br>" : "";
                        echo ($asesoria->dni_as) ? "DNI: ".$asesoria->dni_as."<br>" : "";
                        echo ($asesoria->telefono_as) ? "teléfono: ".$asesoria->telefono_as."<br>" : "";
                        echo ($asesoria->email_as) ? "email: ".$asesoria->email_as."<br>" : "";
                    ?></td>
                    <td><?php echo $asesoria->descripcion_as ?></td>
                    <td><?php echo $asesoria->domicilio_as ?></td>
                    <td>
                        <?php if($asesoria->id_estado == 1): ?>
                            <strong class="text-success"><?php echo $asesoria->estado ?></strong>
                        <?php elseif($asesoria->id_estado == 2): ?>
                            <strong class="text-warning"><?php echo $asesoria->estado ?></strong>
                        <?php endif ?>
                    </td>


        <?php if (tienePrivilegios($datos['usuarioSesion']->id_rol,[200,300])): ?>
                    <td class="text-nowrap">
                        <a class="btn btn-outline-success btn-sm" href="<?php echo RUTA_URL ?>/asesorias/ver_asesoria/<?php echo $asesoria->id_asesoria ?>">
                            <i class="bi-eye"></i>
                        </a>
                        <!-- <a class="btn btn-outline-warning btn-sm" >
                            <i class="bi-pencil-square"></i>
                        </a> -->
                        <a class="btn btn-outline-danger btn-sm">
                            <i class="bi-trash"></i>
                        </a>
                    </td>
        <?php endif ?>


                </tr>
                <tr>
                    <td></td>
                    <td colspan="6">
                        <ul>
                            <?php foreach($asesoria->acciones as $accion): ?>
                                <li>
                                    <strong>Fecha: </strong> <?php echo  formatoFecha($accion->fecha_reg) ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Creada por: </strong> <?php echo  $accion->nombre_completo ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <strong>Acción: </strong> <?php echo  $accion->accion ?>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>



<?php require_once RUTA_APP.'/vistas/inc/footer.php' ?>

