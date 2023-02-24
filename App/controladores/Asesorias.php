<?php

class Asesorias extends Controlador{

    public function __construct(){
        Sesion::iniciarSesion($this->datos);
        
        $this->datos["menuActivo"] = "asesorias";

        $this->asesoriaModelo = $this->modelo('AsesoriaModelo');
        
        $this->datos["usuarioSesion"]->roles = $this->asesoriaModelo->getRolesProfesor($this->datos["usuarioSesion"]->id_profesor);
        $this->datos["usuarioSesion"]->id_rol = obtenerRol($this->datos["usuarioSesion"]->roles);

        $this->datos['rolesPermitidos'] = [200,300];         // Definimos los roles que tendran acceso
                                                            // Comprobamos si tiene privilegios
        if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
            echo "No tienes privilegios!!!";
            exit();
            // redireccionar('/');
        }
    }

    public function index(){

        $this->datos["asesorias"] = $this->asesoriaModelo->getAsesorias();
        foreach($this->datos["asesorias"] as $asesoria){
            $asesoria->acciones = $this->asesoriaModelo->getAccionesAsesoria($asesoria->id_asesoria);
        }

        $this->vista("asesorias/index",$this->datos);
    }


    public function add_asesoria($error=0){
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $asesoria = $_POST;

            if (!$_POST["nombre_as"] && !$_POST["dni_as"] && !$_POST["titulo_as"] && !$_POST["telefono_as"] && !$_POST["email_as"] && !$_POST["domicilio_as"] && !$_POST["descripcion_as"]){        // Comprobamos que haya datos por lo menos en un campo
                redireccionar('/asesorias/add_asesoria/1');
            } else {
                if($this->asesoriaModelo->addAsesoria($asesoria,$this->datos["usuarioSesion"]->id_profesor)) {
                    redireccionar('/');
                } else {
                    echo "Se ha producido un Error!!!";
                }
            }

        } else {
            $this->datos["menuActivo"] = "";
            $this->datos["error"] = $error;

            $this->vista("asesorias/add_asesoria",$this->datos);
        }
    }


    public function ver_asesoria($id_asesoria){

        $this->datos['rolesPermitidos'] = [200,300];

        if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
            echo "No tienes privilegios!!!";
                exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $asesoria = $_POST;

            if($this->asesoriaModelo->asesoriaCerrada($id_asesoria)){
                exit();
            }

            if ($this->asesoriaModelo->editAsesoria($asesoria,$id_asesoria)){
                redireccionar("/asesorias/ver_asesoria/$id_asesoria");
            } else {
                echo "Se ha producido un Error!!!";
            }
        } else {
            $this->datos["asesoria"] = $this->asesoriaModelo->getAsesoria($id_asesoria);
            $this->datos["asesoria"]->acciones = $this->asesoriaModelo->getAccionesAsesoria($id_asesoria);
            
            $this->vista("asesorias/ver_asesoria",$this->datos);
        }
    }

    public function add_accion(){
        $this->datos['rolesPermitidos'] = [200,300];
        if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
            echo "No tienes privilegios!!!";
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $accion = $_POST;
            $accion['id_profesor'] = $this->datos["usuarioSesion"]->id_profesor;
            
            if($this->asesoriaModelo->asesoriaCerrada($accion["id_asesoria"])){
                exit();
            }

            // print_r($accion);
            if ($this->asesoriaModelo->addAccion($accion)){
                redireccionar("/asesorias/ver_asesoria/".$accion["id_asesoria"]);
            } else {
                echo "Se ha producido un Error!!!";
            }
        } else {

        }
    }


    public function cerrar_asesoria(){
        $this->datos['rolesPermitidos'] = [200,300];
        if (!tienePrivilegios($this->datos['usuarioSesion']->id_rol,$this->datos['rolesPermitidos'])) {
            echo "No tienes privilegios!!!";
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $accion = $_POST;
            $accion['id_profesor'] = $this->datos["usuarioSesion"]->id_profesor;

            if($this->asesoriaModelo->asesoriaCerrada($accion["id_asesoria"])){
                exit();
            }

            // print_r($accion);
            if ($this->asesoriaModelo->cerrarAccion($accion)){
                redireccionar("/asesorias/ver_asesoria/".$accion["id_asesoria"]);
            } else {
                echo "Se ha producido un Error!!!";
            }
        } else {

        }
    }

    public function get_accion($id_reg_acciones){

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {         // Solo acceso GET
            $datos = $this->asesoriaModelo->getAccion($id_reg_acciones);    // No necesitamos la informacion que nos aporta $this->datos
            $this->vistaApi($datos);
        }
    }


    public function set_accion(){

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {         // Solo acceso POST
            $accion = $_POST;
            if ($this->asesoriaModelo->setAccion($accion)){
                $this->vistaApi(true);
            } else {
                $this->vistaApi(false);
            }
        }
    }

}
