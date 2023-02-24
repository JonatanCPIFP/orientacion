<?php

class AsesoriaModelo {
    private $db;

    public function __construct(){
        $this->db = new Base;
    }


    public function addAsesoria($datos,$id_profesor){
        $this->db->query("INSERT INTO asesoria (nombre_as, dni_as, titulo_as, 
                                    telefono_as, email_as, descripcion_as, 
                                    domicilio_as, fecha_inicio, id_estado) 
                            VALUES (:nombre_as, :dni_as, :titulo_as, :telefono_as, 
                                    :email_as, :descripcion_as, :domicilio_as, 
                                    NOW(), 1)");

        //vinculamos los valores
        $this->db->bind(':nombre_as',trim($datos['nombre_as']));
        $this->db->bind(':dni_as',trim($datos['dni_as']));
        $this->db->bind(':titulo_as',trim($datos['titulo_as']));
        $this->db->bind(':telefono_as',trim($datos['telefono_as']));
        $this->db->bind(':email_as',trim($datos['email_as']));
        $this->db->bind(':domicilio_as',trim($datos['domicilio_as']));
        $this->db->bind(':descripcion_as',trim($datos['descripcion_as']));

        $id_asesoria = $this->db->executeLastId();


        $this->db->query("INSERT INTO reg_acciones (fecha_reg,accion,automatica,
                                id_asesoria, id_profesor) 
                            VALUES (NOW(),'Inicia', 1, :id_asesoria,:id_profesor)");

        $this->db->bind(':id_asesoria',$id_asesoria);
        $this->db->bind(':id_profesor',$id_profesor);

        //ejecutamos
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }



    public function getAsesoriasActivas(){
        $this->db->query("SELECT * FROM asesoria
                                    NATURAL JOIN estados
                                WHERE id_estado=1 OR id_estado=2
                                ORDER BY fecha_inicio DESC");

        return $this->db->registros();
    }


    public function getProfesor($id_profesor){
        $this->db->query("SELECT * FROM profesores
                                WHERE id_profesor=:id_profesor");

        $this->db->bind(':id_profesor',$id_profesor);

        return $this->db->registro();
    }


    public function getRolesProfesor($id_profesor){
        $this->db->query("SELECT * 
                                    FROM profesores_departamento
                                        NATURAL JOIN rol
                                        NATURAL JOIN departamento
                                    WHERE id_profesor=:id_profesor");

        $this->db->bind(':id_profesor',$id_profesor);

        return $this->db->registros();
    }


    public function getAccionesAsesoria($id_asesoria){
        $this->db->query("SELECT * FROM reg_acciones
                                    NATURAL JOIN profesores
                                WHERE id_asesoria=:id_asesoria
                                ORDER BY fecha_reg");

        $this->db->bind(':id_asesoria',$id_asesoria);

        return $this->db->registros();
    }


    public function getAsesoria($id_asesoria){
        $this->db->query("SELECT * FROM asesoria
                                    NATURAL JOIN estados
                                WHERE id_asesoria=:id_asesoria");

        $this->db->bind(':id_asesoria',$id_asesoria);

        return $this->db->registro();
    }


    public function editAsesoria($datos,$id_asesoria){

        $this->db->query("UPDATE asesoria SET nombre_as=:nombre_as, dni_as=:dni_as, titulo_as=:titulo_as, 
                                            telefono_as=:telefono_as, email_as=:email_as, domicilio_as=:domicilio_as, 
                                            descripcion_as=:descripcion_as
                                    WHERE id_asesoria=:id_asesoria");

        $this->db->bind(':nombre_as',$datos['nombre_as']);
        $this->db->bind(':dni_as',$datos['dni_as']);
        $this->db->bind(':titulo_as',$datos['titulo_as']);
        $this->db->bind(':telefono_as',$datos['telefono_as']);
        $this->db->bind(':email_as',$datos['email_as']);
        $this->db->bind(':domicilio_as',$datos['domicilio_as']);
        $this->db->bind(':descripcion_as',$datos['descripcion_as']);
        $this->db->bind(':id_asesoria',$id_asesoria);

        // print_r($datos);

        // exit();
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }


    public function addAccion($datos){

        // Cambiamos estado de la asesoria: 2 -- Procesando
        $this->db->query("UPDATE asesoria SET id_estado=2
                                            WHERE id_asesoria=:id_asesoria");

        $this->db->bind(':id_asesoria',$datos['id_asesoria']);
        $this->db->execute();

        $this->db->query("INSERT INTO reg_acciones (fecha_reg,accion,automatica,
                                id_asesoria, id_profesor) 
                            VALUES (NOW(),:accion, 0, :id_asesoria,:id_profesor)");

        $this->db->bind(':id_asesoria',$datos['id_asesoria']);
        $this->db->bind(':id_profesor',$datos['id_profesor']);
        $this->db->bind(':accion',$datos['accion']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }


    public function cerrarAccion($datos){

        // Cambiamos estado de la asesoria: 3 -- Cerrado
        $this->db->query("UPDATE asesoria SET id_estado=3, fecha_fin=NOW()
                                            WHERE id_asesoria=:id_asesoria");

        $this->db->bind(':id_asesoria',$datos['id_asesoria']);
        $this->db->execute();

        $this->db->query("INSERT INTO reg_acciones (fecha_reg,accion,automatica,
                                id_asesoria, id_profesor) 
                            VALUES (NOW(),'Cierra', 1, :id_asesoria,:id_profesor)");

        $this->db->bind(':id_asesoria',$datos['id_asesoria']);
        $this->db->bind(':id_profesor',$datos['id_profesor']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }


    public function asesoriaCerrada($id_asesoria){
        $this->db->query("SELECT * FROM asesoria
                                WHERE id_asesoria = $id_asesoria
                                    AND id_estado=3");

        return $this->db->rowCount();
    }


    public function getAsesorias(){
        $this->db->query("SELECT * FROM asesoria NATURAL JOIN estados");

        return $this->db->registros();
    }


    public function getAccion($id_reg_acciones){
        $this->db->query("SELECT * FROM reg_acciones
                                WHERE id_reg_acciones=:id_reg_acciones");

        $this->db->bind(':id_reg_acciones',$id_reg_acciones);

        return $this->db->registro();
    }


    public function setAccion($datos){

        $this->db->query("UPDATE reg_acciones SET accion=:accion
                                            WHERE id_reg_acciones=:id_reg_acciones");

        $this->db->bind(':accion',trim($datos['accion']));
        $this->db->bind(':id_reg_acciones',$datos['id_reg_acciones']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}
